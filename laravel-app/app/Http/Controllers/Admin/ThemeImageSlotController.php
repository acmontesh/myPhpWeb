<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThemeImageSlot;
use App\Services\ImageUploadService;
use App\Services\ThemeManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ThemeImageSlotController extends Controller
{
    public function __construct(private readonly ImageUploadService $images)
    {
    }

    public function index(ThemeManager $themes): View
    {
        $activeTheme = $themes->active();

        $slots = ThemeImageSlot::query()
            ->where('theme_slug', $activeTheme)
            ->orderBy('slot_key')
            ->get();

        return view('admin.images.index', [
            'slots' => $slots,
            'activeTheme' => $themes->all()[$activeTheme]['label'],
        ]);
    }

    public function update(Request $request, ThemeManager $themes, string $slotKey): RedirectResponse
    {
        abort_unless(array_key_exists($slotKey, ThemeImageSlot::SLOTS), 404);

        $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'reset' => ['nullable', 'boolean'],
        ]);

        $slot = ThemeImageSlot::query()->firstOrNew([
            'theme_slug' => $themes->active(),
            'slot_key' => $slotKey,
        ]);
        $slot->label ??= ThemeImageSlot::SLOTS[$slotKey];

        if ($request->boolean('reset')) {
            $this->images->delete($slot->current_image_path);
            $slot->current_image_path = null;
        } elseif ($request->hasFile('image')) {
            $this->images->delete($slot->current_image_path);
            $slot->current_image_path = $this->images->store($request->file('image'), 'uploads/theme-slots');
        }

        $slot->save();

        return redirect()->route('admin.images.index')->with('status', "\"{$slot->label}\" image updated.");
    }
}
