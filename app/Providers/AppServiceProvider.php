<?php

namespace App\Providers;

use App\Support\SifecSidebarMenuBuilder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $passportScopes = config('sifec_passport.scopes', []);
        if ($passportScopes !== []) {
            Passport::tokensCan($passportScopes);
        }

        // Même session / flash : les redirections absolues doivent utiliser l’hôte réellement visité
        // (ex. http://sifec alors que APP_URL=http://192.168.x/sifec → cookie de session perdu, aucun message).
        $this->app->booted(function (): void {
            if ($this->app->runningInConsole()) {
                return;
            }
            if (! $this->app->bound('request')) {
                return;
            }
            $request = $this->app->make('request');
            if ($request->getHost() === '') {
                return;
            }
            URL::forceRootUrl($request->root());
        });

        View::composer('layout.sidebar', function (\Illuminate\View\View $view): void {
            $view->with('sidebarMenu', app(SifecSidebarMenuBuilder::class)->tree());
        });
    }
}
