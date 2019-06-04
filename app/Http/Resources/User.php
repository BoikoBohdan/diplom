<?php

namespace App\Http\Resources;

use App\Http\Resources\Drivers\Edit;
use App\Http\Resources\WorkLocations\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'company' => (string)$this->company->name,
            'first_name' => (string)$this->first_name,
            'last_name' => (string)$this->last_name,
            'email' => (string)$this->email,
            'phone' => (string)$this->phone,
            'address' => (string)$this->address,
            'status' => (integer)$this->status,
            'image' => $this->image,
            'driver' => $this->when($this->driver, new Edit($this->driver)),
            'work_locations' => $this->when($this->workLocations, new Collection($this->workLocations))
        ];
    }
}
