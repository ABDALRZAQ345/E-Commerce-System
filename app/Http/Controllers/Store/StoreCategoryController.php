<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Models\Store;
use App\Services\Category\StoreCategoryService;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class StoreCategoryController extends Controller
{
    //
    /**
     * @OA\Get(
     *     path="/stores/{store}/categories",
     *     summary="Get categories of a store",
     *     description="Retrieve the list of categories for a given store. Accessible by any authorized user.",
     *     tags={"Stores", "Categories"},
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         description="The unique identifier of the store",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of store categories",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *
     *                 @OA\Items(
     *                     type="object",
     *
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Electronics"),
     *                     @OA\Property(property="description", type="string", example="Category description")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized to access the store categories",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/stores/{store}/categories",
     *     summary="Update store categories",
     *     description="Allows the store owner to update the categories associated with the store. The input must be an array of existing category IDs.",
     *     tags={"Stores", "Categories"},
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         description="The unique identifier of the store",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="categories",
     *                 type="array",
     *                 description="Array of category IDs to associate with the store",
     *
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Categories updated successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Categories updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Error during update",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Something went wrong. Please try again later.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized to update categories",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     )
     * )
     */
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
