<?php

namespace App\Http\Requests;

use App\Models\Store;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdatestoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:50', function ($attribute, $value, $fail) {
                if (Store::where('user_id', '!=', Auth::id())->where('name', $value)->exists()) {
                    $fail('there is another store with the same name');
                }
            }],
            'description' => ['nullable'],
            'photo' => ['nullable', 'image', 'max:3072'],
        ];
    }
}
