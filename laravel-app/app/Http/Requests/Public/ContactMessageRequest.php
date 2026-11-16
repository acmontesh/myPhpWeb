<?php

namespace App\Http\Requests\Public;

use App\Rules\Turnstile;
use App\Services\TurnstileVerifier;
use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:5000'],
            'website' => ['prohibited'],
            'cf-turnstile-response' => app(TurnstileVerifier::class)->isConfigured()
                ? ['required', new Turnstile]
                : ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('site.validation_name_required'),
            'email.required' => __('site.validation_email_required'),
            'email.email' => __('site.validation_email_invalid'),
            'message.required' => __('site.validation_message_required'),
            'cf-turnstile-response.required' => __('site.validation_captcha'),
        ];
    }
}
