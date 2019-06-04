<?php

namespace App\Http\Resources\DriversApp\Orders;

use App\Http\Resources\Customer\Collection;
use App\Http\Resources\DriversApp\Products\OrdersProductResource;
use App\Http\Resources\Rating\Collection as AppCollection;
use App\Order;
use App\OrdersLocations;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray ($request)
    {
        $pickup_location = $this->locations->where('type', OrdersLocations::LOCATION_TYPE['pickup'])->first();
        $dropoff_location = $this->locations->where('type', OrdersLocations::LOCATION_TYPE['dropoff'])->first();

        return [
            'id' => $this->id,
            'collect' => $this->reference_id,
            'payment_type' => $this->payment_type,
            'order_note' => $this->note,
            'total' => $this->fee,
            'pickup_address' => $pickup_location->address,
            'pickup_city' => $pickup_location->city,
            'pickup_country_code' => $pickup_location->country_code,
            'pickup_date' => $this->pickup_date,
            'pickup_time_from' => $this->pickup_time_from,
            'pickup_time_to' => $this->pickup_time_to,
            'pickup_latitude' => $pickup_location->lat,
            'pickup_longitude' => $pickup_location->lng,
            'dropoff_address' => $dropoff_location->address,
            'dropoff_city' => $dropoff_location->city,
            'dropoff_country_code' => $dropoff_location->country_code,
            'dropoff_date' => $this->dropoff_date,
            'dropoff_time_from' => $this->dropoff_time_from,
            'dropoff_time_to' => $this->dropoff_time_to,
            'dropoff_latitude' => $dropoff_location->lat,
            'dropoff_longitude' => $dropoff_location->lng,
            'restaurant_id' => $this->restaurant->id,
            'restaurant_name' => $this->restaurant->name,
            'restaurant_phone' => $this->restaurant->phone,
            'restaurant_address' => $this->restaurant->address,
            'restaurant_city' => $this->restaurant->city,
            'restaurant_country_code' => $this->restaurant->country_code,
            'restaurant_postcode' => $this->restaurant->postcode,
            'restaurant_note' => $pickup_location->note,
            'order_group_id' => $this->order_group_id,
            'products' => OrdersProductResource::collection($this->products),
            'status' => $this->status,
            'status_title' => Order::STATUS_TITLES[$this->status],
            'customer' => new Collection($this->customers),
            'ratings' => new AppCollection($this->getRatings()),
            'drivers' => new OrderDriversCollection($this->drivers),
        ];
    }
}
