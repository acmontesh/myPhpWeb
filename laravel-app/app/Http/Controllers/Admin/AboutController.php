<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutContentRequest;
use App\Models\AboutContent;
use App\Models\AboutEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;

class AboutController extends Controller
{
    public function edit(): View
    {
        return view('admin.about.edit', [
            'about' => AboutContent::current(),
            'entriesBySection' => AboutEntry::query()->ordered()->get()->groupBy('section'),
        ]);
    }

    public function update(AboutContentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $about = AboutContent::current();
        $about->setTranslations('headline', array_filter($data['headline']));
        $about->setTranslations('bio', $this->purifyLocales($data['bio']));
        $about->save();

        return redirect()->route('admin.settings.about')->with('status', 'About Me content saved.');
    }

    private function purifyLocales(array $locales): array
    {
        return collect($locales)
            ->filter()
            ->map(fn (string $html) => Purifier::clean($html, 'default'))
            ->all();
    }
}
