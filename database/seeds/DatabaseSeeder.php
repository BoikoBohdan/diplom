<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run ()
    {
        $this->call(CitiesSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(CreateAdminsSeeder::class);
        $this->call(VehicleTypesSeeder::class);
        $this->call(CancelReasons::class);
    }
}
