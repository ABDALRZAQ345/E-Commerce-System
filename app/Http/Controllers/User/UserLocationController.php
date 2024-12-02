<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class UserLocationController extends Controller
{
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
    public function index(User $user): JsonResponse
    {
        $locations = $user->locations()->select(['name', 'longitude', 'latitude'])->get();

        return response()->json([
            'status' => true,
            'locations' => $locations,
        ]);
    }

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
    public function store(Request $request, User $user): JsonResponse
    {

        $validated = $request->validate([
            'locations' => ['required', 'array', 'max:5'],
            'locations.*.longitude' => ['required', 'numeric', 'between:-180,180'],
            'locations.*.latitude' => ['required', 'numeric', 'between:-90,90'],
            'locations.*.name' => ['required', 'string', 'max:50'],
        ]);

        $user = User::findOrFail($user->id);

        $user->locations()->delete();
        $user->locations()->createMany($validated['locations']);

        return response()->json(['message' => 'Locations added successfully']);
    }
}
