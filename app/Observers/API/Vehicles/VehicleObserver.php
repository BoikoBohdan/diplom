<?php

namespace App\Observers\API\Vehicles;

use App\Vehicle;

class VehicleObserver
{
    /**
     * Handle the vehicle "created" event.
     *
     * @param Vehicle $vehicle
     * @return void
     */
    public function created (Vehicle $vehicle)
    {
        $vehicle->isShift();
    }

    /**
     * Handle the vehicle "creating" event.
     *
     * @param Vehicle $vehicle
     * @return void
     */
    public function creating (Vehicle $vehicle)
    {

    }

    /**
     * Handle the vehicle "updated" event.
     *
     * @param Vehicle $vehicle
     * @return void
     */
    public function updated (Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle the vehicle "updating" event.
     *
     * @param Vehicle $vehicle
     * @return void
     */
    public function updating (Vehicle $vehicle)
    {
        $vehicle->isShift();
    }

    /**
     * Handle the vehicle "deleted" event.
     *
     * @param Vehicle $vehicle
     * @return void
     */
    public function deleted (Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle the vehicle "restored" event.
     *
     * @param Vehicle $vehicle
     * @return void
     */
    public function restored (Vehicle $vehicle)
    {
        //
    }

    /**
     * Handle the vehicle "force deleted" event.
     *
     * @param Vehicle $vehicle
     * @return void
     */
    public function forceDeleted (Vehicle $vehicle)
    {
        //
    }
}
