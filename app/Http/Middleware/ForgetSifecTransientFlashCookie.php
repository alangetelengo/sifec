<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\SifecTransientFlashCookie;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Après affichage de la page, supprime le cookie flash ponctuel (évite de réafficher le message).
 */
final class ForgetSifecTransientFlashCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        $had = $request->cookies->has(SifecTransientFlashCookie::NAME);

        $response = $next($request);

        // Ne jamais envoyer Set-Cookie d’effacement sur une réponse POST/PUT (ex. 302 après enregistrement) :
        // le navigateur peut encore envoyer l’ancien sifec_tf ; combiné au nouveau cookie posé par
        // AppendSifecFlashQueryToRedirects, le « forget » passait en dernier et annulait le flash du redirect.
        if ($had && ($request->isMethod('GET') || $request->isMethod('HEAD'))) {
            return $response->withCookie(cookie()->forget(SifecTransientFlashCookie::NAME));
        }

        return $response;
    }
}
