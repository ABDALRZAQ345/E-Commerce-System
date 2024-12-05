<?php

namespace App\Http\Controllers\Product;

use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Models\FavouriteProduct;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FavouriteProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @throws ServerErrorException
     */
    public function index(): JsonResponse
    {
        try {
            $user = Auth::user();
            $favourites = $user->favouriteProducts()->get();
            foreach ($favourites as $favourite) {
                $this->productService->get_the_user_info_for_product($favourite, $user);
            }

            return response()->json([
                'status' => true,
                'count' => count($favourites),
                'favourites' => $favourites,
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException('Failed to retrieve favourites ');
        }
    }

    /**
     * @throws BadRequestException
     * @throws ServerErrorException
     */
    public function store(Product $product): JsonResponse
    {


        $user = Auth::user();
        if (FavouriteProduct::where('user_id', $user->id)->where('product_id', $product->id)->first()) {
            throw new BadRequestException('Product is already in your favourite list');
        }
        if($user->favouriteProducts()->count() == config('app.data.max_favourites')) {
            throw new BadRequestException('you cant add more than 100 favourite stores');
        }

        try {
            $user->favouriteProducts()->attach($product);

            return response()->json([
                'status' => true,
                'message' => 'Product added to favourites',
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }


    }

    public function delete(Product $product): JsonResponse
    {

        try{
            $user = Auth::user();
            $product = $user->favouriteProducts()->findOrFail($product->id);
            $user->favouriteProducts()->detach($product);

            return response()->json([
                'status' => true,
                'message' => 'Product removed from favourite list'
            ]);
        }
        catch (Exception $e) {
            return  response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }


    }
}
