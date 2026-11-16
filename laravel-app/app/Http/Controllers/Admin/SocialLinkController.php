<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SocialLinkRequest;
use App\Models\SocialLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SocialLinkController extends Controller
{
    public function index(): View
    {
        $socialLinks = SocialLink::query()->ordered()->get();

        return view('admin.social-links.index', compact('socialLinks'));
    }

    public function create(): View
    {
        return view('admin.social-links.create', ['socialLink' => new SocialLink()]);
    }

    public function store(SocialLinkRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['active'] = $request->boolean('active');

        SocialLink::query()->create($data);

        return redirect()->route('admin.settings.social.index')->with('status', 'Social link created.');
    }

    public function edit(SocialLink $socialLink): View
    {
        return view('admin.social-links.edit', compact('socialLink'));
    }

    public function update(SocialLinkRequest $request, SocialLink $socialLink): RedirectResponse
    {
        $data = $request->validated();
        $data['active'] = $request->boolean('active');

        $socialLink->update($data);

        return redirect()->route('admin.settings.social.index')->with('status', 'Social link updated.');
    }

    public function destroy(SocialLink $socialLink): RedirectResponse
    {
        $socialLink->delete();

        return redirect()->route('admin.settings.social.index')->with('status', 'Social link deleted.');
    }
}
