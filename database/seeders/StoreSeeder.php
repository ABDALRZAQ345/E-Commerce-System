<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Photo;
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


        ///McDonald's


        $stores = [
            ['name' => 'McDonald\'s', 'description' => 'Global fast-food restaurant.', 'photo' => 'storage/stores/mc.png', 'user_id' => 1],
            ['name' => 'Samsung', 'description' => 'Electronics company.', 'photo' => 'storage/stores/sam.png', 'user_id' => 2],
            ['name' => 'Apple', 'description' => 'Technology company.', 'photo' => 'storage/stores/apple.png', 'user_id' => 3],
            ['name' => 'Nike', 'description' => 'Sportswear brand.', 'photo' => 'storage/stores/nike.png', 'user_id' => 4],
            ['name' => 'Zara', 'description' => 'Fashion brand.', 'photo' => 'storage/stores/zara.png', 'user_id' => 5],
            ['name' => 'KFC', 'description' => 'Fast-food chain.', 'photo' => 'storage/stores/kfc.png', 'user_id' => 6],
            ['name' => 'Starbucks', 'description' => 'Coffeehouse chain.', 'photo' => 'storage/stores/starbucks.png', 'user_id' => 7],
            ['name' => 'Adidas', 'description' => 'Sports apparel brand.', 'photo' => 'storage/stores/adidas.png', 'user_id' => 8],
            ['name' => 'Sony', 'description' => 'Electronics and gaming.', 'photo' => 'storage/stores/sony.png', 'user_id' => 9],
            ['name' => 'H&M', 'description' => 'Clothing retailer', 'photo' => 'storage/stores/h&m.png', 'user_id' => 10],
        ];
        foreach ($stores as $store) {
          Store::create($store);
        }
        Store::find(1)->categories()->attach([11]);
        Store::find(2)->categories()->attach([1,4]);
        Store::find(3)->categories()->attach([1]);
        Store::find(4)->categories()->attach([3,2]);
        Store::find(5)->categories()->attach([2]);
        Store::find(6)->categories()->attach([11]);
        Store::find(7)->categories()->attach([11]);
        Store::find(8)->categories()->attach([3,2]);
        Store::find(9)->categories()->attach([1,4]);
        Store::find(10)->categories()->attach([2]);

    }
}
