@php
    $socialLinks = \App\Models\SocialLink::active()->ordered()->get();
    $footerMode = \App\Models\Setting::get('site_mode', 'multipage');
    $footerContact = \App\Models\Section::query()->where('key', 'contact')->where('enabled', true)->exists();
    $footerContactHref = $footerMode === 'landing' ? route('home').'#contact' : route('contact.show');
    $footerTitle = \App\Models\PublicPhrase::text('footer.cta_title');
    $footerText = \App\Models\PublicPhrase::text('footer.cta_text');
@endphp

<footer class="site-footer">
    @if ($footerTitle)
        <div class="site-footer__cta container">
            <p class="site-footer__cta-title" data-reveal="up">{{ $footerTitle }}</p>
            @if ($footerText)
                <p class="site-footer__cta-text" data-reveal="up" style="--reveal-delay: 120ms">{{ $footerText }}</p>
            @endif
            @if ($footerContact)
                <a href="{{ $footerContactHref }}" class="site-footer__cta-button" data-magnetic data-reveal="scale" style="--reveal-delay: 220ms">
                    <span>{{ __('site.get_in_touch') }}</span>
                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
            @endif
        </div>
    @endif

    <div class="site-footer__bar container">
        <p class="site-footer__brand">{{ \App\Models\Setting::siteName() }}</p>

        <nav class="site-footer__nav" aria-label="{{ __('site.main_navigation') }}">
            @include('themes.base.partials.nav-links')
        </nav>

        @if ($socialLinks->isNotEmpty())
            <div class="site-footer__social">
                @foreach ($socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $link->platform }}" data-magnetic>
                        <i class="{{ $link->icon_class }}" aria-hidden="true"></i>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <p class="site-footer__copyright container">&copy; {{ now()->year }} {{ \App\Models\Setting::siteName() }}. {{ __('site.all_rights') }}</p>
</footer>
