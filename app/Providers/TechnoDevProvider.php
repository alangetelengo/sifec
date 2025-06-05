<?php

namespace App\Providers;


use App\Technodev\TechnoDev;
use Illuminate\Support\ServiceProvider;

class TechnoDevProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("TechnoDev",function(){
            return new TechnoDev();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
