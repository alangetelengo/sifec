<?php

namespace Modules\Naissance\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Referentiel\Entities\Registre;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\TypeRegistre;

class LivretNaissanceController extends Controller
{

    public function index()
    {

        $inst = Auth::user()->affectationActive();
        // $registres = Registre::where("code_type_registre","TPRG_0001")->where("cui",$inst->cui)->get();
        $registres = $inst->institution->registres();


        return view('naissance::livret.index',compact("registres"));
    }

    public function shows($id)
    {
        $registre = Registre::find($id);
        $dummy = "XXXXXXXXXXXXXXXX";
        $actesRegistre = ActeNaissance::where("code_registre",$id)->orderBy("code_declaration_naissance")->get();
        // dd($actesRegistre);
        return view('naissance::registre.shows', compact('registre','actesRegistre','dummy'));
    }

    public function show($id)
    {

        $acte = ActeNaissance::findByIdentifier($id);
        $dummy = "XXXXXXXXXXXXXXXX";
        return view('naissance::registre.show', compact('acte','dummy'));
    }

}
