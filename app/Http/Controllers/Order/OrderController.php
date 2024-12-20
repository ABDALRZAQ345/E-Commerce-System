<?php

namespace App\Http\Controllers\Order;

use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\StoreOrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Services\Order\OrderService;
use App\Services\StripeService;
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
        $paymentMethodId = $validated['payment_method_id'];
        try {
            $stripeService = new StripeService(); // خدمة الدفع
            $paymentIntent = $stripeService->createPaymentIntent(
                $this->calculateOrderAmount($products), // حساب المبلغ الكلي
                'usd', // العملة
                $paymentMethodId // معرف طريقة الدفع من الجهة الأمامية
            );

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment failed. Please try again.',
                ], 400);
            }
            $this->orderService->createOrder(Auth::id(), $products,$validated['location_id']);

            return response()->json([
                'status' => true,
                'message' => 'Order created successfully',
            ], 201);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
    private function calculateOrderAmount(array $products): int
    {
        $totalAmount = 0;

        foreach ($products as $product) {
            $totalAmount += $product['price'] * $product['quantity'];
        }

    // التحويل لسنت لان الدفع بلدولار
        return $totalAmount * 100;
    }

}
