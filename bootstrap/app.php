<?php

use App\Http\Middleware\AppendSifecFlashQueryToRedirects;
use App\Http\Middleware\EnsureBusinessModuleIsActive;
use App\Http\Middleware\EnsureCentreEtatCivilForMariage;
use App\Http\Middleware\EnsureInitialPasswordChanged;
use App\Http\Middleware\ForgetSifecTransientFlashCookie;
use App\Http\Middleware\TwoFactorMiddleware;
use App\Sifec\Sifec;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/v1/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            EnsureInitialPasswordChanged::class,
            ForgetSifecTransientFlashCookie::class,
            AppendSifecFlashQueryToRedirects::class,
            EnsureBusinessModuleIsActive::class,
        ]);

        $middleware->api(append: [
            EnsureBusinessModuleIsActive::class,
        ]);

        $middleware->alias([
            '2fa' => TwoFactorMiddleware::class,
            'mariage.cec' => EnsureCentreEtatCivilForMariage::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->call(function () {
            (new Sifec)->pushDataTo();
        })->everyMinute();

        $schedule->call(function () {
            (new Sifec)->checkMatching();
        })->everyMinute();

        $schedule->call(function () {
            (new Sifec)->validiteCodeOtpRegistre();
        })->everyMinute();

        $schedule->call(function () {
            (new Sifec)->validiteCodeOtpActeNaissance();
        })->everyMinute();

        $schedule->call(function () {
            (new Sifec)->validiteCodeOtpActeMariage();
        })->everyMinute();

        $schedule->call(function () {
            (new Sifec)->validiteCodeOtpActeDeces();
        })->everyMinute();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
