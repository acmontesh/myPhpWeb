@extends('admin.layouts.dashboard')

@section('title', 'Images')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">Slots used by the currently active theme (<strong>{{ $activeTheme }}</strong>). Switch themes under Appearance → Themes.</p>
    </div>

    <div class="slot-grid">
        @foreach ($slots as $slot)
            <div class="slot-card">
                <div class="slot-card__preview">
                    @if ($slot->resolvedPath())
                        <img src="{{ asset('storage/'.$slot->resolvedPath()) }}" alt="{{ $slot->label }}">
                    @else
                        <span class="slot-card__placeholder"><i class="fa-solid fa-image" aria-hidden="true"></i></span>
                    @endif
                </div>

                <h2>{{ $slot->label }}</h2>
                <p class="form-field__hint">
                    {{ $slot->current_image_path ? 'Custom image in use.' : 'Using the theme\'s default image.' }}
                </p>

                <form method="POST" action="{{ route('admin.images.update', $slot->slot_key) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="file" name="image" accept="image/png,image/jpeg,image/webp">
                    <div class="slot-card__actions">
                        <button type="submit" class="btn btn--inline">Upload</button>
                        @if ($slot->current_image_path)
                            <button type="submit" name="reset" value="1" class="btn btn--ghost btn--inline">Reset to default</button>
                        @endif
                    </div>
                </form>
            </div>
        @endforeach
    </div>
@endsection
