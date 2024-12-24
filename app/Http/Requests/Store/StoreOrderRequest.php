<?php

namespace App\Http\Requests\Store;

use App\Models\Product;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreOrderRequest extends FormRequest
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
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1',  function ($attribute, $value, $fail) {
                $index = explode('.', $attribute)[1];
                $productId = $this->input("products.$index.id");

                $product = Product::find($productId);

                if ($product && $value > $product->quantity) {
                    $fail("The quantity for product ID $productId must not exceed the available stock ({$product->quantity}).");
                }
            },

            ],
            'location_id' => ['required', function ($attribute, $value, $fail) {
                $locations = Auth::user()->locations()->pluck('id')->toArray();
                if (! in_array($value, $locations)) {
                    $fail('no such location exist for that user ');
                }
            }],
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
