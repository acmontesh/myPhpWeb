@extends('admin.layouts.dashboard')

@section('title', 'Edit Profile')

@section('content')
    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-grid">
            <div class="form-main">
                <div class="form-field">
                    <label for="name">Name <span class="required">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $admin->name) }}">
                    @error('name')
                        <p class="form-field__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email', $admin->email) }}">
                    @error('email')
                        <p class="form-field__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="phone">Phone <span class="required">*</span></label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $admin->phone) }}">
                    @error('phone')
                        <p class="form-field__error">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="admin-form__divider">

                <h2 class="admin-form__subheading">Change password</h2>
                <p class="form-field__hint">Leave blank to keep your current password.</p>

                <div class="form-field">
                    <label for="current_password">Current password</label>
                    <input type="password" id="current_password" name="current_password" autocomplete="current-password">
                    @error('current_password')
                        <p class="form-field__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="new_password">New password</label>
                    <input type="password" id="new_password" name="new_password" autocomplete="new-password">
                    @error('new_password')
                        <p class="form-field__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-field">
                    <label for="new_password_confirmation">Confirm new password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password">
                </div>
            </div>

            <div class="form-sidebar">
                <x-image-upload
                    name="avatar"
                    label="Avatar"
                    :current-url="$admin->avatar_path ? asset('storage/'.$admin->avatar_path) : null"
                />

                @if ($admin->avatar_path)
                    <div class="form-check">
                        <input type="checkbox" id="remove_avatar" name="remove_avatar" value="1">
                        <label for="remove_avatar">Remove current avatar</label>
                    </div>
                @endif

                <button type="submit" class="btn">Save profile</button>
            </div>
        </div>
    </form>
@endsection
