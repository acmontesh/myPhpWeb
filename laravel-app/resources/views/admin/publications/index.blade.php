@extends('admin.layouts.dashboard')

@section('title', 'Publications')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">{{ $publications->total() }} {{ \Illuminate\Support\Str::plural('publication', $publications->total()) }}</p>
        <a href="{{ route('admin.publications.create') }}" class="btn btn--inline">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            New Publication
        </a>
    </div>

    @if ($publications->isEmpty())
        <x-admin.empty-state
            icon="fa-solid fa-book"
            title="No publications yet"
            message="Add a journal or conference paper — DOI, abstract, PDF, video and citation count all optional except the DOI and title."
            action-label="New Publication"
            :action-href="route('admin.publications.create')"
        />
    @else
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>DOI</th>
                        <th>Citations</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($publications as $publication)
                        <tr>
                            <td class="data-table__thumb">
                                @if ($publication->image)
                                    <img src="{{ asset('storage/'.$publication->image) }}" alt="">
                                @else
                                    <span class="data-table__thumb-placeholder"><i class="fa-solid fa-book" aria-hidden="true"></i></span>
                                @endif
                            </td>
                            <td>{{ $publication->getTranslation('title', 'en', false) }}</td>
                            <td><span class="badge badge--muted">{{ ucfirst($publication->type) }}</span></td>
                            <td>{{ $publication->doi }}</td>
                            <td>
                                {{ $publication->citation_count }}
                                <span class="badge badge--muted">{{ $publication->citation_source }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $publication->active ? 'badge--success' : 'badge--muted' }}">
                                    {{ $publication->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="data-table__actions">
                                <a href="{{ route('admin.publications.edit', $publication) }}" class="icon-link" aria-label="Edit">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.publications.destroy', $publication) }}" data-confirm="Delete this publication? This can't be undone.">
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

        <div class="pagination">{{ $publications->links() }}</div>
    @endif
@endsection
