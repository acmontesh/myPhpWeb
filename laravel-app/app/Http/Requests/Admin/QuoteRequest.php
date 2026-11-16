<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class QuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author_name' => ['required', 'string', 'max:255'],
            'quote_text.en' => ['required', 'string', 'max:600'],
            'quote_text.es' => ['nullable', 'string', 'max:600'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
            'author_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }
}
