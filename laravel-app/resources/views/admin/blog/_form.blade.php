@php
    $locales = ['en' => 'English', 'es' => 'Español'];
@endphp

<form
    method="POST"
    action="{{ $post->exists ? route('admin.blog.update', $post) : route('admin.blog.store') }}"
    enctype="multipart/form-data"
    class="admin-form"
>
    @csrf
    @if ($post->exists)
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
                        $titleValue = old("title.$locale", $post->exists ? $post->getTranslation('title', $locale, false) : '') ?? '';
                        $excerptValue = old("excerpt.$locale", $post->exists ? $post->getTranslation('excerpt', $locale, false) : '') ?? '';
                        $bodyValue = old("body.$locale", $post->exists ? $post->getTranslation('body', $locale, false) : '') ?? '';
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
                            <label for="excerpt-{{ $locale }}">Excerpt ({{ $label }})</label>
                            <textarea id="excerpt-{{ $locale }}" name="excerpt[{{ $locale }}]" rows="3">{{ $excerptValue }}</textarea>
                            @error("excerpt.$locale")
                                <p class="form-field__error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-field">
                            <label for="body-{{ $locale }}">
                                Body ({{ $label }})
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
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="draft" {{ old('status', $post->status ?? 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $post->status ?? 'draft') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            <x-image-upload
                name="featured_image"
                label="Featured image"
                :current-url="$post->featured_image ? asset('storage/'.$post->featured_image) : null"
            />

            @if ($post->exists && $post->featured_image)
                <div class="form-check">
                    <input type="checkbox" id="remove_image" name="remove_image" value="1">
                    <label for="remove_image">Remove current image</label>
                </div>
            @endif

            <button type="submit" class="btn">{{ $post->exists ? 'Save changes' : 'Create post' }}</button>
        </div>
    </div>
</form>
