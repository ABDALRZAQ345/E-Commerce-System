<?php

namespace App\Http\ManualOpenApi\Store;

use App\Exceptions\ServerErrorException;
use OpenApi\Annotations as OA;

class StoreOrderController
{
    /**
     * @OA\Get(
     *     path="/stores/{store}/orders",
     *     summary="Get paginated orders of a store",
     *     description="Retrieve orders for a specific store with optional filtering by status and sorting by date. User must be the owner of the store.",
     *     tags={"Stores"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         required=true,
     *         description="ID of the store",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter orders by status (processing, shipped, delivered)",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"processing", "shipped", "delivered"}
     *         )
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
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of sub-orders",
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
     *         response=401,
     *         description="Unauthorized to view sub-orders",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Store not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Object not found.")
     *         )
     *     )
     * )
     *
     */
    public function index() {}


    /**
     * @OA\Post(
     *     path="/stores/{store}/orders/{order}",
     *     summary="Update order status",
     *     description="Update the status of an order. Only the owner of the store can update the status to the next status in the flow.",
     *     tags={"Stores"},
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         required=true,
     *         description="ID of the store",
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
     *         required=true,
     *         description="The new status for the order. Must be the next status in the flow.",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"processing", "shipped", "delivered"}
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Order status updated successfully.",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Order status updated successfully."),
     *             @OA\Property(property="order", ref="#/components/schemas/SubOrder")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status transition. Can only move to the next status.",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Invalid status transition. Can only move to the next status.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized to update order status",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Store or order not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Store or order not found.")
     *         )
     *     )
     * )
     */
    public function update() {}
}
