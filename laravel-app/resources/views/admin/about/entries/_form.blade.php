@php
    $locales = ['en' => 'English', 'es' => 'Español'];
@endphp

<form
    method="POST"
    action="{{ $entry->exists ? route('admin.settings.about.entries.update', $entry) : route('admin.settings.about.entries.store') }}"
    class="admin-form"
>
    @csrf
    @if ($entry->exists)
        @method('PUT')
    @endif

    <div class="form-main form-main--narrow">
        <div class="form-field">
            <label for="section">Subsection <span class="required">*</span></label>
            <select id="section" name="section">
                @foreach (\App\Models\AboutEntry::SECTIONS as $key => $label)
                    <option value="{{ $key }}" {{ old('section', $entry->section) === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('section')
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
                    $titleValue = old("title.$locale", $entry->exists ? $entry->getTranslation('title', $locale, false) : '') ?? '';
                    $detailValue = old("detail.$locale", $entry->exists ? $entry->getTranslation('detail', $locale, false) : '') ?? '';
                @endphp
                <div class="tabs__panel {{ $loop->first ? 'is-active' : '' }}" data-tab-panel="{{ $locale }}">
                    <div class="form-field">
                        <label for="title-{{ $locale }}">
                            Title ({{ $label }})
                            @if ($locale === 'en')<span class="required">*</span>@endif
                        </label>
                        <input type="text" id="title-{{ $locale }}" name="title[{{ $locale }}]" value="{{ $titleValue }}">
                        <p class="form-field__hint">The main line of the bullet — e.g. "PhD in Petroleum Engineering".</p>
                        @error("title.$locale")
                            <p class="form-field__error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-field">
                        <label for="detail-{{ $locale }}">Detail ({{ $label }})</label>
                        <textarea id="detail-{{ $locale }}" name="detail[{{ $locale }}]" rows="3">{{ $detailValue }}</textarea>
                        <p class="form-field__hint">Optional supporting line — e.g. "The University of Texas at Austin, 2021–2025".</p>
                        @error("detail.$locale")
                            <p class="form-field__error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endforeach
        </div>

        <div class="form-field">
            <label>Icon <span class="required">*</span></label>
            <x-icon-picker
                name="icon_class"
                :value="old('icon_class', $entry->icon_class)"
                :icons="\App\Models\AboutEntry::ICONS"
            />
            @error('icon_class')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="display_order">Display order</label>
            <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $entry->display_order ?? 0) }}" class="data-table__order-input">
            <p class="form-field__hint">Lower numbers appear first within the subsection.</p>
            @error('display_order')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="admin-form__buttons">
            <button type="submit" class="btn btn--inline">{{ $entry->exists ? 'Save changes' : 'Create entry' }}</button>
            <a href="{{ route('admin.settings.about') }}" class="btn btn--ghost btn--inline">Cancel</a>
        </div>
    </div>
</form>
