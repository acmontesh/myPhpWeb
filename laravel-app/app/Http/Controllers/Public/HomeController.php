<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AboutContent;
use App\Models\Award;
use App\Models\BlogPost;
use App\Models\Publication;
use App\Models\Quote;
use App\Models\ResearchProject;
use App\Models\Section;
use App\Models\NewsItem;
use App\Models\Setting;
use App\Models\ThemeImageSlot;
use App\Services\ThemeManager;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(ThemeManager $themes): View
    {
        $active = $themes->active();
        $heroBackground = ThemeImageSlot::resolve($active, 'hero_background');
        $aboutPhoto = ThemeImageSlot::resolve($active, 'about_photo');
        $sections = Section::query()->where('enabled', true)->orderBy('display_order')->get();

        if (Setting::get('site_mode', 'multipage') !== 'landing') {
            return view($themes->view('home'), compact('heroBackground', 'aboutPhoto', 'sections'));
        }

        return view($themes->view('landing'), [
            'heroBackground' => $heroBackground,
            'aboutPhoto' => $aboutPhoto,
            'sections' => $sections,
            'about' => $sections->contains('key', 'about') ? AboutContent::current() : null,
            'newsItems' => $sections->contains('key', 'news') ? NewsItem::active()->ordered()->take(6)->get() : collect(),
            'projects' => $sections->contains('key', 'research') ? ResearchProject::active()->ordered()->get() : collect(),
            'publications' => $sections->contains('key', 'publications') ? Publication::active()->ordered()->get() : collect(),
            'awards' => $sections->contains('key', 'awards') ? Award::active()->ordered()->get() : collect(),
            'posts' => $sections->contains('key', 'blog') ? BlogPost::published()->latest('published_at')->take(6)->get() : collect(),
            'quotes' => $sections->contains('key', 'quotes') ? Quote::active()->ordered()->get() : collect(),
        ]);
    }
}
