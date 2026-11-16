<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewsItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title.en' => ['required', 'string', 'max:300'],
            'title.es' => ['nullable', 'string', 'max:300'],
            'body.en' => ['required', 'string'],
            'body.es' => ['nullable', 'string'],
            'published_at' => ['required', 'date'],
            'active' => ['nullable', 'boolean'],
            'image1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image1' => ['nullable', 'boolean'],
            'image2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image2' => ['nullable', 'boolean'],
        ];
    }
}
