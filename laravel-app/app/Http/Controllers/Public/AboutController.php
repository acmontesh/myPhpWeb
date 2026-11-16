<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AboutContent;
use App\Models\ThemeImageSlot;
use App\Services\ThemeManager;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function show(ThemeManager $themes): View
    {
        return view($themes->view('about'), [
            'about' => AboutContent::current(),
            'aboutPhoto' => ThemeImageSlot::resolve($themes->active(), 'about_photo'),
        ]);
    }
}
