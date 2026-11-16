<?php

namespace App\Providers;

use App\Services\ThemeManager;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Plain, easily-styleable markup (pagination/page-item/page-link)
        // instead of the Tailwind-class default, since Tailwind isn't used.
        Paginator::useBootstrapFive();

        // Shared content views (themes/base/*) extend whichever theme layout
        // is active, so a single set of templates renders under any of the
        // 5 themes. A controller may set $activeLayout explicitly (theme
        // preview) — this only fills it in when that hasn't happened.
        View::composer('themes.base.*', function ($view): void {
            if (! array_key_exists('activeLayout', $view->getData())) {
                $view->with('activeLayout', app(ThemeManager::class)->layout());
            }
        });
    }
}
