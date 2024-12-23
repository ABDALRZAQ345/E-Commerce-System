<?php

namespace App\Jobs;

use App\Models\VerificationCode;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use SomarKesen\TelegramGateway\Facades\TelegramGateway;

class SendVerificationCode implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $phoneNumber;

    public function __construct($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        VerificationCode::where('phone_number', $this->phoneNumber)->delete();
        $code = rand(100000, 999999);
        VerificationCode::create([
            'phone_number' => $this->phoneNumber,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);
        Log::channel('verification_code')->info($code);
        TelegramGateway::sendVerificationMessage(config('app.data.telegram_number'), [
            'code' => $code,
            'ttl' => 300,
            'callback_url' => 'https://yourapp.com/callback',
        ]);
    }
}
