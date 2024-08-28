<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product_id = range(1, 2);
        $user_id = range(1,55);
        $rating = range(1, 5);
        $order_id = range(1, 52);

        return [
            //
            'order_id' => fake()->randomElement($order_id),
            'product_id' => fake()->randomElement($product_id),
            'user_id' => fake()->randomElement($user_id),
            'content' => fake()->sentence(mt_rand(3, 9)),
            'rating' => fake()->randomElement($rating),
        ];
    }
}
