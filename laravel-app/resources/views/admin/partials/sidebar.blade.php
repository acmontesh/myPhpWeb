@php
    $nav = config('admin_nav');
@endphp

<aside class="sidebar">
    <div class="sidebar__brand">
        <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
        <span>{{ config('app.name') }}</span>
    </div>

    <nav class="sidebar__nav">
        @foreach ($nav['top'] as $item)
            <a href="{{ route($item['route']) }}" class="sidebar__link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}">
                <i class="{{ $item['icon'] }}" aria-hidden="true"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

        @foreach ($nav['groups'] as $group)
            @php
                $groupIsOpen = collect($group['items'])->contains(fn ($item) => request()->routeIs($item['route']));
            @endphp
            <div class="sidebar__group {{ $groupIsOpen ? 'is-open' : '' }}">
                <button type="button" class="sidebar__group-toggle">
                    <i class="sidebar__group-icon {{ $group['icon'] }}" aria-hidden="true"></i>
                    <span>{{ $group['label'] }}</span>
                    <i class="sidebar__chevron fa-solid fa-chevron-right" aria-hidden="true"></i>
                </button>
                <div class="sidebar__group-list">
                    @foreach ($group['items'] as $item)
                        <a href="{{ route($item['route']) }}" class="sidebar__link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}">
                            <i class="{{ $item['icon'] }}" aria-hidden="true"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="sidebar__view-site">
        <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
        <span>View Public Site</span>
    </a>
</aside>
