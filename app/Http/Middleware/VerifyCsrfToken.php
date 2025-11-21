<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        // Logger les informations de la requête pour debug
        if ($request->is('two-factor/*')) {
            Log::channel('ecole')->info('=== CSRF MIDDLEWARE ===', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'route' => $request->route()?->getName(),
                'session_id' => $request->session()?->getId(),
                'has_session' => $request->hasSession(),
                'token_in_request' => $request->input('_token'),
                'token_in_header' => $request->header('X-CSRF-TOKEN'),
                'cookie_token' => $request->cookie('XSRF-TOKEN'),
                'session_token' => $request->session()?->token(),
                'referer' => $request->header('referer'),
                'user_agent' => $request->header('user-agent'),
            ]);
        }

        try {
            return parent::handle($request, $next);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            Log::channel('ecole')->error('=== CSRF TOKEN MISMATCH ===', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'session_id' => $request->session()?->getId(),
                'token_in_request' => $request->input('_token'),
                'token_in_header' => $request->header('X-CSRF-TOKEN'),
                'session_token' => $request->session()?->token(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
