{{-- Shared <head> contents for every theme. $themeStyles is the theme's own
     Vite SCSS entry; everything else (fonts, icons, SEO meta, the no-flash
     colour-scheme script) is identical across themes. --}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', \App\Models\Setting::siteName())</title>
<meta name="description" content="@yield('description', \App\Models\Setting::siteTagline() ?? \App\Models\Setting::siteName())">
@include('themes.base.partials.seo-meta')
<link rel="stylesheet" href="{{ asset('themes/base/assets/fonts/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('themes/base/assets/fonts/webfonts/fonts.css') }}">
{{-- Resolves the visitor's colour scheme before first paint so a returning
     visitor's choice doesn't flash. Falls back to the active theme's own
     natural mode when they've never chosen one. --}}
<script>
    (function () {
        const root = document.documentElement;
        const natural = '{{ app(\App\Services\ThemeManager::class)->isDark() ? 'dark' : 'light' }}';
        let stored = null;

        try {
            stored = localStorage.getItem('color-scheme');
        } catch (error) {
            stored = null;
        }

        if (stored === 'light' || stored === 'dark') {
            root.setAttribute('data-scheme', stored);
        }

        root.setAttribute('data-theme-mode', natural);
        root.setAttribute('data-effective-scheme', stored === 'light' || stored === 'dark' ? stored : natural);

        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (root.getAttribute('data-motion') === 'on' && !reduced) {
            root.classList.add('motion-ready');
        }

        try {
            if (reduced || sessionStorage.getItem('intro-seen') === '1') {
                root.classList.add('intro-seen');
            }
        } catch (error) {
            root.classList.add('intro-seen');
        }
    })();
</script>
@if ($motionOn ?? false)
    <style>
        @view-transition { navigation: auto; }
    </style>
@endif
@stack('head')
@stack('schema')
@vite([$themeStyles, 'resources/js/themes/base/app.js'])
