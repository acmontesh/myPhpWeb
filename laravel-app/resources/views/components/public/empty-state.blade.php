@props(['icon', 'title', 'message'])

<div class="empty-state">
    <i class="{{ $icon }}" aria-hidden="true"></i>
    <h2>{{ $title }}</h2>
    <p>{{ $message }}</p>
</div>
