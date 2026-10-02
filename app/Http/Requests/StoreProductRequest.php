<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array($this->user()?->role, [User::ROLE_ADMIN, User::ROLE_SELLER], true);
    }

    protected function prepareForValidation(): void
    {
        $number = $this->input('whatsapp_number');

        if (is_string($number)) {
            $this->merge([
                'whatsapp_number' => User::normalizeWhatsappNumber($number),
            ]);
        }
    }

    public function rules(): array
    {
        $isAdmin = $this->user()?->role === User::ROLE_ADMIN;

        return [
            'seller_id' => [
                Rule::requiredIf($isAdmin),
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn (Builder $query) => $query
                    ->where('role', User::ROLE_SELLER)
                    ->where('is_active', true)),
            ],
            'category_id' => ['required', 'integer', Rule::exists('categories', 'id')],
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:1000'],
            'full_description' => ['required', 'string', 'max:20000'],
            'price' => ['required', 'integer', 'min:1'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'payment_proof' => [
                Rule::requiredIf(! $isAdmin),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            //
        ];
    }
}
