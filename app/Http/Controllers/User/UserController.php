<?php

namespace App\Http\Controllers\User;

use App\Enums\RoleEnum;
use App\Exceptions\ServerErrorException;
use App\Exceptions\UNAUTHORIZED;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
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
                $query->where('name', $request->role);
            });
        }

        $users = $users->with('roles:name')->paginate(20);

        $users->getCollection()->transform(function ($user) {
            $user = $this->userService->FormatRoles($user);
            $user->active = now()->subMinutes(5) <= $user->last_login;
            return $user;
        });

        return response()->json($users);
    }

    /**
     * @throws UNAUTHORIZED
     */
    public function show(User $user): JsonResponse
    {
        if (! Auth::id() && ! Auth::user()->hasRole('admin')) {
            throw new UNAUTHORIZED;
        }
        $user = $this->userService->FormatRoles($user);

        return response()->json([
            'status' => true,
            'message' => 'user retrieved successfully',
            'user' => $user,
        ]);
    }

    /**
     * @throws ServerErrorException
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();
        try {
            if ($request->has('photo') && $validated['photo'] != null) {
                if ($user->photo) {
                    DeletePublicPhoto($user->photo);
                }
                $validated['photo'] = NewPublicPhoto($validated['photo'], 'profiles');
            }
            $user->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'User updated successfully',
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }
}
