@php
    $aboutEntries = \App\Models\AboutEntry::query()->ordered()->get()->groupBy('section');
    $hasAboutEntries = $aboutEntries->flatten()->isNotEmpty();
    $sectionIcons = ['education' => 'fa-solid fa-graduation-cap', 'experience' => 'fa-solid fa-briefcase', 'interests' => 'fa-solid fa-lightbulb'];
@endphp

@if (! $about->bio && ! $hasAboutEntries)
    <x-public.empty-state
        icon="fa-solid fa-user"
        :title="\App\Models\PublicPhrase::text('empty_state.about.title', 'About Me is coming soon')"
        :message="\App\Models\PublicPhrase::text('empty_state.about.message', 'A full profile will appear here soon.')"
    />
@else
    <div class="about-content">
        @if ($aboutPhoto)
            <div class="about-content__media" data-reveal="left">
                <div class="about-content__frame" data-tilt data-tilt-max="7">
                    <img src="{{ asset('storage/'.$aboutPhoto) }}" alt="{{ \App\Models\Setting::siteName() }}" loading="lazy">
                </div>
                <span class="about-content__deco" data-parallax="0.12" aria-hidden="true"></span>
            </div>
        @endif

        <div class="about-content__body" data-reveal="up" style="--reveal-delay: 120ms">
            @if ($about->headline)
                <p class="about-content__headline">{{ $about->headline }}</p>
            @endif
            {!! $about->bio !!}
        </div>
    </div>

    @if ($hasAboutEntries)
        <div class="about-subsections">
            @foreach (\App\Models\AboutEntry::SECTIONS as $sectionKey => $sectionLabel)
                @php
                    $entries = $aboutEntries[$sectionKey] ?? collect();
                @endphp

                @continue($entries->isEmpty())

                <section class="about-subsection about-subsection--{{ $sectionKey }}" data-reveal="up" data-tilt data-tilt-max="4" style="--reveal-delay: {{ $loop->index * 110 }}ms">
                    <h3 class="about-subsection__title">
                        <i class="{{ $sectionIcons[$sectionKey] ?? 'fa-solid fa-circle' }}" aria-hidden="true"></i>
                        {{ __('site.'.$sectionKey) }}
                    </h3>

                    <ul class="about-subsection__list">
                        @foreach ($entries as $entry)
                            <li class="about-subsection__item">
                                <span class="about-subsection__icon">
                                    <i class="{{ $entry->icon_class }}" aria-hidden="true"></i>
                                </span>
                                <div class="about-subsection__text">
                                    <strong>{{ $entry->title }}</strong>
                                    @if ($entry->detail)
                                        <span>{{ $entry->detail }}</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>
    @endif
@endif
