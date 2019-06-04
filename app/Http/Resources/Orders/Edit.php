<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Edit extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray ($request)
    {
        $pickup = $this->locations->where('type', 0)->first();
        $dropoff = $this->locations->where('type', 1)->first();

        return [
            'id' => (integer)$this->id,
            'group_guid' => (string)$this->group_guid,
            'reference_id' => (string)$this->reference_id,

            'restaurant_name' => (string)$pickup->name,
            'restaurant_postcode' => (integer)$pickup->postcode,
            'fee' => (double)$this->fee,
            'driver_name' => $this->when($this->driver, function () {
                return $this->driver->user->name;
            }),
            'payment_info' => (string)$this->payment_info,
            'payment_type' => (string)$this->payment_type,
            'asap' => (string)$this->asap,

            'pickup' => [
                'date' => (string)$this->pickup_date,
                'time_from' => (string)$this->pickup_time_from,
                'time_to' => (string)$this->pickup_time_to,
                'notes' => (string)$pickup->notes,
                'phone' => (string)$pickup->phone,
                'contact_name' => (string)$pickup->contact_name,
                'reference' => (string)$pickup->reference_id,
                'coordinates' => $pickup->coordinates,
                'location' => [
                    'country_code' => $pickup->country_code,
                    'city' => $pickup->city,
                    'streetaddress' => $pickup->streetaddress
                ]

            ],
            'dropoff' => [
                'date' => (string)$this->dropoff_date,
                'time_from' => (string)$this->dropoff_time_from,
                'time_to' => (string)$this->dropoff_time_to,
                'notes' => (string)$dropoff->notes,
                'postcode' => (integer)$dropoff->postcode,
                'reference' => (string)$dropoff->reference_id,
                'contact_name' => (string)$dropoff->contact_name,
                'phone' => (string)$dropoff->phone,
                'coordinates' => $dropoff->coordinates,
                'location' => [
                    'country_code' => $dropoff->country_code,
                    'city' => $dropoff->city,
                    'streetaddress' => $dropoff->streetaddress
                ]
            ],
            'products' => $this->when($this->products, function () {
                return new ProductsCollection($this->products);
            })
        ];
    }
}
