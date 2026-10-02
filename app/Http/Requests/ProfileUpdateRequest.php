<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
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
        $isSeller = $this->user()?->role === User::ROLE_SELLER;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'whatsapp_number' => [
                Rule::requiredIf($isSeller),
                'nullable',
                'regex:/^628[0-9]{7,11}$/',
            ],
            'major' => [Rule::requiredIf($isSeller), 'nullable', Rule::in(User::MAJORS)],
            'bussiness_name' => [Rule::requiredIf($isSeller), 'nullable', 'string', 'max:255'],
            'bussiness_description' => [Rule::requiredIf($isSeller), 'nullable', 'string', 'max:5000'],
            'avatar_url' => ['nullable', 'url', 'max:255'],
        ];
    }
}
