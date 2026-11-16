@props(['name' => 'icon_class', 'value' => null, 'icons' => []])

@php
    $selected = $value ?: ($icons[0] ?? '');
@endphp

<div class="icon-picker" data-icon-picker>
    <input type="hidden" name="{{ $name }}" value="{{ $selected }}" data-icon-picker-input>

    <div class="icon-picker__preview">
        <i class="{{ $selected }}" aria-hidden="true" data-icon-picker-preview></i>
        <span>Selected icon</span>
    </div>

    <div class="icon-picker__grid" role="radiogroup" aria-label="Choose an icon">
        @foreach ($icons as $icon)
            <button
                type="button"
                class="icon-picker__option {{ $icon === $selected ? 'is-selected' : '' }}"
                data-icon-option="{{ $icon }}"
                role="radio"
                aria-checked="{{ $icon === $selected ? 'true' : 'false' }}"
                title="{{ $icon }}"
            >
                <i class="{{ $icon }}" aria-hidden="true"></i>
            </button>
        @endforeach
    </div>
</div>
