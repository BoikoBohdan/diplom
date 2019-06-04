<?php

namespace App\Http\Resources\Vehicles;

use App\Http\Resources\Documents\Collection as VehicleDocumentsCollection;
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
        return [
            'id' => (int)$this->id,
            'name' => (string)$this->name,
            'vehicle_type_id' => (int)$this->vehicle_type_id,
            'is_shift' => (int)$this->is_shift,
            'documents' => new VehicleDocumentsCollection($this->documents)
        ];
    }
}
