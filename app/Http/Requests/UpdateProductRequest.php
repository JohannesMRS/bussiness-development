<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_SELLER], true);
    }

    public function rules(): array
    {
        $product = $this->route('product');
        $sellerChangingPrice = $this->user()?->role === User::ROLE_SELLER
            && $product instanceof Product
            && (int) $this->input('price') !== (int) $product->price;

        return [
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:1000'],
            'full_description' => ['required', 'string', 'max:20000'],
            'price' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'payment_proof' => [
                Rule::requiredIf($sellerChangingPrice),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            //
        ];
    }
}
