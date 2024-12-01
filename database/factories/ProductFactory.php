<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'price' => $this->faker->numberBetween(1000, 10000),
            'photo' => $this->faker->imageUrl(),
            'store_id' => Store::factory()->create()->id,
            'category_id' => rand(1, Category::all()->count()),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
