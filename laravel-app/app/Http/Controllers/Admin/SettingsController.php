<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GeneralSettingsRequest;
use App\Http\Requests\Admin\NotificationSettingsRequest;
use App\Http\Requests\Admin\MotionSettingsRequest;
use App\Http\Requests\Admin\SectionsRequest;
use App\Http\Requests\Admin\TurnstileSettingsRequest;
use App\Models\Section;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private const GENERAL_KEYS = ['site_name', 'site_tagline', 'site_tagline_es', 'default_locale', 'site_mode'];

    public function general(): View
    {
        $settings = collect(self::GENERAL_KEYS)->mapWithKeys(
            fn (string $key) => [$key => Setting::get($key)]
        );

        $settings['site_name'] ??= config('app.name');
        $settings['default_locale'] ??= config('app.locale');
        $settings['site_mode'] ??= 'multipage';

        return view('admin.settings.general', compact('settings'));
    }

    public function updateGeneral(GeneralSettingsRequest $request): RedirectResponse
    {
        foreach ($request->validated() as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.general')->with('status', 'General settings saved.');
    }

    public function sections(): View
    {
        $sections = Section::query()->orderBy('display_order')->get();

        return view('admin.settings.sections', compact('sections'));
    }

    public function updateSections(SectionsRequest $request): RedirectResponse
    {
        foreach ($request->validated()['sections'] as $id => $data) {
            Section::query()->whereKey($id)->update([
                'enabled' => (bool) ($data['enabled'] ?? false),
                'display_order' => $data['display_order'],
            ]);
        }

        return redirect()->route('admin.settings.sections')->with('status', 'Sections saved.');
    }

    public function notifications(): View
    {
        return view('admin.settings.notifications', [
            'contactNotifyEmail' => Setting::get('contact_notify_email'),
            'notificationsEnabled' => Setting::get('notifications_email_enabled', '1') === '1',
        ]);
    }

    public function updateNotifications(NotificationSettingsRequest $request): RedirectResponse
    {
        Setting::set('contact_notify_email', $request->validated()['contact_notify_email']);
        Setting::set('notifications_email_enabled', $request->boolean('notifications_email_enabled') ? '1' : '0');

        return redirect()->route('admin.settings.notifications')->with('status', 'Notification settings saved.');
    }

    public function turnstile(): View
    {
        return view('admin.settings.turnstile', [
            'turnstileSiteKey' => Setting::get('turnstile_site_key'),
            'turnstileSecretKey' => Setting::get('turnstile_secret_key'),
        ]);
    }

    public function updateTurnstile(TurnstileSettingsRequest $request): RedirectResponse
    {
        Setting::set('turnstile_site_key', $request->validated()['turnstile_site_key'] ?? null);
        Setting::set('turnstile_secret_key', $request->validated()['turnstile_secret_key'] ?? null);

        return redirect()->route('admin.settings.turnstile')->with('status', 'Cloudflare Turnstile settings saved.');
    }

    public function motion(): View
    {
        return view('admin.settings.motion', [
            'motion' => Setting::motion(),
            'parallaxLevels' => array_keys(Setting::PARALLAX_LEVELS),
        ]);
    }

    public function updateMotion(MotionSettingsRequest $request): RedirectResponse
    {
        foreach (array_keys(Setting::MOTION_DEFAULTS) as $key) {
            $value = $key === 'motion_parallax'
                ? $request->validated()['motion_parallax']
                : ($request->boolean($key) ? '1' : '0');

            Setting::set($key, $value);
        }

        return redirect()->route('admin.appearance.motion')->with('status', 'Motion & effects saved.');
    }
}
