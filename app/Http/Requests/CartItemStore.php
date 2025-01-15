<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class CartItemStore extends FormRequest
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
            'id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1',
                function ($attribute, $value, $fail) {

                    $productId = $this->input('id');

                    $product = Product::find($productId);

                    if ($product && $value > $product->quantity) {
                        $fail("The quantity for product ID $productId must not exceed the available stock ({$product->quantity}).");
                    }
                },
            ],
        ];
    }
}
