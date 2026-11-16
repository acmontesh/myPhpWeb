@php
    $locales = ['en' => 'English', 'es' => 'Español'];
@endphp

<form
    method="POST"
    action="{{ $newsItem->exists ? route('admin.news.update', $newsItem) : route('admin.news.store') }}"
    enctype="multipart/form-data"
    class="admin-form"
>
    @csrf
    @if ($newsItem->exists)
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
                        $titleValue = old("title.$locale", $newsItem->exists ? $newsItem->getTranslation('title', $locale, false) : '') ?? '';
                        $bodyValue = old("body.$locale", $newsItem->exists ? $newsItem->getTranslation('body', $locale, false) : '') ?? '';
                    @endphp
                    <div class="tabs__panel {{ $loop->first ? 'is-active' : '' }}" data-tab-panel="{{ $locale }}">
                        <div class="form-field">
                            <label for="title-{{ $locale }}">
                                Headline ({{ $label }})
                                @if ($locale === 'en')<span class="required">*</span>@endif
                            </label>
                            <input type="text" id="title-{{ $locale }}" name="title[{{ $locale }}]" value="{{ $titleValue }}">
                            @error("title.$locale")
                                <p class="form-field__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="body-{{ $locale }}">
                                Content ({{ $label }})
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
                <label for="published_at">Publish date <span class="required">*</span></label>
                <input type="date" id="published_at" name="published_at" value="{{ old('published_at', optional($newsItem->published_at)->toDateString()) }}">
                @error('published_at')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-check">
                <input type="checkbox" id="active" name="active" value="1" {{ old('active', $newsItem->active ?? true) ? 'checked' : '' }}>
                <label for="active">Active (shown on the public site)</label>
            </div>

            <x-image-upload
                name="image1"
                label="Image 1"
                :current-url="$newsItem->image1 ? asset('storage/'.$newsItem->image1) : null"
            />

            @if ($newsItem->exists && $newsItem->image1)
                <div class="form-check">
                    <input type="checkbox" id="remove_image1" name="remove_image1" value="1">
                    <label for="remove_image1">Remove image 1</label>
                </div>
            @endif

            <x-image-upload
                name="image2"
                label="Image 2"
                :current-url="$newsItem->image2 ? asset('storage/'.$newsItem->image2) : null"
            />

            @if ($newsItem->exists && $newsItem->image2)
                <div class="form-check">
                    <input type="checkbox" id="remove_image2" name="remove_image2" value="1">
                    <label for="remove_image2">Remove image 2</label>
                </div>
            @endif

            <button type="submit" class="btn">{{ $newsItem->exists ? 'Save changes' : 'Create news item' }}</button>
        </div>
    </div>
</form>
