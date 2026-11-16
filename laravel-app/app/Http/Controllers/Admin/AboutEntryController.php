<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutEntryRequest;
use App\Models\AboutEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AboutEntryController extends Controller
{
    public function create(): View
    {
        $entry = new AboutEntry([
            'section' => request('section', 'education'),
            'icon_class' => AboutEntry::ICONS[0],
            'display_order' => 0,
        ]);

        return view('admin.about.entries.create', compact('entry'));
    }

    public function store(AboutEntryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $entry = new AboutEntry();
        $entry->section = $data['section'];
        $entry->icon_class = $data['icon_class'];
        $entry->display_order = $data['display_order'];
        $entry->setTranslations('title', array_filter($data['title']));
        $entry->setTranslations('detail', array_filter($data['detail'] ?? []));
        $entry->save();

        return redirect()->route('admin.settings.about')->with('status', 'About Me entry created.');
    }

    public function edit(AboutEntry $entry): View
    {
        return view('admin.about.entries.edit', compact('entry'));
    }

    public function update(AboutEntryRequest $request, AboutEntry $entry): RedirectResponse
    {
        $data = $request->validated();

        $entry->section = $data['section'];
        $entry->icon_class = $data['icon_class'];
        $entry->display_order = $data['display_order'];
        $entry->setTranslations('title', array_filter($data['title']));
        $entry->setTranslations('detail', array_filter($data['detail'] ?? []));
        $entry->save();

        return redirect()->route('admin.settings.about')->with('status', 'About Me entry updated.');
    }

    public function destroy(AboutEntry $entry): RedirectResponse
    {
        $entry->delete();

        return redirect()->route('admin.settings.about')->with('status', 'About Me entry deleted.');
    }
}
