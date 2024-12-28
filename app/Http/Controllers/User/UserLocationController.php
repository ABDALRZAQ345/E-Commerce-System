<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserLocationController extends Controller
{
    /**
     * @throws Exception
     */
    public function index(User $user): JsonResponse
    {
        try {
            $locations = $user->locations()->select(['id', 'name', 'longitude', 'latitude', 'key'])->get();

            return response()->json([
                'status' => true,
                'message' => 'locations retrieved successfully',
                'locations' => $locations,
            ]);
        }
        catch (\Exception $exception){
            Throw new Exception("Something went wrong");
        }

    }

    public function store(Request $request, User $user): JsonResponse
    {

        $validated = $request->validate([
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'name' => ['required', 'string', 'max:50'],
            'key' => ['required', 'string', 'max:50'],
        ]);

        $location = $user->locations()->create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Location added successfully',
            'location' => $location,
        ]);
    }

    public function update(Request $request, User $user, Location $location)
    {
        $location = $user->locations()->findOrFail($location->id);
        $validated = $request->validate([
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'name' => ['required', 'string', 'max:50'],
            'key' => ['required', 'string', 'max:50'],
        ]);
        $location->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Location updated successfully',
            'location' => $location,
        ]);

    }
}
