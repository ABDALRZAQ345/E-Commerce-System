<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Http\FormRequest;

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
            'products' => ['required','array'],
            'products.*.id' =>  ['required','exists:products,id'],
            'products.*.quantity' =>  ['required','integer','min:1',  function ($attribute, $value, $fail) {
                $index = explode('.', $attribute)[1];
                $productId = $this->input("products.$index.id");

                $product = Product::find($productId);

                if ($product && $value > $product->quantity) {
                    $fail("The quantity for product ID $productId must not exceed the available stock ({$product->quantity}).");
                }
            }
            ],
        ];
    }
}
