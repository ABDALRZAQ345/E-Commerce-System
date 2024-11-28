<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Fashion',
            'Home and Kitchen',
            'Beauty and Personal Care',
            'Sports and Outdoors',
            'Toys and Games',
            'Automotive',
            'Books and Stationery',
            'Health and Wellness',
            'Groceries',
            'Jewelry and Accessories',
            'Pet Supplies',
            'Office Supplies',
            'Baby Products',
            'Garden and Outdoor',
            'Music and Instruments',
            'Movies and TV Shows',
            'Handmade Products',
            'Art and Collectibles',
            'Travel and Luggage',
            'Shoes and Footwear',
            'Watches',
            'Smartphones and Tablets',
            'Cameras and Photography',
            'Video Games and Consoles',
            'Fitness Equipment',
            'Tools and Hardware',
            'Appliances',
            'Furniture',
            'Industrial and Scientific',
            'Other',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);

        }
    }
}
