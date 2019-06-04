<?php

namespace App\Http\Resources\Restaurant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray ($request)
    {
        return [
            'restaurant_id' => $this->id,
            'restaurant_name' => $this->name,
            'restaurant_phone' => $this->phone,
            'restaurant_address' => $this->address,
            'restaurant_city' => $this->city,
            'restaurant_country_code' => $this->country_code,
            'restaurant_postcode' => $this->postcode
        ];
    }
}
