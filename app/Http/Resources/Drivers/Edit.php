<?php

namespace App\Http\Resources\Drivers;

use App\Driver;
use App\Http\Resources\Documents\Collection as DriverDocumentCollection;
use App\Http\Resources\Vehicles\Collection;
use App\Http\Resources\Wallet\Index as SingleWallet;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Edit extends JsonResource
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
            'rating' => (double)$this->rating,
            'documents' => $this->when($this->documents, new DriverDocumentCollection($this->documents)),
            'vehicles' => $this->when($this->vehicles, new Collection($this->vehicles)),
            'wallet' => $this->when($this->wallet, new SingleWallet($this->wallet)),
            'driver_model' => Driver ::class,
            'vehicle_model' => Vehicle::class,
        ];
    }
}
