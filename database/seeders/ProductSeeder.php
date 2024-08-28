<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'business_id' => 1,
            'name' => 'Uniqlo UT',
            'slug' => create_slug('Uniqlo UT 1'),
            'description' => 'Uniqlo T-Shirt for everyone',
            'brand' => 'Uniqlo',
            'category_id' => 2,
            'price' => 199000
        ]);
        Product::create([
            'business_id' => 1,
            'name' => 'Kindle Paperwhite',
            'slug' => create_slug('Kindle Paperwhite 1'),
            'description' => 'Kindle paperwhite by amazon, e-reader',
            'brand' => 'Amazon',
            'category_id' => 1,
            'price' => 2499000
        ]);

        Product::factory(50)->create();
    }
}
