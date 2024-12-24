<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\App\Models\Store;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $locations = [
            ['longitude' => 36.215, 'latitude' => 37.123, 'name' => 'Branch 1 - McDonald\'s', 'object_id' => 1, 'object_type' => 'App\Models\Store'],
            ['longitude' => 40.123, 'latitude' => 38.123, 'name' => 'Branch 1 - Samsung', 'object_id' => 2, 'object_type' => 'App\Models\Store'],
            ['longitude' => 34.567, 'latitude' => 32.789, 'name' => 'Branch 1 - Apple', 'object_id' => 3, 'object_type' => 'App\Models\Store'],
            ['longitude' => 35.890, 'latitude' => 31.456, 'name' => 'Branch 1 - Nike', 'object_id' => 4, 'object_type' => 'App\Models\Store'],
            ['longitude' => 33.234, 'latitude' => 30.987, 'name' => 'Branch 1 - Zara', 'object_id' => 5, 'object_type' => 'App\Models\Store'],
            ['longitude' => 38.543, 'latitude' => 29.654, 'name' => 'Branch 1 - KFC', 'object_id' => 6, 'object_type' => 'App\Models\Store'],
            ['longitude' => 37.890, 'latitude' => 28.123, 'name' => 'Branch 1 - Starbucks', 'object_id' => 7, 'object_type' => 'App\Models\Store'],
            ['longitude' => 36.456, 'latitude' => 27.345, 'name' => 'Branch 1 - Adidas', 'object_id' => 8, 'object_type' => 'App\Models\Store'],
            ['longitude' => 35.678, 'latitude' => 26.789, 'name' => 'Branch 1 - Sony', 'object_id' => 9, 'object_type' => 'App\Models\Store'],
            ['longitude' => 34.890, 'latitude' => 25.123, 'name' => 'Branch 1 - H&M', 'object_id' => 10, 'object_type' => 'App\Models\Store'],
        ];
        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
