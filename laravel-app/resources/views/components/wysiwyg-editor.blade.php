@props(['name', 'id' => null, 'value' => ''])

@php $id = $id ?? \Illuminate\Support\Str::slug($name); @endphp

<textarea name="{{ $name }}" id="{{ $id }}" class="wysiwyg-editor" data-wysiwyg rows="12">{{ $value }}</textarea>
