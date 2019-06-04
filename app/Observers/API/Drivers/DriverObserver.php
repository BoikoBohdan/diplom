<?php

namespace App\Observers\API\Drivers;

use App\{Driver, Jobs\API\Wallets\CreateWallet};

class DriverObserver
{
    /**
     * Handle the driver "created" event.
     *
     * @param Driver $driver
     * @return void
     */
    public function created (Driver $driver)
    {
        dispatch_now(new CreateWallet($driver));
        $driver->user->assignRole('driver');

        $driver->user->workLocations()->create([
            'city_id' => 1,
            'is_active' => 1
        ]);
    }

    /**
     * @param Driver $driver
     */
    public function deleting (Driver $driver)
    {
        $driver->orders()->detach();
        $driver->vehicles()->delete();
        $driver->documents()->delete();
        $driver->wallet()->delete();
        $driver->ratings()->delete();
        $driver->coordinates()->delete();
        $driver->shifts()->detach();
        $driver->user()->delete();
    }

    /**
     * Handle the driver "updated" event.
     *
     * @param Driver $driver
     * @return void
     */
    public function updated (Driver $driver)
    {
        //
    }

    /**
     * Handle the driver "deleted" event.
     *
     * @param Driver $driver
     * @return void
     */
    public function deleted (Driver $driver)
    {
        //
    }


    /**
     * Handle the driver "restored" event.
     *
     * @param Driver $driver
     * @return void
     */
    public function restored (Driver $driver)
    {
        //
    }

    /**
     * Handle the driver "force deleted" event.
     *
     * @param Driver $driver
     * @return void
     */
    public function forceDeleted (Driver $driver)
    {
        //
    }
}
