@extends('admin.layouts.dashboard')

@section('title', 'News')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">{{ $newsItems->total() }} {{ \Illuminate\Support\Str::plural('item', $newsItems->total()) }}</p>
        <a href="{{ route('admin.news.create') }}" class="btn btn--inline">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            New News Item
        </a>
    </div>

    @if ($newsItems->isEmpty())
        <x-admin.empty-state
            icon="fa-solid fa-newspaper"
            title="No news yet"
            message="Add an announcement with up to two images and a publish date."
            action-label="New News Item"
            :action-href="route('admin.news.create')"
        />
    @else
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Headline</th>
                        <th>Published</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($newsItems as $newsItem)
                        <tr>
                            <td class="data-table__thumb">
                                @if ($newsItem->image1)
                                    <img src="{{ asset('storage/'.$newsItem->image1) }}" alt="">
                                @else
                                    <span class="data-table__thumb-placeholder"><i class="fa-solid fa-newspaper" aria-hidden="true"></i></span>
                                @endif
                            </td>
                            <td>{{ $newsItem->getTranslation('title', 'en', false) }}</td>
                            <td>{{ $newsItem->published_at?->format('M j, Y') }}</td>
                            <td>
                                <span class="badge {{ $newsItem->active ? 'badge--success' : 'badge--muted' }}">
                                    {{ $newsItem->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="data-table__actions">
                                <a href="{{ route('admin.news.edit', $newsItem) }}" class="icon-link" aria-label="Edit">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.news.destroy', $newsItem) }}" data-confirm="Delete this news item? This can't be undone.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-link icon-link--danger" aria-label="Delete">
                                        <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination">{{ $newsItems->links() }}</div>
    @endif
@endsection
