<?php

namespace App\Http\ManualOpenApi\User;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Location",
 *     type="object",
 *
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the location"),
 *     @OA\Property(property="latitude", type="number", format="float", description="Latitude of the location"),
 *     @OA\Property(property="longitude", type="number", format="float", description="Longitude of the location"),
 *     @OA\Property(property="name", type="string", maxLength=50, description="Name of the location")
 * )
 */
class UserLocationSwaggerController
{
    /**
     * @OA\Post(
     *     path="/users/{user}/locations",
     *     summary="Add locations for a user",
     *     description="Allows the authenticated user to add locations with longitude, latitude, and name. A maximum of 5 locations can be added.",
     *     tags={"Users", "Locations"},
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
     *         description="Request body for adding locations",
     *
     *         @OA\JsonContent(
     *             type="object",
     *             required={"locations"},
     *
     *             @OA\Property(
     *                 property="locations",
     *                 type="array",
     *                 maxItems=5,
     *
     *                 @OA\Items(
     *                     type="object",
     *                     required={"longitude", "latitude", "name"},
     *
     *                     @OA\Property(property="longitude", type="number", format="float", description="Longitude of the location"),
     *                     @OA\Property(property="latitude", type="number", format="float", description="Latitude of the location"),
     *                     @OA\Property(property="name", type="string", maxLength=50, description="Name of the location")
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Locations added successfully",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Locations added successfully")
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
     *         response=404,
     *         description="User not found",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized to add locations",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/users/{user}/locations",
     *     summary="Get locations of a user",
     *     description="Retrieve the locations (name, longitude, and latitude) for a specific user.",
     *     tags={"Users", "Locations"},
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
     *     @OA\Response(
     *         response=200,
     *         description="List of locations for the user",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(
     *                 property="locations",
     *                 type="array",
     *
     *                 @OA\Items(
     *                     type="object",
     *
     *                     @OA\Property(property="name", type="string", description="Name of the location"),
     *                     @OA\Property(property="longitude", type="number", format="float", description="Longitude of the location"),
     *                     @OA\Property(property="latitude", type="number", format="float", description="Latitude of the location")
     *                 )
     *             )
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
     *             @OA\Property(property="message", type="string", example="Object not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized to view locations",
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
}
