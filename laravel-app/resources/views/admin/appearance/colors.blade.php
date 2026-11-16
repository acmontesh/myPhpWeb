@extends('admin.layouts.dashboard')

@section('title', 'Dashboard Colors')

@section('content')
    @php
        $admin = auth('admin')->user();
    @endphp

    <form method="POST" action="{{ route('admin.appearance.update') }}" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-main form-main--narrow">
            <div class="form-field">
                <label>Mode</label>
                <div class="mode-toggle">
                    <label class="mode-toggle__option">
                        <input type="radio" name="ui_color_mode" value="light" {{ old('ui_color_mode', $admin->ui_color_mode) === 'light' ? 'checked' : '' }}>
                        <i class="fa-solid fa-sun" aria-hidden="true"></i>
                        Light
                    </label>
                    <label class="mode-toggle__option">
                        <input type="radio" name="ui_color_mode" value="dark" {{ old('ui_color_mode', $admin->ui_color_mode) === 'dark' ? 'checked' : '' }}>
                        <i class="fa-solid fa-moon" aria-hidden="true"></i>
                        Dark
                    </label>
                </div>
                <p class="form-field__hint">This applies to your dashboard only, stays set even after you log out.</p>
            </div>

            <div class="form-field">
                <label>Accent color</label>
                <div class="accent-picker">
                    <span class="accent-picker__preview" data-accent-preview style="background-color: {{ old('ui_accent_color', $admin->ui_accent_color) }}"></span>
                    <button type="button" class="btn btn--ghost btn--inline" data-color-modal-open>
                        <i class="fa-solid fa-palette" aria-hidden="true"></i>
                        Choose color
                    </button>
                </div>
                <input type="hidden" name="ui_accent_color" value="{{ old('ui_accent_color', $admin->ui_accent_color) }}" data-accent-input>
                @error('ui_accent_color')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn--inline">Save appearance</button>
        </div>
    </form>

    <div class="modal" data-color-modal>
        <div class="modal__panel">
            <h2>Choose an accent color</h2>
            <div class="color-grid">
                @foreach ($colors as $hex => $label)
                    <button
                        type="button"
                        class="color-grid__swatch {{ $hex === old('ui_accent_color', $admin->ui_accent_color) ? 'is-selected' : '' }}"
                        style="background-color: {{ $hex }}"
                        data-color-option="{{ $hex }}"
                        aria-label="{{ $label }}"
                    ></button>
                @endforeach
            </div>
            <div class="modal__actions">
                <button type="button" class="btn btn--ghost" data-color-modal-close>Close</button>
            </div>
        </div>
    </div>
@endsection
