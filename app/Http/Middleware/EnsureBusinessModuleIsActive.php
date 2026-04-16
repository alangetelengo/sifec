<?php

namespace App\Http\Middleware;

use App\Support\SifecBusinessModuleAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBusinessModuleIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();
        $name = $route?->getName();

        if ($name && ! SifecBusinessModuleAccess::isRouteAllowedByModuleState($name)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Ce module est désactivé ou indisponible.',
                ], 403);
            }

            abort(403, 'Ce module est désactivé ou indisponible.');
        }

        return $next($request);
    }
}
