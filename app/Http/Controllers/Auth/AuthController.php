<?php

namespace App\Http\Controllers\Auth;

use App\Events\RegisteredEvent;
use App\Exceptions\ServerErrorException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Http\Requests\AuthRequests\SignupRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\VerificationCode;
use App\Services\UserService;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(SignupRequest $request)
    {
        $validatedData = $request->validated();
        try {
            if(User::where('phone_number', $validatedData['phone_number'])->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone number has taken before',
                ], 403);
            }

            $user = UserService::createUser($validatedData);
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
            ], 200);
        } catch (\Throwable $th) {
            throw new ServerErrorException($th->getMessage());
        }
    }
    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $user = UserService::findUserByPhoneNumber($validatedData['phone_number']);
            if (!Hash::check($validatedData['password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone & Password do not match our record.',
                ], 401);
            }
            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            throw new ServerErrorException($th);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json([
                'message' => 'Logged out successfully',
            ]);
        } catch (\Throwable $th) {
            throw new ServerErrorException($th);
        }
    }

    public function update(UpdateUserRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $user = UserService::findUserByPhoneNumber($validatedData['phone_number']);
            UserService::updatePhoneNumber($user, $validatedData['new_phone_number']);
        } catch (\Exception $e) {
            throw new ServerErrorException($e);

        }
    }
}
