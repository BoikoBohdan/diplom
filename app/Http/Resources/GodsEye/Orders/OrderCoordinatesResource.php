<?php

namespace App\Http\Resources\GodsEye\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderCoordinatesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray ($request)
    {
        return [
            'id' => $this->id,
            'coordinates' => $this->coordinates
        ];
    }
}
