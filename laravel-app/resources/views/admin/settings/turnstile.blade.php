@extends('admin.layouts.dashboard')

@section('title', 'Cloudflare Turnstile')

@section('content')
    <form method="POST" action="{{ route('admin.settings.turnstile.update') }}" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-main form-main--narrow">
            <p class="form-field__hint">
                Get both keys from the <a href="https://dash.cloudflare.com/?to=/:account/turnstile" target="_blank" rel="noopener">Cloudflare dashboard → Turnstile</a>: add a widget for your domain (widget mode "Managed" is recommended).
                Leave both blank to keep the contact form working without Turnstile — it stays protected by its honeypot field and rate limit either way.
                The widget automatically follows the visitor's light/dark mode and language.
            </p>

            <div class="form-field">
                <label for="turnstile_site_key">Site key</label>
                <input type="text" id="turnstile_site_key" name="turnstile_site_key" value="{{ old('turnstile_site_key', $turnstileSiteKey) }}" autocomplete="off">
                @error('turnstile_site_key')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="turnstile_secret_key">Secret key</label>
                <input type="password" id="turnstile_secret_key" name="turnstile_secret_key" value="{{ old('turnstile_secret_key', $turnstileSecretKey) }}" autocomplete="off">
                <p class="form-field__hint">Used server-side only, never shown on the public site.</p>
                @error('turnstile_secret_key')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn--inline">Save settings</button>
        </div>
    </form>
@endsection
