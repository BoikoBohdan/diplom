<?php

namespace App\Http\Resources\DriversApp\Products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersProductResource extends JsonResource
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
            'name' => $this->name,
            'quantity' => $this->quantity,
            'note' => $this->note,
            'fee' => $this->fee,
            'image' => $this->image
        ];
    }
}
