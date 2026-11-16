<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AppearanceRequest;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AppearanceController extends Controller
{
    public function edit(): View
    {
        return view('admin.appearance.colors', ['colors' => Admin::ACCENT_COLORS]);
    }

    public function update(AppearanceRequest $request): RedirectResponse
    {
        $request->user('admin')->update($request->validated());

        return redirect()->route('admin.appearance.colors')->with('status', 'Appearance saved.');
    }
}
