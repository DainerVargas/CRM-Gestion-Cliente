<?php

namespace Database\Factories;

use App\Models\Call;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Call>
 */
class CallFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => \App\Models\Client::factory(),
            'user_id' => 1,
            'called_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'type' => fake()->randomElement(['inbound', 'outbound']),
            'duration' => fake()->numberBetween(30, 600),
            'observations' => fake()->sentence(),
            'result' => fake()->randomElement(['interested', 'not_interested', 'pending', 'closed']),
        ];
    }
}
