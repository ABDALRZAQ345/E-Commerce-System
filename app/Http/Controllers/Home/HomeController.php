<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;

class HomeController extends Controller
{
    public function __invoke()
    {
        $recommended_products = Product::filter('recommended')->take(10)->get();
        $latest_products = Product::filter('latest')->take(10)->get();
        $top_products = Product::filter('top_rated')->take(10)->get();
        $latest_stores = Store::filter('latest')->take(10)->get();
        $top_stores = Store::filter('top_rated')->take(10)->get();
        $recommended_stores=Store::filter('recommended')->take(10)->get();
        return response()->json([
            'recommended_products' => $recommended_products,
            'latest_products' => $latest_products,
            'top_products' => $top_products,
            'latest_stores' => $latest_stores,
            'top_stores' => $top_stores,
            'recommended_stores' => $recommended_stores,
        ]);
    }
}
