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
        $product->load('category');
        $your_rate = $product->rates()->where('user_id', auth()->user()->id)->first();
        $your_rate = $your_rate != null ? $your_rate->rate : 0;

        //todo load the photos and details
        return response()->json([
            'product' => $product,
            'your_rate' => $your_rate,
        ]);
    }

    public function audits(Product $product): JsonResponse
    {
        $audits = $product->audits()->paginate(20);

        return response()->json([
            'audits' => $audits,
        ]);

    }

    public function rate(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'rate' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $product->rates()->where('user_id', auth()->id())->delete();
        $product->rates()->create([
            'rate' => $validated['rate'],
            'user_id' => auth()->id(),
        ]);
        $product->rate = $product->rates()->avg('rate');
        $product->save();

        return response()->json([
            'status' => true,
            'product' => $product,
        ]);

    }
}
