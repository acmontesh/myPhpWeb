<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AwardRequest extends FormRequest
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
            'granted_by' => ['required', 'string', 'max:255'],
            'awarded_on' => ['required', 'date'],
            'description.en' => ['nullable', 'string', 'max:1000'],
            'description.es' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'display_order' => ['required', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.max' => 'The image must not be larger than 5 MB.',
        ];
    }
}
