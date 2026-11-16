@extends('admin.layouts.dashboard')

@section('title', 'Email & Notifications')

@section('content')
    <form method="POST" action="{{ route('admin.settings.notifications.update') }}" class="admin-form">
        @csrf
        @method('PUT')

        <div class="form-main form-main--narrow">
            <div class="form-check">
                <input
                    type="checkbox"
                    id="notifications_email_enabled"
                    name="notifications_email_enabled"
                    value="1"
                    {{ old('notifications_email_enabled', $notificationsEnabled) ? 'checked' : '' }}
                >
                <label for="notifications_email_enabled">Email me when a new contact message arrives</label>
            </div>

            <div class="form-field">
                <label for="contact_notify_email">Notification email <span class="required">*</span></label>
                <input type="email" id="contact_notify_email" name="contact_notify_email" value="{{ old('contact_notify_email', $contactNotifyEmail) }}">
                <p class="form-field__hint">Where new contact form messages are sent (the contact form itself arrives in Phase 12).</p>
                @error('contact_notify_email')
                    <p class="form-field__error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn--inline">Save settings</button>
        </div>
    </form>
@endsection
