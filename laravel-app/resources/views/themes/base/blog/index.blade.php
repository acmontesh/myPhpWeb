@extends($activeLayout)

@section('title', __('nav.blog') . ' — ' . \App\Models\Setting::siteName())

@section('content')
    <div class="container blog-index">
        @include('themes.base.partials.section-heading', ['key' => 'blog', 'tag' => 'h1'])

        @include('themes.base.partials.sections.blog', ['posts' => $posts])
    </div>
@endsection
