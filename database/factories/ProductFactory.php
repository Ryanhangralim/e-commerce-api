<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $business_id = range(1, 2);
        $category_id = range(1, 7);
        $price = range(1000, 100000000, 1000);
        $stock = range(0, 10);
        $discount = range(0, 10);
        $name = implode(' ', fake()->unique()->words(mt_rand(1, 4)));
        $selected_business_id = fake()->randomElement($business_id);

        return [
            //
            'business_id' => $selected_business_id,
            'name' => $name,
            'slug' => create_slug($name . " " . $selected_business_id),
            'description' => fake()->sentence(mt_rand(3, 9)),
            'brand' => ucwords(fake()->firstName()),
            'category_id' => fake()->randomElement($category_id),
            'price' => fake()->randomElement($price),
            'stock' => fake()->randomElement($stock),
            'discount' => fake()->randomElement($discount)
        ];
    }
}
