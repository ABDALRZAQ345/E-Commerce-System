<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
use App\Exceptions\VerificationCodeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Password\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Services\UserService;
use App\Services\VerificationCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    protected VerificationCodeService $verificationCodeService;

    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    /**
     * @throws ServerErrorException
     * @throws VerificationCodeException
     */
    public function forget(ForgetPasswordRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $this->verificationCodeService->Check($validated['phone_number'], $validated['code']);

        try {

            $user = User::where('phone_number', $validated['phone_number'])->firstOrFail();

            UserService::updatePassword($user, $validated['password']);

            $this->verificationCodeService->delete($validated['phone_number']);

            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully!'
            ]);
        } catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }
    }

    /**
     * @throws ServerErrorException
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {

        $validated = $request->validated();
        $user = Auth::user();

        try {
            if (Hash::check($validated['old_password'], $user->password)) {

                UserService::updatePassword($user, $validated['new_password']);


                return response()->json([
                    'status' => true,
                    'message' => 'Password reset successfully!',
                ]);
            }


            return response()->json([
                'status' => false,
                'error' => 'Wrong old password!',
            ], 403);
        }
        catch (\Exception $e) {
            throw new ServerErrorException($e->getMessage());
        }

    }
}
