<?php

namespace App\Services;

use App\Exceptions\VerificationCodeException;
use App\Jobs\SendVerificationCode;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class VerificationCodeService
{
    public static function Send($phone_number): void
    {
        SendVerificationCode::dispatch($phone_number);
    }

    /**
     * @throws VerificationCodeException
     */
    public static function Check($phoneNumber, $code): void
    {
        $verificationCode = VerificationCode::where('phone_number', $phoneNumber)->first();

        if (! $verificationCode || ! Hash::check($code, $verificationCode->code)) {
            throw new VerificationCodeException;
        }
        if ($verificationCode->isExpired()) {
            throw new VerificationCodeException('Expired code');
        }

    }

    /**
     * @throws VerificationCodeException
     */
    public function delete($phoneNumber): void
    {

            VerificationCode::where(
                'phone_number',
                $phoneNumber
            )->delete();


    }
}
