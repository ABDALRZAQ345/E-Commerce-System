<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function get_the_user_info_for_product(Product $product, \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null $user): void
    {
        $your_review = $product->reviews()->where('user_id', $user->id)->first();

        $your_favourite = $user->favouriteProducts()->find($product->id);
        $product->your_review = $your_review;
        $product->your_favourite = $your_favourite != null;
    }
}
