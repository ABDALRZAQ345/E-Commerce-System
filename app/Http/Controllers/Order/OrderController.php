<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatusEnum;
use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreOrderRequest;
use App\Jobs\SendNotification;
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
            'message' => 'orders retrieved successfully',
            'orders' => $orders,
        ]);
    }

    public function show(User $user, Order $order): JsonResponse
    {

        $order = $user->orders()->findOrFail($order->id);

        return response()->json([
            'status' => true,
            'message' => 'order retrieved successfully',
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

            $order = $this->orderService->createOrder(Auth::id(), $products, $validated['location_id']);
            SendNotification::dispatch(Auth::user(), 'i am abd and i am testing notifications', 'new order placed its status is '.OrderStatusEnum::Pending, [
                'order_id' => $order->id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Order created successfully',
            ], 201);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
