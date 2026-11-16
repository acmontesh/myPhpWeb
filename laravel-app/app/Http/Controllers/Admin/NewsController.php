<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsItemRequest;
use App\Models\NewsItem;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;

class NewsController extends Controller
{
    public function __construct(private readonly ImageUploadService $images)
    {
    }

    public function index(): View
    {
        $newsItems = NewsItem::query()->ordered()->paginate(15);

        return view('admin.news.index', compact('newsItems'));
    }

    public function create(): View
    {
        return view('admin.news.create', ['newsItem' => new NewsItem()]);
    }

    public function store(NewsItemRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $newsItem = new NewsItem();
        $newsItem->slug = $this->uniqueSlug($data['title']['en']);
        $newsItem->setTranslations('title', array_filter($data['title']));
        $newsItem->setTranslations('body', $this->purifyLocales($data['body']));
        $newsItem->published_at = $data['published_at'];
        $newsItem->active = $request->boolean('active');

        if ($request->hasFile('image1')) {
            $newsItem->image1 = $this->images->store($request->file('image1'), 'uploads/news');
        }

        if ($request->hasFile('image2')) {
            $newsItem->image2 = $this->images->store($request->file('image2'), 'uploads/news');
        }

        $newsItem->save();

        return redirect()->route('admin.news.index')->with('status', 'News item created.');
    }

    public function edit(NewsItem $newsItem): View
    {
        return view('admin.news.edit', compact('newsItem'));
    }

    public function update(NewsItemRequest $request, NewsItem $newsItem): RedirectResponse
    {
        $data = $request->validated();

        $newsItem->setTranslations('title', array_filter($data['title']));
        $newsItem->setTranslations('body', $this->purifyLocales($data['body']));
        $newsItem->published_at = $data['published_at'];
        $newsItem->active = $request->boolean('active');

        if ($request->boolean('remove_image1')) {
            $this->images->delete($newsItem->image1);
            $newsItem->image1 = null;
        }

        if ($request->hasFile('image1')) {
            $this->images->delete($newsItem->image1);
            $newsItem->image1 = $this->images->store($request->file('image1'), 'uploads/news');
        }

        if ($request->boolean('remove_image2')) {
            $this->images->delete($newsItem->image2);
            $newsItem->image2 = null;
        }

        if ($request->hasFile('image2')) {
            $this->images->delete($newsItem->image2);
            $newsItem->image2 = $this->images->store($request->file('image2'), 'uploads/news');
        }

        $newsItem->save();

        return redirect()->route('admin.news.index')->with('status', 'News item updated.');
    }

    public function destroy(NewsItem $newsItem): RedirectResponse
    {
        $this->images->delete($newsItem->image1);
        $this->images->delete($newsItem->image2);
        $newsItem->delete();

        return redirect()->route('admin.news.index')->with('status', 'News item deleted.');
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $suffix = 2;

        while (NewsItem::query()->where('slug', $slug)->exists()) {
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
