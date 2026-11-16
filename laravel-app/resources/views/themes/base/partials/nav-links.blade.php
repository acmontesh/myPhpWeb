{{-- Shared nav link list, included by every theme's header. Driven by the
     `sections` table (Phase 10) so disabling a section removes it from
     navigation everywhere at once. In landing mode (Phase 12), links point
     to in-page anchors on the homepage instead of dedicated URLs; detail
     pages (a blog post, a news item) always keep their own URL either way. --}}
@php
    $sectionRoutes = [
        'about' => 'about.show',
        'news' => 'news.index',
        'research' => 'research.index',
        'publications' => 'publications.index',
        'awards' => 'awards.index',
        'blog' => 'blog.index',
        'quotes' => 'quotes.index',
        'contact' => 'contact.show',
    ];
    $siteMode = \App\Models\Setting::get('site_mode', 'multipage');
    $navSections = \App\Models\Section::query()
        ->where('enabled', true)
        ->orderBy('display_order')
        ->get();
@endphp
@foreach ($navSections as $section)
    @if (isset($sectionRoutes[$section->key]))
        @if ($siteMode === 'landing')
            <a href="{{ route('home') }}#{{ $section->key }}" data-landing-nav-link="{{ $section->key }}">{{ __('nav.'.$section->key) }}</a>
        @else
            <a href="{{ route($sectionRoutes[$section->key]) }}">{{ __('nav.'.$section->key) }}</a>
        @endif
    @endif
@endforeach
