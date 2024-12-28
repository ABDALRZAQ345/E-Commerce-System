<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            'McDonald\'s' => [
                ['name' => 'Big Mac', 'price' => 15, 'description' => 'A classic Big Mac burger with two beef patties.'],
                ['name' => 'Chicken McNuggets', 'price' => 10, 'description' => 'Crispy chicken nuggets, perfect for sharing.'],
                ['name' => 'French Fries', 'price' => 5, 'description' => 'Golden crispy fries.'],
                ['name' => 'McFlurry', 'price' => 7, 'description' => 'Creamy dessert with various toppings.'],
                ['name' => 'Happy Meal', 'price' => 12, 'description' => 'A meal for kids with a toy included.'],
            ],
            'Samsung' => [
                ['name' => 'Samsung Galaxy S23', 'price' => 999, 'description' => 'Flagship smartphone with top features.'],
                ['name' => 'Samsung Smart TV 65"', 'price' => 1200, 'description' => 'Ultra HD smart TV with stunning visuals.'],
                ['name' => 'Samsung Galaxy Buds', 'price' => 150, 'description' => 'True wireless earbuds with premium sound.'],
                ['name' => 'Samsung Refrigerator', 'price' => 800, 'description' => 'Modern refrigerator with smart features.'],
                ['name' => 'Samsung Washing Machine', 'price' => 600, 'description' => 'High-efficiency washing machine.'],
            ],
            'Apple' => [
                ['name' => 'iPhone 15', 'price' => 1200, 'description' => 'The latest iPhone with groundbreaking features.'],
                ['name' => 'MacBook Pro', 'price' => 2500, 'description' => 'Powerful laptop for professionals.'],
                ['name' => 'Apple Watch', 'price' => 500, 'description' => 'Smartwatch with health tracking.'],
                ['name' => 'AirPods Pro', 'price' => 250, 'description' => 'Noise-cancelling true wireless earbuds.'],
                ['name' => 'iPad Pro', 'price' => 1100, 'description' => 'Professional-grade tablet.'],
            ],
            'Nike' => [
                ['name' => 'Nike Air Max', 'price' => 150, 'description' => 'Stylish and comfortable running shoes.'],
                ['name' => 'Nike Dri-FIT Shirt', 'price' => 30, 'description' => 'Breathable athletic shirt.'],
                ['name' => 'Nike Sports Shorts', 'price' => 40, 'description' => 'Durable and flexible sportswear.'],
                ['name' => 'Nike Basketball', 'price' => 50, 'description' => 'Professional-grade basketball.'],
                ['name' => 'Nike Backpack', 'price' => 70, 'description' => 'High-quality backpack for all purposes.'],
            ],
            'Zara' => [
                ['name' => 'Zara Leather Jacket', 'price' => 200, 'description' => 'Stylish leather jacket for men.'],
                ['name' => 'Zara Dress', 'price' => 120, 'description' => 'Elegant dress for women.'],
                ['name' => 'Zara Trousers', 'price' => 80, 'description' => 'Comfortable and trendy trousers.'],
                ['name' => 'Zara Scarf', 'price' => 40, 'description' => 'Soft and cozy scarf.'],
                ['name' => 'Zara Shoes', 'price' => 100, 'description' => 'Modern footwear for every occasion.'],
            ],
            'KFC' => [
                ['name' => 'Bucket Meal', 'price' => 20, 'description' => 'Large bucket with fried chicken.'],
                ['name' => 'Zinger Sandwich', 'price' => 7, 'description' => 'Spicy chicken sandwich.'],
                ['name' => 'Twister Wrap', 'price' => 8, 'description' => 'Chicken wrap with delicious sauce.'],
                ['name' => 'Popcorn Chicken', 'price' => 6, 'description' => 'Crispy chicken bites.'],
                ['name' => 'Coleslaw', 'price' => 3, 'description' => 'Fresh coleslaw salad.'],
            ],
            'Starbucks' => [
                ['name' => 'Caffe Latte', 'price' => 5, 'description' => 'Classic espresso drink with steamed milk.'],
                ['name' => 'Caramel Macchiato', 'price' => 6, 'description' => 'Espresso drink with caramel drizzle.'],
                ['name' => 'Frappuccino', 'price' => 7, 'description' => 'Blended iced coffee beverage.'],
                ['name' => 'Pumpkin Spice Latte', 'price' => 5.5, 'description' => 'Seasonal espresso drink.'],
                ['name' => 'Starbucks Mug', 'price' => 15, 'description' => 'Stylish mug for coffee lovers.'],
            ],
            'Adidas' => [
                ['name' => 'Adidas Ultraboost', 'price' => 180, 'description' => 'High-performance running shoes.'],
                ['name' => 'Adidas Soccer Ball', 'price' => 50, 'description' => 'FIFA-approved soccer ball.'],
                ['name' => 'Adidas Tracksuit', 'price' => 120, 'description' => 'Comfortable athletic wear.'],
                ['name' => 'Adidas Cap', 'price' => 25, 'description' => 'Stylish cap for everyday use.'],
                ['name' => 'Adidas Gym Bag', 'price' => 60, 'description' => 'Durable gym bag for athletes.'],
            ],
            'Sony' => [
                ['name' => 'PlayStation 5', 'price' => 500, 'description' => 'Next-generation gaming console.'],
                ['name' => 'Sony WH-1000XM5', 'price' => 350, 'description' => 'Noise-cancelling headphones.'],
                ['name' => 'Sony Bravia TV', 'price' => 1500, 'description' => '4K HDR smart TV.'],
                ['name' => 'Sony Camera', 'price' => 1000, 'description' => 'Professional mirrorless camera.'],
                ['name' => 'Sony Soundbar', 'price' => 400, 'description' => 'Premium audio system.'],
            ],
            'H&M' => [
                ['name' => 'H&M T-Shirt', 'price' => 20, 'description' => 'Casual and comfortable T-shirt.'],
                ['name' => 'H&M Jeans', 'price' => 40, 'description' => 'Trendy denim jeans.'],
                ['name' => 'H&M Jacket', 'price' => 60, 'description' => 'Warm and stylish jacket.'],
                ['name' => 'H&M Hat', 'price' => 15, 'description' => 'Casual hat for everyday wear.'],
                ['name' => 'H&M Sweater', 'price' => 50, 'description' => 'Cozy and stylish sweater.'],
            ],
        ];

        // إنشاء المنتجات لكل متجر
        $stores = Store::all();
        foreach ($stores as $store) {

            if (isset($products[$store->name])) {
                foreach ($products[$store->name] as $productData) {
                    Product::create([
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
                }
            }
        }
    }
}
