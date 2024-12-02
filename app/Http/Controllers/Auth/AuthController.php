<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\SignupRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use App\Services\VerificationCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"id", "first_name", "last_name", "phone_number", "created_at", "updated_at", "roles_status"},
 *
 *     @OA\Property(property="id", type="integer", description="The user's unique ID"),
 *     @OA\Property(property="first_name", type="string", description="The user's first name"),
 *     @OA\Property(property="last_name", type="string", description="The user's last name"),
 *     @OA\Property(property="phone_number", type="string", description="The user's phone number"),
 *     @OA\Property(property="photo", type="string", nullable=true, description="Profile photo URL or base64 encoded (optional)"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp of when the user was created"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp of when the user was last updated"),
 *     @OA\Property(
 *         property="roles_status",
 *         type="object",
 *         description="The user's roles status",
 *         required={"manager", "admin", "user"},
 *         @OA\Property(property="manager", type="boolean", description="Indicates if the user is a manager"),
 *         @OA\Property(property="admin", type="boolean", description="Indicates if the user is an admin"),
 *         @OA\Property(property="user", type="boolean", description="Indicates if the user is a regular user")
 *     )
 * )
 */
class AuthController extends Controller
{
    protected UserService $userService;

    protected VerificationCodeService $verificationCodeService;

    public function __construct(UserService $userService, VerificationCodeService $verificationCodeService)
    {
        $this->userService = $userService;
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Auth"},
     *     summary="Register a new user",
     *     description="Registers a new user by validating the phone number, code, and user details.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"first_name", "password", "phone_number", "code"},
     *
     *             @OA\Property(property="first_name", type="string", maxLength=50, description="The user's first name"),
     *             @OA\Property(property="last_name", type="string", maxLength=50, description="The user's last name"),
     *             @OA\Property(property="password", type="string", description="The user's password"),
     *             @OA\Property(property="phone_number", type="string", description="The user's phone number"),
     *             @OA\Property(property="photo", type="string", format="binary", description="Profile photo (optional)"),
     *             @OA\Property(property="code", type="string", description="Verification code sent to the user", maxLength=6)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User Created Successfully"),
     *             @OA\Property(property="token", type="string", example="sample-sanctum-token"),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error message")
     *         )
     *     ),
     *
     *     @OA\Response(
     *          response=422,
     *          description="Validation error",
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="phone_number",
     *                      type="array",
     *
     *                      @OA\Items(
     *                          type="string",
     *                          example="The phone number field is required."
     *                      )
     *                  ),
     *
     *                  @OA\Property(
     *                      property="code",
     *                      type="array",
     *
     *                      @OA\Items(
     *                          type="string",
     *                          example="The verification code must be exactly 6 digits."
     *                      )
     *                  )
     *              )
     *          )
     *      )
     * )
     */
    public function register(SignupRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $this->verificationCodeService->Check($validated['phone_number'], $validated['code']);
            $user = UserService::createUser($validated);
            $this->verificationCodeService->delete($validated['phone_number']);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
                'user' => $user = $this->userService->FormatRoles($user),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="User login",
     *     description="Logs in a user by validating the phone number and password.",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"phone_number", "password"},
     *
     *             @OA\Property(property="phone_number", type="string", description="The user's phone number"),
     *             @OA\Property(property="password", type="string", description="The user's password")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User logged in successfully"),
     *             @OA\Property(property="token", type="string", example="sample-sanctum-token"),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid phone number or password",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Phone & Password do not match our record.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="phone_number",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="string",
     *                         example="The phone number field is required."
     *                     )
     *                 ),
     *
     *                 @OA\Property(
     *                     property="password",
     *                     type="array",
     *
     *                     @OA\Items(
     *                         type="string",
     *                         example="The password field is required."
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error message")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        try {
            $user = UserService::findUserByPhoneNumber($validatedData['phone_number']);
            if (! Hash::check($validatedData['password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone & Password do not match our record.',
                ], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
                'user' => $user = $this->userService->FormatRoles($user),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Auth"},
     *     summary="User logout",
     *     description="Logs out the user by deleting their active Sanctum token.",
     *    security={{"bearerAuth": {} }},
     *
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - No active token found or invalid token",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Server error message")
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = \Auth::user();
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @OA\Put(
     *     path="/users/{user}/update",
     *     tags={"Users"},
     *     summary="Update a user",
     *     description="Updates the details of the  user. Requires authentication.",
     *     security={{"bearerAuth": {}}},
     *
     *     @OA\Parameter(
     *         name="user",
     *         in="path",
     *         description="User ID to update",
     *         required=true,
     *
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="first_name", type="string", maxLength=50, example="John"),
     *             @OA\Property(property="last_name", type="string", maxLength=50, example="Doe"),
     *             @OA\Property(
     *                 property="photo",
     *                 type="string",
     *                 format="binary",
     *                 description="Photo of the user in jpg, jpeg, png, gif formats"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User updated successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - Invalid or missing token - not the same user",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="errors", type="object", example={"first_name": {"The first name field is required."}})
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Something went wrong")
     *         )
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();
        try {

            $user->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'User updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
