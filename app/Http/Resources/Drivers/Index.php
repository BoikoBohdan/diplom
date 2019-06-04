<?php

namespace App\Http\Resources\Drivers;

use App\{UsersWorkLocations, Vehicle, VehicleType};
use App\Http\Resources\Wallet\Index as SingleWallet;
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
        $vehicle = Vehicle::isActive($this->vehicles);
        $location = UsersWorkLocations::isActive($this->user->workLocations);

        return [
            'id' => (integer)$this->user->id,
            'assigned_orders' => AssignedOrderResource::collection($this->orders),
            'full_name' => (string)$this->user->getFullName(),
            'phone' => (string)$this->phone,
            'status' => (integer)$this->status,
            'vehicle' => $vehicle ? VehicleType::LIST_TYPES[$vehicle->vehicle_type_id] : '',
            'location' => $location->city->name ?? '',
            'stops_left' => (integer)$this->countStops(),
            'wallet' => $this->when($this->wallet, new SingleWallet($this->wallet)),
        ];
    }
}
