@extends($activeLayout)

@section('title', __('nav.news') . ' — ' . \App\Models\Setting::siteName())

@section('content')
    <div class="container news-index">
        @include('themes.base.partials.section-heading', ['key' => 'news', 'tag' => 'h1'])

        @include('themes.base.partials.sections.news', ['newsItems' => $newsItems])
    </div>
@endsection
