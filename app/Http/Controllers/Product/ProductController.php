<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     required={"id", "name", "price", "quantity", "category_id", "store_id", "rate", "sales", "created_at", "updated_at", "your_rate", "your_favourite"},
 *
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the product"),
 *     @OA\Property(property="name", type="string", description="The name of the product"),
 *     @OA\Property(property="store_id", type="integer", description="The ID of the store the product belongs to"),
 *     @OA\Property(property="price", type="integer", description="The price of the product in the smallest currency unit (e.g., cents)"),
 *     @OA\Property(property="discount", type="integer", minimum=0, maximum=100, default=0, description="Discount percentage for the product"),
 *     @OA\Property(property="quantity", type="integer", description="The available quantity of the product"),
 *     @OA\Property(property="category_id", type="integer", description="The ID of the category the product belongs to"),
 *     @OA\Property(property="expire_date", type="string", format="date", nullable=true, description="The expiration date of the product, if applicable"),
 *     @OA\Property(property="photo", type="string", nullable=true, description="The URL of the product's photo or image path"),
 *     @OA\Property(property="rate", type="number", format="float", minimum=0, maximum=5, default=0, description="The average rating of the product"),
 *     @OA\Property(property="sales", type="integer", default=0, description="The total number of sales for the product"),
 *     @OA\Property(property="your_rate", type="integer", description="The rating given by the authenticated user to this product, default is 0"),
 *     @OA\Property(property="your_favourite", type="boolean", description="Indicates whether the product is marked as a favourite by the authenticated user"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp of when the product was created"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp of when the product was last updated")
 * )
 */
class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *     path="/products",
     *     summary="Retrieve a list of products with optional search and filtering",
     *     tags={"Products"},
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search by product names to filter products",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         required=false,
     *         description="Filter options for products must from [best_selling,latest,top_rated,recommended]",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of products with additional user-specific information",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="current_page",
     *                 type="integer",
     *                 description="Current page of the paginated results"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             ),
     *
     *             @OA\Property(
     *                 property="first_page_url",
     *                 type="string",
     *                 description="URL for the first page of results"
     *             ),
     *             @OA\Property(
     *                 property="last_page_url",
     *                 type="string",
     *                 description="URL for the last page of results"
     *             ),
     *             @OA\Property(
     *                 property="next_page_url",
     *                 type="string",
     *                 description="URL for the next page of results"
     *             ),
     *             @OA\Property(
     *                 property="prev_page_url",
     *                 type="string",
     *                 description="URL for the previous page of results"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid search or filter parameters"
     *     ),
     *     security={{
     *         "bearerAuth": {}
     *     }}
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/products/{product}",
     *     summary="Retrieve a specific product by ID",
     *     tags={"Products"},
     *
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="The unique identifier for the product",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="The requested product details",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="product",
     *                 ref="#/components/schemas/Product"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product not found",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication is required"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/products/{product}/rate",
     *     summary="Rate a product",
     *     tags={"Products"},
     *
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="The unique identifier for the product",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"rate"},
     *
     *             @OA\Property(
     *                 property="rate",
     *                 type="integer",
     *                 description="Rating value between 1 and 5",
     *                 example=4
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product rating successfully updated",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", description="Status of the operation"),
     *             @OA\Property(
     *                 property="product",
     *                 ref="#/components/schemas/Product"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity - Validation error for rate",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="rate",
     *                     type="array",
     *                     items=@OA\Items(type="string"),
     *                     description="Validation errors for the rate"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication is required"
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
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
