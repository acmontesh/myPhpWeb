<form
    method="POST"
    action="{{ $slide->exists ? route('admin.hero-slides.update', $slide) : route('admin.hero-slides.store') }}"
    enctype="multipart/form-data"
    class="admin-form"
>
    @csrf
    @if ($slide->exists)
        @method('PUT')
    @endif

    <div class="form-main form-main--narrow">
        <x-image-upload
            name="image"
            label="Background image"
            :current-url="$slide->image_path ? asset('storage/'.$slide->displayPath()) : null"
        />
        <p class="form-field__hint">Wide, landscape photos work best — they're resized and converted to WebP automatically.</p>
        @error('image')
            <p class="form-field__error">{{ $message }}</p>
        @enderror

        <div class="form-field">
            <label for="caption">Caption</label>
            <input type="text" id="caption" name="caption" value="{{ old('caption', $slide->caption) }}">
            <p class="form-field__hint">Internal label only — helps you tell slides apart here. Not shown on the site.</p>
            @error('caption')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="display_order">Display order</label>
            <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $slide->display_order ?? 0) }}" class="data-table__order-input">
            @error('display_order')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-check">
            <input type="checkbox" id="active" name="active" value="1" {{ old('active', $slide->exists ? $slide->active : true) ? 'checked' : '' }}>
            <label for="active">Active</label>
        </div>

        <div class="admin-form__buttons">
            <button type="submit" class="btn btn--inline">{{ $slide->exists ? 'Save changes' : 'Add slide' }}</button>
            <a href="{{ route('admin.hero-slides.index') }}" class="btn btn--ghost btn--inline">Cancel</a>
        </div>
    </div>
</form>
