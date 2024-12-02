<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SubOrder;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class SubOrderController extends Controller
{
    //
    /**
     * @OA\Get(
     *     path="/users/{user}/orders/{order}/suborders",
     *     summary="Get paginated sub-orders for a specific order",
     *     description="Retrieve paginated sub-orders of a specific order for the authenticated user with optional filtering by status.",
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
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         description="ID of the order",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter sub-orders by status (processing, shipped, delivered)",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"processing", "shipped", "delivered"}
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of sub-orders for the order",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/SubOrder")
     *             ),
     *
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=5),
     *             @OA\Property(property="per_page", type="integer", example=20),
     *             @OA\Property(property="total", type="integer", example=100)
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
     *         description="Unauthorized to view sub-orders for this order",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     )
     * )
     */
    public function index(Request $request, User $user, Order $order): JsonResponse
    {

        $order = $user->orders()->findOrFail($order->id);

        $validStatuses = OrderStatusEnum::getAllStatus();

        $subOrders = $order->subOrders();

        if ($request->filled('status') && in_array($request->status, $validStatuses)) {
            $subOrders->where('status', $request->status);
        }

        $paginatedSubOrders = $subOrders->paginate(20);

        return response()->json($paginatedSubOrders);
    }

    /**
     * @OA\Get(
     *     path="/users/{user}/orders/{order}/suborders/{subOrder}",
     *     summary="Get details of a specific sub-order",
     *     description="Retrieve details of a specific sub-order for a specific order of the authenticated user.",
     *     tags={"Users", "Orders", "SubOrders"},
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
     *     @OA\Parameter(
     *         name="subOrder",
     *         in="path",
     *         required=true,
     *         description="ID of the sub-order",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sub-order details",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="sub_order", ref="#/components/schemas/SubOrder")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Sub-order not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Sub-order not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized to view sub-order details",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     )
     * )
     */
    public function show(User $user, Order $order, SubOrder $subOrder): JsonResponse
    {
        $order = $user->orders()->findOrFail($order->id);
        $sub_order = $order->subOrders()->findOrFail($subOrder->id);

        return response()->json([
            'sub_order' => $sub_order,
        ]);
    }
}
