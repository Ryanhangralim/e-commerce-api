<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $business_id = range(1, 2);
        $user_id = range(1,55);
        $status =  ['pending', 'processing', 'delivered', 'received', 'completed', 'canceled'];

        return [
            //
            'user_id' => fake()->randomElement($user_id),
            'business_id' => fake()->randomElement($business_id),
            'status' => fake()->randomElement($status),
        ];
    }
}
