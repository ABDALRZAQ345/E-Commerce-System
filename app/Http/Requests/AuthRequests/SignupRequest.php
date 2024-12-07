<?php

namespace App\Http\Requests\AuthRequests;

use App\Rules\ValidPhoneNumber;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

class SignupRequest extends FormRequest
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
        return [
            'first_name' => ['required', 'max:50'],
            'last_name' => ['nullable', 'max:50'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone_number' => ['required', new ValidPhoneNumber, 'unique:users,phone_number'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:3072'],
            'code' => ['required', 'numeric', 'digits:6'],
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
