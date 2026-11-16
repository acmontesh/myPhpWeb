@extends('admin.layouts.dashboard')

@section('title', 'Quotes I Like')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">{{ $quotes->total() }} {{ \Illuminate\Support\Str::plural('quote', $quotes->total()) }}</p>
        <a href="{{ route('admin.quotes.create') }}" class="btn btn--inline">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            New Quote
        </a>
    </div>

    @if ($quotes->isEmpty())
        <x-admin.empty-state
            icon="fa-solid fa-quote-left"
            title="No quotes yet"
            message="Add a quote you like, with its author's name and photo, to start the carousel."
            action-label="New Quote"
            :action-href="route('admin.quotes.create')"
        />
    @else
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Author</th>
                        <th>Quote</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($quotes as $quote)
                        <tr>
                            <td class="data-table__thumb">
                                @if ($quote->author_image)
                                    <img src="{{ asset('storage/'.$quote->author_image) }}" alt="">
                                @else
                                    <span class="data-table__thumb-placeholder"><i class="fa-solid fa-user" aria-hidden="true"></i></span>
                                @endif
                            </td>
                            <td>{{ $quote->author_name }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($quote->getTranslation('quote_text', 'en', false), 60) }}</td>
                            <td>{{ $quote->display_order }}</td>
                            <td>
                                <span class="badge {{ $quote->active ? 'badge--success' : 'badge--muted' }}">
                                    {{ $quote->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="data-table__actions">
                                <a href="{{ route('admin.quotes.edit', $quote) }}" class="icon-link" aria-label="Edit">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.quotes.destroy', $quote) }}" data-confirm="Delete this quote? This can't be undone.">
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

        <div class="pagination">{{ $quotes->links() }}</div>
    @endif
@endsection
