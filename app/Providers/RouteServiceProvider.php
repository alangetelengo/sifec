<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->getAuthIdentifier() ?: $request->ip());
        });

        // Paraphe registre : fenêtre 1 minute (decay de perMinute). Ne doit pas bloquer avant la logique métier 3/3.
        RateLimiter::for('registre-validate-otp', function (Request $request) {
            return Limit::perMinute(40)->by(optional($request->user())->getAuthIdentifier() ?: $request->ip());
        });

        RateLimiter::for('registre-send-otp', function (Request $request) {
            return Limit::perMinute(20)->by(optional($request->user())->getAuthIdentifier() ?: $request->ip());
        });
    }
}
