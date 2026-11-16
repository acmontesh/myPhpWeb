{{-- Homepage hero, shared by the multipage home view and the landing composite.
     Header slides cross-fade behind a parallax layer; without slides it falls
     back to the single hero_background theme image slot. --}}
@php
    $heroSlides = \App\Models\HeroSlide::active()->ordered()->get();
    $heroSocialLinks = \App\Models\SocialLink::active()->ordered()->get();
    $heroName = \App\Models\Setting::siteName();
    $heroRoles = collect(explode('|', (string) \App\Models\PublicPhrase::text('hero.roles', '')))
        ->map(fn ($role) => trim($role))
        ->filter()
        ->values();
    $heroSiteMode = \App\Models\Setting::get('site_mode', 'multipage');
    $heroEnabled = \App\Models\Section::query()->where('enabled', true)->pluck('key');
    $heroContactHref = $heroSiteMode === 'landing' ? '#contact' : route('contact.show');
    $heroSecondary = $heroEnabled->contains('publications') ? 'publications' : ($heroEnabled->contains('research') ? 'research' : null);
    $heroSecondaryHref = $heroSecondary
        ? ($heroSiteMode === 'landing' ? '#'.$heroSecondary : route($heroSecondary.'.index'))
        : null;
    $orbitIcons = ['fa-solid fa-brain', 'fa-solid fa-oil-well', 'fa-solid fa-chart-line', 'fa-solid fa-microchip'];
@endphp

<section class="home-hero" data-hero>
    <div class="home-hero__media" data-hero-media>
        @if ($heroSlides->isNotEmpty())
            <div class="home-hero__slides" data-hero-carousel aria-hidden="true">
                @foreach ($heroSlides as $slide)
                    <div
                        class="home-hero__slide {{ $loop->first ? 'is-active' : '' }}"
                        data-hero-slide
                        style="background-image: url('{{ asset('storage/'.$slide->displayPath()) }}')"
                    ></div>
                @endforeach
            </div>
        @elseif ($heroBackground)
            <div class="home-hero__slides" aria-hidden="true">
                <div class="home-hero__slide is-active" style="background-image: url('{{ asset('storage/'.$heroBackground) }}')"></div>
            </div>
        @endif
    </div>

    <div class="home-hero__overlay" style="--hero-overlay-alpha: {{ \App\Models\Setting::heroOverlayAlpha() }}"></div>
    <div class="home-hero__mesh" aria-hidden="true">
        <span></span><span></span><span></span>
    </div>
    <div class="home-hero__grid" aria-hidden="true"></div>
    <div class="home-hero__spotlight" data-hero-spotlight aria-hidden="true"></div>

    <div class="container home-hero__content" data-hero-content>
        <div class="home-hero__text">
            <p class="home-hero__eyebrow">
                <span class="home-hero__eyebrow-dot" aria-hidden="true"></span>
                {{ \App\Models\PublicPhrase::text('hero.greeting', "Hello, I'm") }}
            </p>

            <h1 class="home-hero__title" data-split>{{ $heroName }}</h1>

            @if ($heroRoles->isNotEmpty())
                <p class="home-hero__roles">
                    <span class="home-hero__roles-text" data-typer data-words="{{ $heroRoles->toJson(JSON_UNESCAPED_UNICODE) }}">{{ $heroRoles->first() }}</span><span class="home-hero__caret" aria-hidden="true"></span>
                </p>
            @endif

            @if (\App\Models\Setting::siteTagline())
                <p class="home-hero__tagline">{{ \App\Models\Setting::siteTagline() }}</p>
            @endif

            <div class="home-hero__actions">
                @if ($heroEnabled->contains('contact'))
                    <a href="{{ $heroContactHref }}" class="btn btn--glow" data-magnetic>
                        <span>{{ __('site.get_in_touch') }}</span>
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                @endif
                @if ($heroSecondaryHref)
                    <a href="{{ $heroSecondaryHref }}" class="btn btn--ghost" data-magnetic>
                        <span>{{ __('nav.'.$heroSecondary) }}</span>
                    </a>
                @endif
            </div>

            @if ($heroSocialLinks->isNotEmpty())
                <div class="home-hero__social">
                    @foreach ($heroSocialLinks as $link)
                        <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $link->platform }}" data-magnetic style="--i: {{ $loop->index }}">
                            <i class="{{ $link->icon_class }}" aria-hidden="true"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($aboutPhoto)
            <div class="home-hero__visual" data-tilt data-tilt-max="8">
                <span class="home-hero__ring" aria-hidden="true"></span>
                <span class="home-hero__orbit home-hero__orbit--inner" aria-hidden="true">
                    @foreach ($orbitIcons as $icon)
                        <span class="home-hero__planet" style="--i: {{ $loop->index }}"><i class="{{ $icon }}"></i></span>
                    @endforeach
                </span>
                <span class="home-hero__orbit home-hero__orbit--outer" aria-hidden="true"></span>
                <img class="home-hero__photo" src="{{ asset('storage/'.$aboutPhoto) }}" alt="{{ $heroName }}" width="320" height="320" fetchpriority="high">
            </div>
        @endif
    </div>

    {{-- Real anchor: works without JS and uses the global smooth scroll.
         #site-content sits right after the hero in both site modes. --}}
    <a href="#site-content" class="home-hero__scroll" aria-label="{{ __('site.scroll_down') }}">
        <span class="home-hero__mouse" aria-hidden="true"><span class="home-hero__wheel"></span></span>
        <span class="home-hero__scroll-label" aria-hidden="true">{{ __('site.scroll') }}</span>
    </a>
</section>

@include('themes.base.partials.home-extras')
