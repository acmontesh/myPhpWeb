<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AwardRequest;
use App\Models\Award;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AwardController extends Controller
{
    public function __construct(private readonly ImageUploadService $images)
    {
    }

    public function index(): View
    {
        $awards = Award::query()->ordered()->paginate(15);

        return view('admin.awards.index', compact('awards'));
    }

    public function create(): View
    {
        return view('admin.awards.create', ['award' => new Award()]);
    }

    public function store(AwardRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $award = new Award();
        $this->fill($award, $data, $request);
        $award->save();

        return redirect()->route('admin.awards.index')->with('status', 'Award created.');
    }

    public function edit(Award $award): View
    {
        return view('admin.awards.edit', compact('award'));
    }

    public function update(AwardRequest $request, Award $award): RedirectResponse
    {
        $data = $request->validated();

        if ($request->boolean('remove_image')) {
            $this->images->delete($award->image);
            $award->image = null;
        }

        $this->fill($award, $data, $request);
        $award->save();

        return redirect()->route('admin.awards.index')->with('status', 'Award updated.');
    }

    public function destroy(Award $award): RedirectResponse
    {
        $this->images->delete($award->image);
        $award->delete();

        return redirect()->route('admin.awards.index')->with('status', 'Award deleted.');
    }

    private function fill(Award $award, array $data, AwardRequest $request): void
    {
        $award->setTranslations('title', array_filter($data['title']));
        $award->setTranslations('description', array_filter($data['description'] ?? []));
        $award->granted_by = $data['granted_by'];
        $award->awarded_on = $data['awarded_on'];
        $award->display_order = $data['display_order'];
        $award->active = $request->boolean('active');

        if ($request->hasFile('image')) {
            $this->images->delete($award->image);
            $award->image = $this->images->store($request->file('image'), 'uploads/awards');
        }
    }
}
