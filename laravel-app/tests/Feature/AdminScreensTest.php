<?php

namespace Tests\Feature;

use App\Models\Admin;
use Database\Seeders\PublicPhraseSeeder;
use Database\Seeders\SectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminScreensTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_parameterless_admin_screen_renders(): void
    {
        $this->seed([SectionSeeder::class, PublicPhraseSeeder::class]);

        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.test',
            'phone' => '5550000000',
            'password' => 'secret-password',
        ]);

        $uris = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($route) => in_array('GET', $route->methods(), true))
            ->map(fn ($route) => $route->uri())
            ->filter(fn ($uri) => str_starts_with($uri, 'panel-abraham') && ! str_contains($uri, '{'))
            ->values();

        $this->assertNotEmpty($uris);

        foreach ($uris as $uri) {
            $this->actingAs($admin, 'admin')->get('/'.$uri)->assertOk();
        }

        foreach (array_keys(\App\Services\ThemeManager::THEMES) as $theme) {
            $this->actingAs($admin, 'admin')
                ->get('/panel-abraham/themes/'.$theme.'/preview')
                ->assertOk()
                ->assertSee('theme-'.$theme, false);
        }
    }
}
