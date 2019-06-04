<?php

namespace App\Http\Resources\DriversApp\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDriversResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user_id'   => $this->user_id,
            'full_name' => $this->user->full_name,
            'image'     => $this->user->image
        ];
    }
}
