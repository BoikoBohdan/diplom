<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Order::class, function (Faker $faker, $attr) {
    return [
        //'group_guid'      => $faker->uuid,
        'reference_id'      => $faker->unique()->uuid,
        'notes'             => $faker->sentence,
        'customer_info'     => $faker->sentence,
        'payment_info'      => $faker->sentence,
        'pickup_date'       => $faker->date,
        'dropoff_date'      => $faker->date,
        'fee'               => $faker->numberBetween(10, 1200),
        'time_loading'      => $faker->randomDigitNotNull ,
        'time_dropping'     => $faker->randomDigitNotNull ,
        'time_break'        => $faker->randomDigitNotNull ,
        'load_type'         => $faker->randomDigitNotNull ,
        'status'            => 0,
        'payment_type'      => $faker->numberBetween(1,10),
        'shipment_type'     => $faker->numberBetween(1,10),
        'asap'              => $faker->numberBetween(0,1),
        'enforce_signature' => $faker->numberBetween(0,1),
       /*  'driver_id'      => null, */
        'restaurant_id'     => $attr['restaurant_id'],
        'pickup_time_from'  => $faker->time,
        'pickup_time_to'    => $faker->time,
        'dropoff_time_from' => $faker->time,
        'dropoff_time_to'   => $faker->time,
    ];
});

$factory->define(App\OrdersProducts::class, function (Faker $faker) {
    return [
        'reference_id' => $faker->unique()->uuid,
        'name'         => $faker->unique()->word,
        'quantity'     => $faker->randomDigitNotNull,
        'unit_type'    => $faker->randomDigitNotNull,
        'note'         => $faker->word,
        'fee'          => $faker->numberBetween(10, 1200),
        'type'         => $faker->randomDigitNotNull,
    ];
});

$factory->define(App\OrdersLocations::class, function (Faker $faker, $attr) {
    return [
        'reference_id'  => $faker->unique()->uuid,
        'name'          => $faker->unique()->word,
        'type'          => $attr['type'],
        'phone'         => $faker->phoneNumber,
        //'address'     => $faker->streetAddress,
        'city'          => '',
        'country_code'  => '',
        'streetaddress' => $faker->streetAddress,
        'postcode'      => '',
        'contact_name'  => $faker->name,
        'note'          => $faker->sentence
    ];
});
