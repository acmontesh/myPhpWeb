{{-- Single-page assembly for site_mode=landing. Reuses the exact same section
     partials as the standalone multipage routes. --}}
@extends($activeLayout)

@section('content')
    @include('themes.base.partials.hero', ['heroBackground' => $heroBackground, 'aboutPhoto' => $aboutPhoto])

    {{-- Target for the hero's scroll cue; the first enabled section follows. --}}
    <span id="site-content" aria-hidden="true"></span>

    @php
        $landingKeys = ['about', 'news', 'research', 'publications', 'awards', 'blog', 'quotes', 'contact'];
        $landingSections = $sections->filter(fn ($section) => in_array($section->key, $landingKeys, true))->values();
    @endphp

    @foreach ($landingSections as $section)
        @if ($section->key === 'quotes')
            <div id="quotes" data-landing-section>
                @include('themes.base.partials.quotes-carousel', ['quotes' => $quotes])
            </div>
        @else
            <section id="{{ $section->key }}" data-landing-section class="landing-section landing-section--{{ $section->key }}">
                <div class="container">
                    @include('themes.base.partials.section-heading', ['key' => $section->key, 'index' => $loop->iteration, 'reverse' => $loop->even])

                    @switch($section->key)
                        @case('about')
                            @include('themes.base.partials.sections.about', ['about' => $about, 'aboutPhoto' => $aboutPhoto])
                            @break
                        @case('news')
                            @include('themes.base.partials.sections.news', ['newsItems' => $newsItems])
                            @break
                        @case('research')
                            @include('themes.base.partials.sections.research', ['projects' => $projects])
                            @break
                        @case('publications')
                            @include('themes.base.partials.sections.publications', ['publications' => $publications])
                            @break
                        @case('awards')
                            @include('themes.base.partials.sections.awards', ['awards' => $awards])
                            @break
                        @case('blog')
                            @include('themes.base.partials.sections.blog', ['posts' => $posts])
                            @break
                        @case('contact')
                            @include('themes.base.partials.sections.contact')
                            @break
                    @endswitch
                </div>
            </section>
        @endif
    @endforeach
@endsection
