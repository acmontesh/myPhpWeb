@extends('admin.layouts.dashboard')

@section('title', 'Public Phrases')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">Customize strings shown by default on the public site.</p>
        <a href="{{ route('admin.settings.phrases.create') }}" class="btn btn--inline">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            New Phrase
        </a>
    </div>

    @if ($phrases->isEmpty())
        <x-admin.empty-state
            icon="fa-solid fa-quote-right"
            title="No phrases yet"
            message="Add a phrase to override any default string shown on the public site."
            action-label="New Phrase"
            :action-href="route('admin.settings.phrases.create')"
        />
    @else
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Key</th>
                        <th>Group</th>
                        <th>English</th>
                        <th>Español</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($phrases as $phrase)
                        <tr>
                            <td><code>{{ $phrase->key }}</code></td>
                            <td>{{ $phrase->group ?? '—' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($phrase->getTranslation('value', 'en', false), 40) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($phrase->getTranslation('value', 'es', false) ?: '—', 40) }}</td>
                            <td class="data-table__actions">
                                <a href="{{ route('admin.settings.phrases.edit', $phrase) }}" class="icon-link" aria-label="Edit">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.settings.phrases.destroy', $phrase) }}" data-confirm="Delete this phrase? Any page using it will fall back to its default text.">
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

        <div class="pagination">{{ $phrases->links() }}</div>
    @endif
@endsection
