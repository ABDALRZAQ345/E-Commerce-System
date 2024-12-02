<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\FavouriteProduct;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class FavouriteProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *     path="/favourites/products",
     *     tags={"Favourites"},
     *     summary="Get user products favourites",
     *     description="Retrieve the authenticated user's favourite products.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved favourite products",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(
     *                 property="favourites",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication is required",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve favourites",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="error", type="string", example="Failed to retrieve favourites"),
     *             @OA\Property(property="message", type="string", example="Server error message")
     *         )
     *     )
     * )
     */
    public function getFavourites()
    {
        try {
            $user = Auth::user();
            $favourites = $user->favouriteProducts()->get();
            foreach ($favourites as $favourite) {
                $this->productService->get_the_user_info_for_product($favourite, $user);
            }

            return response()->json([
                'status' => true,
                'favourites' => $favourites,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve favourites', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/favourites/products/{product}",
     *     tags={"Favourites"},
     *     summary="Add a product to favourites",
     *     description="Add a product to the authenticated user's favourite list.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="The ID of the product to be added to favourites",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product added to favourites successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Product added to favourites")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Product is already in the favourite list",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Product is already in your favourite list")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication is required",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to add favourite",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="error", type="string", example="Failed to add favourite"),
     *             @OA\Property(property="message", type="string", example="Server error message")
     *         )
     *     ),
     *
     *      @OA\Response(
     *          response=404,
     *          description="Product not found in favourites",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="error", type="string", example="Not Found"),
     *              @OA\Property(property="message", type="string", example="Object not fount ")
     *          )
     *      )
     * )
     */
    public function addFavourite(Product $product)
    {

        try {
            $user = Auth::user();
            if (FavouriteProduct::where('user_id', $user->id)->where('product_id', $product->id)->first()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product is already in your favourite list',
                ], 400);
            }
            $user->favouriteProducts()->attach($product);

            return response()->json([
                'message' => 'Product added to favourites',
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to add favourite', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/favourites/products/{product}",
     *     tags={"Favourites"},
     *     summary="Remove a product from favourites",
     *     description="Remove a product from the authenticated user's favourite list.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="The ID of the product to be removed from favourites",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Product removed from favourites successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Product removed from favourites")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Product not found in favourites",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Not Found"),
     *             @OA\Property(property="message", type="string", example="Object not fount")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication is required",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function removeFavourite(Product $product)
    {

        $user = Auth::user();
        $product = $user->favouriteProducts()->findOrFail($product->id);
        $user->favouriteProducts()->detach($product);

        return response()->json(['message' => 'Product removed from favourites'], 200);

    }
}
