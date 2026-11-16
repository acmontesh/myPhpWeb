@extends('admin.layouts.dashboard')

@section('title', 'Awards & Distinctions')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">{{ $awards->total() }} {{ \Illuminate\Support\Str::plural('award', $awards->total()) }}</p>
        <a href="{{ route('admin.awards.create') }}" class="btn btn--inline">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            New Award
        </a>
    </div>

    @if ($awards->isEmpty())
        <x-admin.empty-state
            icon="fa-solid fa-trophy"
            title="No awards yet"
            message="Add an award or distinction with the granting institution, the date and a short description."
            action-label="New Award"
            :action-href="route('admin.awards.create')"
        />
    @else
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Award</th>
                        <th>Granted by</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($awards as $award)
                        <tr>
                            <td class="data-table__thumb">
                                @if ($award->image)
                                    <img src="{{ asset('storage/'.$award->image) }}" alt="">
                                @else
                                    <span class="data-table__thumb-placeholder"><i class="fa-solid fa-trophy" aria-hidden="true"></i></span>
                                @endif
                            </td>
                            <td>{{ $award->getTranslation('title', 'en', false) }}</td>
                            <td>{{ $award->granted_by }}</td>
                            <td>{{ $award->awarded_on?->format('M j, Y') }}</td>
                            <td>
                                <span class="badge {{ $award->active ? 'badge--success' : 'badge--muted' }}">
                                    {{ $award->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="data-table__actions">
                                <a href="{{ route('admin.awards.edit', $award) }}" class="icon-link" aria-label="Edit">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.awards.destroy', $award) }}" data-confirm="Delete this award? This can't be undone.">
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

        <div class="pagination">{{ $awards->links() }}</div>
    @endif
@endsection
