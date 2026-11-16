<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TurnstileSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'turnstile_site_key' => ['nullable', 'string', 'max:255', 'required_with:turnstile_secret_key'],
            'turnstile_secret_key' => ['nullable', 'string', 'max:255', 'required_with:turnstile_site_key'],
        ];
    }

    public function messages(): array
    {
        return [
            'turnstile_site_key.required_with' => 'Enter both keys, or leave both blank to switch Turnstile off.',
            'turnstile_secret_key.required_with' => 'Enter both keys, or leave both blank to switch Turnstile off.',
        ];
    }
}
