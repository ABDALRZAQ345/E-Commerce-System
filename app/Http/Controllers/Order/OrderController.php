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
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     required={"id", "user_id", "total", "created_at", "updated_at"},
 *
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example=1,
 *         description="The unique identifier for the order"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         example=1,
 *         description="The ID of the user who placed the order"
 *     ),
 *     @OA\Property(
 *         property="total",
 *         type="integer",
 *         example=1000,
 *         description="The total amount of the order"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-12-02T14:00:00Z",
 *         description="The timestamp when the order was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         example="2024-12-02T14:00:00Z",
 *         description="The timestamp when the order was last updated"
 *     )
 * )
 */
class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @OA\Get(
     *     path="/users/{user}/orders",
     *     summary="Get orders for a specific user",
     *     description="Retrieve orders for a specific user, optionally filtered by creation date. Only the authenticated user can access their own orders.",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=false,
     *         description="Sort orders by creation date (asc or desc)",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"asc", "desc"}
     *         )
     *     ),
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved orders",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(
     *                 property="orders",
     *                 type="object",
     *                 ref="#/components/schemas/Order"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized: Authentication required",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/users/{user}/orders/{order}",
     *     summary="Get a specific order for the authenticated user",
     *     description="Retrieve a specific order of the authenticated user by order ID.",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         description="ID of the order",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Order details for the user",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="order", ref="#/components/schemas/Order")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Order not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized to view this order",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     )
     * )
     */
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
    /**
     * @OA\Post(
     *     path="/users/{user}/orders",
     *     summary="Create a new order for a user",
     *     description="Allows the authenticated user to create a new order with a list of products. The user must be authorized to place an order.",
     *     tags={"Users", "Orders"},
     *
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Request body for creating an order",
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"products"},
     *
     *             @OA\Property(
     *                 property="products",
     *                 type="array",
     *
     *                 @OA\Items(
     *                     type="object",
     *                     required={"id", "quantity"},
     *
     *                     @OA\Property(property="id", type="integer", description="Product ID"),
     *                     @OA\Property(property="quantity", type="integer", description="Quantity of the product")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Order created successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error, invalid input",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to create order",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Failed to create order"),
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized to create an order",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     )
     * )
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
