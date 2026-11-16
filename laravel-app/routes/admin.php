<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AboutEntryController;
use App\Http\Controllers\Admin\AppearanceController;
use App\Http\Controllers\Admin\AwardController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PhraseController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\ResearchController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\ThemeImageSlotController;
use App\Http\Controllers\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    Route::get('/access-abraham', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/access-abraham', [AdminAuthController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('admin.login.attempt');
});

Route::post('/access-abraham/logout', [AdminAuthController::class, 'logout'])
    ->middleware('auth:admin')
    ->name('admin.logout');

Route::prefix('panel-abraham')->middleware('auth:admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Content
    Route::resource('blog', BlogController::class)
        ->parameters(['blog' => 'post'])
        ->except('show');

    Route::resource('research', ResearchController::class)
        ->parameters(['research' => 'project'])
        ->except('show');

    Route::resource('publications', PublicationController::class)->except('show');
    Route::post('/publications/{publication}/refresh-citations', [PublicationController::class, 'refreshCitations'])
        ->name('publications.refresh-citations');

    Route::resource('news', NewsController::class)
        ->parameters(['news' => 'newsItem'])
        ->except('show');

    Route::resource('awards', AwardController::class)->except('show');

    Route::resource('quotes', QuoteController::class)
        ->parameters(['quotes' => 'quote'])
        ->except('show');

    Route::get('/settings/about', [AboutController::class, 'edit'])->name('settings.about');
    Route::put('/settings/about', [AboutController::class, 'update'])->name('settings.about.update');

    Route::resource('settings/about-entries', AboutEntryController::class)
        ->names('settings.about.entries')
        ->parameters(['about-entries' => 'entry'])
        ->except(['index', 'show']);

    // Appearance
    Route::get('/themes', [ThemeController::class, 'index'])->name('themes.index');
    Route::get('/themes/{theme}/preview', [ThemeController::class, 'preview'])->name('themes.preview');
    Route::post('/themes/{theme}/activate', [ThemeController::class, 'activate'])->name('themes.activate');

    Route::get('/images', [ThemeImageSlotController::class, 'index'])->name('images.index');
    Route::put('/images/{slotKey}', [ThemeImageSlotController::class, 'update'])->name('images.update');

    Route::resource('hero-slides', HeroSlideController::class)
        ->parameters(['hero-slides' => 'slide'])
        ->except('show');

    // Own path rather than /hero-slides/overlay so it can never be shadowed by
    // the resource's PUT /hero-slides/{slide}.
    Route::put('/hero-overlay', [HeroSlideController::class, 'updateOverlay'])->name('hero-slides.overlay');

    Route::get('/appearance/colors', [AppearanceController::class, 'edit'])->name('appearance.colors');
    Route::put('/appearance/colors', [AppearanceController::class, 'update'])->name('appearance.update');
    Route::get('/appearance/motion', [SettingsController::class, 'motion'])->name('appearance.motion');
    Route::put('/appearance/motion', [SettingsController::class, 'updateMotion'])->name('appearance.motion.update');

    // Settings
    Route::get('/settings/general', [SettingsController::class, 'general'])->name('settings.general');
    Route::put('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general.update');

    Route::get('/settings/sections', [SettingsController::class, 'sections'])->name('settings.sections');
    Route::put('/settings/sections', [SettingsController::class, 'updateSections'])->name('settings.sections.update');

    Route::resource('settings/phrases', PhraseController::class)
        ->names('settings.phrases')
        ->parameters(['phrases' => 'phrase'])
        ->except('show');

    Route::resource('settings/social', SocialLinkController::class)
        ->names('settings.social')
        ->parameters(['social' => 'socialLink'])
        ->except('show');

    Route::get('/settings/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.notifications.update');

    Route::get('/settings/turnstile', [SettingsController::class, 'turnstile'])->name('settings.turnstile');
    Route::put('/settings/turnstile', [SettingsController::class, 'updateTurnstile'])->name('settings.turnstile.update');
});
