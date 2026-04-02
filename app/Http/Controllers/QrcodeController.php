<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Deces\Entities\ActeDeces;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Entities\Declarationnaissance;

class QrcodeController extends Controller
{
//Naissance
    public function index()
    {
        $niupp = request("niupp");
        $acte = ActeNaissance::findByIdentifier($niupp);
        $dummy = "XXXXXXXXXXXXXXXX";
        if ($acte == null) {
            return "Aucune donnée trouvée";
        }
        if (! $acte->niupp) {
            return "Acte en attente de signature : aucune donnée de vérification publique disponible.";
        }
        return view("qrcode.index", compact("acte", "dummy"));
    }

    public function certificatNaissance()
    {
        $cdn = request("niupp");
        $certificat = Declarationnaissance::find($cdn);
        if($certificat == null){
            return "Aucune donnée trouvée";
        }
        return view("qrcode.certificat_naissance",compact("certificat"));
    }

    public function duplicata()
    {
        $cdn = request("niupp");
        $acte = Declarationnaissance::find($cdn);
        if($acte == null){
            return "Aucune donnée trouvée";
        }
        return view("qrcode.duplicata",compact("acte"));
    }

    public function requisitionNaissance()
    {
        $cdn = request("niupp");
        $requisition = Declarationnaissance::find($cdn);
        if($requisition == null){
            return "Aucune donnée trouvée";
        }
        return view("qrcode.requisition_naissance",compact("requisition"));
    }

// DECES
    public function deces()
    {
        $niupp = request("niupp");
        $declaration = DeclarationDeces::find($niupp);
        // dd($declaration);
        if($declaration == null){
            return "Aucune donnée trouvée";
        }
        return view("qrcode.deces",compact("declaration"));
    }

    public function certificatDeces()
    {
        $cdd = request("niupp");
        $certificat = DeclarationDeces::find($cdd);
        if($certificat == null){
            return "Aucune donnée trouvée";
        }
        return view("qrcode.certificat_deces",compact("certificat"));
    }

    public function requisitionDeces()
    {
        $cdd = request("niupp");
        $requisition = DeclarationDeces::find($cdd);
        if($requisition == null){
            return "Aucune donnée trouvée";
        }
        return view("qrcode.requisition_deces",compact("requisition"));
    }
}


