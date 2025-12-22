<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Log;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        // Logger avant la vérification d'authentification
        if ($request->is('two-factor/*')) {
            Log::channel('sifec')->info('=== AUTHENTICATE MIDDLEWARE - AVANT VÉRIFICATION ===', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'route' => $request->route()?->getName(),
                'session_id' => $request->session()?->getId(),
                'has_session' => $request->hasSession(),
                'auth_check' => auth()->check(),
                'auth_id' => auth()->id(),
                'auth_user' => auth()->user()?->code_user ?? 'null',
            ]);
        }

        try {
            $response = parent::handle($request, $next, ...$guards);

            // Logger après la vérification (si succès)
            if ($request->is('two-factor/*')) {
                Log::channel('sifec')->info('=== AUTHENTICATE MIDDLEWARE - APRÈS VÉRIFICATION (SUCCÈS) ===', [
                    'url' => $request->fullUrl(),
                    'auth_check' => auth()->check(),
                    'auth_id' => auth()->id(),
                ]);
            }

            return $response;
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            // Logger en cas d'échec d'authentification
            Log::channel('sifec')->error('=== AUTHENTICATE MIDDLEWARE - ÉCHEC AUTHENTIFICATION ===', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'session_id' => $request->session()?->getId(),
                'has_session' => $request->hasSession(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
