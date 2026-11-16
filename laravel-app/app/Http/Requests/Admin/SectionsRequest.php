<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SectionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sections' => ['required', 'array'],
            'sections.*.enabled' => ['nullable', 'boolean'],
            'sections.*.display_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
