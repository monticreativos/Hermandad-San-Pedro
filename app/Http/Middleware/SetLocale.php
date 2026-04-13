<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['es', 'en'];
        $routeLocale = $request->route('locale');

        if (! in_array($routeLocale, $supportedLocales, true)) {
            $routeLocale = session('locale', config('app.locale'));
        }

        if (! in_array($routeLocale, $supportedLocales, true)) {
            $routeLocale = 'es';
        }

        app()->setLocale($routeLocale);
        session(['locale' => $routeLocale]);

        return $next($request);
    }
}
