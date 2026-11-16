@extends('admin.layouts.dashboard')

@section('title', 'General Settings')

@section('content')
    <form method="POST" action="{{ route('admin.settings.general.update') }}" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-main form-main--narrow">
            <div class="form-field">
                <label for="site_name">Site name <span class="required">*</span></label>
                <input type="text" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}">
                <p class="form-field__hint">Shown in the public site header, browser tab and footer.</p>
                @error('site_name')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="site_tagline">Tagline (English)</label>
                <input type="text" id="site_tagline" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}">
                <p class="form-field__hint">Shown under your name in the homepage header, and used as the default page description for search engines.</p>
                @error('site_tagline')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="site_tagline_es">Tagline (Español)</label>
                <input type="text" id="site_tagline_es" name="site_tagline_es" value="{{ old('site_tagline_es', $settings['site_tagline_es']) }}">
                <p class="form-field__hint">Shown when a visitor switches to Spanish. Leave blank to use the English tagline.</p>
                @error('site_tagline_es')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label for="default_locale">Default language</label>
                <select id="default_locale" name="default_locale">
                    <option value="en" {{ old('default_locale', $settings['default_locale']) === 'en' ? 'selected' : '' }}>English</option>
                    <option value="es" {{ old('default_locale', $settings['default_locale']) === 'es' ? 'selected' : '' }}>Español</option>
                </select>
                <p class="form-field__hint">The language a first-time visitor sees; they can switch it themselves from the EN/ES link in the public site header.</p>
                @error('default_locale')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-field">
                <label>Site layout mode</label>
                <div class="form-check">
                    <input type="radio" id="site_mode_multipage" name="site_mode" value="multipage" {{ old('site_mode', $settings['site_mode']) === 'multipage' ? 'checked' : '' }}>
                    <label for="site_mode_multipage">Multipage — each section has its own URL</label>
                </div>
                <div class="form-check">
                    <input type="radio" id="site_mode_landing" name="site_mode" value="landing" {{ old('site_mode', $settings['site_mode']) === 'landing' ? 'checked' : '' }}>
                    <label for="site_mode_landing">Landing — every enabled section appears on one scrolling homepage</label>
                </div>
                <p class="form-field__hint">Individual detail pages (a blog post, a news item) always keep their own URL in both modes.</p>
                @error('site_mode')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn--inline">Save settings</button>
        </div>
    </form>
@endsection
