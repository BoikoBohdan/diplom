<?php

namespace App\Providers;

use App\Customer;
use App\Driver;
use App\DriverOrder;
use App\Message;
use App\Observers\API\Chat\MessageObserver;
use App\Observers\API\Cusomers\CustomerObserver;
use App\Observers\API\Drivers\DriverObserver;
use App\Observers\API\Orders\DriverOrderObserver;
use App\Observers\API\Orders\OrderObserver;
use App\Observers\API\Orders\OrderGroupObserver;
use App\Observers\API\Orders\OrdersLocationsObserver;
use App\Observers\API\Orders\WaypointObserver;
use App\Observers\API\Shifts\ShiftObserver;
use App\Observers\API\Users\UserObserver;
use App\Observers\API\Users\WorkLocations;
use App\Observers\API\Vehicles\VehicleObserver;
use App\Order;
use App\OrdersLocations;
use App\Shift;
use App\User;
use App\UsersWorkLocations;
use App\Vehicle;
use App\Waypoint;
use Illuminate\Support\ServiceProvider;
use App\OrderGroup;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register ()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot ()
    {
        // User observer
        User::observe(UserObserver::class);

        // Order observer
        Order::observe(OrderObserver::class);

        // Order locations observer
        OrdersLocations::observe(OrdersLocationsObserver::class);

        // Driver order observer
        DriverOrder::observe(DriverOrderObserver::class);

        // Work locations observer
        UsersWorkLocations::observe(WorkLocations::class);

        // Vehicle observer
        Vehicle::observe(VehicleObserver::class);

        // Driver observer
        Driver::observe(DriverObserver::class);

        // Customer observer
        Customer::observe(CustomerObserver::class);

        // Message observer
        Message::observe(MessageObserver::class);

        // Waypoint observer
        Waypoint::observe(WaypointObserver::class);

        // Shifts observer
        Shift::observe(ShiftObserver::class);

        // OrderGroup observer
        OrderGroup::observe(OrderGroupObserver::class);
    }
}
