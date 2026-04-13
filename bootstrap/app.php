<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /*
         * ngrok, Cloudflare Tunnel, nginx, etc. envían X-Forwarded-Proto: https.
         * Sin proxies de confianza, url() y Vite generan http:// y el navegador bloquea los assets (mixed content).
         */
        $middleware->trustProxies(at: '*');

        $middleware->web(prepend: [
            \App\Http\Middleware\ForceRootUrlFromRequest::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
