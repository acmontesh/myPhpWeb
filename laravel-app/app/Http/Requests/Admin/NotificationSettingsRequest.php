<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NotificationSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contact_notify_email' => ['required', 'email', 'max:255'],
            'notifications_email_enabled' => ['nullable', 'boolean'],
        ];
    }
}
