<?php

namespace App\Services\Category;

use App\Models\Product;

class ProductCategoryService extends  CategoryService
{

    public function __construct($id)
    {
        parent::__construct(Product::class, $id);
    }


    public function UpdateCategories(array $categoryIds)
    {

    }
}
