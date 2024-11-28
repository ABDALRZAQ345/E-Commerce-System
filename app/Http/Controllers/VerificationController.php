<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckVerificationRequest;
use App\Http\Requests\VerificationRequest;
use App\Models\VerificationCode;
use App\Services\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    public function sendVerificationCode(VerificationRequest $request)
    {
        $validatedData = $request->validated();
        try {
            VerificationCode::where('phone_number', $validatedData['phone_number'])->delete();
            $code = VerificationService::createCode($validatedData['phone_number']);
            Log::channel('verification_code')->info("Password reset code for {$validatedData['phone_number']}: {$code}");
            return response()->json(['message' => 'Verification number sended successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function checkVerificationCode(CheckVerificationRequest $request)
    {
        $validatedData = $request->validated();
        try {
            VerificationService::checkCode($validatedData['phone_number'], $validatedData['code']);
            return response()->json(['message' => 'Verification number is correct!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}
