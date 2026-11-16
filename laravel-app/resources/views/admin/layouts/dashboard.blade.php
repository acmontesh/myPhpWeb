<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-color-mode="{{ auth('admin')->user()->ui_color_mode }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('themes/base/assets/fonts/fontawesome/css/all.min.css') }}">
    <style>:root { --admin-accent: {{ auth('admin')->user()->ui_accent_color }}; }</style>
    @stack('head')
    @vite(['resources/scss/admin/app.scss', 'resources/js/admin/app.js'])
</head>
<body>
    <div class="dashboard-shell">
        <div class="dashboard-shell__backdrop"></div>

        @include('admin.partials.sidebar')

        <div class="dashboard-main">
            @include('admin.partials.topbar')

            <main class="dashboard-content">
                @if (session('status'))
                    <div class="alert alert--success" role="status">{{ session('status') }}</div>
                @endif

                {{-- PHP rejects an oversized POST before the session starts, so
                     this one is signalled by a query flag rather than a flash. --}}
                @if (request()->boolean('upload_too_large'))
                    <div class="alert alert--error" role="alert">
                        That upload was too large for the server to accept
                        ({{ ini_get('post_max_size') }} maximum per submission). Please upload a smaller file.
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <x-admin.confirm-modal />
</body>
</html>
