<?php

namespace App\Http\Controllers\Product;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\RateRequest;
use App\Models\Product;
use App\Services\ProductService;
use App\Services\RateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class ProductController extends Controller
{
    protected RateService $rateService;

    protected ProductService $productService;

    public function __construct(RateService $rateService, ProductService $productService)
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
            if ($request->has('search')) {
                $products = Product::search($request->input('search'))->paginate(20);
            } else {
                $products = Product::filter($request->input('filter'))->paginate(20);
            }
            $user = Auth::user();
            foreach ($products as $product) {
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
            $product->load('category');
            // todo load photos and details
            $user = Auth::user();
            $this->productService->get_the_user_info_for_product($product, $user);

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

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function rate(RateRequest $request, Product $product): JsonResponse
    {
        $validated = $request->validated();
        try {

            $user = Auth::user();
            $this->rateService->Rate($user, $product, $validated['rate']);
            $this->productService->get_the_user_info_for_product($product, $user);

            return response()->json([
                'status' => true,
                'message' => 'product rated successfully',
                'product' => $product,
            ]);
        } catch (\Exception $e) {

            throw new ServerErrorException($e->getMessage());
        }

    }
}
