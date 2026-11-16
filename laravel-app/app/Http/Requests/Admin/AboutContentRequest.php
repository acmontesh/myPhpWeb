<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AboutContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'headline.en' => ['required', 'string', 'max:255'],
            'headline.es' => ['nullable', 'string', 'max:255'],
            'bio.en' => ['required', 'string'],
            'bio.es' => ['nullable', 'string'],
        ];
    }
}
