<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name"=> "superadmin",
            "email"=> "superadmin@gmail.com",
            'is_admin_user' => '1',
            'status' => '1',
            "password" => bcrypt(env("DEFAULT_PASSWORD", '12345678')),
        ])->assignRole('super_admin');

        
        User::create([
            "name"=> "admin",
            "email"=> "admin@gmail.com",
            'is_admin_user' => '1',
            'status' => '1',
            "password" => bcrypt(env("DEFAULT_PASSWORD", '12345678')),
        ])->assignRole('admin');

        User::create([
            "name"=> "user",
            "email"=> "user@gmail.com",
            'is_admin_user' => '0',
            'status' => '1',
            "password" => bcrypt(env("DEFAULT_PASSWORD", '12345678')),
        ])->assignRole('user');
    }
}
