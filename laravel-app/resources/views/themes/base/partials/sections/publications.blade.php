@if ($publications->isEmpty())
    <x-public.empty-state
        icon="fa-solid fa-book"
        :title="\App\Models\PublicPhrase::text('empty_state.publications.title', 'No publications yet')"
        :message="\App\Models\PublicPhrase::text('empty_state.publications.message', 'Papers and conference proceedings will appear here soon.')"
    />
@else
    @php
        $publicationTypes = $publications->groupBy('type')->map->count();
    @endphp

    <div class="publications" data-filter-group>
        @if ($publicationTypes->count() > 1)
            <div class="filter-chips" role="group" aria-label="{{ __('site.filter_label') }}" data-reveal="up">
                <button type="button" class="filter-chips__chip is-active" data-filter="all" aria-pressed="true">
                    {{ __('site.filter_all') }} <span>{{ $publications->count() }}</span>
                </button>
                @foreach ($publicationTypes as $type => $count)
                    <button type="button" class="filter-chips__chip" data-filter="{{ $type }}" aria-pressed="false">
                        {{ __('site.'.$type) }} <span>{{ $count }}</span>
                    </button>
                @endforeach
            </div>
        @endif

        <div class="publications-list">
            @foreach ($publications as $publication)
                <article class="publication-card" data-filter-item="{{ $publication->type }}" data-reveal="up" data-tilt data-tilt-max="3">
                    @if ($publication->image)
                        <div class="publication-card__media">
                            <img src="{{ asset('storage/'.$publication->image) }}" alt="{{ $publication->title }}" loading="lazy">
                        </div>
                    @endif

                    <div class="publication-card__body">
                        <div class="publication-card__top">
                            <span class="publication-card__type badge-{{ $publication->type }}">
                                <i class="fa-solid {{ $publication->type === 'journal' ? 'fa-book-open' : 'fa-people-group' }}" aria-hidden="true"></i>
                                {{ __('site.'.$publication->type) }}
                            </span>

                            @if ($publication->citation_count > 0)
                                <span class="publication-card__citations">
                                    <i class="fa-solid fa-quote-right" aria-hidden="true"></i>
                                    {{ $publication->citation_count }} {{ (int) $publication->citation_count === 1 ? __('site.citation') : __('site.citations') }}
                                </span>
                            @endif
                        </div>

                        <h3>{{ $publication->title }}</h3>

                        @if ($publication->abstract)
                            <p>{{ \Illuminate\Support\Str::limit(strip_tags($publication->abstract), 220) }}</p>
                        @endif

                        <div class="publication-card__footer">
                            <a href="{{ $publication->doiUrl() }}" target="_blank" rel="noopener" class="publication-card__doi">
                                <i class="fa-solid fa-link" aria-hidden="true"></i>
                                {{ $publication->doi }}
                            </a>

                            <div class="publication-card__actions">
                                @if ($publication->hasPdf())
                                    <a href="{{ asset('storage/'.$publication->pdf_path) }}" target="_blank" rel="noopener" class="publication-card__btn">
                                        <i class="fa-solid fa-file-arrow-down" aria-hidden="true"></i>
                                        {{ __('site.download_pdf') }}
                                    </a>
                                @endif

                                @if ($publication->hasVideo())
                                    <a href="{{ $publication->video_url }}" target="_blank" rel="noopener" class="publication-card__btn">
                                        <i class="fa-solid fa-circle-play" aria-hidden="true"></i>
                                        {{ __('site.watch_highlights') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif
