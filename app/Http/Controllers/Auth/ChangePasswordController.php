<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgetPasswordRequest;
use App\Models\User;
use App\Models\VerificationCode;
use App\Services\UserService;
use App\Services\VerificationService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
{

    public function ChangePassword(ChangePasswordRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $user = UserService::findUserByPhoneNumber($validatedData['phone_number']);
            if (!Hash::check($validatedData['current_password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Current password do not match our record.',
                ], 401);
            }

            UserService::updatePassword($user, $validatedData['new_password']);
            return response()->json(['message' => 'Password changed successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
