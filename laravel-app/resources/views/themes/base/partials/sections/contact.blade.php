@php
    $turnstileSiteKey = app(\App\Services\TurnstileVerifier::class)->isConfigured()
        ? app(\App\Services\TurnstileVerifier::class)->siteKey()
        : null;
    $contactSocialLinks = \App\Models\SocialLink::active()->ordered()->get();
@endphp

<div class="contact-panel">
    <aside class="contact-panel__aside" data-reveal="left">
        <span class="contact-panel__status">
            <span class="contact-panel__status-dot" aria-hidden="true"></span>
            {{ __('site.get_in_touch') }}
        </span>
        <p class="contact-panel__intro">{{ \App\Models\PublicPhrase::text('contact.intro', __('site.contact_intro')) }}</p>

        @if ($contactSocialLinks->isNotEmpty())
            <div class="contact-panel__social">
                @foreach ($contactSocialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $link->platform }}" data-magnetic>
                        <i class="{{ $link->icon_class }}" aria-hidden="true"></i>
                        <span>{{ $link->platform }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </aside>

    <div class="contact-panel__card" data-reveal="right" style="--reveal-delay: 120ms">
        @if (session('status'))
            <div class="form-confirmation" role="status">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
            @csrf

            <div class="contact-form__honeypot" aria-hidden="true">
                <label for="website">{{ __('site.honeypot_label') }}</label>
                <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="contact-form__row">
                <div class="form-field">
                    <label for="contact-name">{{ __('site.name') }} <span class="required">*</span></label>
                    <div class="form-field__control">
                        <i class="fa-solid fa-user" aria-hidden="true"></i>
                        <input type="text" id="contact-name" name="name" value="{{ old('name') }}" autocomplete="name" required>
                    </div>
                    @error('name')
                        <p class="form-field__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="contact-email">{{ __('site.email') }} <span class="required">*</span></label>
                    <div class="form-field__control">
                        <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                        <input type="email" id="contact-email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                    </div>
                    @error('email')
                        <p class="form-field__error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="form-field">
                <label for="contact-phone">{{ __('site.phone') }}</label>
                <div class="form-field__control">
                    <i class="fa-solid fa-phone" aria-hidden="true"></i>
                    <input type="tel" id="contact-phone" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                </div>
                @error('phone')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="contact-message">{{ __('site.message') }} <span class="required">*</span></label>
                <div class="form-field__control form-field__control--textarea">
                    <i class="fa-solid fa-comment-dots" aria-hidden="true"></i>
                    <textarea id="contact-message" name="message" rows="6" required>{{ old('message') }}</textarea>
                </div>
                @error('message')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            @if ($turnstileSiteKey)
                <div class="form-field contact-form__captcha">
                    <div class="contact-form__turnstile" data-turnstile data-sitekey="{{ $turnstileSiteKey }}"></div>
                    @error('cf-turnstile-response')
                        <p class="form-field__error">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <button type="submit" class="btn btn--glow contact-form__submit" data-magnetic>
                <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                <span>{{ __('site.send_message') }}</span>
            </button>
        </form>
    </div>
</div>
