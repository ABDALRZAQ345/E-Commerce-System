<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): JsonResponse
    {
        if ($request->has('search')) {
            $products = Product::search($request->input('search'))->paginate(20);
        } else {
            $products = Product::filter($request->input('filter'))->paginate(20);
        }
        $user = Auth::user();
        foreach ($products as $product) {

            $this->productService->get_the_user_info_for_product($product, $user);
        }

        return response()->json($products);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load('category');
        $user = Auth::user();
        $this->productService->get_the_user_info_for_product($product, $user);

        return response()->json([
            'product' => $product,
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
