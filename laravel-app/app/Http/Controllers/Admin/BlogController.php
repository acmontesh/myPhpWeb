<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogPostRequest;
use App\Models\BlogPost;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;

class BlogController extends Controller
{
    public function __construct(private readonly ImageUploadService $images)
    {
    }

    public function index(): View
    {
        $posts = BlogPost::query()->latest('created_at')->paginate(15);

        return view('admin.blog.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.blog.create', ['post' => new BlogPost()]);
    }

    public function store(BlogPostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $post = new BlogPost();
        $post->slug = $this->uniqueSlug($data['title']['en']);
        $post->setTranslations('title', array_filter($data['title']));
        $post->setTranslations('excerpt', array_filter($data['excerpt'] ?? []));
        $post->setTranslations('body', $this->purifyLocales($data['body']));
        $post->status = $data['status'];

        if ($post->status === 'published') {
            $post->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            $post->featured_image = $this->images->store($request->file('featured_image'), 'uploads/blog');
        }

        $post->save();

        return redirect()->route('admin.blog.index')->with('status', 'Blog post created.');
    }

    public function edit(BlogPost $post): View
    {
        return view('admin.blog.edit', compact('post'));
    }

    public function update(BlogPostRequest $request, BlogPost $post): RedirectResponse
    {
        $data = $request->validated();

        $post->setTranslations('title', array_filter($data['title']));
        $post->setTranslations('excerpt', array_filter($data['excerpt'] ?? []));
        $post->setTranslations('body', $this->purifyLocales($data['body']));

        if ($post->status !== 'published' && $data['status'] === 'published') {
            $post->published_at = now();
        }

        $post->status = $data['status'];

        if ($request->boolean('remove_image')) {
            $this->images->delete($post->featured_image);
            $post->featured_image = null;
        }

        if ($request->hasFile('featured_image')) {
            $this->images->delete($post->featured_image);
            $post->featured_image = $this->images->store($request->file('featured_image'), 'uploads/blog');
        }

        $post->save();

        return redirect()->route('admin.blog.index')->with('status', 'Blog post updated.');
    }

    public function destroy(BlogPost $post): RedirectResponse
    {
        $this->images->delete($post->featured_image);
        $post->delete();

        return redirect()->route('admin.blog.index')->with('status', 'Blog post deleted.');
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $suffix = 2;

        while (BlogPost::query()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function purifyLocales(array $locales): array
    {
        return collect($locales)
            ->filter()
            ->map(fn (string $html) => Purifier::clean($html, 'default'))
            ->all();
    }
}
