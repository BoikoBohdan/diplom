<?php

namespace App\Observers\API\Orders;

use App\{Driver, Events\API\Drivers\GodsEyeDriver, Waypoint};

class WaypointObserver
{
    /**
     * Handle the waypoint "created" event.
     *
     * @param Waypoint $waypoint
     * @return void
     */
    public function created (Waypoint $waypoint)
    {
        $waypoint->order->drivers->each(static function (Driver $driver) {
            event(new GodsEyeDriver($driver));
        });
    }

    /**
     * Handle the waypoint "updated" event.
     *
     * @param Waypoint $waypoint
     * @return void
     */
    public function updated (Waypoint $waypoint)
    {
        //
    }

    /**
     * Handle the waypoint "deleted" event.
     *
     * @param Waypoint $waypoint
     * @return void
     */
    public function deleted (Waypoint $waypoint)
    {
        //
    }

    /**
     * Handle the waypoint "restored" event.
     *
     * @param Waypoint $waypoint
     * @return void
     */
    public function restored (Waypoint $waypoint)
    {
        //
    }

    /**
     * Handle the waypoint "force deleted" event.
     *
     * @param Waypoint $waypoint
     * @return void
     */
    public function forceDeleted (Waypoint $waypoint)
    {
        //
    }
}
