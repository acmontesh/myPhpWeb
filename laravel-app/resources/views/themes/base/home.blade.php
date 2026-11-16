@extends($activeLayout)

@section('content')
    @include('themes.base.partials.hero', ['heroBackground' => $heroBackground, 'aboutPhoto' => $aboutPhoto])

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
        $sectionIcons = [
            'about' => 'fa-solid fa-user',
            'news' => 'fa-solid fa-newspaper',
            'research' => 'fa-solid fa-flask',
            'publications' => 'fa-solid fa-book',
            'awards' => 'fa-solid fa-trophy',
            'blog' => 'fa-solid fa-pen-nib',
            'quotes' => 'fa-solid fa-quote-left',
            'contact' => 'fa-solid fa-envelope',
        ];
        $quickLinks = $sections->filter(fn ($section) => isset($sectionRoutes[$section->key]))->values();
    @endphp

    <div class="container home-explore" id="site-content">
        @if ($quickLinks->isEmpty())
            <x-public.empty-state
                icon="fa-solid fa-compass"
                :title="\App\Models\PublicPhrase::text('empty_state.home.title', 'More of the site is on its way')"
                :message="\App\Models\PublicPhrase::text('empty_state.home.message', 'Sections will appear here as they are enabled.')"
            />
        @else
            <div class="home-quicklinks">
                @foreach ($quickLinks as $section)
                    <a
                        href="{{ route($sectionRoutes[$section->key]) }}"
                        class="home-quicklinks__item"
                        data-reveal="up"
                        data-tilt
                        data-cursor-label="{{ __('site.view_all') }}"
                        style="--reveal-delay: {{ $loop->index * 70 }}ms"
                    >
                        <span class="home-quicklinks__index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <i class="{{ $sectionIcons[$section->key] }}" aria-hidden="true"></i>
                        <span class="home-quicklinks__label">{{ __('nav.'.$section->key) }}</span>
                        @if (\App\Models\PublicPhrase::text('section_lead.'.$section->key))
                            <span class="home-quicklinks__lead">{{ \App\Models\PublicPhrase::text('section_lead.'.$section->key) }}</span>
                        @endif
                        <span class="home-quicklinks__arrow" aria-hidden="true"><i class="fa-solid fa-arrow-right"></i></span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endsection
