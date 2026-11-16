@if ($projects->isEmpty())
    <x-public.empty-state
        icon="fa-solid fa-flask"
        :title="\App\Models\PublicPhrase::text('empty_state.research.title', 'No research projects yet')"
        :message="\App\Models\PublicPhrase::text('empty_state.research.message', 'Project write-ups will appear here soon.')"
    />
@else
    <div class="research-list">
        @foreach ($projects as $project)
            <article class="research-item {{ $loop->even ? 'research-item--reverse' : '' }}">
                @if ($project->image)
                    <div class="research-item__media" data-reveal="{{ $loop->even ? 'right' : 'left' }}">
                        <img src="{{ asset('storage/'.$project->image) }}" alt="{{ $project->title }}" loading="lazy" data-parallax-img>
                    </div>
                @endif

                <div class="research-item__body" data-reveal="up" style="--reveal-delay: 120ms">
                    <span class="research-item__index" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $project->title }}</h3>

                    @if ($project->summary)
                        <p>{{ $project->summary }}</p>
                    @endif

                    <details class="research-item__details" data-accordion>
                        <summary>
                            <span>{{ __('site.read_more') }}</span>
                            <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                        </summary>
                        <div class="research-item__content" data-accordion-content>
                            <div>{!! $project->body !!}</div>
                        </div>
                    </details>
                </div>
            </article>
        @endforeach
    </div>
@endif
