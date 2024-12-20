<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
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
        $createdAt = $this->faker->dateTimeBetween('-1 year', 'now');
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');
        return [
            'user_id' => User::factory()->create(),
            'total' => $this->faker->randomFloat(2, 10, 100),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
