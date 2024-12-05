<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['nullable', 'in:asc,desc'],
        ]);

        $query = $user->orders();

        if (! empty($validated['date'])) {
            $query->orderBy('created_at', $validated['date']);
        }

        $orders = $query->paginate(20);

        return response()->json([
            'status' => true,
            'orders' => $orders,
        ]);
    }

    public function show(User $user, Order $order): JsonResponse
    {

        $order = $user->orders()->findOrFail($order->id);

        return response()->json([
            'status' => true,
            'order' => $order,
        ]);

    }

    /**
     * @throws \Throwable
     */
    public function store(StoreOrderRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();
        $products = $validated['products'];

        try {
            $this->orderService->createOrder(Auth::id(), $products);

            return response()->json([
                'message' => 'Order created successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
}
