<?php

namespace App\Http\ManualOpenApi\User;

use OpenApi\Annotations as OA;

class UserSwaggerController
{
    /**
     * @OA\Put(
     *     path="/users/{user}/update",
     *     tags={"Users"},
     *     summary="Update a user",
     *     description="Updates the details of the  user. Requires authentication.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="User ID to update",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="first_name", type="string", maxLength=50, example="John"),
     *             @OA\Property(property="last_name", type="string", maxLength=50, example="Doe"),
     *             @OA\Property(
     *                 property="photo",
     *                 type="string",
     *                 format="binary",
     *                 description="Photo of the user in jpg, jpeg, png, gif formats"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing token - not the same user",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="errors", type="object", example={"first_name": {"The first name field is required."}})
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Something went wrong")
     *         )
     *     )
     * )
     */
    public function update() {}

    /**
     * @OA\Get(
     *     path="/users/{user}",
     *     summary="Get a user by ID",
     *     description="Retrieve a user by their ID. Only accessible by the user themselves or an admin.",
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
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="User details successfully retrieved",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 ref="#/components/schemas/User"
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
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     )
     * )
     */
    public function show() {}

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get paginated list of users",
     *     description="Retrieve users with optional filtering by role. Only accessible by admin.",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         required=false,
     *         description="Filter users by role",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"admin", "user", "manager"}
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of users",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/User")
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
     *         description="Unauthorized: Authentication required",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function index() {}
}
