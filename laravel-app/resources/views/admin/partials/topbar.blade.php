@php
    $admin = auth('admin')->user();
@endphp

<header class="topbar">
    <button type="button" class="topbar__toggle" aria-label="Toggle menu">
        <i class="fa-solid fa-bars" aria-hidden="true"></i>
    </button>

    <h1 class="topbar__title">@yield('title', 'Dashboard')</h1>

    <div class="topbar__actions">
        <div class="dropdown">
            <button type="button" class="icon-button dropdown__toggle" aria-label="Notifications">
                <i class="fa-solid fa-bell" aria-hidden="true"></i>
            </button>
            <div class="dropdown__panel">
                <div class="dropdown__empty">
                    <i class="fa-regular fa-bell-slash" aria-hidden="true"></i>
                    <p>No notifications yet.</p>
                </div>
            </div>
        </div>

        <div class="dropdown">
            <button type="button" class="user-menu__button dropdown__toggle">
                <span class="user-menu__avatar">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
                <span class="user-menu__name">{{ $admin->name }}</span>
                <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
            </button>
            <div class="dropdown__panel">
                <a href="{{ route('admin.profile.edit') }}" class="dropdown__link">
                    <i class="fa-solid fa-user" aria-hidden="true"></i>
                    <span>Edit profile</span>
                </a>
                <div class="dropdown__divider"></div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="dropdown__link">
                        <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                        <span>Log out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
