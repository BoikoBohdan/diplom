<?php

use App\{User, VehicleType};
use Illuminate\Database\Seeder;

class VehicleTypesSeeder extends Seeder
{
    use \App\Components\Traits\DummyData\ZurichStreets;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        VehicleType::create([
            'type' => 'car',
            'completed_orders_charges' => 0,
            'cancelled_orders_charges' => 0,
            'delivery_radius' => 1
        ]);
        VehicleType::create([
            'type' => 'bicycle',
            'completed_orders_charges' => 0,
            'cancelled_orders_charges' => 0,
            'delivery_radius' => 1
        ]);
        VehicleType::create([
            'type' => 'scooter',
            'completed_orders_charges' => 0,
            'cancelled_orders_charges' => 0,
            'delivery_radius' => 1
        ]);

        $u = User::create([
            'email' => 'driver@test.com',
            'first_name' => 'Driver',
            'last_name' => 'Test',
            'password' => bcrypt('123qwe'),
            'company_id' => 1,
            'status' => true,
            'address' => 'Brandschenkestrasse 64 8002 Zürich Switzerland, 8048'
        ]);

        $u->assignRole('driver');

        $u->workLocations()->create([
            'city_id' => 1,
            'is_active' => 1
        ]);

        $driver = $u->driver()->create();

        $driver->vehicles()->create([
            'vehicle_type_id' => 1,
            'name' => 'Mercedes',
            'is_shift' => 1
        ]);

        $loc = dispatch_now(new \App\Jobs\API\Common\Geolocate(self::getStreets()->random(1)));

        $driver->coordinates()->create([
            'lat' => $loc['lat'],
            'lng' => $loc['lng']
        ]);
    }
}
