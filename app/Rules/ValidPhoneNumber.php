<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (str_starts_with($value, '+963')) {
            $value = '0'.substr($value, 4); // Replace "+963" with "0"
        }

        if (! preg_match('/^09\d{8}$/', $value)) {
            $fail("The $attribute must start with '09' and be exactly 10 digits.");
        }
    }
}
