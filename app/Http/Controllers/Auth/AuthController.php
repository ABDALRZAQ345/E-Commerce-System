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
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected UserService $userService;

    protected VerificationCodeService $verificationCodeService;

    public function __construct(UserService $userService, VerificationCodeService $verificationCodeService)
    {
        $this->userService = $userService;
        $this->verificationCodeService = $verificationCodeService;
    }

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
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

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
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $user = \Auth::user();
            $user->tokens()->delete();

            return response()->json([
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(UpdateUserRequest $request,User $user): JsonResponse
    {
        $validated = $request->validated();
        try {
            $user = \Auth::user();
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
