<?php

namespace App\Http\Controllers\Store;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Models\Store;
use App\Services\Category\StoreCategoryService;
use Illuminate\Http\JsonResponse;

class StoreCategoryController extends Controller
{
    //

    public function index(Store $store): JsonResponse
    {
        $storeCategoryService = new StoreCategoryService($store->id);

        return response()->json([
            'status' => true,
            'message' => 'category list retrieved successfully',
            'categories' => $storeCategoryService->getCategories()->get(),
        ]);

    }

    /**
     * @throws ServerErrorException
     */
    public function store(CategoryRequest $request, Store $store): JsonResponse
    {
        $validated = $request->validated();

        $storeCategoryService = new StoreCategoryService($store->id);
        try {
            $storeCategoryService->AddCategories($validated['categories']);

            return response()->json([
                'status' => true,
                'message' => 'category added successfully',
            ]);
        } catch (\Exception|\Throwable $exception) {
            throw new ServerErrorException($exception->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function update(CategoryRequest $request, Store $store): JsonResponse
    {
        $validated = $request->validated();
        $storeCategoryService = new StoreCategoryService($store->id);
        try {
            $storeCategoryService->UpdateCategories($validated['categories']);

            return response()->json([
                'status' => true,
                'message' => 'categories updated successfully',
            ]);
        } catch (\Exception $exception) {
            throw new ServerErrorException($exception->getMessage());
        }

    }
}
