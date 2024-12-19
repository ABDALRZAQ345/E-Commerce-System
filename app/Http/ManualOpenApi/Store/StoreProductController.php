<?php

namespace App\Http\ManualOpenApi\Store;

use OpenApi\Annotations as OA;

class StoreProductController
{
    /**
     * @OA\Get(
     *     path="/stores/{store}/products",
     *     summary="Get products for a specific store paginated",
     *     description="Fetch a list of products from a specific store, with an optional search filter.",
     *     tags={"Stores", "Products"},
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
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="The search term to filter products by (optional)",
     *         required=false,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved the list of products",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="products", type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Store not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Store not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * ,
     *
     *      @OA\Response(
     *          response=401,
     *          description="Forbidden, the user is not authorized to create a product for this store",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="error", type="string", example="Unauthorized")
     *          )
     *      )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/stores/{store}/products",
     *     summary="Create a new product for a store",
     *     description="Create a new product within a specific store. The user must be a manager and the store owner. Validation for fields such as name, price, discount, quantity, and category is applied.",
     *     tags={"Stores", "Products"},
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
     *             required={"name", "price", "discount", "quantity", "category_id"},
     *
     *             @OA\Property(property="name", type="string", description="The name of the product", maxLength=255),
     *             @OA\Property(property="price", type="number", format="float", description="The price of the product (greater than 0)"),
     *             @OA\Property(property="discount", type="number", format="float", description="The discount on the product (between 0 and 100)"),
     *             @OA\Property(property="quantity", type="integer", description="The available quantity of the product (min: 1)"),
     *             @OA\Property(property="expire_date", type="string", format="date", description="The expiration date of the product (optional)"),
     *             @OA\Property(property="photo", type="string", format="binary", description="The product image (optional, max size: 3MB)"),
     *             @OA\Property(property="category_id", type="integer", description="The category ID the product belongs to (must exist in categories table)")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Product created successfully."),
     *             @OA\Property(property="product", ref="#/components/schemas/Product")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, validation failed",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Validation error message")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Forbidden, the user is not authorized to create a product for this store",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Failed to create product: Error message")
     *         )
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Post(
     *     path="/stores/{store}/products/{product}",
     *     summary="Update an existing product",
     *     description="Update the details of an existing product within a store. The user must be the store's manager and owner to update the product.",
     *     tags={"Stores", "Products"},
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
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         description="The unique identifier of the product",
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
     *             required={"name", "price", "discount", "quantity", "category_id"},
     *
     *             @OA\Property(property="name", type="string", description="The name of the product", maxLength=255),
     *             @OA\Property(property="price", type="number", format="float", description="The price of the product (greater than 0)"),
     *             @OA\Property(property="discount", type="number", format="float", description="The discount on the product (between 0 and 100)"),
     *             @OA\Property(property="quantity", type="integer", description="The available quantity of the product (min: 1)"),
     *             @OA\Property(property="expire_date", type="string", format="date", description="The expiration date of the product (optional)"),
     *             @OA\Property(property="photo", type="string", format="binary", description="The product image (optional, max size: 3MB)"),
     *             @OA\Property(property="category_id", type="integer", description="The category ID the product belongs to (must exist in categories table)")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Product updated successfully."),
     *             @OA\Property(property="product", ref="#/components/schemas/Product")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request, validation failed",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Validation error message")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Forbidden, the user is not authorized to update this product",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Not found, the product does not exist",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Product not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Failed to update product: Error message")
     *         )
     *     )
     * )
     */
    public function update() {}
}
