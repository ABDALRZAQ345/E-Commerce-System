<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Password\ForgetPasswordRequest;
use App\Services\UserService;
use App\Services\VerificationCodeService;
use Illuminate\Http\JsonResponse;

class PasswordController extends Controller
{
    protected VerificationCodeService $verificationCodeService;

    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    public function Forget(ForgetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $this->verificationCodeService->Check($validated['phone_number'], $validated['code']);
            $user = UserService::findUserByPhoneNumber($validated['phone_number']);

            UserService::updatePassword($user, $validated['password']);
            $this->verificationCodeService->delete($validated['phone_number']);

            return response()->json(['message' => 'Password changed successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
