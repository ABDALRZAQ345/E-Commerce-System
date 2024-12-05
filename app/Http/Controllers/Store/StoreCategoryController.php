<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Models\Store;
use App\Services\Category\StoreCategoryService;
use Illuminate\Http\JsonResponse;

class StoreCategoryController extends Controller
{
    //

    public function index(Store $store)
    {
        $storeCategoryService = new StoreCategoryService($store->id);

        return $storeCategoryService->getCategories()->get();
    }

    public function store(CategoryRequest $request, Store $store): JsonResponse
    {
        $validated = $request->validated();

        $storeCategoryService = new StoreCategoryService($store->id);
        try {
            $storeCategoryService->AddCategories($validated['categories']);

            return response()->json([
                'message' => 'success',
            ]);
        } catch (\Exception|\Throwable $exception) {
            return response()->json([
                'message' => 'some thing went wrong please try again later',
            ], 400);
        }

    }

    public function update(CategoryRequest $request, Store $store): JsonResponse
    {
        $validated = $request->validated();
        $storeCategoryService = new StoreCategoryService($store->id);
        try {
            $storeCategoryService->UpdateCategories($validated['categories']);

            return response()->json([
                'message' => 'categories updated successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'some thing went wrong please try again later',
            ], 400);
        }

    }
}
