<?php

namespace App\Enums;

enum CategoryEnum
{


    public static function getCategories(): array
    {

        return [
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
    }
}
