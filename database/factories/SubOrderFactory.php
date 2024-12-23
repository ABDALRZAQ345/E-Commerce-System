<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubOrder>
 */
class SubOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = OrderStatusEnum::getAllStatus();
        $randomStatus = $statuses[array_rand($statuses)];
        $createdAt = $this->faker->dateTimeBetween('-1 year', 'now');
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');

        return [
            'order_id' => Order::factory()->create()->id,
            'status' => $randomStatus,
            'total' => $this->faker->randomFloat(2, 10, 100),
            'store_id' => Store::factory()->create()->id,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ];
    }
}
