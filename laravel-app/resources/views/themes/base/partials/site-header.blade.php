{{-- Shared site header for all 5 themes: sticky on scroll, animated hover,
     collapsible animated hamburger on mobile, visitor light/dark toggle,
     contact button and language switcher. Themes restyle it through the
     --color-nav-* tokens rather than replacing the markup.
     Social links deliberately live in the hero (below the name/tagline) and
     the footer, not here. --}}
@php
    $headerSiteMode = \App\Models\Setting::get('site_mode', 'multipage');
    $contactEnabled = \App\Models\Section::query()->where('key', 'contact')->where('enabled', true)->exists();
    $contactHref = $headerSiteMode === 'landing' ? route('home').'#contact' : route('contact.show');
@endphp

<header class="site-header" data-site-header>
    <div class="site-header__inner container">
        <a href="{{ route('home') }}" class="site-header__brand">
            @isset($brandIcon)
                <i class="{{ $brandIcon }}" aria-hidden="true"></i>
            @endisset
            @isset($brandPrefix)
                <span class="site-header__prompt" aria-hidden="true">{{ $brandPrefix }}</span>
            @endisset
            <span>{{ \App\Models\Setting::siteName() }}</span>
            <span class="site-header__pulse" aria-hidden="true"></span>
        </a>

        <button
            type="button"
            class="hamburger"
            data-nav-toggle
            aria-expanded="false"
            aria-controls="site-navigation"
            aria-label="{{ __('site.toggle_menu') }}"
        >
            <span class="hamburger__bar hamburger__bar--top"></span>
            <span class="hamburger__bar hamburger__bar--middle"></span>
            <span class="hamburger__bar hamburger__bar--bottom"></span>
        </button>

        <div class="site-header__collapse" id="site-navigation" data-nav-panel>
            <nav class="site-header__nav" aria-label="{{ __('site.main_navigation') }}">
                @include('themes.base.partials.nav-links')
            </nav>

            <div class="site-header__actions">
                <button
                    type="button"
                    class="scheme-toggle"
                    data-scheme-toggle
                    aria-label="{{ __('site.toggle_scheme') }}"
                    title="{{ __('site.toggle_scheme') }}"
                >
                    <i class="fa-solid fa-sun scheme-toggle__sun" aria-hidden="true"></i>
                    <i class="fa-solid fa-moon scheme-toggle__moon" aria-hidden="true"></i>
                </button>

                @include('themes.base.partials.language-switcher')

                @if ($contactEnabled)
                    <a href="{{ $contactHref }}" class="site-header__cta" data-magnetic>
                        <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                        <span>{{ __('nav.contact') }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
