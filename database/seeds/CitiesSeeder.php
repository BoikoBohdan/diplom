<?php

use Illuminate\Database\Seeder;

use App\City;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::create(['name' => 'Zurich']);
    }
}
