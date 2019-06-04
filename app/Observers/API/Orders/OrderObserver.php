<?php

namespace App\Observers\API\Orders;

use App\{Events\API\Orders\ActionOnOrder, Events\API\Orders\GodsEyeOrder, Order, Wallet, OrdersLocations};

class OrderObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param Order $order
     * @return void
     */
    public function created (Order $order)
    {
        event(new ActionOnOrder($order, __FUNCTION__));
        event(new GodsEyeOrder($order, __FUNCTION__));
    }

    /**
     * Handle the order "updated" event.
     *
     * @param Order $order
     * @return void
     */
    public function updated (Order $order)
    {
        if ($order->isBroadcastable(collect($order->getChanges()), $order->getBroadcastTrigger())) {
            event(new ActionOnOrder($order, __FUNCTION__));
            event(new GodsEyeOrder($order, __FUNCTION__));
        }
    }

    /**
     * Handle the order "updating" event.
     *
     * @param Order $order
     * @return void
     */
    public function updating (Order $order)
    {
        if ($order->statusHasChanged()) {
            $order->status === Order::STATUSES['rollback_to_previous']
                ? $order->rollbackToPreviousStatus()
                : $order->setPreviousStatus();

            $order->status === Order::STATUSES['delivered'] && $order->payment_type === Order::PAYMENT_TYPE['cash']
                ? Wallet::setMassAmount($order->fee, $order->drivers)
                : false;

            $order->status === Order::STATUSES['left_restaurant']
                ? $order->waypoints()->where('type', OrdersLocations::LOCATION_TYPE['pickup'])->delete()
                : false;

            if ($order->status === Order::STATUSES['delivered']) {

                $order->waypoints()->where('type', OrdersLocations::LOCATION_TYPE['dropoff'])->delete();

                if($order->group) {
                    $group = $order->group;
                    $order->order_group_id = null;

                    if (count($group->orders) == 1){
                        $group->delete();
                    }
                }
            }

            if ($order->status === Order::STATUSES['cancelled']) {

                $order->waypoints()->delete();

                if($order->group) {
                    $group = $order->group;
                    $order->order_group_id = null;

                    if (count($group->orders) == 1){
                        $group->delete();
                    }
                }
            }
        }
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function deleted (Order $order)
    {
        event(new ActionOnOrder($order, __FUNCTION__));
        event(new GodsEyeOrder($order, __FUNCTION__));
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function deleting (Order $order)
    {
        $order->drivers()->detach();

        $order->customers()->detach();

        $order->locations()->delete();

        $order->products()->delete();

        $order->cancelReason()->delete();

        $order->customers()->detach();

        $order->waypoints()->delete();
    }

    /**
     * Handle the order "restored" event.
     *
     * @param Order $order
     * @return void
     */
    public function restored (Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function forceDeleted (Order $order)
    {
        //
    }
}
