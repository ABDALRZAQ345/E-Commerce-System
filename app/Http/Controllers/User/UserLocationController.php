<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserLocationController extends Controller
{
    public function index(User $user): JsonResponse
    {
        $locations = $user->locations()->select(['name', 'longitude', 'latitude'])->get();

        return response()->json([
            'status' => true,
            'message' => 'locations retrieved successfully',
            'locations' => $locations,
        ]);
    }

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

        return response()->json([
            'status' => true,
            'message' => 'Locations added successfully',
        ]);
    }
}
