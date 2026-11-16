@php
    $locales = ['en' => 'English', 'es' => 'Español'];
@endphp

<form
    method="POST"
    action="{{ $award->exists ? route('admin.awards.update', $award) : route('admin.awards.store') }}"
    enctype="multipart/form-data"
    class="admin-form"
>
    @csrf
    @if ($award->exists)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="form-main">
            <div class="tabs" data-tabs>
                <div class="tabs__nav" role="tablist">
                    @foreach ($locales as $locale => $label)
                        <button type="button" class="tabs__trigger {{ $loop->first ? 'is-active' : '' }}" data-tab-trigger="{{ $locale }}">{{ $label }}</button>
                    @endforeach
                </div>

                @foreach ($locales as $locale => $label)
                    @php
                        $titleValue = old("title.$locale", $award->exists ? $award->getTranslation('title', $locale, false) : '') ?? '';
                        $descriptionValue = old("description.$locale", $award->exists ? $award->getTranslation('description', $locale, false) : '') ?? '';
                    @endphp
                    <div class="tabs__panel {{ $loop->first ? 'is-active' : '' }}" data-tab-panel="{{ $locale }}">
                        <div class="form-field">
                            <label for="title-{{ $locale }}">
                                Award name ({{ $label }})
                                @if ($locale === 'en')<span class="required">*</span>@endif
                            </label>
                            <input type="text" id="title-{{ $locale }}" name="title[{{ $locale }}]" value="{{ $titleValue }}">
                            @error("title.$locale")
                                <p class="form-field__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="description-{{ $locale }}">Short description ({{ $label }})</label>
                            <textarea id="description-{{ $locale }}" name="description[{{ $locale }}]" rows="4">{{ $descriptionValue }}</textarea>
                            <p class="form-field__hint">A sentence or two on what the award recognises.</p>
                            @error("description.$locale")
                                <p class="form-field__error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-sidebar">
            <div class="form-field">
                <label for="granted_by">Granted by <span class="required">*</span></label>
                <input type="text" id="granted_by" name="granted_by" value="{{ old('granted_by', $award->granted_by) }}">
                <p class="form-field__hint">The institution, society or person that granted it.</p>
                @error('granted_by')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="awarded_on">Date <span class="required">*</span></label>
                <input type="date" id="awarded_on" name="awarded_on" value="{{ old('awarded_on', optional($award->awarded_on)->toDateString()) }}">
                @error('awarded_on')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="display_order">Display order</label>
                <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $award->display_order ?? 0) }}" class="data-table__order-input">
                <p class="form-field__hint">Lower numbers first; ties fall back to most recent.</p>
                @error('display_order')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-check">
                <input type="checkbox" id="active" name="active" value="1" {{ old('active', $award->exists ? $award->active : true) ? 'checked' : '' }}>
                <label for="active">Active (shown on the public site)</label>
            </div>

            <x-image-upload
                name="image"
                label="Image"
                :current-url="$award->image ? asset('storage/'.$award->image) : null"
            />

            @if ($award->exists && $award->image)
                <div class="form-check">
                    <input type="checkbox" id="remove_image" name="remove_image" value="1">
                    <label for="remove_image">Remove current image</label>
                </div>
            @endif

            <button type="submit" class="btn">{{ $award->exists ? 'Save changes' : 'Create award' }}</button>
        </div>
    </div>
</form>
