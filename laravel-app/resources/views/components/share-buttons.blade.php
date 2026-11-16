@props(['url' => null, 'title' => ''])

@php
    $labels = [
        'twitter' => __('site.share_twitter'),
        'linkedin' => __('site.share_linkedin'),
        'facebook' => __('site.share_facebook'),
        'whatsapp' => __('site.share_whatsapp'),
    ];
@endphp

<div class="share-buttons" data-share-buttons data-url="{{ $url ?? url()->current() }}" data-title="{{ $title }}" data-labels="{{ json_encode($labels) }}"></div>
