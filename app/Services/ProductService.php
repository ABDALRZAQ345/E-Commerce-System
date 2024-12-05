<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function get_the_user_info_for_product(Product $product, \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null $user): void
    {
        $your_rate = $product->rates()->where('user_id', $user->id)->first();
        $your_rate = $your_rate != null ? $your_rate->rate : 0;
        $your_favourite = $user->favouriteProducts()->find($product->id);
        $product->your_rate = $your_rate;
        $product->your_favourite = $your_favourite != null;
    }

}
