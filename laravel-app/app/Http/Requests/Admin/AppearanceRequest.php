<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppearanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ui_color_mode' => ['required', 'in:light,dark'],
            'ui_accent_color' => ['required', Rule::in(array_keys(Admin::ACCENT_COLORS))],
        ];
    }
}
