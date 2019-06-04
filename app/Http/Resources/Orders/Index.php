<?php

namespace App\Http\Resources\Orders;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Index extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray ($request)
    {
        $pickup = $this->pickup_location;
        $dropoff = $this->dropoff_location;
        return [
            'id' => (integer)$this->id,
            'reference_id' => (string)$this->reference_id,
            'pickup_date' => (string)$this->pickup_date,
            'pickup_time_from' => (string)$this->pickup_time_from,
            'dropoff_time_from' => (string)$this->dropoff_time_from,
            'restaurant_name' => (string)$this->restaurant->name,
            'restaurant_postcode' => (integer)$this->restaurant->postcode,
            'dropoff_postcode' => (integer)isset($dropoff->postcode) ? $dropoff->postcode : $this->postcode,
            'fee' => (double)$this->fee,
            'driver_name' => $this->when($this->drivers, function () {
                return $this->getDriversList();
            }),
            'status' => [
                'key' => array_flip(Order                                        :: STATUSES)[$this->status],
                'value' => $this->status,
                'title' => Order                                                   :: STATUS_TITLES[$this->status],
                'color' => Order                                                   :: STATUS_COLORS[$this->status]
            ]
        ];
    }
}
