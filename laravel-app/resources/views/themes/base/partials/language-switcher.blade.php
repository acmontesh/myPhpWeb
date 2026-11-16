{{-- EN/ES switcher (Phase 13). Preserves the current page via /lang/{locale}'s redirect()->back(). --}}
<nav class="language-switcher" aria-label="{{ __('site.language') }}">
    <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'is-active' : '' }}" @if (app()->getLocale() === 'en') aria-current="true" @endif>EN</a>
    <span aria-hidden="true">/</span>
    <a href="{{ route('locale.switch', 'es') }}" class="{{ app()->getLocale() === 'es' ? 'is-active' : '' }}" @if (app()->getLocale() === 'es') aria-current="true" @endif>ES</a>
</nav>
