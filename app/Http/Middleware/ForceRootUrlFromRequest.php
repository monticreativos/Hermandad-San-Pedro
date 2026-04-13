<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * En local, alinear url() / Ziggy con el host y puerto reales de la petición
 * (p. ej. 127.0.0.1:8000) para que APP_URL genérico no rompa los enlaces de Inertia.
 */
class ForceRootUrlFromRequest
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local')) {
            URL::forceRootUrl($request->getSchemeAndHttpHost());
        }

        return $next($request);
    }
}
