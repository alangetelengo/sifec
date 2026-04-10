<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirige vers la page de changement de mot de passe obligatoire (première connexion)
 * tant que tr_user.must_change_password est vrai.
 */
class EnsureInitialPasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        if (! $user || ! (bool) $user->must_change_password) {
            return $next($request);
        }

        if ($request->is('premiere-connexion', 'premiere-connexion/*')) {
            return $next($request);
        }

        if ($request->is('logout') || $request->routeIs('logout')) {
            return $next($request);
        }

        if ($request->is('two-factor/*')) {
            return $next($request);
        }

        return redirect()->route('first-login-password.show');
    }
}
