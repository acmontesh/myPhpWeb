<?php

use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\AwardController;
use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\LocaleController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\Public\PublicationController;
use App\Http\Controllers\Public\QuoteController;
use App\Http\Controllers\Public\ResearchController;
use App\Http\Controllers\Public\SeoController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::middleware(SetLocale::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

    Route::get('/about-me', [AboutController::class, 'show'])->name('about.show');

    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('/quotes-i-like', [QuoteController::class, 'index'])->name('quotes.index');

    Route::get('/research', [ResearchController::class, 'index'])->name('research.index');

    Route::get('/publications', [PublicationController::class, 'index'])->name('publications.index');

    Route::get('/awards', [AwardController::class, 'index'])->name('awards.index');

    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{newsItem:slug}', [NewsController::class, 'show'])->name('news.show');

    Route::get('/contact-me', [ContactController::class, 'show'])->name('contact.show');
    Route::post('/contact-me', [ContactController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('contact.store');
});

Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');

require __DIR__.'/admin.php';
