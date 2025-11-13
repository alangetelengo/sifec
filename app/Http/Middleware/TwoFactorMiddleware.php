<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TwoFactorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Si l'utilisateur n'est pas authentifié, continuer
        if (!$user) {
            return $next($request);
        }

        // Si l'utilisateur a la 2FA activée mais n'a pas encore vérifié
        if ($user->hasTwoFactorEnabled() && !session('2fa:verified')) {
            // Si on est déjà sur la page de vérification, continuer
            if ($request->is('two-factor/verify') || $request->is('two-factor/verify-recovery')) {
                return $next($request);
            }

            // Sinon, rediriger vers la vérification
            return redirect()->route('two-factor.verify');
        }

        return $next($request);
    }
}

