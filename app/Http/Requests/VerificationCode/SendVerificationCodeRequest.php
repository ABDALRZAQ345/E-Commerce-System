<?php

namespace App\Http\Requests\VerificationCode;

use App\Rules\ValidPhoneNumber;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class SendVerificationCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isRegistration = filter_var($this->input('registration'), FILTER_VALIDATE_BOOLEAN);

        return [
            'phone_number' => ['required', 'string', new ValidPhoneNumber,
                Rule::when(
                    $isRegistration,
                    ['unique:users,phone_number'],
                    ['exists:users,phone_number']
                ), ],
        ];

    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
