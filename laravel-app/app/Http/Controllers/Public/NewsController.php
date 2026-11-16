<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\NewsItem;
use App\Services\ThemeManager;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(ThemeManager $themes): View
    {
        $newsItems = NewsItem::active()->ordered()->paginate(10);

        return view($themes->view('news.index'), compact('newsItems'));
    }

    public function show(ThemeManager $themes, NewsItem $newsItem): View
    {
        abort_unless($newsItem->active, 404);

        return view($themes->view('news.show'), compact('newsItem'));
    }
}
