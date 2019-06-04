<?php

namespace App\Http\Resources\DriversApp\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Waypoint;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $ordersIds = $this->map(function($item){
            return $item->id;
        });

        return [
            'orders' => OrderResource::collection($this->collection)->where('order_group_id', null)->toArray(),
            'groupped_orders' => OrderResource::collection($this->collection)->where('order_group_id', '>', '0')->groupBy('order_group_id')->toArray(),
            'waypoints' => Waypoint::waypointsByIds($ordersIds)->orderBy('id')->get()->map(function($item){
                return [
                    'id' => $item->id,
                    'order_id' => $item->order_id,
                    'type' => Waypoint::TYPE_TITLES[$item->type]
                ];
            })
        ];
    }
}
