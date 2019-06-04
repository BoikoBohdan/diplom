<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'master_admin' => 'Master Admin',
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'driver' => 'Driver',
            'restaurant' => 'Restaurant'
        ];

        foreach ($roles as $name => $display_name) {
            Role::create([
                'name' => $name,
                'display_name' => $display_name
            ]);
        }
    }
}
