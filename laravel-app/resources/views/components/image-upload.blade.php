@props(['name', 'label' => 'Image', 'currentUrl' => null])

<div class="form-field">
    <label>{{ $label }}</label>
    <div class="image-upload" data-image-upload>
        <div class="image-upload__preview" data-image-upload-preview>
            @if ($currentUrl)
                <img src="{{ $currentUrl }}" alt="">
            @endif
        </div>
        <label class="image-upload__dropzone" data-image-upload-dropzone>
            <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
            <span>Click or drag an image here</span>
            <input type="file" name="{{ $name }}" accept="image/png,image/jpeg,image/webp" data-image-upload-input hidden>
        </label>
    </div>
    @error($name)
        <p class="form-field__error">{{ $message }}</p>
    @enderror
</div>
