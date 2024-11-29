<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::pluck('id')->toArray(); // Get all category IDs
        Store::factory(10)->create();
        $stores = Store::all();
        foreach ($stores as $store) {
            $products = Product::factory(10)->create(['store_id' => $store->id]);
            foreach ($products as $product) {
                $product->categories()->sync(collect($categories)->random(rand(3, 7)));
            }

            // Attach the selected categories to the store
            $store->categories()->sync(collect($categories)->random(rand(3, 7)));
        }
    }
}
