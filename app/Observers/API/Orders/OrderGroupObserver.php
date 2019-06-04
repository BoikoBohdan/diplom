<?php

namespace App\Observers\API\Orders;

use App\ORderGroup;

class OrderGroupObserver
{
    /**
     * Handle the order "deleting" event.
     *
     * @param OrderGroup $group
     * @return void
     */
    public function deleting (OrderGroup $group)
    {
        $group->orders->each(function($order){
            $order->update(['order_group_id'=>null]);
        });
    }
}
