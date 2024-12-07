<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Exceptions\VerificationCodeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\SignupRequest;
use App\Models\User;
use App\Services\UserService;
use App\Services\VerificationCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    /**
     * @throws ServerErrorException
     * @throws VerificationCodeException
     */
    public function register(SignupRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->verificationCodeService->Check($validated['phone_number'], $validated['code']);

        try {

            $user = UserService::createUser($validated);
            $this->verificationCodeService->delete($validated['phone_number']);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
                'user' => $this->userService->FormatRoles($user),
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        try {
            $user = User::where('phone_number', $validatedData['phone_number'])->firstOrFail();
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
                'user' => $this->userService->FormatRoles($user),
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully',
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }
}
