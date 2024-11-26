<?php

namespace Database\Seeders;

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
        Store::factory(10)->create();
        $stores = Store::all();
        foreach ($stores as $store) {
            Product::factory(10)->create(['store_id' => $store->id]);
        }
    }
}
