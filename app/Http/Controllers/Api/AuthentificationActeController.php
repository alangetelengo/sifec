<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\AdministrationAuthentification;
use App\Models\AuthentificationActe;
use Modules\Deces\Entities\ActeDeces;
use App\Models\DemandePortailParticulier;
use App\Models\PaiementDocument;
use App\Sifec\Sifec;
use Carbon\Carbon;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Referentiel\Entities\Institution;
use Omnipay\Omnipay;

use App\Mail\TransmissionDocumentPortail;
use Illuminate\Support\Facades\Mail;

class AuthentificationActeController extends Controller
{
    public function authentification(Request $request)
    {

       
        $request->validate([
            "type_acte" => ['required','string'],
            "numero_acte" => ['required','string']
        ]);


        //récupération du formulaire authentification :: provisoire
        $typeActe = $request->type_acte;
        $numeroActe = $request->numero_acte;
        $admin = $request->administration;
        if($admin=="DIRECTION DES EXAMENS ET CONCOURS (DEC)DIRECTION DES EXAMENS ET CONCOURS (DEC)"){
            $administration = "ADM_0001";
        }

        if($admin=="BANQUE CONGOLAISE DE L'HABITAT (BCH)BANQUE CONGOLAISE DE L'HABITAT (BCH)"){
            $administration = "ADM_0002";
        }
        if($admin=="DIRECTION DE L'IDENTIFICATION CIVILE(DIC)DIRECTION DE L'IDENTIFICATION CIVILE(DIC)"){
            $administration = "ADM_0005";
        }
        $route = "";

      
        //enregitrement de l'opération d'authentification d'acte en bdd
        //0.enregistrement des informations de la demande
        $auth = new AuthentificationActe();
        $auth->code_authentification = Sifec::genererCodeUniqueReferentiel($auth,"code_authentification",4,"AUTH_");
        $auth->type_acte_authentification = $typeActe;
        $auth->date_authentification = date("Y-m-d", strtotime(now()));
        $auth->numero_acte_authentification = $numeroActe;
        $auth->montant_authentification = 1000;//coût du service d'authentification :: Estimation
        $auth->administration = $administration;//récuépration à partir des paramètres code l'amdinistration de l'utilisateur:: provisoire

      
        if($typeActe == 'Acte de naissance'){

            $an = ActeNaissance::find($numeroActe);
            if($an == null){

                $auth->statut_authentification = "NON AUTHENTIQUE";
                $auth->save();

                $routeRecu =  route('etatRecuNaissanceNA', $numeroActe);
                //return $routeRecu;

            return response()->json([
                "code"=>"180",
                "message" => "Acte de naissance Non authentique",
                'etatRecu'=>$routeRecu
            ]);
            }

            $auth->statut_authentification = "AUTHENTIQUE";

            $auth->save();


           


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
                 $an = ActeNaissance::find($numeroActe);
                if($an != null){
                    //récupéération code déclaration pour l'appel des services Etats
                    $numDec =  $an->code_declaration_naissance;
                }else{
                    return response()->json(["code"=>"180",'message'=>"Acte non trouvé"]);
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

                  /*return response()->json([
                      "code"=>"200",
                      "demandeDocument" => $dmd
                  ]);*/

                                   //Transmission par mail
                /* $liste = [];
                Mail::to($dmd->email_demandeur)->send(new TransmissionDocumentPortail($dmd, $liste));
                */
                 //transmission de l'acte par mail

                // main header (multipart mandatory)
            /*$filename = 'piece';
            $path = 'your path goes here';
            $file = $path . "/" . $filename;*/

           // $file = file_get_contents($route);
            
            /*$mailto = $dmd->email_demandeur;

          
            

            $subject = 'Document état civil';

          

            $message = "M/Mme ".$dmd->nom_demandeur.",\nVeuillez recevoir votre ".$dmd->type_document." d'acte de ".$dmd->type_acte."\nL'état-civil.";
           
            
            //$content = file_get_contents($file);
            ///$content = chunk_split(base64_encode($content));
        
            // a random hash will be necessary to send mixed content
            $separator = md5(time());
        
            // carriage return type (RFC)
            $eol = "\r\n";

            $headers = "From: name <sifec@mid.cg>" . $eol;
            $headers .= "MIME-Version: 1.0" . $eol;
            $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
            $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
            $headers .= "This is a MIME encoded message." . $eol;

            // message
            $body = "--" . $separator . $eol;
            $body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
            $body .= "Content-Transfer-Encoding: 8bit" . $eol;
            $body .= $message . $eol;

            // attachment
            /*$body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol;
            $body .= $content . $eol;
            $body .= "--" . $separator . "--";

            //SEND Mail
            
            mail($mailto, $subject, $body, $headers);/*) {
                return "Envoi d'email réussie"; // or use booleans here
            } else {
                return "Erreur d'envoi d'email !";
               // print_r( error_get_last() );
            }*/

            //affichage de la demande à la vue
            $route = "";
            if($typeDocument == "Copie"){

                $route = route('copieActeNaissance',$numDec);
            }

            if($typeDocument == "Extrait acte naissance"){
                $route = route("acteNaissance.displayExtraitActe",$numDec);
            }



            return response()->json([
                "etat"=> $route
            ]);
                

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

            /*return response()->json([
                "code"=>"200",
                "demandeDocument" => $dmd
            ]);*/

             //traitement paiement
            /*return response()->json([
                "code"=>"200",
                "demandeDocument" => $dmd
            ]);*/

            //Transmission par mail
            /*$liste = [];
            Mail::to($dmd->email_demandeur)->send(new TransmissionDocumentPortail($dmd, $liste));*/
            //affichage de la demande à la vue
            $route = "";
            if($typeDocument == "Copie"){

                $route = route('copieActeMariage',$numDec);
            }

            if($typeDocument == "Extrait acte naissance"){
                $route = route("extraitActeMariage",$numDec);
            }



            return response()->json([
                "etat"=> $route
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

               //traitement paiement
            /*return response()->json([
                "code"=>"200",
                "demandeDocument" => $dmd
            ]);*/

            //Transmission par mail
           /* $liste = [];
            Mail::to($dmd->email_demandeur)->send(new TransmissionDocumentPortail($dmd, $liste));*/
            //affichage de la demande à la vue
            $route = "";
            if($typeDocument == "Copie"){

                $route = route('copieActeDeces',$numDec);
            }

            if($typeDocument == "Extrait acte naissance"){
                $route = route("acteDeces.displayExtrait",$numDec);
            }



            return response()->json([
                "etat"=> $route
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


    public function etatRecouvrement()
    {
        $etat = DB::select('select libelle_administration, sum(montant_authentification) as montantApayer from tr_authentification_acte auth, tr_administration_authentification admin where admin.code_administration = auth.administration group by libelle_administration');
        //$numExtrait = substr(time(), 2);
        //return $etat;
        if($etat == null){
            toastr()->error("Vous ne pouvez pas généré l'état de recouvrement");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('p', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('recouvrement.etat', compact("etat"))->render());
        return $html2pdf->output("recouvrement.pdf");

    }

    public function historiqueAuthentification(Request $request)
    {
        //récupération de l'id administration
        $code_administration = $request->code_administration;
        //return "reponse: ".$code_administration;
        //récupération de létat
        $routeEtat =  route('etatHistorique', $code_administration);
        return response()->json([
            "code"=>"200",
            'etat'=>$routeEtat
        ]);

    }

    public function etatHistorique($id)
    {
        //récupération de l'id administration
        $code_administration = $id;
        $administration = AdministrationAuthentification::find($code_administration);
        $etat = DB::select("select * from tr_authentification_acte where administration ='$code_administration'");
        //$numExtrait = substr(time(), 2);
        //return $etat;
        if($etat == null){
            toastr()->error("Vous ne pouvez pas généré l'état de recouvrement");
            return back();
        }
        //création de l'état de sortie
        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('L', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('recouvrement.historique', compact("etat", "administration"))->render());
        return $html2pdf->output("historique.pdf");

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

      public function displayActe($id)
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
          $html2pdf->writeHTML(view('naissance::etats.acte', compact("acte","dummy"))->render());

          return $html2pdf->output($acte->code_acte_naissance.".pdf");
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
