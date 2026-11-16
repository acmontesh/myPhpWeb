{{-- Full HTML document shared by every theme layout. Each theme's
     layouts/app.blade.php only passes its slug and header options, so the
     motion layer (progress bar, intro, ambient background, cursor, back-to-top)
     is identical across themes and restyled through each theme's tokens. --}}
@php
    $motion = \App\Models\Setting::motion();
    $motionOn = $motion['motion_enabled'] === '1';
    $parallaxFactor = $motionOn ? (\App\Models\Setting::PARALLAX_LEVELS[$motion['motion_parallax']] ?? 1) : 0;
    $siteName = \App\Models\Setting::siteName();
    $initials = collect(preg_split('/\s+/', trim($siteName)))
        ->filter(fn ($word) => preg_match('/^\p{L}/u', $word) && ! str_ends_with($word, '.'))
        ->map(fn ($word) => mb_substr($word, 0, 1))
        ->pipe(fn ($letters) => $letters->count() > 1 ? $letters->first().$letters->last() : $letters->first());
@endphp
<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    data-motion="{{ $motionOn ? 'on' : 'off' }}"
    data-cursor="{{ $motionOn && $motion['motion_cursor'] === '1' ? 'on' : 'off' }}"
    data-parallax="{{ $parallaxFactor }}"
>
<head>
    @include('themes.base.partials.head', ['themeStyles' => "resources/scss/themes/{$themeSlug}/app.scss", 'motionOn' => $motionOn])
</head>
<body class="theme-{{ $themeSlug }}">
    <a href="#main" class="skip-link">{{ __('site.skip_to_content') }}</a>

    <div class="scroll-progress" data-scroll-progress aria-hidden="true"></div>

    @if ($motionOn && $motion['motion_intro'] === '1')
        <div class="intro" data-intro aria-hidden="true">
            <div class="intro__panel"></div>
            <div class="intro__panel intro__panel--accent"></div>
            <span class="intro__mark">{{ $initials ?: 'AM' }}</span>
        </div>
    @endif

    <div class="ambient" aria-hidden="true">
        <span class="ambient__orb ambient__orb--one" data-parallax-fixed="-0.12"></span>
        <span class="ambient__orb ambient__orb--two" data-parallax-fixed="0.18"></span>
        <span class="ambient__orb ambient__orb--three" data-parallax-fixed="-0.08"></span>
        <span class="ambient__grid"></span>
    </div>

    @if ($motionOn && $motion['motion_cursor'] === '1')
        <div class="cursor" data-cursor aria-hidden="true">
            <span class="cursor__ring"><span class="cursor__label" data-cursor-text></span></span>
            <span class="cursor__dot"></span>
        </div>
    @endif

    @include('themes.base.partials.site-header', $headerOptions ?? [])

    <main id="main">
        @yield('content')
    </main>

    @include('themes.base.partials.footer')

    <a href="#main" class="back-to-top" data-back-to-top aria-label="{{ __('site.back_to_top') }}">
        <svg class="back-to-top__ring" viewBox="0 0 48 48" aria-hidden="true">
            <circle cx="24" cy="24" r="22" pathLength="100"></circle>
            <circle class="back-to-top__progress" cx="24" cy="24" r="22" pathLength="100" data-back-to-top-progress></circle>
        </svg>
        <i class="fa-solid fa-arrow-up" aria-hidden="true"></i>
    </a>
</body>
</html>
