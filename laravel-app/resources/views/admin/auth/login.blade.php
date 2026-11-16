@extends('admin.layouts.auth')

@section('content')
    <h1 class="auth-card__title">Admin access</h1>
    <p class="auth-card__subtitle">Sign in with your email, phone, and password.</p>

    @if ($errors->any())
        <div class="alert alert--error" role="alert">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.login.attempt') }}" novalidate>
        @csrf

        <div class="form-field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="phone">Phone number</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required autocomplete="tel">
            @error('phone')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
            @error('password')
                <p class="form-field__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-check">
            <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember">Keep me logged in</label>
        </div>

        <button type="submit" class="btn">Sign in</button>
    </form>
@endsection
