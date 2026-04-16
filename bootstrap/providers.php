<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\TechnoDevProvider;
use App\Sifec\SifecServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    EventServiceProvider::class,
    RouteServiceProvider::class,
    TechnoDevProvider::class,
    SifecServiceProvider::class,
];
