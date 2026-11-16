@extends('admin.layouts.dashboard')

@section('title', 'Blog')

@section('content')
    <div class="content-header">
        <p class="content-header__hint">{{ $posts->total() }} {{ \Illuminate\Support\Str::plural('post', $posts->total()) }}</p>
        <a href="{{ route('admin.blog.create') }}" class="btn btn--inline">
            <i class="fa-solid fa-plus" aria-hidden="true"></i>
            New Post
        </a>
    </div>

    @if ($posts->isEmpty())
        <x-admin.empty-state
            icon="fa-solid fa-pen-nib"
            title="No blog posts yet"
            message="Write your first article — it can be saved as a draft until you're ready to publish."
            action-label="New Post"
            :action-href="route('admin.blog.create')"
        />
    @else
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td class="data-table__thumb">
                                @if ($post->featured_image)
                                    <img src="{{ asset('storage/'.$post->featured_image) }}" alt="">
                                @else
                                    <span class="data-table__thumb-placeholder"><i class="fa-solid fa-image" aria-hidden="true"></i></span>
                                @endif
                            </td>
                            <td>{{ $post->getTranslation('title', 'en', false) }}</td>
                            <td>
                                <span class="badge {{ $post->isPublished() ? 'badge--success' : 'badge--muted' }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td>{{ $post->published_at?->format('M j, Y') ?? '—' }}</td>
                            <td class="data-table__actions">
                                <a href="{{ route('admin.blog.edit', $post) }}" class="icon-link" aria-label="Edit">
                                    <i class="fa-solid fa-pen" aria-hidden="true"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" data-confirm="Delete this blog post? This can't be undone.">
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

        <div class="pagination">{{ $posts->links() }}</div>
    @endif
@endsection
