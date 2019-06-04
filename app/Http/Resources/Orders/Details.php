<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Details extends JsonResource
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
            'group_guid' => (string)$this->group_guid,
            'reference_id' => (string)$this->reference_id,
            'pickup_date' => (string)$this->pickup_date,
            'dropoff_date' => (string)$this->dropoff_date,
            'pickup_time_from' => (string)$pickup->time_from,
            'pickup_time_to' => (string)$pickup->time_to,
            'dropoff_time_from' => (string)$dropoff->time_from,
            'dropoff_time_to' => (string)$dropoff->time_to,
            'enforce_signature' => (bool)$this->enforce_signature,
            'pickup_notes' => (string)$pickup->notes,
            'dropoff_notes' => (string)$dropoff->notes,
            'time_loading' => (integer)$this->time_loading,
            'time_dropping' => (integer)$this->time_dropping,
            'time_break' => (integer)$this->time_break,
            'load_type' => (integer)$this->load_type,
            'fee' => (double)$this->fee,
            'notes' => (string)$this->notes,
            'payment_info' => (string)$this->payment_info,
            'payment_type' => (integer)$this->payment_type,
            'customer_info' => (string)$this->customer_info,
            'asap' => (bool)$this->asap,
            'shipment_type' => (integer)$this->shipment_type,
            'pickup' => $this->when($pickup, new Location($pickup)),
            'dropoff' => $this->when($dropoff, new Location($dropoff)),
            'products' => $this->when($this->products, new ProductsCollection($this->products))

        ];
    }
}
