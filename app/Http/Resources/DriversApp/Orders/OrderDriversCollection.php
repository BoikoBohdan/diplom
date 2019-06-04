<?php

namespace App\Http\Resources\DriversApp\Orders;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderDriversCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return OrderDriversResource::collection($this->collection);
    }
}
