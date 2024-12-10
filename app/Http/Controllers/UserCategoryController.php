<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCategoryRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCategoryController extends Controller
{
    public function store(User $user,UserCategoryRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();

        $user->categories()->detach();
        $user->categories()->syncWithoutDetaching($validated['categories']);

        return response()->json([
           'status'=>true,
           'categories' => $user->categories
        ]);
    }

    public function index(User $user): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status'=>true,
            'categories' => $user->categories
        ]);
    }
}
