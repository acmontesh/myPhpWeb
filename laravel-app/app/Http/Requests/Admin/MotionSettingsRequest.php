<?php

namespace App\Http\Requests\Admin;

use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MotionSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'motion_enabled' => ['nullable', 'boolean'],
            'motion_cursor' => ['nullable', 'boolean'],
            'motion_intro' => ['nullable', 'boolean'],
            'home_marquee' => ['nullable', 'boolean'],
            'home_stats' => ['nullable', 'boolean'],
            'motion_parallax' => ['required', Rule::in(array_keys(Setting::PARALLAX_LEVELS))],
        ];
    }
}
