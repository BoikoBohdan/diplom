<?php

namespace App\Observers\API\Orders;

use App\{DriverOrder, Jobs\API\Orders\SetOrderStatus, Order};

class DriverOrderObserver
{
    /**
     * Handle the driver order "created" event.
     *
     * @param DriverOrder $relation
     * @return void
     */
    public function created (DriverOrder $relation)
    {
        dispatch_now(new SetOrderStatus($relation->order, Order::STATUSES['assigned']));

        $relation->order->setWaypoints();
    }

    /**
     * Handle the driver order "creating" event.
     *
     * @param DriverOrder $relation
     * @return void
     */
    public function creating (DriverOrder $relation)
    {
        $relation->position = DriverOrder::setPosition($relation->driver_id);
    }

    /**
     * Handle the driver order "updated" event.
     *
     * @param DriverOrder $relation
     * @return void
     */
    public function updated (DriverOrder $relation)
    {
        //
    }

    /**
     * Handle the driver order "deleted" event.
     *
     * @param DriverOrder $relation
     * @return void
     */
    public function deleted (DriverOrder $relation)
    {
        //
    }

    /**
     * Handle the driver order "restored" event.
     *
     * @param DriverOrder $relation
     * @return void
     */
    public function restored (DriverOrder $relation)
    {
        //
    }

    /**
     * Handle the driver order "force deleted" event.
     *
     * @param DriverOrder $relation
     * @return void
     */
    public function forceDeleted (DriverOrder $relation)
    {
        //
    }
}
