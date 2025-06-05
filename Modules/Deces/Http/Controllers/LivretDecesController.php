<?php

namespace Modules\Deces\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Deces\Entities\ActeDeces;
use Modules\Referentiel\Entities\Registre;
use Modules\Referentiel\Entities\TypeRegistre;

class LivretDecesController extends Controller
{

    public function index()
    {
        $inst = Auth::user()->affectationActive();
        // $registres = Registre::where("code_type_registre","TPRG_0004")->where("cui",$inst->cui)->get();
        $registres = $inst->institution->registres();

        return view('deces::livret.index',compact("registres"));
    }

    public function shows($id)
    {
        $registre = Registre::find($id);
        $actesRegistre = ActeDeces::where("code_registre",$id)->get();
        return view('deces::livret.shows', compact('registre','actesRegistre'));
    }

    public function show($id)
    {
        $acteReg = ActeDeces::find($id);
        return view('deces::livret.show', compact('acteReg'));
    }

}
