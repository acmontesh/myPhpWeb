@extends($activeLayout)

@section('title', __('nav.research') . ' — ' . \App\Models\Setting::siteName())

@section('content')
    <div class="container research-index">
        @include('themes.base.partials.section-heading', ['key' => 'research', 'tag' => 'h1'])

        @include('themes.base.partials.sections.research', ['projects' => $projects])
    </div>
@endsection
