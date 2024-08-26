<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => 'ryancustomer',
            'first_name' => 'Ryan',
            'last_name' => 'Customer',
            'email' => 'ryanhangralim@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'phone_number' => '081808083811',
            'role_id' => 1
        ]);
        User::create([
            'username' => 'ryancustomer2',
            'first_name' => 'Ryan',
            'last_name' => 'Customer2',
            'email' => 'ryanhangralimm@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'phone_number' => '081808083811',
            'role_id' => 1
        ]);
        User::create([
            'username' => 'ryancustomer3',
            'first_name' => 'Ryan',
            'last_name' => 'Customer3',
            'email' => 'ryanhangralimmm@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'phone_number' => '081808083811',
            'role_id' => 2
        ]);
        User::create([
            'username' => 'ryanseller',
            'first_name' => 'Ryan',
            'last_name' => 'Seller',
            'email' => 'hangralim@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'phone_number' => '081808083811',
            'role_id' => 2
        ]);
        User::create([
            'username' => 'ryanadmin',
            'first_name' => 'Ryan',
            'last_name' => 'Admin',
            'email' => 'ryan@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'phone_number' => '081808083811',
            'role_id' => 3
        ]);
            
        User::factory(55)->create();
    }
}
