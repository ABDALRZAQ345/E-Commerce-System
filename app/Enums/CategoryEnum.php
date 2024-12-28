<?php

namespace App\Enums;

enum CategoryEnum
{
    public static function getCategories(): array
    {

        return [
            'Electronics',
            'Fashion',
            'Sports',
            'Games',
            'Books',
            'Health',
            'Outdoor',
            'Music',
            'Art',
            'Furniture',
            'Food',
            'Other',
        ];
    }
}
