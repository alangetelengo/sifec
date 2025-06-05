<?php

namespace App\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use App\Models\PaiementDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Referentiel\Entities\Localite;
use Modules\Mobile\Entities\DemandeDocument;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\RetraitActe;
use Modules\Mobile\Entities\TypeDocumentDemande;

class PaiementDocumentController extends Controller
{
    public function index()
    {
        // $actes = ActeNaissance::all();
        $actes = Auth::user()->affectationActive()->ActeNaissances->flatten();

        $typeDocumentDemandes = TypeDocumentDemande::all();

        return view("paiement-document.index", compact("actes","typeDocumentDemandes"));
    }

    public function transManager(Request $request)
    {
        $montant = $request->montant;
        $phone = $request->telephone;

        if (Str::startsWith($phone, "242")) {
            $phone = substr($phone, 3);

            //recuperer la clé de transaction de paiement
            $transid = SifecFacade::paiement($phone,"$montant");
            // $transid = SifecFacade::paiement($phone,"20");
            return response()->json([
                "transid" => $transid
            ]);

        }

        if (Str::startsWith($phone, "243")) {

            $uniqtransid = uniqid("SIFEC") . time();
            $referenceid = uniqid("REF");


            // $transid = SifecFacade::flexpay("243814809740","1",$uniqtransid,"PDC01236521");

            return response()->json([
                // "transid" => $transid
                "transid" => $uniqtransid
            ]);

        }
        return ["code" => "190", "msg" => "Numéro de téléphone non supporté"];


        // if (str_contains($request->telephone, "243")) {
        //     $telephone = $request->telephone;
        //     $transid = uniqid("SIFEC");
        //     SifecFacade::flexpay("243814809740","1",$transid,"PDC01236521");
        // }
        //   $telephone = substr($request->telephone,3);


        // //recuperer la clé de transaction de paiement
        // $transid = SifecFacade::paiement($telephone,"$montant");
        // return response()->json([
        //     "transid" => $transid
        // ]);

    }

    //POUR LA DEMANDE DES DOCUMENTS DE L'ACTE
    public function store(Request $request)
    {

        $retire_par = $request->nominteresse;
        $montant = $request->montant;
        $telephone = $request->telephone;
        $telephoneInteresse = $request->telephone_interesse;
        $typedocument = $request->typedocument;
        $cui  = Auth::user()->affectationActive()->cui;
        $acte = ActeNaissance::find($request->niupp);

        try {

            $demadeDoc = new DemandeDocument;
            $demadeDoc->code_demande_document = Sifec::genererCodeUniqueReferentiel($demadeDoc, "code_demande_document", 8, "CDD_");
            $demadeDoc->nom_demandeur = $retire_par;
            $demadeDoc->sexe_demander = "M";
            $demadeDoc->telephone_demander = $telephoneInteresse;
            $demadeDoc->email_demandeur = "alangetelengo@hotmail.fr";
            $demadeDoc->statut = "Traité";
            $demadeDoc->code_type_document_demande = $typedocument;
            $demadeDoc->cui = $cui;
            $demadeDoc->prix = $montant;
            $demadeDoc->save();

            $typeDoc = $demadeDoc->typeDocumentDemande->lib_type_document_demande;
            return response()->json([
                "code"=>"200",
                "cdn" => $acte->declaration->code_declaration_naissance,
                "message"=> "La demande du document ($typeDoc) d'acte de naissance effectuée avec succès"
            ]);

        } catch (Exception $e) {
            Log::channel("sifec")->error($e->getMessage());
            return response()->json([
                "code"=> "201",
                "message"=> ["error"=> $e->getMessage()]
            ]);
        }

    }

    // public function storeDemandeDocument(Request $request)
    // {

    //     $retire_par = $request->nominteresse;
    //     $montant = $request->montant;
    //     $telephone = $request->telephone;
    //     $typedocument = $request->type_document;
    //     $cui  = Auth::user()->affectationActive()->cui;
    //     $acte = ActeNaissance::find($request->niupp);

