@php
    $locales = ['en' => 'English', 'es' => 'Español'];
@endphp

<form
    method="POST"
    action="{{ $project->exists ? route('admin.research.update', $project) : route('admin.research.store') }}"
    enctype="multipart/form-data"
    class="admin-form"
>
    @csrf
    @if ($project->exists)
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
                        $titleValue = old("title.$locale", $project->exists ? $project->getTranslation('title', $locale, false) : '') ?? '';
                        $summaryValue = old("summary.$locale", $project->exists ? $project->getTranslation('summary', $locale, false) : '') ?? '';
                        $bodyValue = old("body.$locale", $project->exists ? $project->getTranslation('body', $locale, false) : '') ?? '';
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
                            <label for="summary-{{ $locale }}">Summary ({{ $label }}) — short teaser shown before "read more"</label>
                            <textarea id="summary-{{ $locale }}" name="summary[{{ $locale }}]" rows="3">{{ $summaryValue }}</textarea>
                            @error("summary.$locale")
                                <p class="form-field__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="body-{{ $locale }}">
                                Full text ({{ $label }}) — shown when a visitor clicks "read more"
                                @if ($locale === 'en')<span class="required">*</span>@endif
                            </label>
                            <x-wysiwyg-editor :name="'body['.$locale.']'" :id="'body-'.$locale" :value="$bodyValue" />
                            @error("body.$locale")
                                <p class="form-field__error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-sidebar">
            <div class="form-field">
                <label for="display_order">Display order</label>
                <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $project->display_order ?? 0) }}">
            </div>

            <div class="form-check">
                <input type="checkbox" id="active" name="active" value="1" {{ old('active', $project->active ?? true) ? 'checked' : '' }}>
                <label for="active">Active (shown on the public site)</label>
            </div>

            <x-image-upload
                name="image"
                label="Project image"
                :current-url="$project->image ? asset('storage/'.$project->image) : null"
            />

            @if ($project->exists && $project->image)
                <div class="form-check">
                    <input type="checkbox" id="remove_image" name="remove_image" value="1">
                    <label for="remove_image">Remove current image</label>
                </div>
            @endif

            <button type="submit" class="btn">{{ $project->exists ? 'Save changes' : 'Create project' }}</button>
        </div>
    </div>
</form>
