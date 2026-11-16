@extends($activeLayout)

@section('title', $post->title . ' — ' . \App\Models\Setting::siteName())
@section('description', $post->excerpt ?: $post->title)
@if ($post->featured_image)
    @section('image', asset('storage/'.$post->featured_image))
@endif

@push('schema')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->excerpt ?: $post->title,
            'datePublished' => $post->published_at?->toIso8601String(),
            'image' => $post->featured_image ? asset('storage/'.$post->featured_image) : null,
            'author' => ['@type' => 'Person', 'name' => \App\Models\Setting::siteName()],
        ], JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <article class="container blog-post">
        <a href="{{ route('blog.index') }}" class="blog-post__back link-arrow link-arrow--back">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            {{ __('site.back_to_blog') }}
        </a>

        <h1 class="blog-post__title" data-split>{{ $post->title }}</h1>

        <time class="blog-post__date" data-reveal="up" datetime="{{ $post->published_at?->toDateString() }}">
            {{ $post->published_at?->translatedFormat('F j, Y') }}
        </time>

        @if ($post->featured_image)
            <img class="blog-post__image" data-reveal="scale" src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}">
        @endif

        <div class="blog-post__body" data-reveal="up">
            {!! $post->body !!}
        </div>

        <x-share-buttons :title="$post->title" />
    </article>
@endsection
