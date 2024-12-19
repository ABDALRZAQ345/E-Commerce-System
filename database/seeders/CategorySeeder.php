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
            'Office Supplies',
            'Baby Products',
            'Music and Instruments',
            'Movies and TV Shows',
            'Art and Collectibles',
            'Watches',
            'Smartphones and Tablets',
            'Cameras and Photography',
            'Video Games and Consoles',
            'Tools and Hardware',
            'Furniture',
            'Other',
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);

        }
    }
}
