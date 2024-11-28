<?php

namespace App\Services;

use App\Models\VerificationCode;
use Illuminate\Support\Str;

class VerificationService
{
    public static function createCode($phone_number)
    {
        $code = Str::random(6);

        VerificationCode::create([
            'phone_number' => $phone_number,
            'code' => $code,
            'created_at' => now(),
            'expires_at' => now()->addMinutes(1),
        ]);

        return $code;
    }
    public static function checkCode($phone_number, $code)
    {
        $verificationCode = VerificationCode::where('phone_number', $phone_number)
            ->where('code', $code)
            ->firstOrFail();

        if (now()->greaterThan($verificationCode->expires_at)) {
            throw new \Exception('Code has expired');
        }
        $verificationCode->delete();

    }
}
