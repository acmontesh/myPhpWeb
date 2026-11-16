@extends($activeLayout)

@section('title', __('nav.awards') . ' — ' . \App\Models\Setting::siteName())

@section('content')
    <div class="container awards-index">
        @include('themes.base.partials.section-heading', ['key' => 'awards', 'tag' => 'h1'])

        @include('themes.base.partials.sections.awards', ['awards' => $awards])
    </div>
@endsection
