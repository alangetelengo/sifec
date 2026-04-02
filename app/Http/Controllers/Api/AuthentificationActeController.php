<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\TransmissionDocumentPortail;
use App\Models\AuthentificationActe;
use App\Models\DemandePortailParticulier;
use App\Models\PaiementDocument;
use App\Sifec\Sifec;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Modules\Deces\Entities\ActeDeces;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Referentiel\Entities\Institution;
use Omnipay\Omnipay;
use Spipu\Html2Pdf\Html2Pdf;
use Twilio\Rest\Client;



class AuthentificationActeController extends Controller
{
    public function authentification(Request $request)
    {

        $request->validate([
            "type_acte" => ['required','string'],
            "numero_acte" => ['required','string']
        ]);



        //récupération du formulaire authentification
        $typeActe = $request->type_acte;
        $numeroActe = $request->numero_acte;
        $route = "";

        //enregitrement de l'opération d'authentification d'acte en bdd
        //0.enregistrement des informations de la demande
        // $auth = new AuthentificationActe();
        // $auth->code_authentification = Sifec::genererCodeUniqueReferentiel($auth,"code_authentification",4,"AUTH_");
        // $auth->type_acte_authentification = $typeActe;
        // $auth->date_authentification = date("Y-m-d", strtotime(now()));
        // $auth->numero_acte_authentification = $numeroActe;

        // //configuration pointe-noire
        // $auth->montant_authentification = 1000;//coût du service d'authentification :: Estimation
        // $auth->administration = "DEC";//récuépration à partir des paramètres de l'utilisateur


        if($typeActe == 'Acte de naissance'){

            $an = ActeNaissance::findByIdentifier($numeroActe);
            if ($an == null || ! $an->niupp) {

                // $auth->statut_authentification = "NON AUTHENTIQUE";
                // $auth->save();

                $routeRecu =  route('etatRecuNaissanceNA', $numeroActe);
                //return $routeRecu;

            return response()->json([
                "code"=>"180",
                "message" => "Acte de naissance Non authentique",
                'etatRecu'=>$routeRecu
            ]);
            }

            // $auth->statut_authentification = "AUTHENTIQUE";

            // $auth->save();

           //récupération déclaration numéro
            $numDec = $an->code_declaration_naissance;
            //récupération de l'état de l'acte

            $route = route('acteNaissance.displayEtat',$numDec);

            //return "ok ok ge";

            //return $route;
            //création de la facture
            $routeRecu =  route('etatRecuNaissance',$numDec);

            return response()->json([
                "code"=>"200",
                "message" => "Acte de naissance authentique",
                'etatActe'=> $route,
                'etatRecu'=>$routeRecu
            ]);

        }
        if($typeActe == 'Acte de mariage'){
            $am = ActeMariage::find($numeroActe);

            if($am == null){
                $auth->statut_authentification = "NON AUTHENTIQUE";
                $auth->save();
               //création de la facture
               $routeRecu =  route('etatRecuMariageNA', $numeroActe);

            return response()->json([
                "code"=>"180",
                "message" => "Acte de mariage non authentique",
                'etatRecu'=>$routeRecu
            ]);
            }
            $auth->statut_authentification = "AUTHENTIQUE";
            $auth->save();
            //récupération déclaration numéro
            $numDec = $am->code_declaration_mariage;

            $route = route('acteMariage.displayEtat',$numDec);
            //return $route;

            //Récupération du reçu
            $routeRecu =  route('etatRecuMariage',$numDec);

            return response()->json([
                "code"=>"200",
                "message" => "Acte de mariage authentique",
                'etatActe'=>$route,
                'etatRecu'=>$routeRecu

            ]);
        }
        if($typeActe == 'Acte de décès'){

            $ad = ActeDeces::find($numeroActe);

            if($ad == null){
                $auth->statut_authentification = "NON AUTHENTIQUE";
                $auth->save();

                $routeRecu =  route('etatRecuDecesNA', $numeroActe);

                return response()->json([
                    "code"=>"180",
                    "message" => "Acte de décès non authentique",
                    'etatRecu'=>$routeRecu
                ]);
            }
            $auth->statut_authentification = "AUTHENTIQUE";
            $auth->save();

            //récupération déclaration numéro
            $numDec = $ad->code_declaration_deces;

            //identification du propriétaire de l'acte
            $personne = $ad->declaration->defunt;
            $route = route('acteDeces.displayEtat',$numDec);

             //Récupération du reçu
             $routeRecu =  route('etatRecuDeces',$numDec);


            return response()->json([
                "code"=>"200",
                "message" => "Acte de décès authentique",
                'etatActe'=>$route,
                 'etatRecu'=>$routeRecu

            ]);

        }

        return response()->json(["code"=>"180",'message'=>"veuiller choisir un type d'acte valable"]);

    }

