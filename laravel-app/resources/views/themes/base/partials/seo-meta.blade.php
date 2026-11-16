{{-- Shared Open Graph / Twitter / canonical tags, included by every theme's <head> (Phase 12 SEO pass). --}}
<link rel="canonical" href="@yield('canonical', url()->current())">
<meta property="og:site_name" content="{{ \App\Models\Setting::siteName() }}">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:title" content="@yield('title', \App\Models\Setting::siteName())">
<meta property="og:description" content="@yield('description', \App\Models\Setting::siteTagline() ?? \App\Models\Setting::siteName())">
<meta property="og:url" content="@yield('canonical', url()->current())">
@hasSection('image')
<meta property="og:image" content="@yield('image')">
@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', \App\Models\Setting::siteName())">
<meta name="twitter:description" content="@yield('description', \App\Models\Setting::siteTagline() ?? \App\Models\Setting::siteName())">
