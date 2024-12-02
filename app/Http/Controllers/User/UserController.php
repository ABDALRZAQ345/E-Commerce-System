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
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get paginated list of users",
     *     description="Retrieve users with optional filtering by role. Only accessible by admin.",
     *     tags={"Users"},
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         required=false,
     *         description="Filter users by role",
     *
     *         @OA\Schema(
     *             type="string",
     *             enum={"admin", "user", "manager"}
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Paginated list of users",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/User")
     *             ),
     *
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=5),
     *             @OA\Property(property="per_page", type="integer", example=20),
     *             @OA\Property(property="total", type="integer", example=100)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized: Authentication required",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
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
    /**
     * @OA\Get(
     *     path="/users/{user}",
     *     summary="Get a user by ID",
     *     description="Retrieve a user by their ID. Only accessible by the user themselves or an admin.",
     *     tags={"Users"},
     *
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *
     *         @OA\Schema(type="integer")
     *     ),
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="User details successfully retrieved",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized: Authentication required",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
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
     *             @OA\Property(property="message", type="string", example="User not found.")
     *         )
     *     )
     * )
     */
    public function show(User $user): JsonResponse
    {
        if (! Auth::id() && ! Auth::user()->hasRole('admin')) {
            throw new UNAUTHORIZED;
        }
        $user = $this->userService->FormatRoles($user);

        return response()->json([
            'status' => true,
            'user' => $user,
        ]);
    }
}
