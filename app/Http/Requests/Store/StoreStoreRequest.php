<?php

namespace App\Http\Requests\Store;

use App\Models\Store;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     *     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', function ($attribute, $value, $fail) {
                if (Store::where('name', $value)->exists()) {
                    $fail('there is another store with the same name');
                }
            }],
            'description' => ['nullable'],
            'photo' => ['nullable', 'image', 'max:3072'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['required', 'image', 'max:3072'],
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
