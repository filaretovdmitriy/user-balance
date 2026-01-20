<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
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
        return [
            'user_id' => User::factory(),
            'amount' => $this->faker->numberBetween(1, 10000),
            'type' => $this->faker->randomElement(['debit', 'credit']),
            'description' => $this->faker->sentence(3),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}