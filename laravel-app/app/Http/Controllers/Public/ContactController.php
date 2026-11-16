<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ContactMessageRequest;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Services\TurnstileVerifier;
use App\Services\ThemeManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(ThemeManager $themes): View
    {
        return view($themes->view('contact'));
    }

    public function store(ContactMessageRequest $request, TurnstileVerifier $turnstile): RedirectResponse
    {
        $data = $request->validated();

        $message = ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'captcha_verified' => $turnstile->isConfigured(),
        ]);

        $notifyEmail = Setting::get('contact_notify_email');

        if ($notifyEmail && Setting::get('notifications_email_enabled', '1') === '1') {
            Mail::to($notifyEmail)->send(new ContactMessageReceived($message));
        }

        return redirect()
            ->route('contact.show')
            ->with('status', __('site.contact_success'));
    }
}
