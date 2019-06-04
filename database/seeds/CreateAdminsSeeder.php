<?php

use App\User;
use Illuminate\Database\Seeder;

class CreateAdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run ()
    {
        User::create([
            'email' => 'master@admin.com',
            'first_name' => 'Master',
            'last_name' => 'Admin',
            'password' => bcrypt('123qwe'),
            'company_id' => null,
            'status' => true
        ])->assignRole('master_admin');
        User::create([
            'email' => 'new@new.com',
            'first_name' => 'Master',
            'last_name' => 'Admin',
            'password' => bcrypt('123qwe'),
            'company_id' => 1,
            'status' => true
        ])->assignRole('super_admin');
        User::create([
            'email' => 'admin@test.com',
            'first_name' => 'Test',
            'last_name' => 'Admin',
            'password' => bcrypt('123qwe'),
            'company_id' => 1,
            'status' => true
        ])->assignRole('admin');
    }
}
