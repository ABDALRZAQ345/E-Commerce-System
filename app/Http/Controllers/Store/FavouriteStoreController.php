<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\FavouriteStore;
use App\Models\Store;
use Exception;
use Illuminate\Support\Facades\Auth;
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
class FavouriteStoreController extends Controller
{
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
    public function getFavourites()
    {
        try {
            $user = Auth::user();
            $favourites = $user->favouriteStores()->get();

            return response()->json([
                'status' => true,
                'favourites' => $favourites,
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve favourites', 'message' => $e->getMessage()], 500);
        }
    }

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
    public function addFavourite(Store $store)
    {

        try {
            $user = Auth::user();
            if (FavouriteStore::where('user_id', $user->id)->where('store_id', $store->id)->first()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Store is already in your favourite list',
                ]);
            }
            $user->favouriteStores()->attach($store);

            return response()->json(['message' => 'Store added to favourites'], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to add favourite', 'message' => $e->getMessage()], 500);
        }
    }

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
    public function removeFavourite(Store $store)
    {

        $user = Auth::user();
        $store = $user->favouriteStores()->findOrFail($store->id);
        $user->favouriteStores()->detach($store);

        return response()->json(['message' => 'Store removed from favourites'], 200);

    }
}
