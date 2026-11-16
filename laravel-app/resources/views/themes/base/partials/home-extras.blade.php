{{-- Animated statistics strip and research-interests ribbon below the hero.
     Both are computed from existing content and toggled in
     Appearance -> Motion & Effects. --}}
@php
    $extrasEnabled = \App\Models\Section::query()->where('enabled', true)->pluck('key');
    $stats = collect();

    if (\App\Models\Setting::motionFlag('home_stats')) {
        if ($extrasEnabled->contains('publications')) {
            $stats->push(['value' => \App\Models\Publication::active()->count(), 'label' => __('site.stats_publications'), 'icon' => 'fa-solid fa-book-open']);
            $stats->push(['value' => (int) \App\Models\Publication::active()->sum('citation_count'), 'label' => __('site.stats_citations'), 'icon' => 'fa-solid fa-quote-right']);
        }
        if ($extrasEnabled->contains('awards')) {
            $stats->push(['value' => \App\Models\Award::active()->count(), 'label' => __('site.stats_awards'), 'icon' => 'fa-solid fa-trophy']);
        }
        if ($extrasEnabled->contains('news')) {
            $stats->push(['value' => \App\Models\NewsItem::active()->count(), 'label' => __('site.stats_news'), 'icon' => 'fa-solid fa-microphone-lines']);
        }
        $stats = $stats->filter(fn ($stat) => $stat['value'] > 0)->values();
    }

    $interests = \App\Models\Setting::motionFlag('home_marquee')
        ? \App\Models\AboutEntry::query()->where('section', 'interests')->ordered()->get()
        : collect();
@endphp

@if ($stats->isNotEmpty())
    <div class="stats-strip">
        <div class="container stats-strip__grid">
            @foreach ($stats as $stat)
                <div class="stats-strip__item" data-reveal="up" style="--reveal-delay: {{ $loop->index * 90 }}ms">
                    <i class="{{ $stat['icon'] }} stats-strip__icon" aria-hidden="true"></i>
                    <span class="stats-strip__value" data-count-to="{{ $stat['value'] }}">{{ number_format($stat['value']) }}</span>
                    <span class="stats-strip__label">{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if ($interests->isNotEmpty())
    <div class="marquee" aria-label="{{ __('site.research_interests') }}" role="region">
        <ul class="marquee__list">
            @foreach ($interests as $interest)
                <li><i class="{{ $interest->icon_class }}" aria-hidden="true"></i>{{ $interest->title }}</li>
            @endforeach
        </ul>
        <ul class="marquee__list" aria-hidden="true">
            @foreach ($interests as $interest)
                <li><i class="{{ $interest->icon_class }}" aria-hidden="true"></i>{{ $interest->title }}</li>
            @endforeach
        </ul>
    </div>
@endif
