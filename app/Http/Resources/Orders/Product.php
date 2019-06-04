<?php

namespace App\Http\Resources\Orders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
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
            'unit_type' => (integer)$this->unit_type,
            'quantity' => (integer)$this->quantity,
            'note' => (string)$this->note,
            'fee' => (double)$this->fee,
            'image' => (string)$this->image,
            'type' => (integer)$this->type
        ];
    }
}
