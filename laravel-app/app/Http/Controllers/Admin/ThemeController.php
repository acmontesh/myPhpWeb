<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Setting;
use App\Models\ThemeImageSlot;
use App\Services\ThemeManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function index(ThemeManager $themes): View
    {
        return view('admin.themes.index', [
            'themes' => $themes->all(),
            'active' => $themes->active(),
        ]);
    }

    public function preview(ThemeManager $themes, string $theme): View
    {
        abort_unless($themes->exists($theme), 404);

        return view($themes->view('home'), [
            'activeLayout' => $themes->layout($theme),
            'heroBackground' => ThemeImageSlot::resolve($theme, 'hero_background'),
            'aboutPhoto' => ThemeImageSlot::resolve($theme, 'about_photo'),
            'sections' => Section::query()->where('enabled', true)->orderBy('display_order')->get(),
        ]);
    }

    public function activate(ThemeManager $themes, string $theme): RedirectResponse
    {
        abort_unless($themes->exists($theme), 404);

        Setting::set('active_theme', $theme);

        return redirect()->route('admin.themes.index')
            ->with('status', "\"{$themes->all()[$theme]['label']}\" is now the active public theme.");
    }
}
