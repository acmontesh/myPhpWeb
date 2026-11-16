@extends($activeLayout)

@section('title', __('nav.about') . ' — ' . \App\Models\Setting::siteName())
@section('description', $about->bio ? \Illuminate\Support\Str::limit(strip_tags($about->bio), 160) : (\App\Models\Setting::siteTagline() ?? \App\Models\Setting::siteName()))

@push('schema')
    @if ($about->bio)
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Person',
                'name' => \App\Models\Setting::siteName(),
                'description' => \Illuminate\Support\Str::limit(strip_tags($about->bio), 300),
                'url' => route('about.show'),
            ], JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
@endpush

@section('content')
    <div class="container about-index">
        @include('themes.base.partials.section-heading', ['key' => 'about', 'tag' => 'h1'])

        @include('themes.base.partials.sections.about', ['about' => $about, 'aboutPhoto' => $aboutPhoto])
    </div>
@endsection
