<?php

use App\Restaurant;
use Faker\Generator as Faker;

$factory->define(Restaurant::class, function (Faker $faker) {
    return [
        'reference_id'      => $faker->uuid,
        'name'              => $faker->company,
        'phone'             => $faker->phoneNumber,
        //'address'           => $faker->address,
        'city'          => $faker->city,
        'country_code'  => $faker->countryCode,
        'streetaddress' => $faker->streetAddress,
        'postcode'          => $faker->postcode,
        'contact_name'      => $faker->name
    ];
});
