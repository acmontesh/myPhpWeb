{{-- Shared heading for landing sections (h2) and interior page headers (h1):
     index number, title with split-letter reveal, editable lead line and a
     giant outlined word drifting sideways with the scroll. --}}
@php
    $headingTag = $tag ?? 'h2';
    $headingTitle = $title ?? __('nav.'.$key);
    $headingLead = \App\Models\PublicPhrase::text('section_lead.'.$key);
@endphp

<header class="section-heading {{ $headingTag === 'h1' ? 'section-heading--page' : '' }}">
    <span class="section-heading__ghost" data-scroll-x="{{ ($reverse ?? false) ? '-0.25' : '0.25' }}" aria-hidden="true">{{ $headingTitle }}</span>

    <p class="section-heading__eyebrow" data-reveal="up">
        @isset($index)
            <span class="section-heading__index">{{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}</span>
        @endisset
        <span class="section-heading__line" aria-hidden="true"></span>
    </p>

    <{{ $headingTag }} class="section-heading__title" data-split>{{ $headingTitle }}</{{ $headingTag }}>

    @if ($headingLead)
        <p class="section-heading__lead" data-reveal="up" style="--reveal-delay: 160ms">{{ $headingLead }}</p>
    @endif
</header>
