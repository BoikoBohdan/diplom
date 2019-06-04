<?php

use Illuminate\Database\Seeder;

class ProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DriverFactorySeeder::class);
        $this->call(OrderFactorySeeder::class);
    }
}
