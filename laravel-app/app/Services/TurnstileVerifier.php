<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Server-side verification against Cloudflare Turnstile's siteverify endpoint.
 * Keys are admin-configurable settings. With no keys configured verification
 * is skipped, so the contact form keeps working (it is still protected by the
 * honeypot field and the rate limit).
 */
class TurnstileVerifier
{
    private const ENDPOINT = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function siteKey(): ?string
    {
        return Setting::get('turnstile_site_key') ?: null;
    }

    public function isConfigured(): bool
    {
        return (bool) $this->siteKey() && (bool) Setting::get('turnstile_secret_key');
    }

    public function verify(?string $token, ?string $ip): bool
    {
        if (! $this->isConfigured()) {
            return true;
        }

        if (! $token) {
            return false;
        }

        try {
            $response = Http::asForm()->timeout(10)->post(self::ENDPOINT, [
                'secret' => Setting::get('turnstile_secret_key'),
                'response' => $token,
                'remoteip' => $ip,
            ]);
        } catch (Throwable) {
            return false;
        }

        return $response->successful() && $response->json('success') === true;
    }
}
