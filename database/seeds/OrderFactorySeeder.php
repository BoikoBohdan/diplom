<?php

use App\{Company, Order, OrdersLocations, OrdersProducts, Restaurant, User};
use App\Components\Traits\DummyData\ZurichStreets;
use Illuminate\Database\Seeder;

class OrderFactorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers = User::whereHas('driver')->get(['id']);   // list of drivers

        $restaurantAddresses = ZurichStreets::getRestaurantAddress(); // list of restaurants addresses

        factory(Company::class, $restaurantAddresses->count())->create()->each(function($company, $key) use ($drivers, $restaurantAddresses) {

            $restaurant = Restaurant::create(factory(Restaurant::class)->make(['streetaddress' => $restaurantAddresses[$key]])->toArray()); // make new restaurant

            $company->users()->save(factory(User::class)->create()); // Add users to restaurant

            $order = $company->orders()->save(factory(Order::class)->make(['restaurant_id' => $restaurant->id])); // Create order

                $order->locations()->create(factory(OrdersLocations::class)->make(['type' => 0, 'streetaddress' => $order->restaurant->address])->toArray()); // create order pickup location
                $order->locations()->create(factory(OrdersLocations::class)->make(['type' => 1, 'streetaddress' => ZurichStreets::getDropoffAddresses()[$key]])->toArray()); // create order dropoff location

                $order->products()->create(factory(OrdersProducts::class)->make()->toArray()); // create products
                $order->products()->create(factory(OrdersProducts::class)->make()->toArray());

               /*  DriverOrder::create([
                    'order_id'  => $order->id,
                    'driver_id' => $drivers->random()->driver->id
                ]); */
                /* rand(0,1) // randomly assign driver for order
                    ? DriverOrder::create([
                        'order_id'  => $order->id,
                        'driver_id' => $drivers->random()->driver->id
                    ])
                    : ''; */
        });
    }
}
