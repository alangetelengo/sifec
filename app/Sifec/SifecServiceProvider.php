<?php

namespace App\Sifec;

use App\Sifec\Sifec;
use Illuminate\Support\ServiceProvider;

class SifecServiceProvider extends ServiceProvider{


    public function register()
    {
        $this->app->singleton("Sifec",function(){

            return new Sifec();
        });
    }

    public function boot(){

    }

}
