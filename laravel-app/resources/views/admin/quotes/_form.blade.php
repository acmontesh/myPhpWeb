@php
    $locales = ['en' => 'English', 'es' => 'Español'];
@endphp

<form
    method="POST"
    action="{{ $quote->exists ? route('admin.quotes.update', $quote) : route('admin.quotes.store') }}"
    enctype="multipart/form-data"
    class="admin-form"
>
    @csrf
    @if ($quote->exists)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="form-main">
            <div class="form-field">
                <label for="author_name">Author name <span class="required">*</span></label>
                <input type="text" id="author_name" name="author_name" value="{{ old('author_name', $quote->author_name) }}">
                @error('author_name')
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
                        $quoteValue = old("quote_text.$locale", $quote->exists ? $quote->getTranslation('quote_text', $locale, false) : '') ?? '';
                    @endphp
                    <div class="tabs__panel {{ $loop->first ? 'is-active' : '' }}" data-tab-panel="{{ $locale }}">
                        <div class="form-field">
                            <label for="quote_text-{{ $locale }}">
                                Quote ({{ $label }})
                                @if ($locale === 'en')<span class="required">*</span>@endif
                            </label>
                            <textarea id="quote_text-{{ $locale }}" name="quote_text[{{ $locale }}]" rows="4">{{ $quoteValue }}</textarea>
                            @error("quote_text.$locale")
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
                <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $quote->display_order ?? 0) }}">
            </div>

            <div class="form-check">
                <input type="checkbox" id="active" name="active" value="1" {{ old('active', $quote->active ?? true) ? 'checked' : '' }}>
                <label for="active">Active (shown in the carousel)</label>
            </div>

            <x-image-upload
                name="author_image"
                label="Author photo"
                :current-url="$quote->author_image ? asset('storage/'.$quote->author_image) : null"
            />

            @if ($quote->exists && $quote->author_image)
                <div class="form-check">
                    <input type="checkbox" id="remove_image" name="remove_image" value="1">
                    <label for="remove_image">Remove current photo</label>
                </div>
            @endif

            <button type="submit" class="btn">{{ $quote->exists ? 'Save changes' : 'Create quote' }}</button>
        </div>
    </div>
</form>
