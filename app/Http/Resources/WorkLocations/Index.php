<?php

namespace App\Http\Resources\WorkLocations;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Cities\CityCollection;
use App\City;

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
            'id' => (integer)$this->id,
            'location' => (string)$this->city->name,
            'is_active' => (boolean)$this->is_active,
            'city_id' => (integer)$this->city_id
        ];
    }
}
