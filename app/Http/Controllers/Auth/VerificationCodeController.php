<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\VerificationCodeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerificationCode\CheckVerificationCode;
use App\Http\Requests\VerificationCode\SendVerificationCodeRequest;
use App\Services\VerificationCodeService;
use Illuminate\Http\JsonResponse;

class VerificationCodeController extends Controller
{
    //
    protected VerificationCodeService $verificationCodeService;

    public function __construct(VerificationCodeService $verificationCodeService)
    {
        $this->verificationCodeService = $verificationCodeService;
    }

    public function Send(SendVerificationCodeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->verificationCodeService->Send($validated['phone_number']);

        return response()->json([
            'status' => true,
            'message' => 'Verification code send successfully to '.$validated['phone_number'],
        ]);
    }

    /**
     * @throws VerificationCodeException
     */
    public function Check(CheckVerificationCode $request): JsonResponse
    {
        $validated = $request->validated();

        $phoneNumber = $validated['phone_number'];
        $code = $validated['code'];

        $this->verificationCodeService->Check($phoneNumber, $code);

        return response()->json([
            'status' => true,
            'message' => 'Verification code is valid ',
        ]);
    }
}
