{{-- Headline + one picture per item; the full story lives at news.show. The
     first item of the first page is featured as a large bento tile. --}}
@if ($newsItems->isEmpty())
    <x-public.empty-state
        icon="fa-solid fa-newspaper"
        :title="\App\Models\PublicPhrase::text('empty_state.news.title', 'No news yet')"
        :message="\App\Models\PublicPhrase::text('empty_state.news.message', 'Announcements and updates will appear here soon.')"
    />
@else
    @php
        $isFirstPage = ! ($newsItems instanceof \Illuminate\Contracts\Pagination\Paginator) || $newsItems->currentPage() === 1;
        $featureFirst = $isFirstPage && $newsItems->count() >= 3;
    @endphp

    <div class="news-teaser-grid {{ $featureFirst ? 'news-teaser-grid--bento' : '' }}">
        @foreach ($newsItems as $newsItem)
            @php
                $isFeatured = $featureFirst && $loop->first;
                $newsImage = $newsItem->image1 ?: $newsItem->image2;
            @endphp
            <article
                class="news-teaser-card {{ $isFeatured ? 'news-teaser-card--featured' : '' }}"
                data-reveal="up"
                data-tilt
                data-tilt-max="{{ $isFeatured ? 3 : 6 }}"
                style="--reveal-delay: {{ ($loop->index % 3) * 90 }}ms"
            >
                <a href="{{ route('news.show', $newsItem) }}" class="news-teaser-card__image" tabindex="-1" aria-hidden="true" data-cursor-label="{{ __('site.read_more') }}">
                    @if ($newsImage)
                        <img src="{{ asset('storage/'.$newsImage) }}" alt="" loading="lazy">
                    @else
                        <i class="fa-solid fa-newspaper news-teaser-card__placeholder"></i>
                    @endif
                </a>
                <div class="news-teaser-card__body">
                    <div class="news-teaser-card__top">
                        <time class="news-teaser-card__date" datetime="{{ $newsItem->published_at?->toDateString() }}">
                            <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                            {{ $newsItem->published_at?->translatedFormat('F j, Y') }}
                        </time>
                        @if ($isFeatured)
                            <span class="news-teaser-card__badge">{{ __('site.featured') }}</span>
                        @endif
                    </div>
                    <h3><a href="{{ route('news.show', $newsItem) }}">{{ $newsItem->title }}</a></h3>
                    <p>{{ \Illuminate\Support\Str::limit(strip_tags($newsItem->body), $isFeatured ? 260 : 140) }}</p>
                    <a href="{{ route('news.show', $newsItem) }}" class="link-arrow">
                        <span>{{ __('site.read_more') }}</span>
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    @if ($newsItems instanceof \Illuminate\Contracts\Pagination\Paginator && $newsItems->hasPages())
        <div class="pagination">{{ $newsItems->links() }}</div>
    @endif
@endif
