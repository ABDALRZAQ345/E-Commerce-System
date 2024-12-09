<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'integer', 'gt:0'],
            'discount' => ['required', 'integer', 'gte:0', 'lte:100'],
            'quantity' => ['required', 'integer', 'min:1'],
            'description' => ['required', 'string'],
            'expire_date' => ['nullable', 'date', 'after_or_equal:today'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'photos' =>['nullable', 'array','max:5'],
            'photos.*' => ['image', 'max:3072'],
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
