<?php

namespace App\Technodev;
use Illuminate\Support\Facades\Facade;

class TechnoDevFacade extends Facade{

    protected static function getFacadeAccessor(){
        
        return "TechnoDev";
    }
}