<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSellerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === User::ROLE_ADMIN;
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
        $seller = $this->route('seller');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($seller instanceof User ? $seller->id : null),
            ],
            'whatsapp_number' => ['required', 'regex:/^628[0-9]{7,11}$/'],
            'major' => ['required', Rule::in(User::MAJORS)],
            'bussiness_name' => ['required', 'string', 'max:255'],
            'bussiness_description' => ['required', 'string', 'max:5000'],
            'avatar_url' => ['nullable', 'url', 'max:255'],
            //
        ];
    }
}
