<x-mail::message>
# New contact message

**Name:** {{ $contactMessage->name }}

**Email:** {{ $contactMessage->email }}

@if ($contactMessage->phone)
**Phone:** {{ $contactMessage->phone }}
@endif

**Message:**

{{ $contactMessage->message }}

Sent from {{ config('app.name') }} on {{ $contactMessage->created_at->format('F j, Y \a\t g:i A') }}.
</x-mail::message>
