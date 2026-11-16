<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostRequest extends FormRequest
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
            'excerpt.en' => ['nullable', 'string', 'max:500'],
            'excerpt.es' => ['nullable', 'string', 'max:500'],
            'body.en' => ['required', 'string'],
            'body.es' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,published'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }
}
