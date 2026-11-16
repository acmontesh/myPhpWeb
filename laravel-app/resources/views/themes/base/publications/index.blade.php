@extends($activeLayout)

@section('title', __('nav.publications') . ' — ' . \App\Models\Setting::siteName())

@section('content')
    <div class="container publications-index">
        @include('themes.base.partials.section-heading', ['key' => 'publications', 'tag' => 'h1'])

        @include('themes.base.partials.sections.publications', ['publications' => $publications])
    </div>
@endsection
