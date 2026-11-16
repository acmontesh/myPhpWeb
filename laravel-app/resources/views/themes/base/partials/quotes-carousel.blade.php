{{-- Included (not an <x-component>) so it can be dropped into either a
     standalone page (Phase 4) or the landing-page section flow (Phase 12)
     with a simple @include('themes.base.partials.quotes-carousel').
     $titleTag lets the standalone page own the <h1> while landing mode uses
     an <h2> under the hero's <h1>. --}}
@php
    $carouselTitleTag = $titleTag ?? 'h2';
    $carouselTitle = \App\Models\PublicPhrase::text('quotes.title', __('site.quotes_title'));
@endphp

@if ($quotes->isEmpty())
    <x-public.empty-state
        icon="fa-solid fa-quote-left"
        :title="\App\Models\PublicPhrase::text('empty_state.quotes.title', 'No quotes yet')"
        :message="\App\Models\PublicPhrase::text('empty_state.quotes.message', 'Quotes worth sharing will appear here soon.')"
    />
@else
    <section class="quotes-carousel" data-carousel style="--autoplay: 7000ms">
        <div class="quotes-carousel__heading">
            <{{ $carouselTitleTag }} class="quotes-carousel__title" data-split>{{ $carouselTitle }}</{{ $carouselTitleTag }}>
        </div>

        <div class="quotes-carousel__viewport">
            <div class="quotes-carousel__track" data-carousel-track>
                @foreach ($quotes as $quote)
                    <div class="quotes-carousel__slide {{ $loop->first ? 'is-active' : '' }}" data-carousel-slide>
                        <div
                            class="quotes-carousel__photo"
                            @if ($quote->author_image)
                                style="background-image: url('{{ asset('storage/'.$quote->author_image) }}')"
                            @endif
                        ></div>
                        <div class="quotes-carousel__panel">
                            <i class="fa-solid fa-quote-left quotes-carousel__mark" aria-hidden="true"></i>
                            <blockquote>{{ $quote->quote_text }}</blockquote>
                            <cite>{{ $quote->author_name }}</cite>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($quotes->count() > 1)
                {{-- Wrapped so autoplay pauses only over the controls, not over
                     the whole full-bleed band. --}}
                <div class="quotes-carousel__controls" data-carousel-controls>
                    <button type="button" class="quotes-carousel__nav quotes-carousel__nav--prev" data-carousel-prev aria-label="{{ __('site.previous_quote') }}">
                        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="quotes-carousel__nav quotes-carousel__nav--next" data-carousel-next aria-label="{{ __('site.next_quote') }}">
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                    </button>

                    <div class="quotes-carousel__dots">
                        @foreach ($quotes as $index => $quote)
                            <button
                                type="button"
                                class="quotes-carousel__dot {{ $loop->first ? 'is-active' : '' }}"
                                data-carousel-dot="{{ $index }}"
                                aria-label="{{ __('site.go_to_quote', ['number' => $index + 1]) }}"
                            ><span class="quotes-carousel__dot-fill"></span></button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
