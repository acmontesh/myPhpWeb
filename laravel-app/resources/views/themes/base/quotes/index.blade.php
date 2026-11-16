@extends($activeLayout)

@section('title', \App\Models\PublicPhrase::text('quotes.title', __('site.quotes_title')) . ' — ' . \App\Models\Setting::siteName())

@section('content')
    @include('themes.base.partials.quotes-carousel', ['quotes' => $quotes, 'titleTag' => 'h1'])
@endsection
