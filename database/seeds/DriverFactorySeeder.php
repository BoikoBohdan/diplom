<?php

use App\{User, Vehicle};
use App\Components\Traits\DummyData\ZurichStreets;
use Illuminate\Database\Seeder;

class DriverFactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        $driverAddresses = ZurichStreets::getStreets();
        factory(User::class, $driverAddresses->count())->create()->each(function ($user, $key) use ($driverAddresses) {
            $driver = $user->driver()->create();
            $driver->vehicles()->save(factory(Vehicle::class)->make());

            $coordinates = dispatch_now(new \App\Jobs\API\Common\Geolocate($driverAddresses[$key]));

            $driver->coordinates()->create([
                'lat' => $coordinates['lat'],
                'lng' => $coordinates['lng']
            ]);

            $user->assignRole('driver');


            $user->workLocations()->create([
                'city_id' => 1,
                'is_active' => 1
            ]);
        });
    }
}
