@extends('admin.layouts.dashboard')

@section('title', 'Social Networks')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">Links shown in the public site's footer.</p>
        <a href="{{ route('admin.settings.social.create') }}" class="btn btn--inline">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            New Link
        </a>
    </div>

    @if ($socialLinks->isEmpty())
        <x-admin.empty-state
            icon="fa-solid fa-share-nodes"
            title="No social links yet"
            message="Add a link to LinkedIn, GitHub, Google Scholar, or any other profile."
            action-label="New Link"
            :action-href="route('admin.settings.social.create')"
        />
    @else
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Platform</th>
                        <th>URL</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($socialLinks as $link)
                        <tr>
                            <td><i class="{{ $link->icon_class }}" aria-hidden="true"></i></td>
                            <td>{{ $link->platform }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($link->url, 40) }}</td>
                            <td>{{ $link->display_order }}</td>
                            <td>
                                <span class="badge {{ $link->active ? 'badge--success' : 'badge--muted' }}">
                                    {{ $link->active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="data-table__actions">
                                <a href="{{ route('admin.settings.social.edit', $link) }}" class="icon-link" aria-label="Edit">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.settings.social.destroy', $link) }}" data-confirm="Delete this social link?">
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
    @endif
@endsection
