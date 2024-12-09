<?php

namespace App\Http\Controllers\Store;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\Store;
use App\Services\PhotosService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class StoreProductController extends Controller
{
    protected PhotosService $photosService;
    public function __construct(PhotosService $photosService)
    {
        $this->photosService = $photosService;
    }
    public function index(Request $request, Store $store): JsonResponse
    {
        $search = $request->input('search');

        $products = Product::search($search)
            ->where('store_id', $store->id)
            ->paginate(20);

        foreach ($products as $product) {
            $product->photo=$product->photos()->first() !=null?$product->photos()->first()->photo: null; ;
        }

        return response()->json([
            'status' => true,
            'message' => 'product list retrieved successfully',
            'products' => $products,
        ]);
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function store(StoreProductRequest $request, Store $store): JsonResponse
    {
        $validated = $request->validated();

        try {


            $data = Arr::except($validated, 'photos');
            $product = $store->products()->create($data);

                $this->photosService->AddPhotos($validated['photos'], $product);

            return response()->json([
                'status' => true,
                'message' => 'Product created successfully.',
            ], 201);

        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function update(UpdateProductRequest $request, Store $store, Product $product): JsonResponse
    {
        $validated = $request->validated();
        $product = Product::where('store_id', $store->id)->where('id', $product->id)->firstOrFail();
        try {

            $data = Arr::except($validated, 'photos');
            $product->update($data);

            if ($validated['photos']!=null ) {
                $this->photosService->DeletePhotos($product);
                $this->photosService->AddPhotos($validated['photos'], $product);
            }
            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully.',
            ]);

        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }
}
