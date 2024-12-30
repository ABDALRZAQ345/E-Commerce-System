<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $path='storage/products';

        $products = [
            'McDonald\'s' => [
                ['name' => 'Big Mac', 'price' => 15, 'description' => 'A classic Big Mac burger with two beef patties.', 'image_extension' => 'jpg'],
                ['name' => 'Chicken McNuggets', 'price' => 10, 'description' => 'Crispy chicken nuggets, perfect for sharing.', 'image_extension' => 'png'],
                ['name' => 'French Fries', 'price' => 5, 'description' => 'Golden crispy fries.', 'image_extension' => 'png'],
            ],
            'Samsung' => [
                ['name' => 'Samsung Galaxy S23', 'price' => 999, 'description' => 'Flagship smartphone with top features.', 'image_extension' => 'png'],
                ['name' => 'Samsung Smart TV 65', 'price' => 1200, 'description' => 'Ultra HD smart TV with stunning visuals.', 'image_extension' => 'jpg'],
                ['name' => 'Samsung Galaxy Buds', 'price' => 150, 'description' => 'True wireless earbuds with premium sound.', 'image_extension' => 'jpg'],
            ],
            'Apple' => [
                ['name' => 'iPhone 15', 'price' => 1200, 'description' => 'The latest iPhone with groundbreaking features.', 'image_extension' => 'jpg'],
                ['name' => 'MacBook Pro', 'price' => 2500, 'description' => 'Powerful laptop for professionals.', 'image_extension' => 'png'],
            ],
            'Nike' => [
                ['name' => 'Nike Air Max', 'price' => 150, 'description' => 'Stylish and comfortable running shoes.', 'image_extension' => 'jpg'],
                ['name' => 'Nike Dri-FIT Shirt', 'price' => 30, 'description' => 'Breathable athletic shirt.', 'image_extension' => 'jpg'],
            ],
            'Zara' => [
                ['name' => 'Zara Leather Jacket', 'price' => 200, 'description' => 'Stylish leather jacket for men.', 'image_extension' => 'jpg'],
                ['name' => 'Zara Trousers', 'price' => 80, 'description' => 'Comfortable and trendy trousers.', 'image_extension' => 'jpg'],
            ],
            'KFC' => [
                ['name' => 'Bucket Meal', 'price' => 20, 'description' => 'Large bucket with fried chicken.', 'image_extension' => 'jpg'],
                ['name' => 'Zinger Sandwich', 'price' => 7, 'description' => 'Spicy chicken sandwich.', 'image_extension' => 'png'],
                ['name' => 'Twister Wrap', 'price' => 8, 'description' => 'Chicken wrap with delicious sauce.', 'image_extension' => 'jpg'],
            ],
            'Starbucks' => [
                ['name' => 'Caffe Latte', 'price' => 5, 'description' => 'Classic espresso drink with steamed milk.', 'image_extension' => 'jpg'],
                ['name' => 'Caramel Macchiato', 'price' => 6, 'description' => 'Espresso drink with caramel drizzle.', 'image_extension' => 'jpg'],
            ],
            'Adidas' => [
                ['name' => 'Adidas Ultraboost', 'price' => 180, 'description' => 'High-performance running shoes.', 'image_extension' => 'jpg'],
                ['name' => 'Adidas Soccer Ball', 'price' => 50, 'description' => 'FIFA-approved soccer ball.', 'image_extension' => 'jpg'],
                ['name' => 'Adidas Tracksuit', 'price' => 120, 'description' => 'Comfortable athletic wear.', 'image_extension' => 'jpg'],
            ],
            'Sony' => [
                ['name' => 'PlayStation 5', 'price' => 500, 'description' => 'Next-generation gaming console.', 'image_extension' => 'jpg'],
                ['name' => 'Sony WH-1000XM5', 'price' => 350, 'description' => 'Noise-cancelling headphones.', 'image_extension' => 'jpg'],
                ['name' => 'Sony Bravia TV', 'price' => 1500, 'description' => '4K HDR smart TV.', 'image_extension' => 'jpg'],
            ],
            'H&M' => [
                ['name' => 'H&M T-Shirt', 'price' => 20, 'description' => 'Casual and comfortable T-shirt.', 'image_extension' => 'jpg'],
                ['name' => 'H&M Jeans', 'price' => 40, 'description' => 'Trendy denim jeans.', 'image_extension' => 'jpg'],
            ],
];
        // إنشاء المنتجات لكل متجر
        $stores = Store::all();
        foreach ($stores as $store) {

            if (isset($products[$store->name])) {
                foreach ($products[$store->name] as $productData) {
                    $product=Product::create([
                        'name' => $productData['name'],
                        'store_id' => $store->id,
                        'price' => $productData['price'],
                        'discount' => 0,
                        'quantity' => rand(10, 100),
                        'description' => $productData['description'],
                        'category_id' => rand(1, 11),
                        'rate' => 0,
                        'sales' => 0,
                    ]);
                    DB::table('photos')->insert([
                       'photo' => $path . '/' . $productData['name'] . '.' . $productData['image_extension'],
                        'object_id' => $product->id,
                        'object_type' => 'App\Models\Product',
                    ]);
                }
            }
        }
    }
}
