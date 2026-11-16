<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GeneralSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_tagline_es' => ['nullable', 'string', 'max:255'],
            'default_locale' => ['required', 'in:en,es'],
            'site_mode' => ['required', 'in:landing,multipage'],
        ];
    }
}
