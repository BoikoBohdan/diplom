<?php

namespace App\Observers\API\Cusomers;

use App\{Customer, Jobs\API\Common\Geolocate};

class CustomerObserver
{
    /**
     * Handle the customer "created" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function created (Customer $customer): void
    {
        $geolocation = dispatch_now(new Geolocate($customer->address)); // Geolocate address

        $customer->coordinates()->create([ // Add coordinates to database
            'lat' => $geolocation['lat'],
            'lng' => $geolocation['lng']
        ]);
    }

    /**
     * Handle the customer "updated" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function updated (Customer $customer)
    {
        //
    }

    /**
     * Handle the customer "deleted" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function deleted (Customer $customer)
    {
        //
    }

    /**
     * Handle the customer "restored" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function restored (Customer $customer)
    {
        //
    }

    /**
     * Handle the customer "force deleted" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function forceDeleted (Customer $customer)
    {
        //
    }
}
