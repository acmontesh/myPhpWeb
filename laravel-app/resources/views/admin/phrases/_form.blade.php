<form
    method="POST"
    action="{{ $phrase->exists ? route('admin.settings.phrases.update', $phrase) : route('admin.settings.phrases.store') }}"
    class="admin-form"
>
    @csrf
    @if ($phrase->exists)
        @method('PUT')
    @endif

    <div class="form-main form-main--narrow">
        <div class="form-field">
            <label for="key">Key <span class="required">*</span></label>
            <input type="text" id="key" name="key" value="{{ old('key', $phrase->key) }}" placeholder="empty_state.blog.title">
            <p class="form-field__hint">A unique identifier used in the code to look this phrase up.</p>
            @error('key')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="group">Group</label>
            <input type="text" id="group" name="group" value="{{ old('group', $phrase->group) }}" placeholder="Empty States">
            @error('group')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="value-en">English <span class="required">*</span></label>
            <input type="text" id="value-en" name="value[en]" value="{{ old('value.en', $phrase->exists ? $phrase->getTranslation('value', 'en', false) : '') }}">
            @error('value.en')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="value-es">Español</label>
            <input type="text" id="value-es" name="value[es]" value="{{ old('value.es', $phrase->exists ? $phrase->getTranslation('value', 'es', false) : '') }}">
            @error('value.es')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        @if ($phrase->key === 'hero.roles')
            <p class="form-field__hint">Separate each role with a vertical bar ( | ). The homepage header types them out one after another.</p>
        @endif

        <button type="submit" class="btn btn--inline">{{ $phrase->exists ? 'Save changes' : 'Create phrase' }}</button>
    </div>
</form>
