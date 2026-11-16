<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileRequest;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private readonly ImageUploadService $images)
    {
    }

    public function edit(): View
    {
        return view('admin.profile.edit', ['admin' => auth('admin')->user()]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $admin = $request->user('admin');
        $data = $request->validated();

        $admin->name = $data['name'];
        $admin->email = $data['email'];
        $admin->phone = $data['phone'];

        if ($request->boolean('remove_avatar')) {
            $this->images->delete($admin->avatar_path);
            $admin->avatar_path = null;
        }

        if ($request->hasFile('avatar')) {
            $this->images->delete($admin->avatar_path);
            $admin->avatar_path = $this->images->store($request->file('avatar'), 'uploads/avatars');
        }

        if (! empty($data['new_password'])) {
            $admin->password = $data['new_password'];
        }

        $admin->save();

        return redirect()->route('admin.profile.edit')->with('status', 'Profile updated.');
    }
}
