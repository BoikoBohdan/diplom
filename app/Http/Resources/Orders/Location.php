<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Location extends JsonResource
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
            'id' => (integer)$this->id,
            'reference_id' => (string)$this->reference_id,
            'name' => (string)$this->name,
            'phone' => (string)$this->phone,
            'address' => (string)$this->address,
            'postcode' => (integer)$this->postcode,
            'contact_name' => (string)$this->contact_name,
            'note' => (string)$this->note
        ];
    }
}
