@extends('admin.layouts.dashboard')

@section('title', 'About Me')

@push('head')
    <link rel="stylesheet" href="{{ asset('vendor/jodit/jodit.min.css') }}">
    <script src="{{ asset('vendor/jodit/jodit.min.js') }}" defer></script>
@endpush

@php
    $locales = ['en' => 'English', 'es' => 'Español'];
@endphp

@section('content')
    <form method="POST" action="{{ route('admin.settings.about.update') }}" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-main form-main--narrow">
            <p class="form-field__hint">This copy powers the public About Me page. The photo shown alongside it is managed from Appearance &rarr; Images.</p>

            <div class="tabs" data-tabs>
                <div class="tabs__nav" role="tablist">
                    @foreach ($locales as $locale => $label)
                        <button type="button" class="tabs__trigger {{ $loop->first ? 'is-active' : '' }}" data-tab-trigger="{{ $locale }}">{{ $label }}</button>
                    @endforeach
                </div>

                @foreach ($locales as $locale => $label)
                    @php
                        $headlineValue = old("headline.$locale", $about->getTranslation('headline', $locale, false)) ?? '';
                        $bioValue = old("bio.$locale", $about->getTranslation('bio', $locale, false)) ?? '';
                    @endphp
                    <div class="tabs__panel {{ $loop->first ? 'is-active' : '' }}" data-tab-panel="{{ $locale }}">
                        <div class="form-field">
                            <label for="headline-{{ $locale }}">
                                Headline ({{ $label }})
                                @if ($locale === 'en')<span class="required">*</span>@endif
                            </label>
                            <input type="text" id="headline-{{ $locale }}" name="headline[{{ $locale }}]" value="{{ $headlineValue }}">
                            @error("headline.$locale")
                                <p class="form-field__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="bio-{{ $locale }}">
                                Bio ({{ $label }})
                                @if ($locale === 'en')<span class="required">*</span>@endif
                            </label>
                            <x-wysiwyg-editor :name="'bio['.$locale.']'" :id="'bio-'.$locale" :value="$bioValue" />
                            @error("bio.$locale")
                                <p class="form-field__error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn--inline">Save changes</button>
        </div>
    </form>

    <div class="form-main form-main--narrow">
        <hr class="admin-form__divider">
        <h2 class="admin-form__subheading">About Me subsections</h2>
        <p class="form-field__hint">These appear on the public About Me page as icon bullet lists, below your bio.</p>

        @foreach (\App\Models\AboutEntry::SECTIONS as $sectionKey => $sectionLabel)
            @php
                $entries = $entriesBySection[$sectionKey] ?? collect();
            @endphp

            <div class="about-entries">
                <div class="about-entries__header">
                    <h3>{{ $sectionLabel }}</h3>
                    <a href="{{ route('admin.settings.about.entries.create', ['section' => $sectionKey]) }}" class="btn btn--inline btn--small">
                        <i class="fa-solid fa-plus" aria-hidden="true"></i>
                        Add entry
                    </a>
                </div>

                @if ($entries->isEmpty())
                    <p class="about-entries__empty">No entries yet in this subsection.</p>
                @else
                    <ul class="about-entries__list">
                        @foreach ($entries as $entry)
                            <li class="about-entries__item">
                                <i class="{{ $entry->icon_class }} about-entries__icon" aria-hidden="true"></i>
                                <div class="about-entries__body">
                                    <strong>{{ $entry->title }}</strong>
                                    @if ($entry->detail)
                                        <span>{{ \Illuminate\Support\Str::limit($entry->detail, 90) }}</span>
                                    @endif
                                </div>
                                <div class="about-entries__actions">
                                    <a href="{{ route('admin.settings.about.entries.edit', $entry) }}" class="icon-link" aria-label="Edit">
                                        <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.settings.about.entries.destroy', $entry) }}" data-confirm="Delete this entry?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="icon-link icon-link--danger" aria-label="Delete">
                                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach
    </div>
@endsection
