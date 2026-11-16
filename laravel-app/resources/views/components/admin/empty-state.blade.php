@props(['icon', 'title', 'message', 'actionLabel' => null, 'actionHref' => null])

<div class="coming-soon">
    <i class="{{ $icon }}" aria-hidden="true"></i>
    <h2>{{ $title }}</h2>
    <p>{{ $message }}</p>
    @if ($actionLabel && $actionHref)
        <a href="{{ $actionHref }}" class="btn btn--inline">{{ $actionLabel }}</a>
    @endif
</div>
