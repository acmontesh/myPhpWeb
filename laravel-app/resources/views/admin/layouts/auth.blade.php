<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin Access' }} — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('themes/base/assets/fonts/fontawesome/css/all.min.css') }}">
    @vite(['resources/scss/admin/app.scss', 'resources/js/admin/app.js'])
</head>
<body>
    <div class="auth-screen">
        <div class="auth-card">
            <div class="auth-card__brand">
                <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                <span>{{ config('app.name') }}</span>
            </div>
            @yield('content')
        </div>
    </div>
</body>
</html>
