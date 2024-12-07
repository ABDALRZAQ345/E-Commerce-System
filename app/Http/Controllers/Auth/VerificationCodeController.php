<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ServerErrorException;
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

    /**
     * @throws ServerErrorException
     */
    public function send(SendVerificationCodeRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $this->verificationCodeService->Send($validated['phone_number']);

            return response()->json([
                'status' => true,
                'message' => 'Verification code send successfully to '.$validated['phone_number'],
            ]);
        } catch (\Exception $exception) {
            throw new ServerErrorException($exception->getMessage());
        }

    }

    /**
     * @throws VerificationCodeException
     */
    public function Check(CheckVerificationCode $request): JsonResponse
    {
        $validated = $request->validated();

        $this->verificationCodeService->Check($validated['phone_number'], $validated['code']);

        return response()->json([
            'status' => true,
            'message' => 'Verification code is valid ',
        ]);

    }
}
