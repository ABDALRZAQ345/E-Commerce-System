<?php

namespace App\Http\Controllers\Store;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Requests\ShowReviewsRequest;
use App\Models\Store;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StoreReviewController extends Controller
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
    public function review(ReviewRequest $request, Store $store): JsonResponse
    {
        $validated = $request->validated();
        try {

            $user = Auth::user();
            $this->reviewService->AddReview($user, $store, $validated['rate'], $validated['comment']);

            return response()->json([
                'status' => true,
                'message' => 'store rated successfully',

            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }

    /**
     * @throws ServerErrorException
     */
    public function index(Store $store, ShowReviewsRequest $request): JsonResponse
    {
        $request->validated();
        try {
            $reviews = $this->reviewService->GetReviews($store, $request->rate);

            return response()->json([
                'status' => true,
                'reviews' => $reviews,
            ]);

        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
