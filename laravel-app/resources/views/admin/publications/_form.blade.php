@php
    $locales = ['en' => 'English', 'es' => 'Español'];
@endphp

<form
    method="POST"
    action="{{ $publication->exists ? route('admin.publications.update', $publication) : route('admin.publications.store') }}"
    enctype="multipart/form-data"
    class="admin-form"
>
    @csrf
    @if ($publication->exists)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="form-main">
            <div class="form-field">
                <label for="doi">DOI <span class="required">*</span></label>
                <input type="text" id="doi" name="doi" value="{{ old('doi', $publication->doi) }}" placeholder="10.2118/212569-MS">
                <p class="form-field__hint">Paste the bare DOI or a doi.org URL — either works, it's normalized on save. Used to look up citation counts on Crossref.</p>
                @error('doi')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="type">Type <span class="required">*</span></label>
                <select id="type" name="type">
                    <option value="journal" {{ old('type', $publication->type ?? 'journal') === 'journal' ? 'selected' : '' }}>Journal</option>
                    <option value="conference" {{ old('type', $publication->type ?? 'journal') === 'conference' ? 'selected' : '' }}>Conference</option>
                </select>
                @error('type')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="tabs" data-tabs>
                <div class="tabs__nav" role="tablist">
                    @foreach ($locales as $locale => $label)
                        <button type="button" class="tabs__trigger {{ $loop->first ? 'is-active' : '' }}" data-tab-trigger="{{ $locale }}">{{ $label }}</button>
                    @endforeach
                </div>

                @foreach ($locales as $locale => $label)
                    @php
                        $titleValue = old("title.$locale", $publication->exists ? $publication->getTranslation('title', $locale, false) : '') ?? '';
                        $abstractValue = old("abstract.$locale", $publication->exists ? $publication->getTranslation('abstract', $locale, false) : '') ?? '';
                    @endphp
                    <div class="tabs__panel {{ $loop->first ? 'is-active' : '' }}" data-tab-panel="{{ $locale }}">
                        <div class="form-field">
                            <label for="title-{{ $locale }}">
                                Title ({{ $label }})
                                @if ($locale === 'en')<span class="required">*</span>@endif
                            </label>
                            <input type="text" id="title-{{ $locale }}" name="title[{{ $locale }}]" value="{{ $titleValue }}">
                            @error("title.$locale")
                                <p class="form-field__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="abstract-{{ $locale }}">Abstract ({{ $label }})</label>
                            <x-wysiwyg-editor :name="'abstract['.$locale.']'" :id="'abstract-'.$locale" :value="$abstractValue" />
                            @error("abstract.$locale")
                                <p class="form-field__error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-sidebar">
            <div class="form-field">
                <label for="citation_count">Citation count</label>
                <input type="number" id="citation_count" name="citation_count" min="0" value="{{ old('citation_count', $publication->citation_count ?? '') }}" placeholder="Leave blank to auto-fetch from Crossref">
                <p class="form-field__hint">
                    @if ($publication->exists && $publication->citation_source === 'auto')
                        Last synced from Crossref {{ $publication->citation_synced_at?->diffForHumans() }}.
                    @elseif ($publication->exists)
                        Currently a manual value.
                    @else
                        Leave blank to fetch automatically from Crossref by DOI on save.
                    @endif
                </p>
            </div>

            @if ($publication->exists)
                <button type="submit" form="refresh-citations-form" class="btn btn--ghost btn--inline">
                    <i class="fa-solid fa-arrows-rotate" aria-hidden="true"></i>
                    Refresh from Crossref
                </button>
            @endif

            <div class="form-field">
                <label for="video_url">Video URL</label>
                <input type="text" id="video_url" name="video_url" value="{{ old('video_url', $publication->video_url) }}" placeholder="https://youtube.com/...">
                @error('video_url')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="display_order">Display order</label>
                <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $publication->display_order ?? 0) }}">
            </div>

            <div class="form-check">
                <input type="checkbox" id="active" name="active" value="1" {{ old('active', $publication->active ?? true) ? 'checked' : '' }}>
                <label for="active">Active (shown on the public site)</label>
            </div>

            <x-image-upload
                name="image"
                label="Image"
                :current-url="$publication->image ? asset('storage/'.$publication->image) : null"
            />

            @if ($publication->exists && $publication->image)
                <div class="form-check">
                    <input type="checkbox" id="remove_image" name="remove_image" value="1">
                    <label for="remove_image">Remove current image</label>
                </div>
            @endif

            <x-file-upload
                name="pdf"
                label="PDF"
                accept="application/pdf"
                :current-url="$publication->pdf_path ? asset('storage/'.$publication->pdf_path) : null"
                current-label="Current PDF"
                hint="Maximum 50 MB."
            />

            @if ($publication->exists && $publication->pdf_path)
                <div class="form-check">
                    <input type="checkbox" id="remove_pdf" name="remove_pdf" value="1">
                    <label for="remove_pdf">Remove current PDF</label>
                </div>
            @endif

            <button type="submit" class="btn">{{ $publication->exists ? 'Save changes' : 'Create publication' }}</button>
        </div>
    </div>
</form>

@if ($publication->exists)
    <form id="refresh-citations-form" method="POST" action="{{ route('admin.publications.refresh-citations', $publication) }}" hidden>
        @csrf
    </form>
@endif
