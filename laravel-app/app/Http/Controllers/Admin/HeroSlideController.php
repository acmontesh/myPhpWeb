<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HeroOverlayRequest;
use App\Http\Requests\Admin\HeroSlideRequest;
use App\Models\HeroSlide;
use App\Models\Setting;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function __construct(private readonly ImageUploadService $images)
    {
    }

    public function index(): View
    {
        $slides = HeroSlide::query()->ordered()->get();
        $overlayAlpha = (int) round(Setting::heroOverlayAlpha() * 100);

        return view('admin.hero-slides.index', compact('slides', 'overlayAlpha'));
    }

    public function updateOverlay(HeroOverlayRequest $request): RedirectResponse
    {
        Setting::set('hero_overlay_alpha', (string) $request->validated()['hero_overlay_alpha']);

        return redirect()->route('admin.hero-slides.index')->with('status', 'Header overlay opacity saved.');
    }

    public function create(): View
    {
        return view('admin.hero-slides.create', ['slide' => new HeroSlide()]);
    }

    public function store(HeroSlideRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $slide = new HeroSlide();
        $slide->image_path = $this->images->store($request->file('image'), 'uploads/hero');
        $slide->caption = $data['caption'] ?? null;
        $slide->display_order = $data['display_order'];
        $slide->active = $request->boolean('active');
        $slide->save();

        return redirect()->route('admin.hero-slides.index')->with('status', 'Header slide added.');
    }

    public function edit(HeroSlide $slide): View
    {
        return view('admin.hero-slides.edit', compact('slide'));
    }

    public function update(HeroSlideRequest $request, HeroSlide $slide): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $this->images->delete($slide->image_path);
            $slide->image_path = $this->images->store($request->file('image'), 'uploads/hero');
        }

        $slide->caption = $data['caption'] ?? null;
        $slide->display_order = $data['display_order'];
        $slide->active = $request->boolean('active');
        $slide->save();

        return redirect()->route('admin.hero-slides.index')->with('status', 'Header slide updated.');
    }

    public function destroy(HeroSlide $slide): RedirectResponse
    {
        $this->images->delete($slide->image_path);
        $slide->delete();

        return redirect()->route('admin.hero-slides.index')->with('status', 'Header slide deleted.');
    }
}
