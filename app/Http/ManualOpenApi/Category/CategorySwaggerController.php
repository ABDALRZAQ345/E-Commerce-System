<?php

namespace App\Http\ManualOpenApi\Category;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     required={"id", "name"},
 *
 *     @OA\Property(property="id", type="integer", description="Category unique identifier"),
 *     @OA\Property(property="name", type="string", description="Category name")
 * )
 */
class CategorySwaggerController
{
    /**
     * @OA\Get(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Get all categories",
     *     description="Retrieve a list of all categories. Any authorized user can access this endpoint, regardless of their role.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of categories",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/Category")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Authentication is required",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Server error message")
     *         )
     *     )
     * )
     */
    public function index() {}
}
