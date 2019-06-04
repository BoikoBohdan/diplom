<?php

namespace App\Observers\API\Orders;

use App\{Customer,
    Events\API\Orders\ActionOnOrder,
    Events\API\Orders\GodsEyeOrder,
    Jobs\API\Common\Geolocate,
    OrdersLocations};

class OrdersLocationsObserver
{
    /**
     * Handle the orders locations "created" event.
     *
     * @param OrdersLocations $location
     * @return void
     */
    public function created (OrdersLocations $location)
    {
        if ($location->getCustomer()) {
            $customer = Customer::create($location->getCustomer()); // create customer
            $customer->orders()->attach($location->order->id); // attach order to customer
        }
    }

    public function creating (OrdersLocations $location)
    {
        $geolocation = dispatch_now(new Geolocate($location->address));

        $location->lat = $geolocation['lat'];
        $location->lng = $geolocation['lng'];
    }

    /**
     * Handle the orders locations "updated" event.
     *
     * @param OrdersLocations $location
     * @return void
     */
    public function updated (OrdersLocations $location)
    {
        if ($location->isBroadcastable(collect($location->getChanges()), $location->broadcastable)) {
            event(new ActionOnOrder($location->order, __FUNCTION__));
            event(new GodsEyeOrder($location->order, __FUNCTION__));
        }
    }

    public function updating (OrdersLocations $location)
    {
        $geolocation = dispatch_now(new Geolocate($location->address));

        if (empty($geolocation['lat']) || empty($geolocation['lng'])) {
            throw new \RuntimeException('Address not found', 422);
        }

        $location->lat = $geolocation['lat'];
        $location->lng = $geolocation['lng'];
    }

    /**
     * Handle the orders locations "deleted" event.
     *
     * @param OrdersLocations $location
     * @return void
     */
    public function deleted (OrdersLocations $location)
    {
        //
    }

    /**
     * Handle the orders locations "restored" event.
     *
     * @param OrdersLocations $location
     * @return void
     */
    public function restored (OrdersLocations $location)
    {
        //
    }

    /**
     * Handle the orders locations "force deleted" event.
     *
     * @param OrdersLocations $location
     * @return void
     */
    public function forceDeleted (OrdersLocations $location)
    {
        //
    }
}
