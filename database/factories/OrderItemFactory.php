<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
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
        $quantity = $this->faker->numberBetween(1, 10);
        $price = $this->faker->randomFloat(2, 10, 100);
        
        return [
            'sub_order_id' => \App\Models\SubOrder::inRandomOrder()->first()->id ?? \App\Models\SubOrder::factory(),
            'product_id' => $this->faker->numberBetween(1, 100),
            'quantity' => $quantity,
            'price' => $price,
            'total' => $quantity * $price,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
