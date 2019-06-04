<?php

use App\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'Authtock',
            'guid'  => 'test'
        ]);
        Company::create([
            'name' => 'Just Eat',
            'guid'  => 'test'
        ]);
        Company::create([
            'name' => 'Coffe',
            'guid'  => 'test'
        ]);
    }
}
