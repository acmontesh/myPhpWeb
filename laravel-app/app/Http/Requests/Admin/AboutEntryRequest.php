<?php

namespace App\Http\Requests\Admin;

use App\Models\AboutEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AboutEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'section' => ['required', Rule::in(array_keys(AboutEntry::SECTIONS))],
            'icon_class' => ['required', Rule::in(AboutEntry::ICONS)],
            'title.en' => ['required', 'string', 'max:255'],
            'title.es' => ['nullable', 'string', 'max:255'],
            'detail.en' => ['nullable', 'string', 'max:1000'],
            'detail.es' => ['nullable', 'string', 'max:1000'],
            'display_order' => ['required', 'integer', 'min:0'],
        ];
    }
}
