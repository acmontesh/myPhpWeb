@if ($posts->isEmpty())
    <x-public.empty-state
        icon="fa-solid fa-pen-nib"
        :title="\App\Models\PublicPhrase::text('empty_state.blog.title', 'No articles yet')"
        :message="\App\Models\PublicPhrase::text('empty_state.blog.message', 'New writing will appear here soon — check back later.')"
    />
@else
    <div class="blog-grid">
        @foreach ($posts as $post)
            <article class="blog-card" data-reveal="up" data-tilt style="--reveal-delay: {{ ($loop->index % 3) * 90 }}ms">
                <a href="{{ route('blog.show', $post) }}" class="blog-card__image" tabindex="-1" aria-hidden="true" data-cursor-label="{{ __('site.read_more') }}">
                    @if ($post->featured_image)
                        <img src="{{ asset('storage/'.$post->featured_image) }}" alt="" loading="lazy">
                    @else
                        <i class="fa-solid fa-pen-nib blog-card__placeholder"></i>
                    @endif
                </a>
                <div class="blog-card__body">
                    <time class="blog-card__date" datetime="{{ $post->published_at?->toDateString() }}">
                        <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                        {{ $post->published_at?->translatedFormat('F j, Y') }}
                    </time>
                    <h3><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h3>
                    @if ($post->excerpt)
                        <p>{{ $post->excerpt }}</p>
                    @endif

                    <a href="{{ route('blog.show', $post) }}" class="blog-card__more">
                        <span>{{ __('site.read_more') }}</span>
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    @if ($posts instanceof \Illuminate\Contracts\Pagination\Paginator && $posts->hasPages())
        <div class="pagination">{{ $posts->links() }}</div>
    @endif
@endif
