@extends('admin.layouts.dashboard')

@section('title', 'Motion & Effects')

@php
    $toggles = [
        'motion_enabled' => ['Enable animations', 'Master switch for scroll reveals, parallax, tilt cards, magnetic buttons and text effects. Visitors whose system asks for reduced motion never get them.'],
        'motion_cursor' => ['Custom cursor', 'An accent-coloured dot and trailing ring that grow over links. Desktop with a mouse only.'],
        'motion_intro' => ['Intro animation', 'A short animated curtain with your initials on the first page a visitor opens in a session.'],
        'home_stats' => ['Statistics strip on the homepage', 'Animated counters for publications, citations, awards and news, calculated automatically from your content.'],
        'home_marquee' => ['Research-interests ribbon on the homepage', 'An endless scrolling band built from the "My Research Interests" entries in About Me.'],
    ];
    $parallaxLabels = ['off' => 'Off', 'subtle' => 'Subtle (recommended)', 'bold' => 'Bold'];
@endphp

@section('content')
    <form method="POST" action="{{ route('admin.appearance.motion.update') }}" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-main form-main--narrow">
            <p class="form-field__hint">These options apply to every theme on the public site. Changes are live as soon as you save.</p>

            @foreach ($toggles as $key => [$label, $hint])
                <div class="form-field">
                    <div class="form-check">
                        <input type="checkbox" id="{{ $key }}" name="{{ $key }}" value="1" {{ old($key, $motion[$key]) === '1' ? 'checked' : '' }}>
                        <label for="{{ $key }}">{{ $label }}</label>
                    </div>
                    <p class="form-field__hint">{{ $hint }}</p>
                </div>
            @endforeach

            <div class="form-field">
                <label for="motion_parallax">Parallax intensity</label>
                <select id="motion_parallax" name="motion_parallax">
                    @foreach ($parallaxLevels as $level)
                        <option value="{{ $level }}" {{ old('motion_parallax', $motion['motion_parallax']) === $level ? 'selected' : '' }}>{{ $parallaxLabels[$level] ?? ucfirst($level) }}</option>
                    @endforeach
                </select>
                <p class="form-field__hint">How strongly the header photos, images and background shapes drift as visitors scroll.</p>
                @error('motion_parallax')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn--inline">Save settings</button>
        </div>
    </form>
@endsection