    //DemandeCopie depuis le portail
    public function demandeActe(Request $request)
    {

        //récupération des informations
        $typeActe = $request->type_acte;
        $typeDocument = $request->type_document;
        $numeroActe = $request->numero_acte;
        $nomActe = $request->nom_acte;
        $prenomActe = $request->prenom_acte;
        $sexeActe = $request->sexe_acte;
        $dateNaissanceActe = $request->date_naissance_acte;
        $lieuNaissanceActe = $request->lieu_naissance_acte;
        $cecActe = $request->cec_acte;
        $nomDemandeur = $request->nom_demandeur;
        $telDemandeur = $request->telephone_demandeur;
        $emailDemandeur = $request->email_demandeur;
        //cec chargé de traiter la demande
        $cecTraitement = $request->cec_traitement;
         //nombre d'exemplaires et montant
        // $nombreExemplaire = $request->nombre_exemplaire;
         $montantApayer = $request->montant_a_payer;
        //moyen de paiement
        $moyenPaiement = $request->moyen_paiement;
        //numero de paiement
        $numeroMomo = $request->numero_momo;
        $numDec = "";

        if($typeActe == 'Naissance'){

             //-1 vérification de l'existence de l'authenticité de l'acte
            if($numeroActe!=''){
                //cas de recherche par numéro acte
                $an = ActeNaissance::findByIdentifier($numeroActe);
                if ($an != null && $an->niupp) {
                    //récupération code déclaration pour l'appel des services Etats
                    $numDec =  $an->code_declaration_naissance;

                }else{
                    return "Acte non trouvé";
                }
            }else{
                //recherche par identification du sujet
                $dn = DB::select('SELECT * from t_declaration_naissance dn, tr_identification_personne t
                                        where t.code_personne = dn.code_enfant
                                        and t.sexe = "%'.$sexeActe.'%"
                                            and t.nom like "%'.$nomActe.'%"
                                            and t.prenom like "%'.$prenomActe.'%"
                                            and t.lieu_naissance like "%'.$lieuNaissanceActe.'%"
                                            and t.date_naissance like "%'.$dateNaissanceActe.'%"
                ');



                if($dn != null){
                    //récupéération code déclaration pour l'appel des services Etats
                    $numDec =  $dn->code_declaration_naissance;
                }else{
                    return response()->json(["code"=>"180",'message'=>"Acte non trouvé"]);
                }

            }




                //0.enregistrement des informations de la demande
                // $dmd = new DemandePortailParticulier();

                // //$dmd->code_demande = Sifec::genererCodeUniqueReferentiel($dmd,"code_demande",4,"DMD_PORTAIL_");
                // $dmd->statut_demande = "En attente de paiement";
                // $dmd->type_acte = $typeActe;
                // $dmd->type_document = $typeDocument;
                // $dmd->num_acte = $numeroActe;
                // $dmd->nom_acte = $nomActe;
                // $dmd->prenom_acte = $prenomActe;
                // $dmd->sexe_acte = $sexeActe;
                // $dmd->date_naissance_acte = date("Y-m-d", strtotime($dateNaissanceActe));
                // $dmd->lieu_naissance_acte = $lieuNaissanceActe;
                // $dmd->cec_acte = $cecActe;

                // $dmd->nom_demandeur = $nomDemandeur;
                // $dmd->telephone_demandeur = $telDemandeur;
                // $dmd->email_demandeur = $emailDemandeur;

                // //$dmd->nombre_exemplaire = $nombreExemplaire;
                // $dmd->cout = $montantApayer;
                // $dmd->cec_associe = $cecTraitement; //cas des extraits
                // $dmd->moyen_paiement = $moyenPaiement;

                // $dmd->dateDemande = Carbon::now()->toDateTimeString(); //cas des extraits

                // $dmd->save();

                if($typeDocument == "Copie"){
                    $piece = route('copieActeNaissancePortail',$numDec.'|'.$cecTraitement);
                }

                if($typeDocument == "Extrait acte naissance"){
                    $piece = route("acteNaissance.displayExtraitActePortail",$numDec.'|'.$cecTraitement);
                }

                return $piece;


        }
        if($typeActe == 'Mariage'){
           //-1 vérification de l'existence de l'authenticité de l'acte
           if($numeroActe!=''){
            //cas de recherche par numéro acte
             $am = ActeMariage::find($numeroActe);

            if($am != null){
                //récupéération code déclaration pour l'appel des services Etats
                $numDec =  $am->code_declaration_mariage;

            }else{
                return response()->json(["code"=>"180",'message'=>"Acte non trouvé. Veuillez modifier le numéro d'acte et réessayer!"]);
            }
        }else{
            //recherche par identification du sujet :: information de l'épouse ::
            $am = DB::select('SELECT * from t_declaration_mariage dm, and t_identification_personne t
                            where t.code_personne = dm.code_epouse
                            and t.sexe = "%'.$sexeActe.'%"
                                and t.nom like "%'.$nomActe.'%"
                                and t.prenom like "%'.$prenomActe.'%"
                                and t.lieu_deces like "%'.$lieuNaissanceActe.'%"
                                and t.date_deces like "%'.$dateNaissanceActe.'%"
            ');

            if($am != null){
                //récupéération code déclaration pour l'appel des services Etats
                $numDec =  $am->code_declaration_mariage;

            }else{
                return response()->json(["code"=>"180",'message'=>"Acte non trouvé. Veuillez modifier le numéro d'acte et réessayer!"]);
            }

        }

             //0.enregistrement des informations de la demande
            $dmd = new DemandePortailParticulier();

            //$dmd->code_demande = Sifec::genererCodeUniqueReferentiel($dmd,"code_demande",4,"DMD_PORTAIL_");
            $dmd->statut_demande = "En attente de paiement";
            $dmd->type_acte = $typeActe;
            $dmd->type_document = $typeDocument;
            $dmd->num_acte = $numeroActe;
            $dmd->nom_acte = $nomActe;
            $dmd->prenom_acte = $prenomActe;
            $dmd->sexe_acte = $sexeActe;
            $dmd->date_naissance_acte = date("Y-m-d", strtotime($dateNaissanceActe));
            $dmd->lieu_naissance_acte = $lieuNaissanceActe;
            $dmd->cec_acte = $cecActe;

            $dmd->nom_demandeur = $nomDemandeur;
            $dmd->telephone_demandeur = $telDemandeur;
            $dmd->email_demandeur = $emailDemandeur;

            //$dmd->nombre_exemplaire = $nombreExemplaire;
            $dmd->cout = $montantApayer;
            $dmd->cec_associe = $cecTraitement; //cas des extraits
            $dmd->moyen_paiement = $moyenPaiement;

            $dmd->dateDemande = Carbon::now()->toDateTimeString(); //cas des extraits

            $dmd->save();

            return response()->json([
                "code"=>"200",
                "demandeDocument" => $dmd
            ]);



        }

        if($typeActe == 'Décès'){

             // return response()->json(["typeDocument"=>$request->type_document]);

             //-1 vérification de l'existence de l'authenticité de l'acte
             if($numeroActe!=''){
                //cas de recherche par numéro acte
                 $ad = ActeDeces::find($numeroActe);

                if($ad != null){
                    //récupéération code déclaration pour l'appel des services Etats
                    $numDec =  $ad->code_declaration_deces;

                }else{
                    return response()->json(["code"=>"180",'message'=>"Acte non trouvé. Veuillez modifier le numéro d'acte et réessayer!"]);
                }
            }else{
                //recherche par identification du sujet
                $ad = DB::select('SELECT * from t_declaration_deces dc, and t_identification_personne t
                                where t.code_personne = dc.code_defunt
                                and t.sexe = "%'.$sexeActe.'%"
                                    and t.nom like "%'.$nomActe.'%"
                                    and t.prenom like "%'.$prenomActe.'%"
                                    and t.lieu_deces like "%'.$lieuNaissanceActe.'%"
                                    and t.date_deces like "%'.$dateNaissanceActe.'%"
                ');

                if($ad != null){
                    //récupéération code déclaration pour l'appel des services Etats
                    $numDec =  $ad->code_declaration_deces;

                }else{
                    return response()->json(["code"=>"180",'message'=>"Acte non trouvé. Veuillez modifier le numéro d'acte et réessayer!"]);
                }

            }

                 //0.enregistrement des informations de la demande
                $dmd = new DemandePortailParticulier();

                //$dmd->code_demande = Sifec::genererCodeUniqueReferentiel($dmd,"code_demande",4,"DMD_PORTAIL_");
                $dmd->statut_demande = "En attente de paiement";
                $dmd->type_acte = $typeActe;
                $dmd->type_document = $typeDocument;
                $dmd->num_acte = $numeroActe;
                $dmd->nom_acte = $nomActe;
                $dmd->prenom_acte = $prenomActe;
                $dmd->sexe_acte = $sexeActe;
                $dmd->date_naissance_acte = date("Y-m-d", strtotime($dateNaissanceActe));
                $dmd->lieu_naissance_acte = $lieuNaissanceActe;
                $dmd->cec_acte = $cecActe;

                $dmd->nom_demandeur = $nomDemandeur;
                $dmd->telephone_demandeur = $telDemandeur;
                $dmd->email_demandeur = $emailDemandeur;

                //$dmd->nombre_exemplaire = $nombreExemplaire;
                $dmd->cout = $montantApayer;
                $dmd->cec_associe = $cecTraitement; //cas des extraits
                $dmd->moyen_paiement = $moyenPaiement;

                $dmd->dateDemande = Carbon::now()->toDateTimeString(); //cas des extraits

                $dmd->save();

                return response()->json([
                    "code"=>"200",
                    "demandeDocument" => $dmd
                ]);

        }
            return response()->json(["code"=>"180",'message'=>"veuiller choisir un type d'acte valable"]);


    }




    public function displayCopie($id)
    {

        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        $dummy = "XXXXXXXXXXXXXXXX";

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré une copie d'acte de naissance");
            return back();
        }

        $declarationDeces = DeclarationDeces::where("num_acte_naissance", $acte->niupp)->first();

        $mariage = null;
        if (DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first();
        }
        if (DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first();
        }


        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.copieActeNaissance', compact("acte","dummy", "declarationDeces","mariage"))->render());
        // // $html2pdf->writeHTML(view('naissance::etats.displayextrait', compact("acte","dummy", "declarationDeces","mariage"))->render());
        // // $html2pdf->writeHTML("<h1>Test reussi</h1>");
        return $html2pdf->output($acte->code_acte_naissance.".pdf");

    }

     public function displayCopiePortail($id)
    {

        $lib_institution_portail = explode('|', $id)[1];
        $numActe = explode('|', $id)[0];
        //récupération de l'institution correspondante
        $institutionPortail = Institution::where('lib_institution', $lib_institution_portail)->first();

        //récupération du signataire de l'institution


        $ins_user = DB::table('tr_ins_user as i')
            ->join('tr_fonction as f', 'i.code_fonction', '=', 'f.code_fonction')
            ->where('i.code_institution', $institutionPortail->code_institution)
            ->where('i.active', 1)
            ->where('f.lib_fonction', 'Officier d\'état civil')
            ->select('i.code_user')
            ->first();
        if(is_null($ins_user)){
            return "Aucun officier d'état civil trouvé pour cette institution.";
        }
            //récupération de l'utilisateur
            $signatairePortail = DB::table('tr_user as u')
                ->join('tr_identification_personne as p', 'u.code_personne', '=', 'p.code_personne')
                ->where('u.code_user', $ins_user->code_user)
                ->select('p.nom', 'p.prenom', 'signature')
                ->first();
        //récupération de l'utilisateur
        $signatairePortail = DB::table('tr_user as u')
            ->join('tr_identification_personne as p', 'u.code_personne', '=', 'p.code_personne')
            ->where('u.code_user', $ins_user->code_user)
            ->select('p.nom', 'p.prenom', 'signature')
            ->first();


        $acte = ActeNaissance::where("code_declaration_naissance",$numActe)->first();
        $dummy = "XXXXXXXXXXXXXXXX";

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré une copie d'acte de naissance");
            return back();
        }

        $declarationDeces = DeclarationDeces::where("num_acte_naissance", $acte->niupp)->first();

        $mariage = null;
        if (DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epoux',$acte->niupp)->first();
        }
        if (DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first() != null) {
            $mariage = DeclarationMariage::where('numero_acte_naissance_epouse',$acte->niupp)->first();
        }


        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.copieActeNaissancePortail', compact("acte","dummy", "declarationDeces","mariage", 'institutionPortail', 'signatairePortail'))->render());
        // // $html2pdf->writeHTML(view('naissance::etats.displayextrait', compact("acte","dummy", "declarationDeces","mariage"))->render());
        // // $html2pdf->writeHTML("<h1>Test reussi</h1>");
        return $html2pdf->output($acte->code_acte_naissance.".pdf");

    }


    public function listeCec()
    {

        //affichage de la liste des CEC
        $codeInstitutionMairie = "TPINS_0002";
        $codeInstitutionAmbassade = "TPINS_0005";
        //$codeInstitutionCommunauteUrbaine = "";
        //$codeInstitutionSousPrefecture = "";

        $listeCec = DB::select("(select i.lib_institution from tr_institution i, tr_type_institution ti where ti.code_type_institution=i.code_type_institution and (i.code_type_institution='$codeInstitutionAmbassade' or i.code_type_institution='$codeInstitutionMairie')) UNION (SELECT concat('COMMUNAUTE URBAINE - ', lib_communaute_urbaine) FROM `tr_communaute_urbaine`) UNION (SELECT concat('DISTRICT - ', lib_district) FROM `tr_district`)");

        return response()->json($listeCec);
		/* return response()->json([
                "code"=>"200",
                "data" => $listeCec

            ]);
*/

    }



    public function displayExtraitActe($id)
    {
        $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
        $numExtrait = substr(time(), 2);

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un extrait d'acte de naissance");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('L', 'A5', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.Extrait', compact("acte", "numExtrait"))->render());
        return $html2pdf->output($acte->code_acte_naissance.".pdf");

    }


      public function displayExtraitActePortail($id)
    {

        $lib_institution_portail = explode('|', $id)[1];
        $numActe = explode('|', $id)[0];
        //récupération de l'institution correspondante
        $institutionPortail = Institution::where('lib_institution', $lib_institution_portail)->first();

        //récupération du signataire de l'institution


        $ins_user = DB::table('tr_ins_user as i')
            ->join('tr_fonction as f', 'i.code_fonction', '=', 'f.code_fonction')
            ->where('i.code_institution', $institutionPortail->code_institution)
            ->where('i.active', 1)
            ->where('f.lib_fonction', 'Officier d\'état civil')
            ->select('i.code_user')
            ->first();

         if(is_null($ins_user)){
            return "Aucun officier d'état civil trouvé pour cette institution.";
        }

        //récupération de l'utilisateur
        $signatairePortail = DB::table('tr_user as u')
            ->join('tr_identification_personne as p', 'u.code_personne', '=', 'p.code_personne')
            ->where('u.code_user', $ins_user->code_user)
            ->select('p.nom', 'p.prenom', 'signature')
            ->first();

        $acte = ActeNaissance::where("code_declaration_naissance",$numActe)->first();
        $numExtrait = substr(time(), 2);

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un extrait d'acte de naissance");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('L', 'A5', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.ExtraitPortail', compact("acte", "numExtrait", 'signatairePortail', 'institutionPortail'))->render());
        return $html2pdf->output($acte->code_acte_naissance.".pdf");

    }
      //
      public function displayDuplicata($id)
      {
          $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
          $dummy = "XXXXXXXXXXXXXXXX";

          if($acte == null){
              toastr()->error("Vous ne pouvez pas généré un acte de naissance");
              return back();
          }

          view()->share("tester", "Alange");
          $html2pdf = new Html2Pdf('P', 'A4', 'fr');
          $html2pdf->setDefaultFont('Arial');
          $html2pdf->writeHTML(view('naissance::etats.duplicata', compact("acte","dummy"))->render());

          return $html2pdf->output($acte->code_acte_naissance.".pdf");
      }

    //   public function displayActe($id)
    //   {
    //       $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
    //       $dummy = "XXXXXXXXXXXXXXXX";

    //       if($acte == null){
    //           toastr()->error("Vous ne pouvez pas généré un acte de naissance");
    //           return back();
    //       }

    //       view()->share("tester", "Alange");
    //       $html2pdf = new Html2Pdf('P', 'A4', 'fr');
    //       $html2pdf->setDefaultFont('Arial');
    //       $html2pdf->writeHTML(view('naissance::etats.acte', compact("acte","dummy"))->render());

    //       return $html2pdf->output($acte->code_acte_naissance.".pdf");
    //   }

    public function displayActe($id)
    {
        try {
            // Charger l'acte avec ses relations nécessaires
            $acte = ActeNaissance::with([
                'declaration.enfant',
                'declaration.pere.nationalite',
                'declaration.pere.profession',
                'declaration.mere.nationalite',
                'declaration.mere.profession',
                'declaration.declarant',
                'declaration.adoptant',
                'declaration.jugement.institutionUser.institution.institutionParent',
                'declaration.institutionUser.institution.institutionParent',
                'declaration.institutionUser.institution.lieu.localiteParent',
                'declaration.requisition.typeRequisition',
                'declaration.institution.institutionParent',
                'institutionUser.institution',
                'institutionUser.institution.institutionParent.lieu.localiteParent'
            ])->where("code_declaration_naissance", $id)->first();

            if ($acte == null) {
                Log::channel('sifec')->error("Acte de naissance introuvable pour code_declaration_naissance: {$id}");
                toastr()->error("Vous ne pouvez pas générer un acte de naissance. Acte introuvable.");
                return back();
            }

            // Vérifier que la déclaration existe
            if (!$acte->declaration) {
                Log::channel('sifec')->error("Déclaration manquante pour acte: {$acte->code_acte_naissance}");
                throw new Exception("Données incomplètes pour générer l'acte. Déclaration manquante.");
            }

            $dummy = "XXXXXXXXXXXXXXXX";

            // Recherche de l'acte annulé (si existe)
            $acteannuler = Declarationnaissance::where("numero_ancien_acte", $acte->niupp)->first();

            // Recherche de déclaration de décès (si existe)
            $declarationDeces = DeclarationDeces::where("num_acte_naissance", $acte->niupp)->first();

            // Recherche de mariage (si existe)
            $mariage = null;
            $mariageEpoux = DeclarationMariage::where('numero_acte_naissance_epoux', $acte->niupp)->first();
            if ($mariageEpoux) {
                $mariage = $mariageEpoux;
            } else {
                $mariageEpouse = DeclarationMariage::where('numero_acte_naissance_epouse', $acte->niupp)->first();
                if ($mariageEpouse) {
                    $mariage = $mariageEpouse;
                }
            }

            // Compter le nombre total de mentions
            $nombreMentions = 0;
            if ($mariage != null) {
                $nombreMentions++;
            }
            if ($declarationDeces != null) {
                $nombreMentions++;
            }
            if ($acte->declaration->jugement != null) {
                $nombreMentions++;
            }
            if ($acteannuler != null) {
                $nombreMentions++;
            }
            // Charger les rectifications si nécessaire pour le comptage
            if (!$acte->relationLoaded('rectifications')) {
                $acte->load('rectifications');
            }
            if ($acte->rectifications && $acte->rectifications->count() > 0) {
                $nombreMentions += $acte->rectifications->count();
            }

            DB::beginTransaction();

            view()->share("tester", "Alange");
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');

            $verificationUrl = URL::signedRoute('verification.acte', ['niupp' => $acte->niupp]);
            $qrCode = $verificationUrl;

            // Rendre la vue avec gestion d'erreur
            $htmlContent = view('naissance::etats.acte', compact("acte", "dummy", "acteannuler", "declarationDeces", "mariage", "qrCode", "nombreMentions"))->render();

            if (empty($htmlContent)) {
                throw new Exception("Le contenu HTML de l'acte est vide.");
            }

            $html2pdf->writeHTML($htmlContent);
            DB::commit();

            return $html2pdf->output($acte->code_acte_naissance . ".pdf");
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error("Erreur génération PDF acte de naissance ID: {$id} - Message: " . $e->getMessage());
            Log::channel('sifec')->error("Stack trace: " . $e->getTraceAsString());

            // Si c'est une requête AJAX ou PDF, renvoyer une réponse JSON ou une erreur HTTP
            if (request()->expectsJson() || request()->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => "Erreur lors de la génération du PDF: " . $e->getMessage()
                ], 500);
            }

            // Sinon, renvoyer une réponse HTML d'erreur pour le PDF Viewer
            return response("Erreur lors de la génération du PDF: " . $e->getMessage(), 500)
                ->header('Content-Type', 'text/plain');
        }
    }

      public function etatRecuNaissance($id)
      {
          $acte = ActeNaissance::where("code_declaration_naissance",$id)->first();
          $dummy = "XXXXXXXXXXXXXXXX";
          if($acte == null){
              toastr()->error("Vous ne pouvez pas imprimer le reçu ");
              return back();
          }
          $typeDocument = "Acte de naissance";
          $statut="AUTHENTIQUE";

          view()->share("tester", "Alange");
          $html2pdf = new Html2Pdf('P', 'A6', 'fr');
          $html2pdf->setDefaultFont('Arial');
          $html2pdf->writeHTML(view('naissance::etats.recu', compact("acte","typeDocument", "statut", "dummy"))->render());

          return $html2pdf->output($acte->code_acte_naissance.".pdf");
      }

      public function etatRecuMariage($id)
      {
          $acte = ActeMariage::where("code_declaration_mariage",$id)->first();
          $dummy = "XXXXXXXXXXXXXXXX";

          if($acte == null){
              toastr()->error("Vous ne pouvez pas imprimer le reçu ");
              return back();
          }
           //récupération du statut

          $typeDocument = "Acte de mariage";
          $statut="AUTHENTIQUE";

          $typeDocument = "Acte de mariage";
          view()->share("tester", "Alange");
          $html2pdf = new Html2Pdf('P', 'A6', 'fr');
          $html2pdf->setDefaultFont('Arial');
          $html2pdf->writeHTML(view('naissance::etats.recu', compact("acte","typeDocument","statut", "dummy"))->render());

          return $html2pdf->output($acte->code_acte_naissance.".pdf");
      }

      public function etatRecuDeces($id)
      {
          $acte = ActeDeces::where("code_declaration_deces",$id)->first();
          $dummy = "XXXXXXXXXXXXXXXX";

          $typeDocument = "Acte de décès";
          $statut="AUTHENTIQUE";
          if($acte == null){
              toastr()->error("Vous ne pouvez pas imprimer le reçu ");
              return back();
          }
          //récupération du statut

         view()->share("tester", "Alange");
          $html2pdf = new Html2Pdf('P', 'A6', 'fr');
          $html2pdf->setDefaultFont('Arial');
          $html2pdf->writeHTML(view('naissance::etats.recu', compact('acte', 'typeDocument', 'statut', 'dummy'))->render());

          return $html2pdf->output($acte->code_acte_naissance.".pdf");
      }

      public function etatRecuDecesNA($id)
      {
          //$acte = ActeDeces::where("code_declaration_deces",$id)->first();
          $dummy = "XXXXXXXXXXXXXXXX";
          $numeroActeNA = $id;
          $typeDocument = "Acte de décès";
          $statut="NON AUTHENTIQUE";

         view()->share("tester", "Alange");
          $html2pdf = new Html2Pdf('P', 'A6', 'fr');
          $html2pdf->setDefaultFont('Arial');
          $html2pdf->writeHTML(view('naissance::etats.recuNA', compact('typeDocument', 'statut', 'numeroActeNA',  'dummy'))->render());

          return $html2pdf->output("Rapport.pdf");
      }

      public function etatRecuNaissanceNA($id)
      {
          //$acte = ActeDeces::where("code_declaration_deces",$id)->first();
          $dummy = "XXXXXXXXXXXXXXXX";
         // return "passé ".$id;
          $numeroActeNA = $id;
          $typeDocument = "Acte de naissance";
          $statut="NON AUTHENTIQUE";

         view()->share("tester", "Alange");
          $html2pdf = new Html2Pdf('P', 'A6', 'fr');
          $html2pdf->setDefaultFont('Arial');
          $html2pdf->writeHTML(view('naissance::etats.recuNA', compact('typeDocument', 'statut', 'numeroActeNA',  'dummy'))->render());

          return $html2pdf->output("Rapport.pdf");
      }

      public function etatRecuMariageNA($id)
      {
          //$acte = ActeDeces::where("code_declaration_deces",$id)->first();
          $dummy = "XXXXXXXXXXXXXXXX";
          $numeroActeNA = $id;
          $typeDocument = "Acte de mariage";
          $statut="NON AUTHENTIQUE";

         view()->share("tester", "Alange");
          $html2pdf = new Html2Pdf('P', 'A6', 'fr');
          $html2pdf->setDefaultFont('Arial');
          $html2pdf->writeHTML(view('naissance::etats.recuNA', compact('typeDocument', 'statut', 'numeroActeNA',  'dummy'))->render());

          return $html2pdf->output("Rapport.pdf");
      }






    public function displayActeMariage($id)
    {
        $acte = ActeMariage::where("code_declaration_mariage",$id)->first();

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de mariage");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.ActeMariageEtat', compact("acte"))->render());

        return $html2pdf->output($acte->code_acte_mariage.".pdf");
    }

    public function displayCopieMariage($id)
    {
        $acte = ActeMariage::where("code_declaration_mariage",$id)->first();

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de mariage");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.CopieActeMariage', compact("acte"))->render());

        return $html2pdf->output($acte->code_acte_mariage.".pdf");
    }


    public function displayExtraitMariage($id)
    {
        $acte = ActeMariage::where("code_declaration_mariage",$id)->first();

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de mariage");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.ExtraitActeMariage', compact("acte"))->render());

        return $html2pdf->output($acte->code_acte_mariage.".pdf");
    }

    public function displayDuplicataMariage($id)
    {
        $acte = ActeMariage::where("code_declaration_mariage",$id)->first();

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de mariage");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('mariage::etats.DuplicataActeMariage', compact("acte"))->render());

        return $html2pdf->output($acte->code_acte_mariage.".pdf");
    }


    public function displayActeDeces($id)
    {
        $acte = ActeDeces::where("code_declaration_deces",$id)->first();

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de déces");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.acte', compact("acte"))->render());

        return $html2pdf->output($acte->code_acte_deces.".pdf");
    }

    public function displayCopieDeces($id)
    {
        $acte = ActeDeces::where("code_declaration_deces",$id)->first();

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de décès");
            return back();
        }

        DB::beginTransaction();

       try {
        view()->share("tester", "@l@nge");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.acte_deces_copie', compact("acte"))->render());
        DB::commit();

        return $html2pdf->output($acte->code_acte_deces.".pdf");

       } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
       }
    }

    public function displayExtraitActeDeces($id)
    {
        $acte = ActeDeces::where("code_declaration_deces",$id)->first();

        if($acte == null){
            toastr()->error("Vous ne pouvez pas généré un acte de décès");
            return back();
        }

        DB::beginTransaction();

       try {
        view()->share("tester", "@l@nge");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.acte_deces_extrait', compact("acte"))->render());
        DB::commit();

        return $html2pdf->output($acte->code_acte_deces.".pdf");

       } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
       }
    }


}
