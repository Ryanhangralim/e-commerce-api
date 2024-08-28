<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // business seeder
        Business::create([
            'user_id' => 4,
            'name' => 'Best business',
            'slug' => 'best-business',
            'description' => 'Best business description'
        ]);
        Business::create([
            'user_id' => 3,
            'name' => 'dummy2',
            'slug' => 'dummy2',
            'description' => 'dummy description 2'
        ]);
    }
}
