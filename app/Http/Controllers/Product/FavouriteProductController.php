<?php

namespace App\Http\Controllers\Product;

use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Models\FavouriteProduct;
use App\Models\Product;
use App\Services\Interest\InterestService;
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
            $favourites = $user->favouriteProducts()->paginate(20);

            foreach ($favourites as $product) {
                $product->photo = $product->photos()->first() != null ? $product->photos()->first()->photo : null;
                $this->productService->get_the_user_info_for_product($product, $user);
            }

            return response()->json([
                'status' => true,
                'products' => $favourites,
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

        $this->EditInterests($product, 1);
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

    /**
     * @throws ServerErrorException
     */
    public function delete(Product $product): JsonResponse
    {

        try {
            $user = Auth::user();
            $product = $user->favouriteProducts()->findOrFail($product->id);
            $user->favouriteProducts()->detach($product);
            $this->EditInterests($product, -1);

            return response()->json([
                'status' => true,
                'message' => 'Product removed from favourite list',
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    public function EditInterests($product, $value): void
    {
        $interestService = new InterestService;
        $product = Product::findOrFail($product['id']);
        $category = $product->category_id;
        if (! $interestService->CheckUserInterest(Auth::id(), $category)) {
            $interestService->CreateUserInterest(Auth::id(), $category);
        }
        $interestService->increaseInterestLevel(Auth::id(), $category, $value);

    }
}
