@extends('admin.layouts.dashboard')

@section('title', 'Research')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">{{ $projects->total() }} {{ \Illuminate\Support\Str::plural('project', $projects->total()) }}</p>
        <a href="{{ route('admin.research.create') }}" class="btn btn--inline">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            New Project
        </a>
    </div>

    @if ($projects->isEmpty())
        <x-admin.empty-state
            icon="fa-solid fa-flask"
            title="No research projects yet"
            message="Add a project with a summary, full write-up and an image."
            action-label="New Project"
            :action-href="route('admin.research.create')"
        />
    @else
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Title</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr>
                            <td class="data-table__thumb">
                                @if ($project->image)
                                    <img src="{{ asset('storage/'.$project->image) }}" alt="">
                                @else
                                    <span class="data-table__thumb-placeholder"><i class="fa-solid fa-flask" aria-hidden="true"></i></span>
                                @endif
                            </td>
                            <td>{{ $project->getTranslation('title', 'en', false) }}</td>
                            <td>{{ $project->display_order }}</td>
                            <td>
                                <span class="badge {{ $project->active ? 'badge--success' : 'badge--muted' }}">
                                    {{ $project->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="data-table__actions">
                                <a href="{{ route('admin.research.edit', $project) }}" class="icon-link" aria-label="Edit">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.research.destroy', $project) }}" data-confirm="Delete this research project? This can't be undone.">
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

        <div class="pagination">{{ $projects->links() }}</div>
    @endif
@endsection
