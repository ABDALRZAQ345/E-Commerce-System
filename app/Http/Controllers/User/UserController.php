<?php

namespace App\Http\Controllers\User;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::query();
        if ($request->filled('role') && in_array($request->role, RoleEnum::getAllRoles())) {
            $users->whereHas('roles', function ($query) use ($request) {
                $query->where('name', $request->role); // Assuming roles are filtered by name
            });
        }
        $users = $users->with('roles:name')->paginate(20);
        $users->getCollection()->transform(function ($user) {
            $roles = RoleEnum::getAllRoles();
            $roleStatuses = [];
            $userRoles = $user->roles->pluck('name')->toArray();
            foreach ($roles as $role) {
                $roleStatuses[$role] = in_array($role, $userRoles);
            }
            $user->roles_status = $roleStatuses;
            unset($user->roles);

            return $user;
        });

        return response()->json($users);
    }

    public function show(User $user)
    {
        //todo
    }
}
