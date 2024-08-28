<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SellerApplication>
 */
class SellerApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_id = range(4, 60);
        return [
            //
            'user_id' => fake()->unique()->randomElement($user_id),
            'business_name' => implode(' ', fake()->unique()->words(mt_rand(1, 4))),
            'business_description' => fake()->sentence(mt_rand(3, 9)),
            'application_status' => 'pending'
        ];
    }
}
