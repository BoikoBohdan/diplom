<?php

namespace App\Http\Resources\GodsEye\Drivers;

use App\{Http\Resources\GodsEye\Orders\OrderCoordinatesResource, Order, Vehicle};
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriversResource extends JsonResource
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

        $waypoints = $this->orders->map(static function (Order $order) {
            return $order->waypoints;
        });

        return [
            'id' => $this->user->id,
            'title' => [
                'row' => $this->user->full_name
            ],
            'location' => [
                'row' => $vehicle->name . ' ' . $vehicle->vehicleType->type,
                'coordinates' => $this->coordinates->first()->data
            ],
            'stops' => [
                'row' => $this->countStops() . ' ' . 'stops left'
            ],
            'orders' => OrderCoordinatesResource::collection($this->orders),
            'waypoints' => $this->when($waypoints->count() > 0, new WaypointCollection($waypoints))
        ];
    }
}
