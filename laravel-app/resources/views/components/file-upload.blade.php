@props(['name', 'label' => 'File', 'currentUrl' => null, 'currentLabel' => 'Current file', 'accept' => null, 'hint' => null])

<div class="form-field">
    <label for="{{ $name }}">{{ $label }}</label>

    @if ($currentUrl)
        <p class="file-upload__current">
            <i class="fa-solid fa-file-lines" aria-hidden="true"></i>
            <a href="{{ $currentUrl }}" target="_blank" rel="noopener">{{ $currentLabel }}</a>
        </p>
    @endif

    <input type="file" id="{{ $name }}" name="{{ $name }}" @if ($accept) accept="{{ $accept }}" @endif>

    @if ($hint)
        <p class="form-field__hint">{{ $hint }}</p>
    @endif

    @error($name)
        <p class="form-field__error">{{ $message }}</p>
    @enderror
</div>
