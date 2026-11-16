<form
    method="POST"
    action="{{ $socialLink->exists ? route('admin.settings.social.update', $socialLink) : route('admin.settings.social.store') }}"
    class="admin-form"
>
    @csrf
    @if ($socialLink->exists)
        @method('PUT')
    @endif

    <div class="form-main form-main--narrow">
        <div class="form-field">
            <label for="platform">Platform <span class="required">*</span></label>
            <input type="text" id="platform" name="platform" value="{{ old('platform', $socialLink->platform) }}" placeholder="LinkedIn">
            @error('platform')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="url">URL <span class="required">*</span></label>
            <input type="text" id="url" name="url" value="{{ old('url', $socialLink->url) }}" placeholder="https://...">
            @error('url')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="icon_class">Font Awesome icon class <span class="required">*</span></label>
            <input type="text" id="icon_class" name="icon_class" value="{{ old('icon_class', $socialLink->icon_class) }}" placeholder="fa-brands fa-linkedin-in">
            @error('icon_class')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="display_order">Display order</label>
            <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $socialLink->display_order ?? 0) }}">
        </div>

        <div class="form-check">
            <input type="checkbox" id="active" name="active" value="1" {{ old('active', $socialLink->active ?? true) ? 'checked' : '' }}>
            <label for="active">Active (shown on the public site)</label>
        </div>

        <button type="submit" class="btn btn--inline">{{ $socialLink->exists ? 'Save changes' : 'Create link' }}</button>
    </div>
</form>
