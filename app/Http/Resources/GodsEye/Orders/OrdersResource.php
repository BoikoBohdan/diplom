<?php

namespace App\Http\Resources\GodsEye\Orders;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray ($request)
    {
        $statusTitle = Order::STATUS_TITLES[$this->status];

        return [
            'id' => $this->id,
            'title' => [
                'row' => $this->status === 0
                    ? '#0' . ' - ' . $this->pickup_date
                    : '#' . $this->getPosition() . ' - ' . $this->getDriversList() . ' - ' . $this->pickup_date
            ],
            'price' => [
                'row' => $statusTitle . ' - ' . 'CHF ' . $this->fee
            ],
            'pickup' => [
                'row' => $this->pickup_time_from . ' - ' . $this->restaurant->name . ' - ' . $this->pickup_location->address,
                'coordinates' => $this->pickup_location->coordinates
            ],
            'dropoff' => [
                'row' => $this->dropoff_time_to . ' - ' . $this->dropoff_location->contact_name . ' - ' . $this->dropoff_location->address,
                'coordinates' => $this->dropoff_location->coordinates
            ],
            'status' => $statusTitle,
            'stops' => $this->drivers->count() > 0 ? $this->drivers->first()->countStops() : 0,
            'payment_type' => $this->payment_type === 1
                ? 1
                : 0
        ];
    }
}
