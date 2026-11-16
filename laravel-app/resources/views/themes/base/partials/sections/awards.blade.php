@if ($awards->isEmpty())
    <x-public.empty-state
        icon="fa-solid fa-trophy"
        :title="\App\Models\PublicPhrase::text('empty_state.awards.title', 'No awards yet')"
        :message="\App\Models\PublicPhrase::text('empty_state.awards.message', 'Awards and distinctions will appear here soon.')"
    />
@else
    <div class="awards-list">
        @foreach ($awards as $award)
            <article class="award-card" data-reveal="up" data-tilt style="--reveal-delay: {{ ($loop->index % 2) * 110 }}ms">
                @if ($award->image)
                    <div class="award-card__media">
                        <img src="{{ asset('storage/'.$award->image) }}" alt="{{ $award->title }}" loading="lazy">
                    </div>
                @else
                    <div class="award-card__badge" aria-hidden="true">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                @endif

                <div class="award-card__body">
                    <h3 class="award-card__title">{{ $award->title }}</h3>

                    <p class="award-card__meta">
                        <span class="award-card__granter">
                            <i class="fa-solid fa-building-columns" aria-hidden="true"></i>
                            {{ $award->granted_by }}
                        </span>
                        <time class="award-card__date" datetime="{{ $award->awarded_on?->toDateString() }}">
                            <i class="fa-solid fa-calendar-day" aria-hidden="true"></i>
                            {{ $award->awarded_on?->translatedFormat('F Y') }}
                        </time>
                    </p>

                    @if ($award->description)
                        <p class="award-card__description">{{ $award->description }}</p>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
@endif
