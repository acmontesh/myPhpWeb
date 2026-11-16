<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HeroSlideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Required only when creating — editing may keep the current image.
            'image' => [$this->route('slide') ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:12288'],
            'caption' => ['nullable', 'string', 'max:255'],
            'display_order' => ['required', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
        ];
    }
}
