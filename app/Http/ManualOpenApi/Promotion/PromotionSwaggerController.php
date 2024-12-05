<?php

namespace App\Http\ManualOpenApi\Promotion;

use OpenApi\Annotations as OA;

class PromotionSwaggerController
{
    /**
     * @OA\Get(
     *     path="/promotions",
     *     tags={"Promotions"},
     *     summary="List all promotion requests",
     *     description="Retrieve a paginated list of promotion requests. Only accessible to admins.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="accepted",
     *         in="query",
     *         description="Filter promotions by their acceptance status. Use 'true' for accepted requests and 'false' for pending requests.",
     *         required=false,
     *
     *         @OA\Schema(type="boolean", example=true)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of promotion requests retrieved successfully.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *
     *                     @OA\Items(
     *
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="user_id", type="integer", example=5),
     *                         @OA\Property(property="accepted_at", type="string", format="datetime", nullable=true, example="2024-12-01 12:00:00"),
     *                         @OA\Property(property="created_at", type="string", format="datetime", example="2024-12-01 10:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="datetime", example="2024-12-01 11:00:00")
     *                     )
     *                 ),
     *                 @OA\Property(property="total", type="integer", example=20),
     *                 @OA\Property(property="per_page", type="integer", example=15),
     *                 @OA\Property(property="last_page", type="integer", example=2)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden. User does not have the required admin role.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="You do not have permission to perform this action."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error occurred.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An unexpected error occurred."
     *             )
     *         )
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/promotions/create",
     *     tags={"Promotions"},
     *     summary="Create a promotion request",
     *     description="Allows a user to send a promotion request to become a manager. Users can only send one request, and users with an existing manager role cannot send a request.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=201,
     *         description="Promotion request created successfully.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Promotion request created successfully"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="user_id",
     *                     type="integer",
     *                     example=123
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     format="datetime",
     *                     example="2024-12-01T12:34:56.000000Z"
     *                 ),
     *                 @OA\Property(
     *                     property="updated_at",
     *                     type="string",
     *                     format="datetime",
     *                     example="2024-12-01T12:34:56.000000Z"
     *                 ),
     *                  @OA\Property(
     *                      property="accepted_at",
     *                      type="string",
     *                      format="datetime",
     *                      example="2024-12-01T12:34:56.000000Z"
     *                  )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request due to one of the following reasons: the user already has a manager role, or a promotion request already exists.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="you already have a manager role"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error occurred.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Server error message"
     *             )
     *         )
     *     )
     * )
     */
    public function create() {}

    /**
     * @OA\Post(
     *     path="/promotions/{promotion}/accept",
     *     tags={"Promotions"},
     *     summary="Promote a user to a Manager role",
     *     description="Allows an authorized admin to promote a user by accepting their promotion request.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="promotion",
     *         in="path",
     *         required=true,
     *         description="ID of the promotion request",
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User promoted successfully.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User Promoted Successfully"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden. User does not have the required admin role.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="You do not have permission to perform this action."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Promotion request not found.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Promotion request not found."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error occurred.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(
     *                 property="status",
     *                 type="boolean",
     *                 example=false
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="An unexpected error occurred."
     *             )
     *         )
     *     )
     * )
     */
    public function promote() {}

    /**
     * @OA\Post(
     *     path="/promotions/{promotion}/reject",
     *     tags={"Promotions"},
     *     summary="Reject a promotion request",
     *     description="Rejects a promotion request by deleting it. Only accessible to admins.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="promotion",
     *         in="path",
     *         required=true,
     *         description="ID of the promotion request to be rejected",
     *
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Promotion request rejected successfully.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User Promotion rejected Successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Promotion request not found.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Promotion not found.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden. User does not have the required admin role.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You do not have permission to perform this action.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error occurred.",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred.")
     *         )
     *     )
     * )
     */
    public function reject() {}
}
