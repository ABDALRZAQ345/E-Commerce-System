<?php

namespace App\Http\ManualOpenApi\Store;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Store",
 *     type="object",
 *     required={"id", "name", "user_id", "created_at", "updated_at"},
 *
 *     @OA\Property(property="id", type="integer", description="The unique identifier for the store"),
 *     @OA\Property(property="name", type="string", maxLength=50, description="The name of the store"),
 *     @OA\Property(property="user_id", type="integer", description="The ID of the user who owns the store"),
 *     @OA\Property(property="description", type="string", nullable=true, description="A description of the store"),
 *     @OA\Property(property="photo", type="string", nullable=true, description="URL or path to the store's photo"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the store was created"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the store was last updated"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Timestamp when the store was soft deleted")
 * )
 */
class FavouriteStoreContactSwaggerController
{
    /**
     * @OA\Delete(
     *     path="/api/favourites/stores/{store}",
     *     summary="Remove Store from Favourites",
     *     description="Remove a store from the user's list of favourites.",
     *     operationId="removeFavouriteStore",
     *     tags={"Favourites"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         required=true,
     *         description="The ID of the store to remove from favourites",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Store removed from favourites",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Store removed from favourites")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Store not found in favourites",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Object not fount")
     *         )
     *     )
     * )
     */
    public function delete() {}

    /**
     * @OA\Post(
     *     path="favourites/stores/{store}",
     *     summary="Add Store to Favourites",
     *     description="Add a store to the user's list of favourites.",
     *     operationId="addFavouriteStore",
     *     tags={"Favourites"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *
     *     @OA\Parameter(
     *         name="store",
     *         in="path",
     *         required=true,
     *         description="The ID of the store to add to favourites",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Store added to favourites",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Store added to favourites")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Store already in favourites",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Store is already in your favourite list")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to add favourite",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", example="Failed to add favourite"),
     *             @OA\Property(property="message", type="string", description="Exception message")
     *         )
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/favourites/stores",
     *     summary="Get Favourite Stores",
     *     description="Retrieve a list of the user's favourite stores.",
     *     operationId="getFavouriteStores",
     *     tags={"Favourites"},
     *     security={
     *         {"sanctum": {}}
     *     },
     *
     *     @OA\Response(
     *         response=200,
     *         description="List of favourite stores",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", description="Request status"),
     *             @OA\Property(
     *                 property="favourites",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/Store")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve favourites",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="error", type="string", description="Error message"),
     *             @OA\Property(property="message", type="string", description="Exception message")
     *         )
     *     )
     * )
     */
    public function getFavourites() {}
}
