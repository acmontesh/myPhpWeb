<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResearchProjectRequest;
use App\Models\ResearchProject;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Mews\Purifier\Facades\Purifier;

class ResearchController extends Controller
{
    public function __construct(private readonly ImageUploadService $images)
    {
    }

    public function index(): View
    {
        $projects = ResearchProject::query()->ordered()->paginate(15);

        return view('admin.research.index', compact('projects'));
    }

    public function create(): View
    {
        return view('admin.research.create', ['project' => new ResearchProject()]);
    }

    public function store(ResearchProjectRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $project = new ResearchProject();
        $project->setTranslations('title', array_filter($data['title']));
        $project->setTranslations('summary', array_filter($data['summary'] ?? []));
        $project->setTranslations('body', $this->purifyLocales($data['body']));
        $project->display_order = $data['display_order'] ?? 0;
        $project->active = $request->boolean('active');

        if ($request->hasFile('image')) {
            $project->image = $this->images->store($request->file('image'), 'uploads/research');
        }

        $project->save();

        return redirect()->route('admin.research.index')->with('status', 'Research project created.');
    }

    public function edit(ResearchProject $project): View
    {
        return view('admin.research.edit', compact('project'));
    }

    public function update(ResearchProjectRequest $request, ResearchProject $project): RedirectResponse
    {
        $data = $request->validated();

        $project->setTranslations('title', array_filter($data['title']));
        $project->setTranslations('summary', array_filter($data['summary'] ?? []));
        $project->setTranslations('body', $this->purifyLocales($data['body']));
        $project->display_order = $data['display_order'] ?? 0;
        $project->active = $request->boolean('active');

        if ($request->boolean('remove_image')) {
            $this->images->delete($project->image);
            $project->image = null;
        }

        if ($request->hasFile('image')) {
            $this->images->delete($project->image);
            $project->image = $this->images->store($request->file('image'), 'uploads/research');
        }

        $project->save();

        return redirect()->route('admin.research.index')->with('status', 'Research project updated.');
    }

    public function destroy(ResearchProject $project): RedirectResponse
    {
        $this->images->delete($project->image);
        $project->delete();

        return redirect()->route('admin.research.index')->with('status', 'Research project deleted.');
    }

    private function purifyLocales(array $locales): array
    {
        return collect($locales)
            ->filter()
            ->map(fn (string $html) => Purifier::clean($html, 'default'))
            ->all();
    }
}
