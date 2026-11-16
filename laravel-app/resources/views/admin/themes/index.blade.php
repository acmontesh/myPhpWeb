@extends('admin.layouts.dashboard')

@section('title', 'Themes')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">Pick the public site's look. Activating a theme takes effect immediately — no rebuild needed.</p>
    </div>

    <div class="theme-grid">
        @foreach ($themes as $slug => $theme)
            <div class="theme-card {{ $slug === $active ? 'is-active' : '' }}">
                <div class="theme-card__swatches">
                    @foreach ($theme['swatches'] as $color)
                        <span style="background-color: {{ $color }}"></span>
                    @endforeach
                </div>

                <h2>
                    {{ $theme['label'] }}
                    @if ($slug === $active)
                        <span class="badge badge--success">Active</span>
                    @endif
                </h2>
                <p>{{ $theme['description'] }}</p>

                <div class="theme-card__actions">
                    <button
                        type="button"
                        class="btn btn--ghost btn--inline"
                        data-theme-preview-trigger
                        data-preview-url="{{ route('admin.themes.preview', $slug) }}"
                        data-preview-name="{{ $theme['label'] }}"
                    >
                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                        Preview
                    </button>

                    @unless ($slug === $active)
                        <form method="POST" action="{{ route('admin.themes.activate', $slug) }}">
                            @csrf
                            <button type="submit" class="btn btn--inline">Activate</button>
                        </form>
                    @endunless
                </div>
            </div>
        @endforeach
    </div>

    <div class="modal modal--preview" data-theme-preview-modal>
        <div class="modal__panel modal__panel--wide">
            <div class="modal__preview-header">
                <h2 data-theme-preview-title>Preview</h2>
                <button type="button" class="icon-link" data-theme-preview-close aria-label="Close preview">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
            <iframe title="Theme preview" loading="lazy"></iframe>
        </div>
    </div>
@endsection
