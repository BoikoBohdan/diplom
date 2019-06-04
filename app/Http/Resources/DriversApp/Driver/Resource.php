<?php

namespace App\Http\Resources\DriversApp\Driver;

use App\Http\Resources\Vehicles\Collection;
use App\Http\Resources\Wallet\Index;
use App\Http\Resources\WorkLocations\Collection as AppCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\City;

class Resource extends JsonResource
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
            'id' => $this->id,
            'full_name' => $this->full_name,
            'vehicles' => new Collection($this->driver->vehicles) ?? null,
            'workLocations' => new AppCollection($this->workLocations) ?? null,
            'wallet' => new Index($this->driver->wallet),
            'is_shift' => $this->driver->is_shift,
            'image' => $this->image,
            'cities' => City::all()->map(function($item){
                return [
                    'id' => $item->id,
                    'name' => $item->name
                ];
            })
        ];
    }
}
