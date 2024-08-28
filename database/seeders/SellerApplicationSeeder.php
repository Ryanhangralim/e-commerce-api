<?php

namespace Database\Seeders;

use App\Models\SellerApplication;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SellerApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SellerApplication::create([
            'user_id' => 2,
            'business_name' => 'dummy1',
            'business_description' => 'dummy description 1',
            'application_status' => 'pending',
        ]);
        SellerApplication::create([
            'user_id' => 3,
            'business_name' => 'dummy2',
            'business_description' => 'dummy description 2',
            'application_status' => 'approved',
        ]);
        SellerApplication::create([
            'user_id' => 4,
            'business_name' => 'Best business',
            'business_description' => 'Best business description',
            'application_status' => 'approved',
        ]);
        SellerApplication::factory(25)->create();
        }
    
}
