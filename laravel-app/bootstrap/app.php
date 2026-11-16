<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\PostTooLargeException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/access-abraham');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // PHP rejects an oversized upload before validation runs, which would
        // otherwise surface as a raw 413 page. This is thrown by the global
        // ValidatePostSize middleware, which runs *before* the session starts,
        // so the message can't be flashed — it's passed back as a query flag
        // that the dashboard layout renders instead.
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $back = $request->headers->get('referer') ?: route('admin.dashboard');
            $separator = str_contains($back, '?') ? '&' : '?';

            return redirect()->to($back.$separator.'upload_too_large=1');
        });
    })->create();
