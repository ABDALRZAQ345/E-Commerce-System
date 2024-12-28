<?php

namespace App\Http\Controllers\Product;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewRequest;
use App\Http\Requests\Review\ShowReviewsRequest;
use App\Models\Product;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    protected ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * @throws ServerErrorException
     * @throws \Throwable
     */
    public function review(ReviewRequest $request, Product $product): JsonResponse
    {
        $validated = $request->validated();
        try {

            $user = Auth::user();
            $this->reviewService->AddReview($user, $product, $validated['rate'], $validated['comment']);

            return response()->json([
                'status' => true,
                'message' => 'product rated successfully',
            ]);
        } catch (\Exception $e) {

            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function index(Product $product, ShowReviewsRequest $request): JsonResponse
    {
        $request->validated();
        try {
            $reviews = $this->reviewService->getReviews($product, $request->rate);

            return response()->json([
                'status' => true,
                'reviews' => $reviews,
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
