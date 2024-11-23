<?php

namespace Database\Factories;

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
    {$statuses = ['pending', 'processing', 'in_way', 'completed'];
        $randomStatus = $statuses[array_rand($statuses)];
        return [
            'order_id' => Order::factory()->create()->id,
            'status' => $randomStatus,
            'total' => $this->faker->randomFloat(2, 10),
            'store_id'=> Store::factory()->create()->id,
        ];
    }
}
