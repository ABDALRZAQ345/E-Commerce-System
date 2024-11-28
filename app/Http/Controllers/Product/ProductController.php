<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->has('search')) {
            $products = Product::search($request->input('search'))->paginate(20);
        } else {
            $products = Product::filter($request->input('filter'))->paginate(20);
        }

        return response()->json($products);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load('categories');
        //todo load the photos and details
        return response()->json([
            'product' => $product,
        ]);
    }
}
