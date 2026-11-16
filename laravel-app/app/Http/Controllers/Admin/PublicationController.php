<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PublicationRequest;
use App\Models\Publication;
use App\Services\CitationLookupService;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function __construct(
        private readonly ImageUploadService $images,
        private readonly CitationLookupService $citations,
    ) {
    }

    public function index(): View
    {
        $publications = Publication::query()->ordered()->paginate(15);

        return view('admin.publications.index', compact('publications'));
    }

    public function create(): View
    {
        return view('admin.publications.create', ['publication' => new Publication()]);
    }

    public function store(PublicationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $publication = new Publication();
        $publication->doi = $this->normalizeDoi($data['doi']);
        $publication->type = $data['type'];
        $publication->setTranslations('title', array_filter($data['title']));
        $publication->setTranslations('abstract', array_filter($data['abstract'] ?? []));
        $publication->video_url = $data['video_url'] ?? null;
        $publication->display_order = $data['display_order'] ?? 0;
        $publication->active = $request->boolean('active');

        $this->applyCitationCount($publication, $data);

        if ($request->hasFile('image')) {
            $publication->image = $this->images->store($request->file('image'), 'uploads/publications');
        }

        if ($request->hasFile('pdf')) {
            $publication->pdf_path = $request->file('pdf')->store('uploads/publications/pdfs', 'public');
        }

        $publication->save();

        return redirect()->route('admin.publications.index')->with('status', 'Publication created.');
    }

    public function edit(Publication $publication): View
    {
        return view('admin.publications.edit', compact('publication'));
    }

    public function update(PublicationRequest $request, Publication $publication): RedirectResponse
    {
        $data = $request->validated();

        $publication->doi = $this->normalizeDoi($data['doi']);
        $publication->type = $data['type'];
        $publication->setTranslations('title', array_filter($data['title']));
        $publication->setTranslations('abstract', array_filter($data['abstract'] ?? []));
        $publication->video_url = $data['video_url'] ?? null;
        $publication->display_order = $data['display_order'] ?? 0;
        $publication->active = $request->boolean('active');

        $this->applyCitationCount($publication, $data);

        if ($request->boolean('remove_image')) {
            $this->images->delete($publication->image);
            $publication->image = null;
        }

        if ($request->hasFile('image')) {
            $this->images->delete($publication->image);
            $publication->image = $this->images->store($request->file('image'), 'uploads/publications');
        }

        if ($request->boolean('remove_pdf')) {
            $this->deletePdf($publication);
            $publication->pdf_path = null;
        }

        if ($request->hasFile('pdf')) {
            $this->deletePdf($publication);
            $publication->pdf_path = $request->file('pdf')->store('uploads/publications/pdfs', 'public');
        }

        $publication->save();

        return redirect()->route('admin.publications.index')->with('status', 'Publication updated.');
    }

    public function destroy(Publication $publication): RedirectResponse
    {
        $this->images->delete($publication->image);
        $this->deletePdf($publication);
        $publication->delete();

        return redirect()->route('admin.publications.index')->with('status', 'Publication deleted.');
    }

    public function refreshCitations(Publication $publication): RedirectResponse
    {
        $count = $this->citations->fetchCount($publication->doi);

        if ($count === null) {
            return back()->with('status', 'Crossref has no citation data for this DOI yet — enter a count manually if you have one.');
        }

        $publication->update([
            'citation_count' => $count,
            'citation_source' => 'auto',
            'citation_synced_at' => now(),
        ]);

        return back()->with('status', "Citation count refreshed from Crossref: {$count}.");
    }

    private function applyCitationCount(Publication $publication, array $data): void
    {
        if (array_key_exists('citation_count', $data) && $data['citation_count'] !== null && $data['citation_count'] !== '') {
            $publication->citation_count = (int) $data['citation_count'];
            $publication->citation_source = 'manual';
            $publication->citation_synced_at = null;

            return;
        }

        $count = $this->citations->fetchCount($this->normalizeDoi($data['doi']));

        if ($count !== null) {
            $publication->citation_count = $count;
            $publication->citation_source = 'auto';
            $publication->citation_synced_at = now();
        } else {
            $publication->citation_count = 0;
            $publication->citation_source = 'manual';
            $publication->citation_synced_at = null;
        }
    }

    private function normalizeDoi(string $doi): string
    {
        return preg_replace('#^(https?://)?(dx\.)?doi\.org/#i', '', trim($doi));
    }

    private function deletePdf(Publication $publication): void
    {
        if ($publication->pdf_path) {
            Storage::disk('public')->delete($publication->pdf_path);
        }
    }
}
