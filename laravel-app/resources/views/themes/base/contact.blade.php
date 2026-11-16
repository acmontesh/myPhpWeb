@extends($activeLayout)

@section('title', __('nav.contact') . ' — ' . \App\Models\Setting::siteName())

@section('content')
    <div class="container contact-index">
        @include('themes.base.partials.section-heading', ['key' => 'contact', 'tag' => 'h1'])

        @include('themes.base.partials.sections.contact')
    </div>
@endsection
