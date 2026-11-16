@extends($activeLayout)

@section('title', $newsItem->title . ' — ' . \App\Models\Setting::siteName())
@section('description', \Illuminate\Support\Str::limit(strip_tags($newsItem->body), 160))

@section('content')
    <article class="container news-post">
        <a href="{{ route('news.index') }}" class="news-post__back link-arrow link-arrow--back">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
            {{ __('site.back_to_news') }}
        </a>

        <h1 class="news-post__title" data-split>{{ $newsItem->title }}</h1>

        <time class="news-post__date" data-reveal="up" datetime="{{ $newsItem->published_at?->toDateString() }}">
            {{ $newsItem->published_at?->translatedFormat('F j, Y') }}
        </time>

        @if ($newsItem->image1 || $newsItem->image2)
            <div class="news-post__gallery" data-reveal="scale">
                @if ($newsItem->image1)
                    <img src="{{ asset('storage/'.$newsItem->image1) }}" alt="{{ $newsItem->title }}">
                @endif
                @if ($newsItem->image2)
                    <img src="{{ asset('storage/'.$newsItem->image2) }}" alt="{{ $newsItem->title }}">
                @endif
            </div>
        @endif

        <div class="news-post__body" data-reveal="up">
            {!! $newsItem->body !!}
        </div>
    </article>
@endsection
