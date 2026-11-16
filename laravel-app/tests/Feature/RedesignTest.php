<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ContactMessage;
use App\Models\Section;
use App\Models\Setting;
use Database\Seeders\PublicPhraseSeeder;
use Database\Seeders\SectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RedesignTest extends TestCase
{
    use RefreshDatabase;

    private const PUBLIC_PAGES = ['/', '/about-me', '/news', '/research', '/publications', '/awards', '/blog', '/quotes-i-like', '/contact-me'];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([SectionSeeder::class, PublicPhraseSeeder::class]);
    }

    private function admin(): Admin
    {
        return Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.test',
            'phone' => '5550000000',
            'password' => 'secret-password',
        ]);
    }

    public function test_every_public_page_renders_in_both_site_modes(): void
    {
        foreach (['multipage', 'landing'] as $mode) {
            Setting::set('site_mode', $mode);

            foreach (self::PUBLIC_PAGES as $page) {
                $this->get($page)->assertOk();
            }
        }
    }

    public function test_motion_attributes_follow_the_admin_settings(): void
    {
        $this->get('/')->assertSee('data-motion="on"', false)->assertSee('data-cursor="on"', false);

        Setting::set('motion_enabled', '0');

        $this->get('/')->assertSee('data-motion="off"', false)->assertSee('data-parallax="0"', false);
    }

    public function test_spanish_tagline_is_used_for_spanish_visitors(): void
    {
        Setting::set('site_tagline', 'English tagline');
        Setting::set('site_tagline_es', 'Lema en español');

        $this->get('/')->assertSee('English tagline');
        $this->withSession(['locale' => 'es'])->get('/lang/es');
        $this->get('/')->assertSee('Lema en español');
    }

    public function test_motion_settings_page_saves_and_validates(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')->get('/panel-abraham/appearance/motion')->assertOk()->assertSee('Motion & Effects');

        $this->actingAs($admin, 'admin')->put('/panel-abraham/appearance/motion', [
            'motion_enabled' => '1',
            'motion_parallax' => 'bold',
        ])->assertRedirect('/panel-abraham/appearance/motion');

        $this->assertSame('bold', Setting::get('motion_parallax'));
        $this->assertSame('0', Setting::get('motion_cursor'));

        $this->actingAs($admin, 'admin')->put('/panel-abraham/appearance/motion', [
            'motion_parallax' => 'extreme',
        ])->assertSessionHasErrors('motion_parallax');
    }

    public function test_turnstile_settings_require_both_keys(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin, 'admin')->get('/panel-abraham/settings/turnstile')->assertOk();

        $this->actingAs($admin, 'admin')->put('/panel-abraham/settings/turnstile', [
            'turnstile_site_key' => 'site-only',
        ])->assertSessionHasErrors('turnstile_secret_key');

        $this->actingAs($admin, 'admin')->put('/panel-abraham/settings/turnstile', [
            'turnstile_site_key' => 'site',
            'turnstile_secret_key' => 'secret',
        ])->assertRedirect('/panel-abraham/settings/turnstile');

        $this->assertSame('site', Setting::get('turnstile_site_key'));
    }

    public function test_admin_pages_require_authentication(): void
    {
        $this->get('/panel-abraham/appearance/motion')->assertRedirect('/access-abraham');
        $this->get('/panel-abraham/settings/turnstile')->assertRedirect('/access-abraham');
    }

    public function test_contact_form_works_without_turnstile_keys(): void
    {
        $this->post('/contact-me', $this->contactPayload())->assertRedirect('/contact-me');

        $this->assertSame(1, ContactMessage::count());
        $this->assertFalse(ContactMessage::first()->captcha_verified);
    }

    public function test_contact_form_accepts_a_valid_turnstile_token(): void
    {
        $this->configureTurnstile();
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => true])]);

        $this->get('/contact-me')->assertSee('data-turnstile', false)->assertSee('data-sitekey="site-key"', false);

        $this->post('/contact-me', $this->contactPayload(['cf-turnstile-response' => 'token']))->assertSessionHasNoErrors();

        $this->assertTrue(ContactMessage::first()->captcha_verified);
        Http::assertSent(fn ($request) => $request['secret'] === 'secret-key' && $request['response'] === 'token');
    }

    public function test_contact_form_rejects_missing_or_invalid_turnstile_tokens(): void
    {
        $this->configureTurnstile();
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => false])]);

        $this->post('/contact-me', $this->contactPayload())->assertSessionHasErrors('cf-turnstile-response');
        $this->post('/contact-me', $this->contactPayload(['cf-turnstile-response' => 'bad']))->assertSessionHasErrors('cf-turnstile-response');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_disabled_sections_disappear_from_the_hero_actions(): void
    {
        Section::query()->where('key', 'contact')->update(['enabled' => false]);

        $this->get('/')->assertDontSee(__('site.get_in_touch'));
    }

    private function configureTurnstile(): void
    {
        Setting::set('turnstile_site_key', 'site-key');
        Setting::set('turnstile_secret_key', 'secret-key');
    }

    private function contactPayload(array $extra = []): array
    {
        return array_merge([
            'name' => 'Jane Visitor',
            'email' => 'jane@example.test',
            'message' => 'Hello there.',
        ], $extra);
    }
}
