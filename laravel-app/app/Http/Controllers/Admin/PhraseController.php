<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PublicPhraseRequest;
use App\Models\PublicPhrase;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PhraseController extends Controller
{
    public function index(): View
    {
        $phrases = PublicPhrase::query()->orderBy('group')->orderBy('key')->paginate(20);

        return view('admin.phrases.index', compact('phrases'));
    }

    public function create(): View
    {
        return view('admin.phrases.create', ['phrase' => new PublicPhrase()]);
    }

    public function store(PublicPhraseRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $phrase = new PublicPhrase();
        $phrase->key = $data['key'];
        $phrase->group = $data['group'] ?? null;
        $phrase->setTranslations('value', array_filter($data['value']));
        $phrase->save();

        return redirect()->route('admin.settings.phrases.index')->with('status', 'Phrase created.');
    }

    public function edit(PublicPhrase $phrase): View
    {
        return view('admin.phrases.edit', compact('phrase'));
    }

    public function update(PublicPhraseRequest $request, PublicPhrase $phrase): RedirectResponse
    {
        $data = $request->validated();

        $phrase->key = $data['key'];
        $phrase->group = $data['group'] ?? null;
        $phrase->setTranslations('value', array_filter($data['value']));
        $phrase->save();

        return redirect()->route('admin.settings.phrases.index')->with('status', 'Phrase updated.');
    }

    public function destroy(PublicPhrase $phrase): RedirectResponse
    {
        $phrase->delete();

        return redirect()->route('admin.settings.phrases.index')->with('status', 'Phrase deleted.');
    }
}
