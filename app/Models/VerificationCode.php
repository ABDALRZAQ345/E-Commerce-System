<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;

    protected $fillable = ['phone_number', 'code', 'created_at', 'expires_at'];

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }
}
