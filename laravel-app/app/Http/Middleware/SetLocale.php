<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', Setting::get('default_locale', config('app.locale')));
        $locale = in_array($locale, ['en', 'es'], true) ? $locale : 'en';

        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
