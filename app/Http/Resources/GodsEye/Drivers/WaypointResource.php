<?php

namespace App\Http\Resources\GodsEye\Drivers;

use App\OrdersLocations;
use Illuminate\Http\Resources\Json\JsonResource;

class WaypointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray ($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->getWaypointTitle(),
            'order_id' => $this->order_id,
            'number' => $this->number,
            'type' => array_flip(OrdersLocations::LOCATION_TYPE)[$this->type],
        ];
    }
}
