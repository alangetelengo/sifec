<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Modules\Deces\Entities\ActeDeces;
use App\Models\DemandePortailParticulier;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Referentiel\Entities\Institution;

class AuthentificationActeController extends Controller
{
    public function authentification(Request $request)
    {
        $request->validate([
            "type_acte" => ['required','string'],
            "numero_acte" => ['required','string']
        ]);

        $typeActe = $request->type_acte;
        $numeroActe = $request->numero_acte;
        $route = "";

        if($typeActe == 'Acte de naissance'){
            $an = ActeNaissance::find($numeroActe);

            if($an == null){

                return response()->json(["code"=>"180",'message'=>"ACTE DE NAISSANCE NON AUTHENTIQUE"]);
            }

            //récupération déclaration numéro
            $numDec = $an->code_declaration_naissance;
            $route = route('acteNaissance.displayEtat',$numDec);

            return response()->json([
                "code"=>"200",
                "message" => "Acte de naissance authentique",
                'etatActe'=>$route
            ]);

        }
        if($typeActe == 'Acte de mariage'){
            $am = ActeMariage::find($numeroActe);

            if($am == null){
                return response()->json(["code"=>"180",'message'=>"Acte non authentique"]);
            }
            //récupération déclaration numéro
            $numDec = $am->code_declaration_mariage;

            $route = route('acteMariage.displayEtat',$numDec);


            return response()->json([
                "code"=>"200",
                "message" => "Acte de mariage authentique",
                'etatActe'=>$route
            ]);
        }
        if($typeActe == 'Acte de décès'){

            $ad = ActeDeces::find($numeroActe);

            if($ad == null){
                return response()->json(["code"=>"180",'message'=>"ACTE DE DECES NON AUTHENTIQUE"]);
            }
            //récupération déclaration numéro
            $numDec = $ad->code_declaration_deces;

            //identification du propriétaire de l'acte
            $personne = $ad->declaration->defunt;
            $route = route('acteDeces.displayEtat',$numDec);

            return response()->json([
                "code"=>"200",
                "message" => "Acte de décès authentique",
                'etatActe'=>$route
            ]);

        }

        return response()->json(["code"=>"180",'message'=>"veuiller choisir un type d'acte valable"]);

    }

