<?php

namespace App\Services\Mobile;

use App\{Driver,
    Http\Resources\DriversApp\Orders\OrderCollection,
    Http\Resources\DriversApp\Orders\OrderResource,
    Services\OrderService,
    Order};
use Illuminate\Database\Eloquent\Model;

class OrderMobileService extends OrderService
{
    /**
     * @param Driver $driver
     * @return OrderCollection
     */
    public function getDriversOrders (Driver $driver)
    {
        return new OrderCollection($driver->orders);
    }

    /**
     * @param Model $model
     * @return \App\Http\Resources\Orders\Details|OrderResource|Model
     */
    public function show (Model $model)
    {
        $model->with(['locations', 'restaurant', 'products']);

        return new OrderResource($model);
    }

    /**
     * @param CancelReasonRequest $request
     * @param Order $order
     * @return void
     */
    public function setCancelOrderReason($attributes, $order)
    {
        if(!$order->isCancelReasonRelation()) {
            $this->setStatus($order, Order::STATUSES['cancel_request']);

            $order->cancelReason()->create($attributes);
        } else {
            throw new \RuntimeException('Cancel reason relation already exists', 422);
        }
    }
}