    //    DB::beginTransaction();
    //     try {

    //         $paiementDocument = new PaiementDocument;
    //         $paiementDocument->code_paiement_document = Sifec::genererCodeUniqueReferentiel($paiementDocument, "code_paiement_document", 8, "CPD_");
    //         $paiementDocument->type_document = $typedocument;
    //         $paiementDocument->montant = $montant;
    //         $paiementDocument->date_paiement = date("Y-m-d");
    //         $paiementDocument->cui = $cui;
    //         $paiementDocument->save();

    //         DB::commit();

    //         return response()->json([
    //             "code"=>"200",
    //             "cdn" => $acte->declaration->code_declaration_naissance,
    //             "message"=> "Le retrait de l'acte de naissance enregistré avec succès"
    //         ]);

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         Log::channel("sifec")->error($e->getMessage());
    //         return response()->json([
    //             "code"=> "201",
    //             "message"=> ["error"=> $e->getMessage()]
    //         ]);
    //     }

    // }

    public function etatRecouvrement()
    {

        $fonction = Auth::user()->affectationActive()->fonction->code_fonction;
        // dd($fonction);
        //annee en cours par defaut
        $anneeEncours = date("Y");
        $etat = [];
            // $etat = DB::select('select SUM(prix) as montantApayer, l.lib_localite as lib_institution,l.code_localite from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where YEAR(dd.created_at) = ?
            // AND dd.cui = iu.cui
            // AND iu.code_institution = i.code_institution
            // AND i.code_localite = l.code_localite
            // GROUP BY l.lib_localite,l.code_localite', [$anneeEncours]);

        // Cas du ministre
        if($fonction == "FONC_0023")
        {
            $codeIns = Auth::user()->affectationActive()->institution->code_institution;

            $etat = DB::select('select SUM(prix) as montantApayer, l.code_localite_parent,l.lib_localite, (select lib_localite FROM tr_localite where code_localite=l.code_localite_parent) as lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND YEAR(dd.created_at) = ? GROUP BY l.code_localite_parent,l.lib_localite ORDER BY montantApayer', [$anneeEncours]);
        }
        // Cas gouverneur
        if($fonction == "FONC_0022")
        {
            $code_loc_parent_gouv =  Auth::user()->affectationActive()->institution->lieu->code_localite_parent;
            //dd($code_loc_parent_gouv);

             $etat = DB::select('select SUM(prix) as montantApayer, i.lib_institution, i.lib_institution as lib_localite from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND YEAR(dd.created_at) = ? AND l.code_localite_parent = ? GROUP BY i.lib_institution, lib_localite',[$anneeEncours,$code_loc_parent_gouv]);


           // $etat = DB::select('select SUM(prix) as montantApayer,l.lib_localite,l.code_localite_parent from t_demande_document dd,tr_ins_user iu, tr_institution i, tr_localite l WHERE dd.cui = iu.cui AND i.code_institution = iu.code_institution AND i.code_localite = l.code_localite AND YEAR(dd.created_at) = ? GROUP BY l.lib_localite,l.code_localite_parent;', [$anneeEncours]);
        }

        //Cas de bourgmestre
        if($fonction == "FONC_0002")
        {

            $codeIns = Auth::user()->affectationActive()->institution->code_institution;

            $etat = DB::select('select SUM(prix) as total, l.lib_localite, td.lib_type_document_demande as document from tr_type_document_demande td, t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where td.code_type_document_demande = dd.code_type_document_demande AND ui.cui = dd.cui AND i.code_institution = ui.code_institution and l.code_localite = i.code_localite AND YEAR(dd.created_at) = ? AND i.code_institution = ? GROUP BY td.lib_type_document_demande, l.lib_localite ORDER BY total DESC;', [$anneeEncours,$codeIns]);
        }


        if($etat == null){
            toastr()->error("Vous ne pouvez pas généré l'état de recouvrement");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('p', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('recouvrement.etat', compact("etat","fonction"))->render());
        return $html2pdf->output("recouvrement.pdf");

    }

}
