<?php

namespace App\Http\ManualOpenApi\Store;

use OpenApi\Annotations as OA;

class StoreSwaggerController
{
    /**
     * @OA\Get(
     *     path="/stores",
     *     summary="Get a paginated list of stores",
     *     tags={"Stores"},
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search query to filter stores by name",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of stores retrieved successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="stores",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", description="Current page number"),
     *                 @OA\Property(property="data", type="array", description="List of stores", @OA\Items(ref="#/components/schemas/Store")),
     *                 @OA\Property(property="first_page_url", type="string", description="URL of the first page"),
     *                 @OA\Property(property="from", type="integer", nullable=true, description="Index of the first store in the current page"),
     *                 @OA\Property(property="last_page", type="integer", description="Total number of pages"),
     *                 @OA\Property(property="last_page_url", type="string", description="URL of the last page"),
     *                 @OA\Property(property="links", type="array", description="Pagination links", @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="url", type="string", nullable=true, description="URL for the page"),
     *                     @OA\Property(property="label", type="string", description="Label for the page"),
     *                     @OA\Property(property="active", type="boolean", description="Indicates if the page is active")
     *                 )),
     *                 @OA\Property(property="next_page_url", type="string", nullable=true, description="URL of the next page"),
     *                 @OA\Property(property="path", type="string", description="Base URL for the resource"),
     *                 @OA\Property(property="per_page", type="integer", description="Number of items per page"),
     *                 @OA\Property(property="prev_page_url", type="string", nullable=true, description="URL of the previous page"),
     *                 @OA\Property(property="to", type="integer", nullable=true, description="Index of the last store in the current page"),
     *                 @OA\Property(property="total", type="integer", description="Total number of stores available")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="search",
     *                     type="array",
     *                     items=@OA\Items(type="string"),
     *                     description="Validation errors for the search query"
     *                 )
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index() {}

    /**
     * @OA\Get(
     *     path="/stores/{store}",
     *     summary="Get store details",
     *     description="Fetch the details of a specific store including its contacts, locations, and categories.",
     *     tags={"Stores"},
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         description="The unique identifier of the store",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved the store details",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="store", ref="#/components/schemas/Store"),
     *             @OA\Property(property="contacts", type="array", @OA\Items(ref="#/components/schemas/Contact")),
     *             @OA\Property(property="locations", type="array", @OA\Items(ref="#/components/schemas/Location")),
     *             @OA\Property(property="categories", type="array", @OA\Items(ref="#/components/schemas/Category"))
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
     *             @OA\Property(property="message", type="string", example="Object not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     ),
     *     security={{"BearerAuth": {}}}
     * )
     */
    public function show() {}

    /**
     * @OA\Post(
     *     path="/stores",
     *     summary="Create a new store",
     *     description="Create a new store if the user is a manager and does not already have a store.",
     *     tags={"Stores"},
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Store data",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name"},
     *
     *                 @OA\Property(property="name", type="string", description="The name of the store", maxLength=50),
     *                 @OA\Property(property="description", type="string", description="Description of the store", nullable=true),
     *                 @OA\Property(property="photo", type="string", format="uri", description="The photo of the store", nullable=true)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Store created successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Store created successfully"),
     *             @OA\Property(property="store", ref="#/components/schemas/Store")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="errors", type="object", additionalProperties={"type":"array", "items":{"type":"string"}})
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Forbidden: User is not authorized to create a store or already has a store",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Post(
     *     path="/stores/{store}",
     *     summary="Update store details",
     *     description="Update an existing store's details including optional photo upload.",
     *     tags={"Stores"},
     *     security={{"BearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         description="The unique identifier of the store",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Store data",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *
     *             @OA\Schema(
     *                 type="object",
     *                 required={},
     *
     *                 @OA\Property(property="name", type="string", description="The name of the store", maxLength=50),
     *                 @OA\Property(property="description", type="string", description="Description of the store", nullable=true),
     *                 @OA\Property(property="photo", type="string", format="binary", description="The photo of the store (optional)"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Store updated successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Store updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="errors", type="object", additionalProperties={"type":"array", "items":{"type":"string"}})
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Forbidden: User is not authorized to update this store",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Error message here")
     *         )
     *     )
     * )
     */
    public function update() {}
}
