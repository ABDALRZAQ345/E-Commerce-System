<?php

namespace App\Http\Controllers\User;

use App\Enums\RoleEnum;
use App\Exceptions\UNAUTHORIZED;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

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
            $user = $this->userService->FormatRoles($user);
            $user->active = rand(0, 1); // todo make it real value not random value

            return $user;
        });

        return response()->json($users);
    }

    /**
     * @throws UNAUTHORIZED
     */
    public function show(User $user): JsonResponse
    {
        if(!Auth::id() && !Auth::user()->hasRole('admin') ){
            throw new UNAUTHORIZED();
        }
        $user = $this->userService->FormatRoles($user);

        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }
}
