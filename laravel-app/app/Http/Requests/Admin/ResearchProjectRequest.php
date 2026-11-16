<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ResearchProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title.en' => ['required', 'string', 'max:255'],
            'title.es' => ['nullable', 'string', 'max:255'],
            'summary.en' => ['nullable', 'string', 'max:500'],
            'summary.es' => ['nullable', 'string', 'max:500'],
            'body.en' => ['required', 'string'],
            'body.es' => ['nullable', 'string'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }
}
