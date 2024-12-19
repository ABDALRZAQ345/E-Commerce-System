<?php

namespace App\Http\ManualOpenApi\Order;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SubOrder",
 *     type="object",
 *
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="order_id", type="integer", example=101, description="ID of the main order"),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"pending", "canceled", "delivered"},
 *         example="pending",
 *         description="Status of the sub-order"
 *     ),
 *     @OA\Property(property="total", type="integer", example=5000, description="Total amount for the sub-order"),
 *     @OA\Property(property="store_id", type="integer", example=10, description="ID of the store"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-01T12:00:00Z", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-02T12:00:00Z", description="Last update timestamp")
 * )
 */
class SubOrderSwaggerController
{
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
     *         description="Filter sub-orders by status (pending, canceled, delivered)",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"pending", "canceled", "delivered"}
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
    public function index() {}

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
    public function show() {}
}
