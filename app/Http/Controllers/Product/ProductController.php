<?php

namespace App\Http\Controllers\Product;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class ProductController extends Controller
{
    protected ReviewService $rateService;

    protected ProductService $productService;

    public function __construct(ReviewService $rateService, ProductService $productService)
    {
        $this->rateService = $rateService;
        $this->productService = $productService;
    }

    /**
     * @throws ServerErrorException
     */
    public function index(Request $request): JsonResponse
    {
        try {

            $query = Product::query();

            if ($request->has('search') && $request->search != null) {
                $query->whereIn('id', Product::search($request->input('search'))->get()->pluck('id'));
            }
            $query->filter($request->input('filter'));
            $products = $query->paginate(20);

            $user = Auth::user();
            foreach ($products as $product) {
                $product->photo = $product->photos()->first() != null ? $product->photos()->first()->photo : null;
                $this->productService->get_the_user_info_for_product($product, $user);
            }

            return response()->json([
                'status' => true,
                'message' => 'products retrieved successfully',
                'products' => $products,
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function show(Product $product): JsonResponse
    {
        try {
            $product->load(['category', 'photos']);

            $user = Auth::user();
            $this->productService->get_the_user_info_for_product($product, $user);
            $product->photo = $product->photos()->first() != null ? $product->photos()->first()->photo : null;

            return response()->json([
                'status' => true,
                'message' => 'product retrieved successfully',
                'product' => $product,
            ]);
        } catch (Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    public function audits(Product $product): JsonResponse
    {
        $audits = $product->audits()->paginate(20);

        return response()->json([
            'audits' => $audits,
        ]);

    }
}
