@extends('admin.layouts.dashboard')

@section('title', 'Header Carousel')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">Background images that cross-fade automatically behind the homepage header. Visitors have no controls over these — they advance on their own.</p>
        <a href="{{ route('admin.hero-slides.create') }}" class="btn btn--inline">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            New Slide
        </a>
    </div>

    {{-- Opacity of the accent wash that sits over the header images. Applies
         to the fallback single image too, so it stays visible with no slides. --}}
    <form method="POST" action="{{ route('admin.hero-slides.overlay') }}" class="admin-form hero-overlay-form">
        @csrf
        @method('PUT')

        <div class="hero-overlay-form__intro">
            <h2>Overlay opacity</h2>
            <p>How strongly the accent colour tints the header images. Lower values reveal more of the photograph; higher values make the name and tagline easier to read.</p>
        </div>

        <div class="hero-overlay-form__control">
            <label for="hero_overlay_alpha">Opacity</label>
            <div class="hero-overlay-form__slider">
                <input
                    type="range"
                    id="hero_overlay_alpha"
                    name="hero_overlay_alpha"
                    min="0"
                    max="100"
                    step="1"
                    value="{{ old('hero_overlay_alpha', $overlayAlpha) }}"
                    data-overlay-range
                    data-overlay-preview-target="overlay-preview"
                    aria-describedby="hero_overlay_alpha_value"
                >
                <output id="hero_overlay_alpha_value" for="hero_overlay_alpha" data-overlay-output>{{ old('hero_overlay_alpha', $overlayAlpha) }}%</output>
            </div>
            @error('hero_overlay_alpha')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="hero-overlay-preview" aria-hidden="true">
            @php($previewSlide = $slides->firstWhere('active', true) ?? $slides->first())
            <div
                class="hero-overlay-preview__image"
                @if ($previewSlide) style="background-image: url('{{ asset('storage/'.$previewSlide->displayPath()) }}')" @endif
            ></div>
            <div
                id="overlay-preview"
                class="hero-overlay-preview__wash"
                data-overlay-preview
                style="opacity: {{ old('hero_overlay_alpha', $overlayAlpha) / 100 }}"
            ></div>
            <span class="hero-overlay-preview__label">{{ \App\Models\Setting::siteName() }}</span>
        </div>

        <button type="submit" class="btn btn--inline">Save opacity</button>
    </form>

    @if ($slides->isEmpty())
        <x-admin.empty-state
            icon="fa-solid fa-images"
            title="No header slides yet"
            message="Add images here and the homepage header becomes an automatic carousel. Without any, it falls back to the single hero image set under Appearance → Images."
            action-label="New Slide"
            :action-href="route('admin.hero-slides.create')"
        />
    @else
        <div class="hero-slide-grid">
            @foreach ($slides as $slide)
                <div class="hero-slide-card">
                    <div class="hero-slide-card__image" style="background-image: url('{{ asset('storage/'.$slide->displayPath()) }}')"></div>
                    <div class="hero-slide-card__body">
                        <strong>{{ $slide->caption ?: 'Untitled slide' }}</strong>
                        <span>Order {{ $slide->display_order }}</span>
                        <span class="badge {{ $slide->active ? 'badge--success' : 'badge--muted' }}">
                            {{ $slide->active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="hero-slide-card__actions">
                        <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="icon-link" aria-label="Edit">
                            <i class="fa-solid fa-pen" aria-hidden="true"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.hero-slides.destroy', $slide) }}" data-confirm="Delete this header slide?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-link icon-link--danger" aria-label="Delete">
                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
