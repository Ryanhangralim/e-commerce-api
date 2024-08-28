<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product_id = range(1, 52);
        $transaction_id = range(1, 26);
        $quantity = fake()->randomElement(range(1, 10));

        return [
            //
            'transaction_id' => fake()->randomElement($transaction_id),
            'product_id' => fake()->randomElement($product_id),
            'price' => 10000,
            'quantity' => $quantity,
            'total_price' => 10000
        ];
    }
}
