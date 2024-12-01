<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Requests\Store\StoreProductRequest;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreProductController extends Controller
{
    //
    public function index(Request $request, Store $store): JsonResponse
    {
        $search = $request->input('search');

        $products = Product::search($search)
            ->where('store_id', $store->id)
            ->paginate(20);

        return response()->json([
            'products' => $products,
        ]);
    }

    public function show(Store $store, Product $product): JsonResponse
    {
        $product = $store->products()->with('categories')->findOrFail($product->id);

        return response()->json([
            'product' => $product,
        ]);

    }

    public function store(StoreProductRequest $request, Store $store): JsonResponse
    {
        $validated = $request->validated();

        try {
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('products', 'public');
                $validated['photo'] = 'storage/'.$photoPath;
            }
            $product = $store->products()->create($validated);

            return response()->json([
                'message' => 'Product created successfully.',
                'product' => $product,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create product: '.$e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateProductRequest $request, Store $store, Product $product): JsonResponse
    {
        $validated = $request->validated();
        $product = Product::where('store_id', $store->id)->where('id', $product->id)->firstOrFail();
        try {
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('products', 'public');
                $validated['photo'] = 'storage/'.$photoPath;
            }
            $product->update($validated);

            return response()->json([
                'message' => 'Product updated successfully.',
                'product' => $product,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update product: ',
            ], 500);
        }
    }
}
