<?php

namespace App\Sifec;

use Illuminate\Support\Facades\Facade;

class SifecFacade extends Facade{


    protected static function getFacadeAccessor(){

        return "Sifec";
    }

}
