<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Solo usuarios con rol admin o editor entran al panel. */
class EsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        abort_unless($usuario && $usuario->puedeEntrar(), 403);

        return $next($request);
    }
}