    //DemandeCopie depuis le portail
    public function demandeActe(Request $request)
    {

        die("ok toto");
        //$typeActe = $request->type_acte;
        //return "Reponse API type acte : ".$typeActe;
        //exit;
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
        $cecTraitement = $request->cec_traitement;
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
                $an = DB::select('SELECT * from t_declaration_naissance an, and t_declarationtr_identification_personne t, t_declaration_naissance dn
                                        where t.code_personne = dn.code_enfant
                                        and dn.code_declaration_naissance = an.code_declaration_naissance
                                        and t.sexe = "%'.$sexeActe.'%"
                                            and t.nom like "%'.$nomActe.'%"
                                            and t.prenom like "%'.$prenomActe.'%"
                                            and t.lieu_naissance like "%'.$lieuNaissanceActe.'%"
                                            and t.date_naissance like "%'.$dateNaissanceActe.'%"
                ');

                if($an != null){
                    //récupéération code déclaration pour l'appel des services Etats
                    $numDec =  $an->code_declaration_naissance;
                }else{
                    return response()->json(["code"=>"180",'message'=>"Acte non trouvé"]);
                }

            }
                //0.enregistrement des informations de la demande
                $dmd = new DemandePortailParticulier();
                //$dmd->code_demande = Sifec::genererCodeUniqueReferentiel($dmd,"code_demande",4,"DMD_PORTAIL_");
                $dmd->statut_demande = "En attente de traitement";
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
                $dmd->email_Demandeur = $emailDemandeur;

                $dmd->save();

                //prévisualisation du document au portail
                /*$visualisation = new ActeNaissanceController();
                if(typeDocument =="Copie"){
                    $visualisation->displayCopie($document);
                }elseif($typeDocument =="Duplicata"){
                    $visualisation->displayDuplicata($document);
                }*/
                //retour de l'api vers le portail

                $route = "";
                if($typeDocument == "Copie"){

                    $route = route('copieActeNaissance',$numDec);
                }

                if($typeDocument == "Extrait acte naissance"){
                    $route = route("acteNaissance.displayExtraitActe",$numDec);
                }

               /* if($typeDocument == "Duplicata"){
                    $route = route("duplicataActeNaissance",$numDec);
                }*/

                //transmission de l'acte par mail

                // main header (multipart mandatory)
            /*$filename = 'piece';
            $path = 'your path goes here';
            $file = $path . "/" . $filename;*/

           // $file = file_get_contents($route);
        
           /* $mailto = $dmd->email_Demandeur;
            $subject = 'Document état civil';

            $message = "M/Mme ".$dmd->nom_demandeur."\n, Veuillez recevoir votre ".$dmd->type_document." d'acte de ".$dmd->type_acte."\nL'état-civil.";
           
            /*$content = file_get_contents($file);
            $content = chunk_split(base64_encode($content));
        
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
            $body .= "--" . $separator . $eol;
            $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
            $body .= "Content-Transfer-Encoding: base64" . $eol;
            $body .= "Content-Disposition: attachment" . $eol;
            $body .= $content . $eol;
            $body .= "--" . $separator . "--";

            //SEND Mail
            if (mail($mailto, $subject, $body, $headers)) {
                return "Envoi d'email réussie"; // or use booleans here
            } else {
                return "Erreur d'envoi d'email !";
               // print_r( error_get_last() );
            }
                */

              

                return response()->json([
                    "code"=>"200",
                    "message" => $typeDocument." d'acte de naissance trouvé",
                    "referenceDocument" =>  "Paiement ".$typeDocument." d'acte de naissance N° ".$numeroActe,
                    "cec" =>  $cecTraitement,
                    "etat"=> $route
                ]);


        }
        if($typeActe == 'Mariage'){
            $am = ActeMariage::find($numeroActe);

            if($am != null){
                //identification du propriétaire de l'acte
                $epoux = $am->declaration->epoux;
                $epouse = $am->declaration->epouse;
                $personne = $epoux ?? $epouse;

                return response()->json([
                    "code"=>"200",
                    "message" => "Acte de mariage disponible",
                    "nom"=> $personne->nom,
                    "prenom"=> $personne->prenom,
                    "sexe"=> $personne->sexe == "M" ? "Homme" : "Femme",
                    "dateNaissance"=> date("d-m-Y", strtotime($personne->date_naissance)),
                    "lieuNaissance"=> $personne->lieu_naissance,
                    "cecNaissance"=> $am->declaration->institutionUser->institution->lib_institution,
                    "dateMariage"=> date("d-m-Y", strtotime($am->declaration->date_prevue_mariage)),
                    "optionMariage"=> $am->declaration->optionMariage->lib_option_mariage,
                    "regimeMariage"=> $am->declaration->regime->lib_regime,
                    "cecMariage"=> $am->declaration->institutionUser->institution->lib_institution

                ]);

            } else{
                return response()->json(["code"=>"180",'message'=>"Acte non authentique"]);
            }

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
                    return response()->json(["code"=>"180",'message'=>"Acte non trouvé"]);
                }
            }else{
                //recherche par identification du sujet
                $ad = DB::select('SELECT * from t_declaration_deces an, and t_declarationtr_identification_personne t, t_declaration_deces dd
                                where t.code_personne = dd.code_enfant
                                and dd.code_declaration_deces = an.code_declaration_deces
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
                    return response()->json(["code"=>"180",'message'=>"Acte non trouvé"]);
                }

            }
                //0.enregistrement des informations de la demande
                // $dmd = new DemandePortailParticulier();
                // //$dmd->code_demande = Sifec::genererCodeUniqueReferentiel($dmd,"code_demande",4,"DMD_PORTAIL_");
                // $dmd->statut_demande = "En attente de traitement";
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
                // $dmd->email_Demandeur = $emailDemandeur;

                // $dmd->save();

                //prévisualisation du document au portail
                /*$visualisation = new ActeNaissanceController();
                if(typeDocument =="Copie"){
                    $visualisation->displayCopie($document);
                }elseif($typeDocument =="Duplicata"){
                    $visualisation->displayDuplicata($document);
                }*/
                //retour de l'api vers le portail

                $route = "";
                if($typeDocument == "Copie"){

                    $route = route('copieActeDeces',$numDec);
                }

                if($typeDocument == "Extrait acte décès"){
                    $route = route("acteDeces.displayExtrait",$numDec);
                }

                /*if($typeDocument == "Duplicata"){
                    $route = route("acteDeces.displayDuplicata",$numDec);
                }*/

                return response()->json([
                    "code"=>"200",
                    "message" => $typeDocument." d'acte de décès trouvé",
                    "referenceDocument" =>  "Paiement ".$typeDocument." d'acte de décès N° ".$numDec,
                    "cec" =>  $cecTraitement,
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

    public function displayActeMariage($id)
    {
        $acte = ActeDeces::where("code_declaration_mariage",$id)->first();

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
        $html2pdf->writeHTML(view('deces::etats.acte_deces_copie', compact("acte"))->render());
        DB::commit();

        return $html2pdf->output($acte->code_acte_deces.".pdf");

       } catch (Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back();
       }
    }

    public function listeCec()
    {
        //affichage de la liste des CEC
        $codeInstitutionMairie = "TPINS_0002";
        $codeInstitutionAmbassade = "TPINS_0005";

        $listeCec = DB::select("(select i.lib_institution from tr_institution i, tr_type_institution ti where ti.code_type_institution=i.code_type_institution and (i.code_type_institution='$codeInstitutionAmbassade' or i.code_type_institution='$codeInstitutionMairie')) UNION (SELECT concat('COMMUNAUTE URBAINE - ', lib_communaute_urbaine) FROM `tr_communaute_urbaine`) UNION (SELECT concat('DISTRICT - ', lib_district) FROM `tr_district`)");
        return response()->json($listeCec);
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

    public function etatRecetteDemandeEnLigne()
    {
        $etat = DB::select('select cec_associe, sum(cout) as montantApayer from tr_demande_portail_particulier group by cec_associe');
        //$numExtrait = substr(time(), 2);
        //return $etat;
        if($etat == null){
            toastr()->error("Vous ne pouvez pas généré l'état de recouvrement");
            return back();
        }

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('p', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('recouvrement.etatRecettePortail', compact("etat"))->render());
        return $html2pdf->output("recouvrement.pdf");

    }

}
