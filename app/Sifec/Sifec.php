<?php

namespace  App\Sifec;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\Deces\Entities\ActeDeces;
use Illuminate\Database\Eloquent\Model;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Referentiel\Entities\Commune;
use Modules\Referentiel\Entities\District;
use Modules\Referentiel\Entities\Document;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Registre;
use App\Models\MobileMoneyTransactionDetail;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Mobile\Entities\DemandeDocument;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\AdressePersonne;
use Modules\Referentiel\Entities\ContactPersonne;
use Modules\Referentiel\Entities\TypeDocument;
use Modules\Naissance\Entities\Declarationnaissance;

class Sifec {

    public static function  genererCodeUniqueReferentiel(Model $model, string $code_field, int $tailleString,$prefix ){
        $maxTries = 10;
        $attempt = 0;

        while ($attempt < $maxTries) {
            DB::beginTransaction();
            try {
                // Utiliser une requête avec verrou pour éviter les problèmes de concurrence
                // Utiliser un tri numérique au lieu d'un tri lexicographique pour éviter les problèmes d'ordre
                $prefixLength = strlen($prefix);
                $startPos = $prefixLength + 1;

                // Trouver le maximum numérique réel avec verrou de table
                // Utiliser le modèle pour respecter les soft deletes
                $maxObj = $model::lockForUpdate()
                    ->selectRaw("MAX(CAST(SUBSTRING(`{$code_field}`, {$startPos}) AS UNSIGNED)) as max_num")
                    ->first();

                $max = $maxObj && isset($maxObj->max_num) && $maxObj->max_num !== null ? (int)$maxObj->max_num + 1 : 1;

                // Générer le code et vérifier s'il existe déjà (dans la même transaction avec verrou)
                // IMPORTANT: Vérifier avec withTrashed() car la clé primaire MySQL ne permet pas les doublons
                // même si l'enregistrement est soft deleted
                do {
                    $strNumber = str_pad($max,$tailleString,"0",STR_PAD_LEFT);
                    $code = $prefix.$strNumber;
                    // Vérifier l'existence en incluant les soft deletes (car la clé primaire les bloque)
                    // Vérifier si le modèle utilise SoftDeletes avant d'appeler withTrashed()
                    $query = method_exists($model, 'withTrashed') && in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive($model))
                        ? $model::withTrashed()->where($code_field, $code)
                        : $model::where($code_field, $code);
                    $exists = $query->lockForUpdate()->exists();
                    if ($exists) {
                        $max++;
                    }
                } while ($exists && $max < 99999999); // Éviter une boucle infinie

                DB::commit();

                if (!$exists) {
                    return $code;
                }

            } catch (Exception $e) {
                DB::rollBack();
                Log::channel('sifec')->warning('Erreur génération code unique', [
                    'attempt' => $attempt,
                    'error' => $e->getMessage()
                ]);
            }

            $attempt++;
            // Attendre un peu avant de réessayer
            usleep(rand(10000, 50000)); // 10-50ms
        }

        // Si on n'arrive pas à générer un code unique après plusieurs tentatives,
        // trouver le vrai maximum numérique
        try {
            $prefixLength = strlen($prefix);
            $startPos = $prefixLength + 1;
            $maxObj = $model::selectRaw("MAX(CAST(SUBSTRING(`{$code_field}`, {$startPos}) AS UNSIGNED)) as max_num")
                ->first();
            $maxNum = $maxObj && $maxObj->max_num ? (int)$maxObj->max_num : 0;
            $max = $maxNum + 1;

            // Vérifier que le code n'existe pas (inclure les soft deletes)
            do {
                $strNumber = str_pad($max, $tailleString, "0", STR_PAD_LEFT);
                $code = $prefix.$strNumber;
                // Vérifier avec withTrashed() car la clé primaire MySQL bloque les doublons même supprimés
                // Vérifier si le modèle utilise SoftDeletes avant d'appeler withTrashed()
                $query = method_exists($model, 'withTrashed') && in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive($model))
                    ? $model::withTrashed()->where($code_field, $code)
                    : $model::where($code_field, $code);
                $exists = $query->exists();
                if ($exists) {
                    $max++;
                }
            } while ($exists && $max < 99999999);

            if (!$exists) {
                return $code;
            }
        } catch (Exception $e) {
            Log::channel('sifec')->warning('Erreur lors de la recherche du maximum numérique', [
                'error' => $e->getMessage()
            ]);
        }

        // Dernier recours : utiliser un timestamp avec microsecondes pour garantir l'unicité
        $timestamp = time();
        $micro = substr(microtime(), 2, 6); // Prendre 6 chiffres des microsecondes
        $combined = $timestamp . $micro;
        $strNumber = substr($combined, -$tailleString);
        return $prefix.str_pad($strNumber, $tailleString, "0", STR_PAD_LEFT);
    }

    public static function niveauInstructions(){

        return ['PRIMAIRE','SECONDAIRE NIVEAU I','SECONDAIRE NIVEAU II','SUPERIEUR','NON DECLARE'];
    }

    public static function LieuCeremonie(){

        return ['Centre d\'état civil','Hors centre d\'état civil'];
    }

    public static function mois($numero){

         $mois =["janvier"," février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"];

        return $mois[$numero -1];
    }

    //Transcription date
    public static function asLetters($number,$separateur=",") {
        $convert = explode($separateur, $number);
        $num[17] = array('0', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit',
                        'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize');

        $num[100] = array(20 => 'vingt', 30 => 'trente', 40 => 'quarante', 50 => 'cinquante',
                        60 => 'soixante', 70 => 'soixante-dix', 80 => 'quatre-vingt', 90 => 'quatre-vingt-dix');


        if (isset($convert[1]) && $convert[1] != '') {
          return self::asLetters($convert[0]).' et '.self::asLetters($convert[1]);
        }
        if ($number < 0) return 'moins '.self::asLetters(-$number);
        if ($number < 17) {
          return $num[17][$number];
        }
        elseif ($number < 20) {
          return 'dix-'.self::asLetters($number-10);
        }
        elseif ($number < 100) {
          if ($number%10 == 0) {
            return $num[100][$number];
          }
          elseif (substr($number, -1) == 1) {
            if( ((int)($number/10)*10)<70 ){
              return self::asLetters((int)($number/10)*10).'-et-un';
            }
            elseif ($number == 71) {
              return 'soixante-onze';
            }
            elseif ($number == 81) {
              return 'quatre-vingt-un';
            }
            elseif ($number == 91) {
              return 'quatre-vingt-onze';
            }
          }
          elseif ($number < 70) {
            return self::asLetters($number-$number%10).'-'.self::asLetters($number%10);
          }
          elseif ($number < 80) {
            return self::asLetters(60).'-'.self::asLetters($number%20);
          }
          else {
            return self::asLetters(80).'-'.self::asLetters($number%20);
          }
        }
        elseif ($number == 100) {
          return 'cent';
        }
        elseif ($number < 200) {
          return self::asLetters(100).' '.self::asLetters($number%100);
        }
        elseif ($number < 1000) {
          return self::asLetters((int)($number/100)).' '.self::asLetters(100).($number%100 > 0 ? ' '.self::asLetters($number%100): '');
        }
        elseif ($number == 1000){
          return 'mille';
        }
        elseif ($number < 2000) {
          return self::asLetters(1000).' '.self::asLetters($number%1000).' ';
        }
        elseif ($number < 1000000) {
          return self::asLetters((int)($number/1000)).' '.self::asLetters(1000).($number%1000 > 0 ? ' '.self::asLetters($number%1000): '');
        }
        elseif ($number == 1000000) {
          return 'millions';
        }
        elseif ($number < 2000000) {
          return self::asLetters(1000000).' '.self::asLetters($number%1000000);
        }
        elseif ($number < 1000000000) {
          return self::asLetters((int)($number/1000000)).' '.self::asLetters(1000000).($number%1000000 > 0 ? ' '.self::asLetters($number%1000000): '');
        }
    }

    public function sendSms($to,$content){
        $data = array(
            "client"=>"mukinayiseth",
            // "client"=>"exactit",
            "password"=>"123456789@123456789",
            // "password"=>"@24Ex-Tech",
            "phone"=>substr($to,1),
            "from"=>"ETAT-CIVIL",
            // "from"=>"SIFEC",
            "text"=>$content
        );

        $req = Http::asForm()->get("https://api.wirepick.com/httpsms/send", $data);
        if ($req->status() == 200) {

            return $req->body();
        }

        return $req->body();
    }

    public static function uniqueString(Request $request,$sufix,$sexe,$adopter="",$poids="",$taille="",$pc=""){
        $dummy = "XXXXXXXXXXXXXXXX";
        $timer = substr(time(),8);
        $str = "";
        $str .= $request->input("nom".$sufix);
        $str .= $request->input("prenom".$sufix);
        $str .= $request->input("date_naissance".$sufix);
        $str .= Localite::find($request->input("code_localite".$sufix)) ? Localite::find($request->input("code_localite".$sufix))->lib_localite : $request->input("code_localite".$sufix);
        $str .= $sexe;
        if($request->input("nom".$sufix) == "XXXXXXXXXXXXXXXX"){
            $str .= $timer; //timer pour differencier les store
        }

        if($adopter != ""){
            $str .= "/adoption=>".$adopter;
        }
        $str .= $poids;
        $str .= $taille;
        $str .= $pc;
        $string = str_replace(" ","", $str);
        return strtoupper($string);
    }

     // Enregistrer les informations de la personne
    public static function savePersonne(Request $request,$sufix,$sexe,$uniqueString,$adoption="") : Personne
    {

        DB::beginTransaction();

        try {
            $maxRetries = 3;
            $retryCount = 0;
            $personne = null;

            while ($retryCount < $maxRetries) {
                try {
                    $personne = new Personne;
                    $codedeclarant = Sifec::genererCodeUniqueReferentiel($personne,"code_personne",8,"PRS_");

                    $personne->code_personne = $codedeclarant;
                    $personne->nom = strtoupper($request->input("nom" . $sufix));
                    $personne->prenom = ucfirst($request->input("prenom".$sufix)) ;
                    $personne->date_naissance = $request->input("date_naissance".$sufix);
                    $personne->code_localite  = $request->input("code_localite".$sufix) ?? "LOC_4247";
                    $personne->lieu_naissance = $request->input("lieu_naissance".$sufix) ?? Localite::find($request->input("code_localite".$sufix))->lib_localite ?? "NON DECLARE";

                    $personne->code_profession = $request->input("profession".$sufix) ?? $request->input("code_profession".$sufix) ?? "PROF_0010";
                    // $personne->code_nationalite = $request->input("code_nationalite".$sufix) ?? "NAT_0001";
                    $personne->code_nationalite = "NAT_0001";
                    $personne->niveau_instruction = $request->input("niveau_instruction".$sufix) ?? "NON DECLARE";
                    $personne->telephone = $request->input("telephone".$sufix);
                    $personne->telephone_parent = $request->input("telephone_parent");
                    $personne->sexe = $sexe;
                    // $personne->type_date_naissance = $request->input("type_date_naissance".$sufix);
                    $personne->statut_personne = $request->input("statut_personne".$sufix) ? $request->input("statut_personne".$sufix) : "VIVANT" ;
                    $personne->personne_string = $uniqueString;
                    $personne->type_adoption = $adoption;


                    if($sufix == "_enfant")
                    {
                        $personne->type_date_naissance = "EXACTE";
                        $personne->niveau_instruction = "NON DECLARE";
                        $personne->code_profession=="PROF_0010";
                        $personne->code_nationalite=="NAT_0001";
                    }
                    else
                    {
                        $personne->type_date_naissance = $request->input("type_date_naissance".$sufix);
                        if(empty($request->input("type_date_naissance".$sufix)))
                        {
                            $personne->type_date_naissance = "EXACTE";
                        }
                    }

                    $personne->save();


                    //Ajouter le document
                    if($personne->statut_personne == "VIVANT")
                    {
                        $addocument = new Document();
                        $code = Sifec::genererCodeUniqueReferentiel($addocument,"code_document",8,"DOC_");
                        $addocument->code_document = $code;

                        if($sufix == "_enfant")
                        {
                            $addocument->numero_document = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
                            // Utiliser le premier type de document disponible ou null si aucun n'existe
                            $defaultTypeDoc = TypeDocument::first();
                            $addocument->code_type_document = $defaultTypeDoc ? $defaultTypeDoc->code_type_document : null;
                        }else{
                            $addocument->numero_document = $request->input("numero_document".$sufix) ? $request->input("numero_document".$sufix) : "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
                            $codeTypeDoc = $request->input("code_type_document".$sufix);
                            if ($codeTypeDoc) {
                                // Vérifier que le type de document existe
                                $typeDocExists = TypeDocument::where('code_type_document', $codeTypeDoc)->exists();
                                $addocument->code_type_document = $typeDocExists ? $codeTypeDoc : null;
                            } else {
                                // Utiliser le premier type de document disponible ou null
                                $defaultTypeDoc = TypeDocument::first();
                                $addocument->code_type_document = $defaultTypeDoc ? $defaultTypeDoc->code_type_document : null;
                            }
                        }
                        $addocument->code_personne = $personne->code_personne;
                        $addocument->save();
                    }


                    // if($sufix != "_enfant" && $personne->statut_personne == "VIVANT")
                    // if($sufix != "_enfant")
                    // {
                        $num = $request->input("domicile_numero".$sufix);
                        $num = $num ? $num : null;
                        $typeVoie = $request->input("domicile_typevoie".$sufix);
                        $typeVoie = $typeVoie ? strtolower($typeVoie) : null;
                        $libVoie = $request->input("domicile_nomvoie".$sufix);
                        $libVoie = $libVoie ? ucfirst($libVoie) : null;
                        $comDist = \Modules\Referentiel\Entities\Localite::find($request->input("domicile_ville".$sufix));
                        $comDist = $comDist ? $comDist->lib_localite : null;
                        $arrondissement = \Modules\Referentiel\Entities\Localite::find($request->input("domicile_arrondissement".$sufix));
                        $arrondissement = $arrondissement ? $arrondissement->lib_localite : null;
                        $quartier = \Modules\Referentiel\Entities\Localite::find($request->input("domicile_quartier".$sufix));
                        $quartier = $quartier ? $quartier->lib_localite : null;

                        $adressePers = $num.','.$typeVoie.' '.$libVoie.' '.$quartier.' '.$arrondissement.' '.$comDist;


                        $contact = new ContactPersonne;
                        $contact->indicatif = $request->input("code_pays" . $sufix);
                        $contact->telephone = $request->input("telephone" . $sufix);
                        $contact->email_personnelle = $request->input("email" . $sufix);
                        $contact->code_personne = $personne->code_personne;
                        $contact->save();

                        $adresse = new AdressePersonne();
                        $adresse->lib_pays  = $request->input("domicile_pays". $sufix) ?? "Congo";
                        $adresse->lib_ville = $comDist;
                        $adresse->type_voie = $typeVoie;
                        $adresse->nom_voie = $libVoie;
                        $adresse->numero_rue = $num;

                        // Utiliser code_localite : priorité au quartier, sinon arrondissement, sinon commune/district
                        $codeLocalite = null;
                        if($request->input("domicile_quartier".$sufix) != "" && $request->input("domicile_quartier".$sufix) != "XXXXXXXXXXXXXXXX"){
                            $codeLocalite = $request->input("domicile_quartier".$sufix);
                        } elseif($request->input("domicile_arrondissement".$sufix) != "" && $request->input("domicile_arrondissement".$sufix) != "XXXXXXXXXXXXXXXX"){
                            $codeLocalite = $request->input("domicile_arrondissement".$sufix);
                        } else {
                            $codeLocalite = $request->input("domicile_ville" . $sufix);
                        }
                        $adresse->code_localite = $codeLocalite;


                        if($request->input("domicile_ville".$sufix) != ""){
                            $adresse->code_localite = $request->input("domicile_ville".$sufix);
                        }
                        if($request->input("domicile_ville".$sufix) == "XXXXXXXXXXXXXXXX"){

                            $adresse->code_localite = "LOC_4250";
                        }
                        // else{
                        //     $adresse->code_localite = NULL;
                        // }


                        // $adresse->code_localite  = $request->input("domicile_ville" . $sufix) ?? NULL;
                        $adresse->code_personne = $personne->code_personne;
                        $adresse->save();


                    //update adresse
                    $updateAdresse = Personne::find($personne->code_personne);
                    $updateAdresse->adresse = $adressePers;
                    $updateAdresse->save();

                    // Si nous arrivons ici, l'insertion a réussi, sortir de la boucle
                    break;

                } catch (\Illuminate\Database\QueryException $e) {
                    // Vérifier si c'est une erreur de duplication de clé primaire
                    if ($e->getCode() == 23000 && strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        $retryCount++;
                        if ($retryCount >= $maxRetries) {
                            Log::channel("sifec")->error("Impossible de générer un code unique après {$maxRetries} tentatives", [
                                'error' => $e->getMessage(),
                                'uniqueString' => $uniqueString
                            ]);
                            throw new Exception("Impossible de créer la personne : code en doublon après plusieurs tentatives");
                        }
                        // Attendre un peu avant de réessayer pour éviter les collisions
                        usleep(rand(100000, 500000)); // 0.1 à 0.5 secondes
                        continue;
                    } else {
                        // Si c'est une autre erreur, la relancer
                        throw $e;
                    }
                }
            }

            if (!$personne) {
                throw new Exception("Impossible de créer la personne après plusieurs tentatives");
            }

            DB::commit();
            return $personne;

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel("sifec")->error($e->getMessage());
            throw $e;
        }

    }

    public function pushDataTo(){
        $contacts = Personne::whereNotNull("telephone")->get();
        $contactsPersonnes = ContactPersonne::all()->pluck("telephone")->unique();

        if($contacts->count() > 0){
            foreach($contacts as $contact){
                if(!$contactsPersonnes->contains($contact->telephone) && strlen($contact->telephone) == 9){
                    try {
                        $c = new ContactPersonne;
                        $c->indicatif = "+242";
                        $c->telephone = $contact->telephone;
                        $c->code_personne = $contact->code_personne;
                        $c->save();

                    } catch (Exception $e) {
                        Log::channel("sifec")->error($e->getMessage());
                    }

                }
            }
            return true;
        }
        return false;
    }

    public function validiteCodeOtpRegistre(){
        $otps = Registre::whereNull("signature_tribunal")->get();
        if($otps->count() > 0){
            foreach ($otps as $otp) {
                $otp->otp_paraphage = null;
                $otp->save();
            }
        }
        return true;
    }
    public function validiteCodeOtpActeNaissance(){
        $otps = ActeNaissance::whereNull("signature_mairie")->get();
        if($otps->count() > 0){
            foreach ($otps as $otp) {
                $otp->otp_approbation_mairie = null;
                $otp->save();
            }
        }
        return true;
    }
    public function validiteCodeOtpActeMariage(){
        $otps = ActeMariage::whereNull("signature_mairie")->get();
        if($otps->count() > 0){
            foreach ($otps as $otp) {
                $otp->otp_approbation_mairie = null;
                $otp->save();
            }
        }
        return true;
    }
    public function validiteCodeOtpActeDeces(){
        $otps = ActeNaissance::whereNull("signature_pompe_funebre")->get();
        if($otps->count() > 0){
            foreach ($otps as $otp) {
                $otp->otp_approbation_pompe_funebre = null;
                $otp->save();
            }
        }
        return true;
    }

    public function PushLibArrondissementTo()
    {
        $adresseP = AdressePersonne::all();
        foreach($adresseP as $a){
            $insert = AdressePersonne::find($a->id);
            $insert->localite = $a->arrondissement->lib_arrondissement;
            $insert->save();
        }
        return true;
    }



    //New updatePersonne
    public static function updatePersonne(Request $request,$sufix,$sexe,$code)
    {

        DB::beginTransaction();
        try {

             $personne = Personne::find($code);
            $personne->nom = $request->input("nom".$sufix);
            $personne->prenom = ucfirst($request->input("prenom".$sufix)) ;
            $personne->date_naissance = $request->input("date_naissance".$sufix);
            $personne->lieu_naissance = $request->input("lieu_naissance".$sufix) ?? Localite::find($request->input("code_localite".$sufix))->lib_localite;
            $personne->code_localite = $request->input("code_localite".$sufix);

            $prof = "";
            $nat = "";

            if(($request->input("code_profession".$sufix)!=null))
            {
                $prof = $request->input("code_profession".$sufix);
            }else{
                $prof = "PROF_0010";
            }

            $personne->code_profession = $prof;

            if($request->input("code_nationalite".$sufix)!=null)            {
                $nat = $request->input("code_nationalite".$sufix);
            }else {
                $nat = "NAT_0001";
            }

            $personne->code_nationalite = $nat;

            if($request->input("niveau_instruction".$sufix) != null)
            {
                $personne->niveau_instruction = $request->input("niveau_instruction".$sufix);
            }else{
                $personne->niveau_instruction = "NON DECLARE";
            }

            $personne->sexe = $sexe;

            if($sufix == "_defunt"){
                $personne->statut_personne = "DECEDE";
                $personne->type_date_naissance = $request->input("type_date_naissance".$sufix);
            }

            // $personne->statut_personne = $request->input("statut_personne".$sufix);

            $personne->type_date_naissance = $request->input("type_date_naissance".$sufix);

            if(empty($request->input("type_date_naissance".$sufix)))
            {
                $personne->type_date_naissance = "EXACTE";
            }

            if((empty($request->input("statut_personne".$sufix))) && ($sufix != "_defunt"))
            {
                $personne->statut_personne = "VIVANT";
            }
            //mise à jour de la chaine unique
            $uniqueString = Sifec::uniqueString($request,$sufix,$sexe);
            $personne->personne_string = $uniqueString;

            $personne->save();


            if($sufix!="_defunt" && $sufix!="_enfant" && $request->input("statut_personne".$sufix) == "VIVANT"){
            //modifier le document
                $dop = Document::where('code_personne',$personne->code_personne)->first();

                if($dop == null){
                    $dop = new Document();
                    $code = Sifec::genererCodeUniqueReferentiel($dop,"code_document",8,"DOC_");
                    $dop->numero_document = $request->input("numero_document".$sufix);
                    $codeTypeDoc = $request->input("code_type_document".$sufix);
                    // Vérifier que le type de document existe
                    if ($codeTypeDoc) {
                        $typeDocExists = TypeDocument::where('code_type_document', $codeTypeDoc)->exists();
                        $dop->code_type_document = $typeDocExists ? $codeTypeDoc : null;
                    } else {
                        $dop->code_type_document = null;
                    }
                    $dop->code_document = $code;
                    $dop->code_personne = $personne->code_personne;
                    $dop->save();

                }else{
                    $dop->numero_document = $request->input("numero_document".$sufix);
                    $codeTypeDoc = $request->input("code_type_document".$sufix);
                    // Vérifier que le type de document existe
                    if ($codeTypeDoc) {
                        $typeDocExists = TypeDocument::where('code_type_document', $codeTypeDoc)->exists();
                        $dop->code_type_document = $typeDocExists ? $codeTypeDoc : null;
                    } else {
                        $dop->code_type_document = null;
                    }
                    $dop->save();
                }
            }
            // Log::channel("sifec")->error(["document"=>$dop]);



            //modifier contacts
            if($sufix == "_enfant" && $request->input("telephone".$sufix) != "" || $request->input("domicile_typevoie".$sufix) != "" && $request->input("statut_personne".$sufix) == "VIVANT")
            {
            // if ($sufix == "_enfant" && $request->input("statut_personne".$sufix) == "VIVANT") {

                $addContact = new ContactPersonne;
                $addContact->indicatif = $request->input("code_pays".$sufix);
                $addContact->telephone = $request->input("telephone".$sufix);
                $addContact->email_professionnelle = $request->input("email_professionnel".$sufix);
                $addContact->email_personnelle = $request->input("email".$sufix);
                $addContact->code_personne = $request->input("code".$sufix);
                $addContact->save();

                $addAdresse = new AdressePersonne;
                $addAdresse->lib_pays = $request->input("domicile_pays".$sufix);
                $addAdresse->lib_ville = $request->input("domicile_ville".$sufix);
                $addAdresse->type_voie = $request->input("domicile_typevoie".$sufix);
                $addAdresse->nom_voie = $request->input("domicile_nomvoie".$sufix);
                $addAdresse->numero_rue = $request->input("domicile_numero".$sufix);

                // Utiliser code_localite : priorité au quartier, sinon arrondissement, sinon commune/district
                $codeLocalite = null;
                if($request->input("domicile_quartier".$sufix) != "" && $request->input("domicile_quartier".$sufix) != "XXXXXXXXXXXXXXXX"){
                    $codeLocalite = $request->input("domicile_quartier".$sufix);
                } elseif($request->input("domicile_arrondissement".$sufix) != "" && $request->input("domicile_arrondissement".$sufix) != "XXXXXXXXXXXXXXXX"){
                    $codeLocalite = $request->input("domicile_arrondissement".$sufix);
                } else {
                    $codeLocalite = $request->input("domicile_ville".$sufix);
                }
                $addAdresse->code_localite = $codeLocalite;
                $addAdresse->code_personne = $personne->code_personne;
                $addAdresse->save();

                $personne->telephone = $addContact->telephone;
                // Construire l'adresse avec la localité
                $localiteLib = $addAdresse->code_localite ? (Localite::find($addAdresse->code_localite) ? Localite::find($addAdresse->code_localite)->lib_localite : '') : '';
                $personne->adresse = $addAdresse->numero_rue." ".$addAdresse->type_voie." ".$addAdresse->nom_voie." ".$localiteLib;
                $personne->save();

            }


            if($sufix != "_enfant" && $request->input("statut_personne".$sufix) == "VIVANT")
            {
                $contact = ContactPersonne::where('code_personne',$personne->code_personne)->first();
                Log::channel("sifec")->error(['contact'=>$contact]);

                $contact->indicatif = $request->input("code_pays".$sufix);
                $contact->telephone = $request->input("telephone".$sufix);
                $contact->email_personnelle = $request->input("email".$sufix);
                $contact->save();

                $adresse = AdressePersonne::where('code_personne',$personne->code_personne)->first();
                $adresse->lib_pays = $request->input("domicile_pays" . $sufix);
                $adresse->lib_ville = $request->input("domicile_ville" . $sufix);
                $adresse->type_voie = $request->input("domicile_typevoie" . $sufix);
                $adresse->nom_voie = $request->input("domicile_nomvoie" . $sufix);
                $adresse->numero_rue = $request->input("domicile_numero" . $sufix);

                // Utiliser code_localite : priorité au quartier, sinon arrondissement, sinon commune/district
                $codeLocalite = null;
                if($request->input("domicile_quartier" . $sufix) != "" && $request->input("domicile_quartier" . $sufix) != "XXXXXXXXXXXXXXXX"){
                    $codeLocalite = $request->input("domicile_quartier" . $sufix);
                } elseif($request->input("domicile_arrondissement" . $sufix) != "" && $request->input("domicile_arrondissement" . $sufix) != "XXXXXXXXXXXXXXXX"){
                    $codeLocalite = $request->input("domicile_arrondissement" . $sufix);
                } else {
                    $codeLocalite = $request->input("domicile_ville" . $sufix);
                }
                $adresse->code_localite = $codeLocalite;
                $adresse->code_personne = $personne->code_personne;
                $adresse->save();
            }

            $adresseResidence = \Modules\Referentiel\Entities\AdressePersonne::where('code_personne', $personne->code_personne)->first();
            if (!$adresseResidence) {
                $adresseResidence = new \Modules\Referentiel\Entities\AdressePersonne();
                $adresseResidence->code_personne = $personne->code_personne;
            }
            $adresseResidence->lib_pays  = $request->input("domicile_pays" . $sufix) ?? "Congo";
            $adresseResidence->lib_ville = $request->input("domicile_ville" . $sufix);
            $adresseResidence->type_voie = $request->input("domicile_typevoie" . $sufix);
            $adresseResidence->nom_voie = $request->input("domicile_nomvoie" . $sufix);
            $adresseResidence->numero_rue = $request->input("domicile_numero" . $sufix);

            // Utiliser code_localite : priorité au quartier, sinon arrondissement, sinon commune/district
            $codeLocalite = null;
            if($request->input("domicile_quartier" . $sufix) != "" && $request->input("domicile_quartier" . $sufix) != "XXXXXXXXXXXXXXXX"){
                $codeLocalite = $request->input("domicile_quartier" . $sufix);
            } elseif($request->input("domicile_arrondissement" . $sufix) != "" && $request->input("domicile_arrondissement" . $sufix) != "XXXXXXXXXXXXXXXX"){
                $codeLocalite = $request->input("domicile_arrondissement" . $sufix);
            } else {
                $codeLocalite = $request->input("domicile_ville" . $sufix);
            }
            $adresseResidence->code_localite = $codeLocalite;
            $adresseResidence->save();

           DB::commit();
            return $personne;

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel("sifec")->error($e->getMessage());
            throw $e;
        }
    }

    public function checkMatching(){
        $declarations = DeclarationDeces::whereNull("num_acte_verified")->take(10)->get();
        if($declarations->count() > 0){
            foreach($declarations as $dec){
                self::verifyActe($dec);
            }
        }
    }

    public function verifyActe(DeclarationDeces $dec){
        if($dec == null){
            Log::channel("sifec")->info("Aucune déclaration trouvée");
            return;
        }

        if($dec->num_acte_naissance == ""){
            Log::channel("sifec")->info("Aucun numéro d'acte trouvé");
            return;
        }

        $naissance = ActeNaissance::find($dec->num_acte_naissance);

        if($dec->date_naissance == null){
            Log::channel("sifec")->info("Aucune date de naissance trouvée");
            return;
        }

        $annee = new Carbon($dec->date_naissance);
        $annee = $annee->year;

        if($naissance == null && ($annee < 2020)){
            $dec->num_acte_verified = "Non repris";
            $dec->save();
            Log::channel("sifec")->info("Acte non repris");
            return;
        }

        if($naissance == null && ($annee >= 2020)){
            $dec->num_acte_verified = "Invalide";
            $dec->save();
            Log::channel("sifec")->info("Acte invalide");
            return;
        }

        $dec->num_acte_verified = "Valide";
        $dec->save();
        Log::channel("sifec")->info("Acte valide");
        return;
    }

    public static function getDureeEnMois(string $date):int{
        $date = Carbon::parse($date);
        $now = Carbon::now();

        return $now->diff($date)->m;
    }

    public static function uniqueStringTemoin(Array $item,$sexe){
        $str = "";
        $str .= $item['nom_temoin'];
        $str .= $item['prenom_temoin'];
        $str .= $item['date_naissance_temoin'];
        $str .= Localite::find($item['code_localite_temoin'])->lib_localite;
        $str .= $sexe;
        return strtoupper($str);
    }
    public static function uniqueStringEnfant(Array $item,$sexe){
        $str = "";
        $str .= $item['nom'];
        $str .= $item['prenom'];
        $str .= $item['date_naissance'];
        // $str .= $item['code_localite'] ?? Localite::find($item['code_localite'])->lib_localite;
        $str .= $item['lieu_naissance'] ?? Localite::find($item['lieu_naissance'])->lib_localite;
        $str .= $sexe;
        return strtoupper($str);
    }

    public static function rechercherPersonne($niupp){
        return ActeNaissance::with(['declaration.enfant.nationalite','declaration.enfant.profession','declaration.enfant.localite','declaration.pere','declaration.mere','institutionUser.institution'])->where("niupp",$niupp)->first();
    }

    //update adresse enfant en cas d'eventuels evenements (Mariage,Décès,etc...)
    public function updateAdresse(Request $request,$sufix,$uniqueString) : AdressePersonne
    {
        $personne = Personne::where("personne_string",$uniqueString)->first();
        $num = $request->input("domicile_numero".$sufix);
        $num = $num ? $num : null;
        $typeVoie = $request->input("domicile_typevoie".$sufix);
        $typeVoie = $typeVoie ? strtolower($typeVoie) : null;
        $libVoie = $request->input("domicile_nomvoie".$sufix);
        $libVoie = $libVoie ? ucfirst($libVoie) : null;
        $comDist = \Modules\Referentiel\Entities\Localite::find($request->input("domicile_ville".$sufix));
        $comDist = $comDist ? $comDist->lib_localite : null;
        $arrondissement = \Modules\Referentiel\Entities\Localite::find($request->input("domicile_arrondissement".$sufix));
        $arrondissement = $arrondissement ? $arrondissement->lib_localite : null;
        $quartier = \Modules\Referentiel\Entities\Localite::find($request->input("domicile_quartier".$sufix));
        $quartier = $quartier ? $quartier->lib_localite : null;

        DB::beginTransaction();
        try{
            $residencePersonne = new AdressePersonne();
            $residencePersonne->lib_pays  = $request->input("domicile_pays". $sufix) ?? "Congo";
            $residencePersonne->lib_ville = $comDist;
            $residencePersonne->type_voie = $typeVoie;
            $residencePersonne->nom_voie = $libVoie;
            $residencePersonne->numero_rue = $num;

            // Utiliser code_localite : priorité au quartier, sinon arrondissement, sinon commune/district
            $codeLocalite = null;
            if($request->input("domicile_quartier".$sufix) != "" && $request->input("domicile_quartier".$sufix) != "XXXXXXXXXXXXXXXX"){
                $codeLocalite = $request->input("domicile_quartier".$sufix);
            } elseif($request->input("domicile_arrondissement".$sufix) != "" && $request->input("domicile_arrondissement".$sufix) != "XXXXXXXXXXXXXXXX"){
                $codeLocalite = $request->input("domicile_arrondissement".$sufix);
            } else {
                $codeLocalite = $request->input("domicile_ville" . $sufix);
            }
            $residencePersonne->code_localite = $codeLocalite;
            $residencePersonne->code_personne = $personne->code_personne;
            $residencePersonne->save();

            $contact = new ContactPersonne;
            $contact->indicatif = $request->input("code_pays" . $sufix);
            $contact->telephone = $request->input("telephone" . $sufix);
            $contact->email_personnelle = $request->input("email" . $sufix);
            $contact->code_personne = $personne->code_personne;
            $contact->save();

            DB::commit();
            return $residencePersonne;
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel("sifec")->error($e->getMessage());
            throw $e;
        }
    }


    public function format_nombre(int $nombre,int $taille):string{
        return str_pad($nombre,$taille,"0",STR_PAD_LEFT);
    }

    public function generate_acte_number(Registre $registre, $position){
        if($registre){
            //récupération de l'année du registre
            $ar = substr($registre->created_at,0,4);
            $type = "";
            switch($registre->typeRegistre->lib_type_registre){
                case "NAISSANCE":
                    $type = "RAN";
                    break;
                case "MARIAGE":
                    $type = "RAM";
                    break;
                case "DECES":
                    $type = "RAD";
                    break;
                case "DIVORCE":
                    $type = "RADV";
                    break;
            }

            // $position = $registre->nombre_acte_transcris + 1;
            $code_registre = substr($registre->code_registre,4);
            $numero_acte = $type.$code_registre.$ar.SifecFacade::format_nombre($position,2);
            return $numero_acte;
        }
    }


    public static function communesDistricts($codeparent){

        $comDist = Localite::where('code_localite_parent',$codeparent)->get();

        return $comDist->map->descendants();
    }

    public static function ArrondissementComUrb($parent)
    {
        // $arrondComUrb = Localite::where('code_localite_parent',$parent)->where("code_type_localite","TPLOC_0004")->Orwhere("code_type_localite","TPLOC_0005")->get();
        $arrondComUrb = Localite::where('code_localite_parent',$parent)->where("code_type_localite","TPLOC_0004")->get();
        return $arrondComUrb;
    }
    public static function Quartier($parent)
    {
        // $arrondComUrb = Localite::where('code_localite_parent',$parent)->where("code_type_localite","TPLOC_0004")->Orwhere("code_type_localite","TPLOC_0005")->get();
        $quertiers = Localite::where('code_localite_parent',$parent)->where("code_type_localite","TPLOC_0007")->get();
        return $quertiers;
    }

    public static function institutions($codelocalite,$typeInstitution)
    {
        $localites = Sifec::ArrondissementComUrb($codelocalite);
        $inst = $localites->map->institutions->map->where('code_type_institution',$typeInstitution)->flatten();
        return $inst;
    }



    public function infobipSms($to, $content)
    {
        $endpoint = config("technodev.sms_global.infobip.actions.send_sms.send_url");
        $token = config("technodev.sms_global.infobip.api_key");

        $headers = [
            "Authorization" => $token,
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        ];

        $body = [
            "messages" => [
                "destinations" => [
                    "to" => $to
                ],
                "from" => "ETAT-CIVIL",
                "text" => self::normaliserCaracteresSpeciaux($content)
            ]
        ];

        $response = Http::asJson()->withHeaders($headers)->post($endpoint, $body);

        // Log pour diagnostic
        Log::channel("sifec")->info("Infobip SMS - Status: " . $response->status());
        Log::channel("sifec")->info("Infobip SMS - Response: " . $response->body());
        Log::channel("sifec")->info("Infobip SMS - Request: " . json_encode($body));

        if ($response->status() == 200) {
            return $response->body();
        }

        // Retourner la réponse même en cas d'erreur pour diagnostic
        return $response->body();
    }


    public function normaliserCaracteresSpeciaux($texte)
    {
        $caracteresSpeciaux = array(
            'á' => 'a', 'à' => 'a', 'â' => 'a', 'ä' => 'a', 'ã' => 'a', 'å' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'ö' => 'o', 'õ' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c',
            'ñ' => 'n',
            'Á' => 'A', 'À' => 'A', 'Â' => 'A', 'Ä' => 'A', 'Ã' => 'A', 'Å' => 'A',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Ô' => 'O', 'Ö' => 'O', 'Õ' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'Ç' => 'C',
            'Ñ' => 'N',
        );

        return strtr($texte, $caracteresSpeciaux);
    }


    public function paiement($phone,$amount){
        $token = self::PaymentLogin();
        if( $token != null){

            $url = config("sifec.acsi_paiement.actions.debit.endpoint");
            $body = config("sifec.acsi_paiement.actions.debit.body");
            $body["phone"] = $phone;
            $body["amount"] = $amount;

            $headers = config("sifec.acsi_paiement.actions.debit.header");
            $headers["Authorization"] = "Bearer $token";

            //lancer la requête
            $response = Http::asJson()->withHeaders($headers)->post($url,$body);
            if($response->status() == 200){
                Log::channel("sifec")->info("Paiement passé");

                $corps = json_decode($response->body());
                Log::channel("sifec")->info("TRANSID PAYMENT ###########".json_encode($corps));
                $code = $corps->code;
                if($code=="200"){
                    $transid = $corps->transid;
                    Log::channel("sifec")->info("TRANSID PAYMENT ########### $transid");

                    return $transid;
                }
            }else{
                Log::channel("sifec")->error("TRANSID PAYMENT ###########");
            }
        }else{
            Log::channel("sifec")->error("Login echoué");
        }
    }

    public function PaymentLogin(){
        $url = config("sifec.acsi_paiement.actions.login.endpoint");
        $body = config("sifec.acsi_paiement.actions.login.body");

        // Envoyer la requête
        $response = Http::asJson()->post($url,$body);

        if($response->status() == 200){
            $corps = json_decode($response->body());
            $code = $corps->code;
            if($code=="200"){
                $token = $corps->token;
                return $token;
            }
        }

        return null;
    }

    // public function statutPaiement($transid){
    //     $token = self::PaymentLogin();
    //     if($token != null){
    //         $url = config("sifec.acsi_paiement.actions.status.endpoint");
    //         $body = config("sifec.acsi_paiement.actions.status.body");
    //         $headers = config("sifec.acsi_paiement.actions.status.hearder");
    //         $headers["Authorization"] = "Bearer $token";
    //         $body["transid"] = $transid;

    //         // lancer la requête
    //         $response = Http::asJson()->withHeaders($headers)->get($url,$body);
    //         if($response->status() == 200){
    //             $corps = json_decode($response->body());
    //             $code = $corps->code;
    //             if($code == "200"){
    //                 // Log::channel("sifec")->info(json_encode($corps));
    //                 Log::channel("sifec")->info(json_encode($corps));
    //             }
    //         }
    //         $te = collect($corps)->flatten();
    //         return $te["status"];
    //     }

    // }

    public function statutPaiement($transid){
        $token = self::PaymentLogin();
        if($token != null){
            $url = config("sifec.acsi_paiement.actions.status.endpoint");
            $body = config("sifec.acsi_paiement.actions.status.body");
            $headers = config("sifec.acsi_paiement.actions.status.hearder");
            $headers["Authorization"] = "Bearer $token";
            $body["transid"] = $transid;


            // lancer la requête
            $response = Http::asJson()->withHeaders($headers)->get($url,$body);

            if($response->status() == 200){

                $corps = json_decode($response->body());
                if( $corps != null){
                // return "TransId = ". $response->body();
                //recupere le code du corps au niveau du client
                $code = $corps->code;
                //vérification du corps

                    $transaction = $corps->transaction;
                    if($code == "200"){
                        Log::channel("sifec")->info(json_encode($corps));
                        return $transaction;
                    }
                }
            }
        }

    }

    // PAYIN FLEX PAY RDC
    public function flexpay($phone, $amount, $transid,$code_paiement_document)
    {
        $endpoint = config("technodev.payment_provider.flexpay.endpoints.pay_uri");
        $token = config("technodev.payment_provider.flexpay.headers.authorization");

        $headers = [
            "Authorization" => $token,
            "Content-Type" => "application/json",
            "Accept" => "application/json"
        ];

        $body = [
            "merchant" => "NOKIPAY",
            "type" => "1",
            "phone" => $phone,
            "reference" => $transid,
            "amount" => $amount,
            "currency" => "USD",
            "callbackUrl" => config("technodev.payment_provider.flexpay.endpoints.callback_url")
        ];

        $response = Http::asJson()->withHeaders($headers)->post($endpoint, $body);

        if($response->status() == 200){
            $responseBody = json_decode($response->body());
            if(is_object($responseBody)){
                try {
                    $payment = new MobileMoneyTransactionDetail;
                    $payment->payer_number = $phone;
                    $payment->amount = $amount;
                    $payment->channel = "OTHER";
                    $payment->invoice_number = $transid;
                    $payment->transaction_token = $responseBody->orderNumber;
                    $payment->status = "pending";
                    $payment->code_paiement_document = $code_paiement_document;
                    $payment->save();
                    // return ["code" => "200", "msg" => "Votre transaction va être traitée"];
                    return $responseBody->transid;

                } catch (Exception $e) {
                    Log::channel("technodev")->info("Paiement Flexpay " . $e->getMessage());
                    return ["code" => "201", "msg" => "Votre paiement va être traité, merci pour votre confiance", "data" => $response->body()];
                }
            }
        }


    }


    // LOGIN to get the TOKEN
    public function flexpayPayoutLogin()
    {
        $flexpayConfig = config("technodev.payment_provider.flexpay");
        $username = $flexpayConfig["actions"]["login"]["username"];
        $password = $flexpayConfig["actions"]["login"]["password"];
        $endpoint = $flexpayConfig["actions"]["login"]["endpoint"];

        $body = [
            "username" => $username,
            "password" => $password
        ];

        try {
            $response = Http::asJson()->post($endpoint, $body);
            if ($response->status() == 200) {
                $retour = json_decode($response->body());

                $token = $retour->token;
                return [
                    "code" => "200",
                    "msg" => ["Token recupéré avec succès"],
                    "token" => $token
                ];
            }
        } catch (Exception $e) {
            Log::channel("technodev")->error("FLEXPAY PAYOUT LOGIN :::: " . $e->getMessage());
            return [
                "code" => "8",
                "msg" => ["Une erreur s'est produite"]
            ];
        }
    }


    //
    public function transact($phone, $amount, $target = "collection", $recipient_account=null,$referenceid = null, $motif = null, $callback_url = null, $successful_url = null, $failed_url = null)
    {
        // if (Str::startsWith($phone, "242")) {
        //     //242055259519
        //     $phone = substr($phone, 3);
        //     if (Str::startsWith($phone, '06')) {
        //         $transid = uniqid("NPMOMO") . time();
        //         return self::momoPay($phone, $amount, $transid, $target,$recipient_account,$referenceid, $motif, $callback_url, $successful_url, $failed_url);
        //     }

        //     if (Str::startsWith($phone, ["04", "05"])) {
        //         $transid = uniqid("NPAM") . time();
        //         return self::ampay($phone, $amount, $transid, $recipient_account,$referenceid, $motif, $callback_url, $successful_url, $failed_url);
        //     }
        // }

        if (Str::startsWith($phone, "243")) {
            //242055259519
            $transid = uniqid("SIFEC") . time();
            $referenceid = uniqid("REF");

            return self::flexpay($phone, $amount, $transid, $referenceid, $motif, $callback_url, $successful_url, $failed_url);

        }
        return ["code" => "190", "msg" => "Numéro de téléphone non supporté"];
    }



    //permet de recuperer le total des recettes annuelles
    public function recetteAnnuelle($periode,$fonction)
    {
        //date par defaut
        $anneeEncours = date("Y");
        //recuperation de la periode
        $debut = explode("-",$periode)[0];
        $fin = explode("-",$periode)[1];

        //formater les dates
        $debutPeriode = date_format(date_create($debut), "Y-m-d");
        $finPeriode = date_format(date_create($fin), "Y-m-d");
        //cas du ministre
        if($fonction == "FONC_0023"){

            if($debutPeriode == $finPeriode){
                $totalRecette = DB::select('select SUM(prix) as total from t_demande_document where YEAR(created_at) = ?',[$anneeEncours]);
            }else{
                $totalRecette = DB::select('select SUM(prix) as total from t_demande_document where created_at BETWEEN ? AND ?',[$debutPeriode,$finPeriode]);
            }

        }
        //cas du gouverneur
        if($fonction == "FONC_0022"){
            //récupération code_localite du gouverneur
           // dd(Auth::user()->affectationActive()->institution->lieu->code_localite);
           $code_loc_parent_gouv =  Auth::user()->affectationActive()->institution->lieu->code_localite_parent;
            //dd($code_loc_parent_gouv);
            if($debutPeriode == $finPeriode){
                // $totalRecette = DB::select('select SUM(prix) as total,l.lib_localite from t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and YEAR(dd.created_at) = ? and l.code_localite_parent = ? GROUP BY l.lib_localite',[$anneeEncours,$code_loc_parent_gouv]);
                $totalRecette = DB::select('select SUM(prix) as total from t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and YEAR(dd.created_at) = ? and l.code_localite_parent = ?',[$anneeEncours,$code_loc_parent_gouv]);
            }else{
                $totalRecette = DB::select('select SUM(prix) as total from t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and l.code_localite_parent = ? AND dd.created_at BETWEEN ? AND ?',[$code_loc_parent_gouv,$debutPeriode,$finPeriode]);
            }


        }
        //cas du bourgmestre
        if($fonction == "FONC_0002"){
             //récupération code_localite bourgmestre
             //$code = Autn:user()->affectation()->lieu->code_localite
             $code_ins_bourg =  Auth::user()->affectationActive()->institution->code_institution;
             //return ($code_ins_bourg);
             if($debutPeriode == $finPeriode){
                 $totalRecette = DB::select('select SUM(prix) as total from t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite AND YEAR(dd.created_at) = ? AND ui.code_institution = ?',[$anneeEncours,$code_ins_bourg]);
             }else{
                 $totalRecette = DB::select('select SUM(prix) as total from t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite AND ui.code_institution = ? AND dd.created_at BETWEEN ? AND ? ',[$code_ins_bourg,$debutPeriode,$finPeriode]);
             }

        }
        //recuperation montant total
        return $totalRecette;
    }

    //permet de recuperer la liste des tops 3 recettes annuelles
    public function topRecettes($periode, $fonction)
    {
        //date par defaut
        $anneeEncours = date("Y");
        //recuperation de la periode
        $debut = explode("-",$periode)[0];
        $fin = explode("-",$periode)[1];

        //formater les dates
        $debutPeriode = date_format(date_create($debut), "Y-m-d");
        $finPeriode = date_format(date_create($fin), "Y-m-d");


        //cas du ministre
        if($fonction == "FONC_0023"){

           if($debutPeriode == $finPeriode){
               $topRecette = DB::select('select SUM(prix) as total, l.code_localite_parent, (select lib_localite FROM tr_localite where code_localite=l.code_localite_parent) as lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND YEAR(dd.created_at) = ? GROUP BY l.code_localite_parent ORDER BY total DESC limit 3',[$anneeEncours]);
           }else{
               $topRecette = DB::select('select SUM(prix) as total, l.code_localite_parent, (select lib_localite FROM tr_localite where code_localite=l.code_localite_parent) as lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite and dd.created_at BETWEEN ? AND ? GROUP BY l.code_localite_parent ORDER BY total DESC limit 3',[$debutPeriode,$finPeriode]);
           }
           $listTopRecette = [];
           foreach ($topRecette as $item) {
                //recuperation de codeCommune de la province
                $codecom =  Localite::where("code_localite",$item->code_localite_parent)->first();
                $listTopRecette[] = array(
                    'libInstitution' => $item->lib_institution,
                    'libProvince' => $codecom->localiteParent->lib_localite,
                    'Prix' => number_format($item->total,2,",","."),
                    'auth' => number_format(0,2,",",".")
                );
           }
           $topRecette = $listTopRecette;

        }
        //cas du gouverneur
        if($fonction == "FONC_0022"){
            //récupération code_localite du gouverneur
            //$code_loc_gouv =  Auth::user()->affectationActive()->institution->lieu->localiteParent->code_localite;
            $code_loc_parent_gouv =  Auth::user()->affectationActive()->institution->lieu->code_localite_parent;
            //dd($code_loc_parent_gouv);

            if($debutPeriode == $finPeriode){
                // $totalRecette = DB::select('select SUM(prix) as total, l.lib_localite as lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where YEAR(dd.created_at) = ? AND dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND l.code_localite_parent = ? GROUP BY l.lib_localite ORDER BY total DESC',[$anneeEncours,$code_loc_gouv]);
                $topRecette = DB::select('select SUM(prix) as total,i.lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND YEAR(dd.created_at) = ? AND l.code_localite_parent = ? GROUP BY i.lib_institution',[$anneeEncours,$code_loc_parent_gouv]);

            }else{
                // $totalRecette = DB::select('select SUM(prix) as total, l.lib_localite as lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND l.code_localite_parent = ? AND dd.created_at BETWEEN ? AND ? GROUP BY l.lib_localite ORDER BY total DESC',[$code_loc_gouv,$debutPeriode,$finPeriode]);
                $topRecette = DB::select('select SUM(prix) as total,i.lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND dd.created_at BETWEEN ? AND ? AND l.code_localite_parent = ? GROUP BY i.lib_institution',[$debutPeriode,$finPeriode,$code_loc_parent_gouv]);
            }

        }
        //cas du bourgmestre :: récupération des recettes par typeDocument
        if($fonction == "FONC_0002"){
            //récupération code_localite bourgmestre
            // $code_loc_bourg =  Auth::user()->affectationActive()->institution->lieu->code_localite;
            $code_ins_bourg =  Auth::user()->affectationActive()->institution->code_institution;


            if($debutPeriode == $finPeriode){
                $topRecette = DB::select('select SUM(prix) as total, l.lib_localite, td.lib_type_document_demande as lib_institution from tr_type_document_demande td,  t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l  where td.code_type_document_demande = dd.code_type_document_demande and ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and ui.code_institution = ? and YEAR(dd.created_at) = ?   GROUP BY td.lib_type_document_demande, l.lib_localite ORDER BY total DESC limit 3',[$code_ins_bourg,$anneeEncours]);
            }else{
                // $topRecette = DB::select('select SUM(prix) as total, l.lib_localite,i.lib_institution from t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite AND ui.code_institution = ? AND dd.created_at BETWEEN ? AND ? GROUP BY l.lib_localite,i.lib_institution',[$code_ins_bourg,$debutPeriode,$finPeriode]);
                $topRecette = DB::select('select SUM(prix) as total, l.lib_localite, td.lib_type_document_demande as lib_institution from tr_type_document_demande td,  t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l  where td.code_type_document_demande = dd.code_type_document_demande and ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and ui.code_institution = ? and dd.created_at BETWEEN ? AND ?  GROUP BY td.lib_type_document_demande, l.lib_localite ORDER BY total DESC limit 3',[$code_ins_bourg,$debutPeriode,$finPeriode]);

            }
        }
        //recuperation montant total
        return $topRecette;
    }


    //permet de recuperer la liste des recettes annuelles par cec
    public function listeRecettesParCec($periode,$fonction)
    {
           //date par defaut
           $anneeEncours = date("Y");
           //recuperation de la periode
           $debut = explode("-",$periode)[0];
           $fin = explode("-",$periode)[1];

           //formater les dates
           $debutPeriode = date_format(date_create($debut), "Y-m-d");
           $finPeriode = date_format(date_create($fin), "Y-m-d");

           //cas du ministre
           if($fonction == "FONC_0023"){

                if($debutPeriode == $finPeriode){
                    $totalRecette = DB::select('select SUM(prix) as total, l.code_localite_parent, (select lib_localite FROM tr_localite where code_localite=l.code_localite_parent) as lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND YEAR(dd.created_at) = ? GROUP BY l.code_localite_parent ORDER BY total DESC limit 3',[$anneeEncours]);
                }else{
                    $totalRecette = DB::select('select SUM(prix) as total, l.code_localite_parent, (select lib_localite FROM tr_localite where code_localite=l.code_localite_parent) as lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite and dd.created_at BETWEEN ? AND ? GROUP BY l.code_localite_parent ORDER BY total DESC limit 3',[$debutPeriode,$finPeriode]);
                }

                $listTopRecette = [];
                foreach ($totalRecette as $item) {
                     //recuperation de codeCommune de la province
                     $codecom =  Localite::where("code_localite",$item->code_localite_parent)->first();
                     $listTopRecette[] = array(
                        'libProvince' => $codecom->localiteParent->lib_localite,
                        'institution' => $item->lib_institution,
                        'total' => $item->total
                     );
                }
                $totalRecette = $listTopRecette;

           }

           //cas du gouverneur
           if($fonction == "FONC_0022"){

            //récupération code_localite du gouverneur
            $code_loc_parent_gouv =  Auth::user()->affectationActive()->institution->lieu->code_localite_parent;

            if($debutPeriode == $finPeriode){
                // $totalRecette = DB::select('select SUM(prix) as total, l.lib_localite as lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where YEAR(dd.created_at) = ? AND dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND l.code_localite_parent = ? GROUP BY l.lib_localite ORDER BY total DESC',[$anneeEncours,$code_loc_gouv]);
                $totalRecette = DB::select('select SUM(prix) as total,i.lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND YEAR(dd.created_at) = ? AND l.code_localite_parent = ? GROUP BY i.lib_institution',[$anneeEncours,$code_loc_parent_gouv]);

            }else{
                // $totalRecette = DB::select('select SUM(prix) as total, l.lib_localite as lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND l.code_localite_parent = ? AND dd.created_at BETWEEN ? AND ? GROUP BY l.lib_localite ORDER BY total DESC',[$code_loc_gouv,$debutPeriode,$finPeriode]);
                $totalRecette = DB::select('select SUM(prix) as total,i.lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND dd.created_at BETWEEN ? AND ? AND l.code_localite_parent = ? GROUP BY i.lib_institution',[$debutPeriode,$finPeriode,$code_loc_parent_gouv]);
            }

           }

       //cas du bourgmestre
       if($fonction == "FONC_0002"){
        //récupération code_institution bourgmestre
            $code_ins_bourg =  Auth::user()->affectationActive()->institution->code_institution;

            if($debutPeriode == $finPeriode){
                $totalRecette = DB::select('select SUM(prix) as total, l.lib_localite, td.lib_type_document_demande as lib_institution from tr_type_document_demande td, t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where td.code_type_document_demande = dd.code_type_document_demande AND ui.cui = dd.cui AND i.code_institution = ui.code_institution and l.code_localite = i.code_localite AND YEAR(dd.created_at) = ? AND i.code_institution = ? GROUP BY td.lib_type_document_demande, l.lib_localite ORDER BY total DESC',[$anneeEncours,$code_ins_bourg]);
            }else{
                $totalRecette = DB::select('select SUM(prix) as total, l.lib_localite, td.lib_type_document_demande as lib_institution from tr_type_document_demande td, t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where td.code_type_document_demande = dd.code_type_document_demande AND ui.cui = dd.cui AND i.code_institution = ui.code_institution and l.code_localite = i.code_localite AND i.code_institution = ? AND dd.created_at BETWEEN ? AND ? GROUP BY td.lib_type_document_demande, l.lib_localite ORDER BY total DESC',[$code_ins_bourg,$debutPeriode,$finPeriode]);
            }
        }

           return $totalRecette;

    }

    //permet de recuperer la liste des recettes par mois pour une annee
    public function listeRecettesParMois($periode, $fonction)
    {
        //date par defaut
        $anneeEncours = date("Y");
        //recuperation de la periode
        $debut = explode("-",$periode)[0];
        $fin = explode("-",$periode)[1];

        //formater les dates
        $debutPeriode = date_format(date_create($debut), "Y-m-d");
        $finPeriode = date_format(date_create($fin), "Y-m-d");

        //cas du ministre
        if($fonction == "FONC_0023"){

            if($debutPeriode == $finPeriode){
                $totalRecette = DB::select('select DISTINCT(MONTH(created_at)) as mois, SUM(prix) as total from t_demande_document where YEAR(created_at) = ? GROUP BY mois',[$anneeEncours]);
            }else{
                $totalRecette = DB::select('select DISTINCT(MONTH(created_at)) as mois, SUM(prix) as total from t_demande_document where created_at BETWEEN ? AND ? GROUP BY mois',[$debutPeriode,$finPeriode]);
            }

        }
        //cas du gouverneur
        if($fonction == "FONC_0022"){
            //récupération code_localite du gouverneur
            //$code_loc_gouv =  Auth::user()->affectationActive()->institution->lieu->localiteParent->code_localite;
            $code_loc_parent_gouv =  Auth::user()->affectationActive()->institution->lieu->code_localite_parent;


            if($debutPeriode == $finPeriode){
                $totalRecette = DB::select('select DISTINCT(MONTH(dd.created_at)) as mois, SUM(prix) as total, l.code_localite_parent from t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and YEAR(dd.created_at) = ? and l.code_localite_parent = ? GROUP BY mois, l.code_localite_parent',[$anneeEncours,$code_loc_parent_gouv]);
            }else{
                $totalRecette = DB::select('select DISTINCT(MONTH(dd.created_at)) as mois, SUM(prix) as total, l.code_localite_parent from t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and l.code_localite_parent = ? and dd.created_at BETWEEN ? AND ? GROUP BY mois, l.code_localite_parent',[$code_loc_parent_gouv,$debutPeriode,$finPeriode]);
            }


        }
        //cas du bourgmestre
        if($fonction == "FONC_0002"){
             //récupération code_institution bourgmestre
             $code_ins_bourg =  Auth::user()->affectationActive()->institution->code_institution;

             if($debutPeriode == $finPeriode){
                 $totalRecette = DB::select('select DISTINCT(MONTH(dd.created_at)) as mois, SUM(prix) as total, l.lib_localite from t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and YEAR(dd.created_at) = ? and i.code_institution = ? GROUP BY mois, l.lib_localite',[$anneeEncours,$code_ins_bourg]);
             }else{
                 $totalRecette = DB::select('select DISTINCT(MONTH(dd.created_at)) as mois, SUM(prix) as total, l.lib_localite from t_demande_document dd, tr_ins_user ui, tr_institution i, tr_localite l where ui.cui = dd.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite AND i.code_institution = ? AND dd.created_at BETWEEN ? AND ? GROUP BY mois, l.lib_localite',[$code_ins_bourg,$debutPeriode,$finPeriode]);
             }

        }
        //recuperation montant total
        //$totalRecette = DB::select('select DISTINCT(MONTH(created_at)) as mois, SUM(prix) as total from t_demande_document where YEAR(created_at) = ? GROUP BY mois ',[$annee]);
        return $totalRecette;
    }

        //statistiques des naissances
     //permet de recuperer le total des recettes annuelles
     public function effectifNaissances($periode,$fonction)
     {
         //date par defaut
         $anneeEncours = date("Y");
         //recuperation de la periode
         $debut = explode("-",$periode)[0];
         $fin = explode("-",$periode)[1];

         //formater les dates
         $debutPeriode = date_format(date_create($debut), "Y-m-d");
         $finPeriode = date_format(date_create($fin), "Y-m-d");
         //cas du ministre
         if($fonction == "FONC_0023"){

             if($debutPeriode == $finPeriode){
                 $totalRecette = DB::select('select COUNT(niupp) as total from t_acte_naissance where YEAR(created_at) = ?',[$anneeEncours]);
             }else{
                 $totalRecette = DB::select('select COUNT(niupp) as total from t_acte_naissance where created_at BETWEEN ? AND ?',[$debutPeriode,$finPeriode]);
             }


         }
         //cas du gouverneur
         if($fonction == "FONC_0022"){
            //récupération code_localite du gouverneur
            // dd(Auth::user()->affectationActive()->institution->lieu->code_localite);
            $code_loc_gouv =  Auth::user()->affectationActive()->institution->lieu->localiteParent->code_localite_parent;
            //dd("sortie ".$code_loc_gouv);
             if($debutPeriode == $finPeriode){
                 $totalRecette = DB::select('select COUNT(niupp) as total, l.code_localite_parent from t_acte_naissance an, t_declaration_naissance dn, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and dn.code_user_institution = ui.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and YEAR(an.created_at) = ? and l.code_localite_parent = ?  GROUP BY l.code_localite_parent',[$anneeEncours,$code_loc_gouv]);
             }else{
                 $totalRecette = DB::select('select COUNT(niupp) as total, l.code_localite_parent from t_acte_naissance an, t_declaration_naissance dn, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and dn.code_user_institution = ui.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and l.code_localite_parent = ? and an.created_at BETWEEN ? AND ? GROUP BY l.code_localite_parent',[$code_loc_gouv,$debutPeriode,$finPeriode]);
             }

         }
         //cas du bourgmestre
         if($fonction == "FONC_0002"){
              //récupération code_localite bourgmestre
              //$code = Autn:user()->affectation()->lieu->code_localite
              $code_ins_bourg =  Auth::user()->affectationActive()->institution->code_institution;
              //dd($code_ins_bourg);
              //return ($code_loc_bourg);
              if($debutPeriode == $finPeriode){
                $totalRecette = DB::select('select COUNT(niupp) as total, i.lib_institution from t_acte_naissance an, tr_ins_user ui, tr_institution i where an.cui = ui.cui and i.code_institution = ui.code_institution and YEAR(an.created_at) = ?  and i.code_institution=? GROUP BY i.lib_institution',[$anneeEncours,$code_ins_bourg]);
            }else{
                $totalRecette = DB::select('select COUNT(niupp) as total, i.lib_institution from t_acte_naissance an, tr_ins_user ui, tr_institution i where an.cui = ui.cui and i.code_institution = ui.code_institution and i.code_institution=?  and an.created_at BETWEEN ? AND ? GROUP BY i.lib_institution',[$code_ins_bourg,$debutPeriode,$finPeriode]);
            }

         }
         //recuperation montant total
         return $totalRecette;
     }


      //permet de recuperer le total des recettes annuelles
      public function effectifNaissancesParMois($periode,$fonction)
      {
          //date par defaut
          $anneeEncours = date("Y");
          //recuperation de la periode
          $debut = explode("-",$periode)[0];
          $fin = explode("-",$periode)[1];

          //formater les dates
          $debutPeriode = date_format(date_create($debut), "Y-m-d");
          $finPeriode = date_format(date_create($fin), "Y-m-d");
          //cas du ministre
          if($fonction == "FONC_0023"){

              if($debutPeriode == $finPeriode){
                  $totalRecette = DB::select('select DISTINCT(MONTH(created_at)) as mois,COUNT(niupp) as total from t_acte_naissance where YEAR(created_at) = ? GROUP BY mois',[$anneeEncours]);
              }else{
                  $totalRecette = DB::select('select DISTINCT(MONTH(created_at)) as mois, COUNT(niupp) as total from t_acte_naissance where created_at BETWEEN ? AND ? GROUP BY mois',[$debutPeriode,$finPeriode]);
              }

          }
          //cas du gouverneur
          if($fonction == "FONC_0022"){
             //récupération code_localite du gouverneur
             // dd(Auth::user()->affectationActive()->institution->lieu->code_localite);
             $code_loc_gouv =  Auth::user()->affectationActive()->institution->lieu->localiteParent->code_localite_parent;
             //dd($code_loc_gouv);
              //dd($code_loc_gouv);
              if($debutPeriode == $finPeriode){
                $totalRecette = DB::select('select DISTINCT(MONTH(an.created_at)) as mois, COUNT(niupp) as total, l.code_localite_parent from t_acte_naissance an, t_declaration_naissance dn, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and dn.code_user_institution = ui.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and YEAR(an.created_at) = ? and l.code_localite_parent = ?  GROUP BY l.code_localite_parent, mois',[$anneeEncours,$code_loc_gouv]);
                }else{
                    $totalRecette = DB::select('select DISTINCT(MONTH(an.created_at)) as mois, COUNT(niupp) as total, l.code_localite_parent from t_acte_naissance an, t_declaration_naissance dn, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and dn.code_user_institution = ui.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and l.code_localite_parent = ? and an.created_at BETWEEN ? AND ? GROUP BY l.code_localite_parent, mois',[$code_loc_gouv,$debutPeriode,$finPeriode]);
                }

          }
          //cas du bourgmestre
          if($fonction == "FONC_0002"){
               //récupération code_localite bourgmestre
               //$code = Autn:user()->affectation()->lieu->code_localite
               $code_ins_bourg =  Auth::user()->affectationActive()->institution->code_institution;
               //return ($code_loc_bourg);
               if($debutPeriode == $finPeriode){
                 $totalRecette = DB::select('select DISTINCT(MONTH(an.created_at)) as mois, COUNT(niupp) as total, i.lib_institution from t_acte_naissance an, tr_ins_user ui, tr_institution i where an.cui = ui.cui and i.code_institution = ui.code_institution and YEAR(an.created_at) = ? and i.code_institution = ?  GROUP BY i.lib_institution, mois',[$anneeEncours,$code_ins_bourg]);
             }else{
                 $totalRecette = DB::select('select DISTINCT(MONTH(an.created_at)) as mois, COUNT(niupp) as total, i.lib_institution from t_acte_naissance an, tr_ins_user ui, tr_institution i where an.cui = ui.cui and i.code_institution = ui.code_institution and i.code_institution = ? and an.created_at BETWEEN ? AND ? GROUP BY i.lib_institution, mois',[$code_ins_bourg,$debutPeriode,$finPeriode]);
             }

          }
          //recuperation montant total
          return $totalRecette;
      }



    //permet de recuperer le total des recettes annuelles
    public function naissanceParSexe($periode,$fonction)
    {
        //date par defaut
        $anneeEncours = date("Y");
        //recuperation de la periode
        $debut = explode("-",$periode)[0];
        $fin = explode("-",$periode)[1];

        //formater les dates
        $debutPeriode = date_format(date_create($debut), "Y-m-d");
        $finPeriode = date_format(date_create($fin), "Y-m-d");
        //cas du ministre
        if($fonction == "FONC_0023"){

            if($debutPeriode == $finPeriode){
                $totalRecette = DB::select('SELECT COUNT(niupp) as total, ip.sexe FROM t_acte_naissance an,t_declaration_naissance dn, tr_identification_personne ip WHERE an.code_declaration_naissance = dn.code_declaration_naissance AND dn.code_enfant = ip.code_personne AND YEAR(an.created_at) = ? GROUP BY ip.sexe',[$anneeEncours]);
            }else{
                $totalRecette = DB::select('SELECT COUNT(niupp) as total, ip.sexe FROM t_acte_naissance an,t_declaration_naissance dn, tr_identification_personne ip WHERE an.code_declaration_naissance = dn.code_declaration_naissance AND dn.code_enfant = ip.code_personne AND an.created_at BETWEEN ? AND ? GROUP BY ip.sexe',[$debutPeriode,$finPeriode]);
            }

        }
        //cas du gouverneur
        if($fonction == "FONC_0022"){
            //récupération code_localite du gouverneur
            // dd(Auth::user()->affectationActive()->institution->lieu->code_localite);
            $code_loc_gouv =  Auth::user()->affectationActive()->institution->lieu->localiteParent->code_localite_parent;
            //dd($code_loc_gouv);
            //dd($code_loc_gouv);
            if($debutPeriode == $finPeriode){
                $totalRecette = DB::select('select COUNT(niupp) as total, l.lib_localite, ip.sexe from t_acte_naissance an, t_declaration_naissance dn, tr_identification_personne ip, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and ip.code_personne = dn.code_enfant and dn.code_user_institution = ui.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and YEAR(an.created_at) = ? and l.code_localite_parent = ?  GROUP BY l.lib_localite, ip.sexe',[$anneeEncours,$code_loc_gouv]);
            }else{
                $totalRecette = DB::select('select COUNT(niupp) as total, l.lib_localite, ip.sexe from t_acte_naissance an, t_declaration_naissance dn, tr_identification_personne ip, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and ip.code_personne = dn.code_enfant and dn.code_user_institution = ui.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and l.code_localite_parent = ? and an.created_at BETWEEN ? AND ? GROUP BY l.lib_localite, ip.sexe',[$code_loc_gouv,$debutPeriode,$finPeriode]);
            }


        }
        //cas du bourgmestre
        if($fonction == "FONC_0002"){
            //récupération code_localite bourgmestre
            //$code = Autn:user()->affectation()->lieu->code_localite
            $code_ins_bourg =  Auth::user()->affectationActive()->institution->code_institution;
            //return ($code_loc_bourg);
            if($debutPeriode == $finPeriode){
            $totalRecette = DB::select('select COUNT(niupp) as total, i.lib_institution, ip.sexe from t_acte_naissance an, t_declaration_naissance dn, tr_identification_personne ip, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and an.cui = ui.cui and ip.code_personne = dn.code_enfant and i.code_institution = ui.code_institution and YEAR(an.created_at) = ? and i.code_institution = ?  GROUP BY i.lib_institution, ip.sexe',[$anneeEncours,$code_ins_bourg]);
            }else{
                $totalRecette = DB::select('select COUNT(niupp) as total, i.lib_institution, ip.sexe from t_acte_naissance an, t_declaration_naissance dn, tr_identification_personne ip, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and an.cui = ui.cui and ip.code_personne = dn.code_enfant and i.code_institution = ui.code_institution and i.code_institution = ? and an.created_at BETWEEN ? AND ? GROUP BY i.lib_institution, ip.sexe',[$code_ins_bourg,$debutPeriode,$finPeriode]);
            }

        }
        //recuperation montant total
        return $totalRecette;
    }

    public function naissanceParCec($periode,$fonction)
    {
        //date par defaut
        $anneeEncours = date("Y");
        //recuperation de la periode
        $debut = explode("-",$periode)[0];
        $fin = explode("-",$periode)[1];

        //formater les dates
        $debutPeriode = date_format(date_create($debut), "Y-m-d");
        $finPeriode = date_format(date_create($fin), "Y-m-d");
        //cas du ministre
        if($fonction == "FONC_0023"){

            if($debutPeriode == $finPeriode){
                $totalRecette = DB::select('SELECT COUNT(niupp) as total,l.code_localite_parent, (SELECT lib_localite FROM tr_localite where code_localite = l.code_localite_parent ) as lib_localite FROM t_acte_naissance an,t_declaration_naissance dn, tr_ins_user iu,tr_localite l, tr_institution i WHERE an.code_declaration_naissance = dn.code_declaration_naissance AND dn.code_user_institution = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND YEAR(an.created_at) = ? AND l.code_localite_parent in (select code_localite from tr_localite where code_type_localite="TPLOC_0001") GROUP BY l.code_localite_parent',[$anneeEncours]);
            }else{
                $totalRecette = DB::select('SELECT COUNT(niupp) as total,l.code_localite_parent, (SELECT lib_localite FROM tr_localite where code_localite = l.code_localite_parent ) as lib_localite FROM t_acte_naissance an,t_declaration_naissance dn, tr_ins_user iu,tr_localite l, tr_institution i WHERE an.code_declaration_naissance = dn.code_declaration_naissance AND dn.code_user_institution = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND l.code_localite_parent in (select code_localite from tr_localite where code_type_localite="TPLOC_0001") AND an.created_at BETWEEN ? AND ? GROUP BY l.code_localite_parent',[$debutPeriode,$finPeriode]);
            }

        }
        //cas du gouverneur
        if($fonction == "FONC_0022"){
            //récupération code_localite du gouverneur
            // dd(Auth::user()->affectationActive()->institution->lieu->code_localite);
            $code_loc_gouv =  Auth::user()->affectationActive()->institution->lieu->localiteParent->code_localite_parent;
            //dd($code_loc_gouv);
            //dd($code_loc_gouv);
            if($debutPeriode == $finPeriode){
                $totalRecette = DB::select('select COUNT(niupp) as total, l.lib_localite from t_acte_naissance an, t_declaration_naissance dn, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and dn.code_user_institution = ui.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and YEAR(an.created_at) = ? and l.code_localite_parent = ?  GROUP BY l.lib_localite',[$anneeEncours,$code_loc_gouv]);
            }else{
                $totalRecette = DB::select('select COUNT(niupp) as total, l.lib_localite from t_acte_naissance an, t_declaration_naissance dn, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and dn.code_user_institution = ui.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and l.code_localite_parent = ? and an.created_at BETWEEN ? AND ? GROUP BY l.lib_localite',[$code_loc_gouv,$debutPeriode,$finPeriode]);
            }


        }
        //cas du bourgmestre
        if($fonction == "FONC_0002"){
            //récupération code_localite bourgmestre
            //$code = Autn:user()->affectation()->lieu->code_localite
            $code_loc_bourg =  Auth::user()->affectationActive()->institution->lieu->code_localite;
            //return ($code_loc_bourg);
            if($debutPeriode == $finPeriode){
                $totalRecette = DB::select('select COUNT(niupp) as total, l.lib_localite from t_acte_naissance an, t_declaration_naissance dn, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and dn.code_user_institution = ui.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and YEAR(an.created_at) = ? and l.code_localite = ?  GROUP BY l.lib_localite',[$anneeEncours,$code_loc_bourg]);
            }else{
                $totalRecette = DB::select('select COUNT(niupp) as total, l.lib_localite from t_acte_naissance an, t_declaration_naissance dn, tr_ins_user ui, tr_institution i, tr_localite l where an.code_declaration_naissance = dn.code_declaration_naissance and dn.code_user_institution = ui.cui and i.code_institution = ui.code_institution and l.code_localite = i.code_localite and l.code_localite = ? and an.created_at BETWEEN ? AND ? GROUP BY l.lib_localite',[$code_loc_bourg,$debutPeriode,$finPeriode]);
            }

        }
        //recuperation montant total
        return $totalRecette;
    }

    //permet de recuperer les statistiques de la carte
    public function statCarte($fonction,$libProvince)
    {
        //annee en cours par defaut
        $anneeEncours = date("Y");
        // $code_loc = Localite::where("lib_localite", $libProvince)->where("code_type_localite","TPLOC_0001")->first()->code_localite;
        // $code_loc = Localite::where("lib_localite", $libProvince)->where("code_type_localite","TPLOC_0001")->first()->code_localite;
        // $code_loc_parent = Localite
        // $topRecette = DB::select('select SUM(prix) as total, l.lib_localite as lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where YEAR(dd.created_at) = ? AND dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND l.code_localite_parent = ? GROUP BY l.lib_localite',[$anneeEncours,$code_loc]);
        $topRecette = DB::select('select SUM(prix) as total,i.lib_institution from t_demande_document dd, tr_ins_user iu, tr_institution i,tr_localite l where dd.cui = iu.cui AND iu.code_institution = i.code_institution AND i.code_localite = l.code_localite AND YEAR(dd.created_at) = ? AND l.code_localite_parent = ? GROUP BY i.lib_institution',[$anneeEncours,"LOC_4259"]);


        $listTopRecette = [];
        foreach ($topRecette as $item) {
             //recuperation de codeCommune de la province
             //$codecom =  Localite::where("code_localite",$item->code_localite)->first();
             $listTopRecette[] = array(
                //  'libInstitution' => $item->lib_institution,
                //  'libProvince' => $codecom->localiteParent->lib_localite,
                //  'Prix' => number_format($item->total,2,",","."),
                //  'auth' => number_format(0,2,",",".")

                'libInstitution' =>$item->lib_institution,
                'Prix' => "$ ".number_format($item->total,2,",","."),
                'auth' => number_format(0,2,",",".")

                );
        }
        $topRecette = $listTopRecette;



        return $topRecette;
    }

    //Cette fonction permet de rechercher l'identité d'une personne à partir de son numero d'acte de naissance
    public function rechercheIdentite($niupp)
    {
        $personne = self::rechercherPersonne($niupp);

        if (!$personne) {
            return response()->json([
                "code" => "99",
                "message" => "Aucun numéro d'acte trouvé"
            ]);
        }

        $declaration = $personne->declaration;
        $enfant = $declaration->enfant ?? null;
        $pere = $declaration->pere ?? null;
        $mere = $declaration->mere ?? null;
        $institution = $personne->institutionUser->institution ?? null;

        return response()->json([
            "code" => "200",
            "nom" => $enfant->nom ?? null,
            "prenom" => $enfant->prenom ?? null,
            "sexe" => $enfant->sexe ?? null,
            "date_heure_naissance" => isset($declaration->date_heure_naissance) ? date("H:i", strtotime($declaration->date_heure_naissance)) : null,
            "date_heure_declaration" => isset($declaration->date_heure_declaration) ? date("Y-m-d", strtotime($declaration->date_heure_declaration)) : null,
            "date_naissance" => $enfant->date_naissance ?? null,
            "code_lieu_survenance" => $declaration->lieuSurvenance->code_lieu_survenance ?? null,
            "code_situation_matrimoniale" => $declaration->sitMatParent->code_situation_matrimoniale ?? null,
            "numero_ancien_acte" => $niupp,
            "lieu_naissance" => $enfant->code_localite ?? null,
            "lib_lieu_naissance" => $enfant->localite->lib_localite ?? null,
            "code_nationalite" => $enfant->code_nationalite ?? null,
            "dateEmisAN" => $enfant->date_naissance ?? null,
            "cec_naissance" => $institution->lib_institution ?? null,
            "code_cec_naissance" => $institution->code_institution ?? null,
            "code_profession" => $enfant->profession->code_profession ?? null,
            "pere" => $pere ? $pere->nomcomplet() : null,
            "mere" => $mere ? $mere->nomcomplet() : null,
            "nom_mere" => $mere->nom ?? null,
            "prenom_mere" => $mere->prenom ?? null,
            "nombre_enfant" => $declaration->nombre_enfant ?? null,
            "date_heure_naissance_mere" => isset($declaration->date_heure_naissance) ? date("H:i", strtotime($declaration->date_heure_naissance)) : null,
            "date_heure_declaration_mere" => isset($declaration->date_heure_declaration) ? date("Y-m-d", strtotime($declaration->date_heure_declaration)) : null,
            "date_naissance_mere" => $mere->date_naissance ?? null,
            "code_lieu_survenance_mere" => $declaration->lieuSurvenance->code_lieu_survenance ?? null,
            "code_situation_matrimoniale_mere" => $declaration->sitMatParent->code_situation_matrimoniale ?? null,
            "lieu_naissance_mere" => $mere->code_localite ?? null,
            "code_nationalite_mere" => $mere->nationalite->lib_nationalite ?? null,
            "code_profession_mere" => $mere->profession->code_profession ?? null
        ]);
    }


    //rechercher un acte à partier de son code d'acte codeActe et le type d'acte typeActe
    public function rechercherActe($typeActe, $numeroActe)
    {
        if($typeActe == "TPA_0001"){
            return ActeNaissance::where("niupp", $numeroActe)->first() ?? null;
        }
        if($typeActe == "TPA_0002"){
            return ActeMariage::where("code_acte_mariage", $numeroActe)->first() ?? null;
        }
        if($typeActe == "TPA_0003"){
            return ActeDeces::where("code_acte_deces", $numeroActe)->first() ?? null;
        }
    }

    //rechercher une acte à partier du code de l'acte simple
    public function getActe($numeroActe)
    {
        return  ActeNaissance::where("niupp", $numeroActe)->first() ?? ActeMariage::where("code_acte_mariage", $numeroActe)->first() ?? ActeDeces::where("code_acte_deces", $numeroActe)->first() ?? null;
    }
    //rechercher code declaration d'un acte à partier du code de l'acte simple
    public function getDeclaration($numeroActe)
    {
        $codeDeclaration = null;

        $acte = self::getActe($numeroActe);
        if($acte){
            if($acte instanceof ActeNaissance){
                $codeDeclaration = $acte->code_declaration_naissance;
            }elseif($acte instanceof ActeMariage){
                $codeDeclaration = $acte->code_declaration_mariage;
            }elseif($acte instanceof ActeDeces){
                $codeDeclaration = $acte->code_declaration_deces;
            }
        }
        return $codeDeclaration;
    }

    public static function genererNiupp(string $codeDn)
    {
        // $cec =
        $dn = Declarationnaissance::find($codeDn);

        if($dn == null) return "Aucune déclaration naissance trouvée";



            $dept = $dn->institutionUser->institution->lieu->localiteParent->localiteParent ?? $dn->institutionUser->institution->lieu->localiteParent;
            $codeDept = $dept->code_officel;
            $institution = $dn->institution;
            $codeCec = $institution->lieu->localiteParent->typeLocalite->type_cec;
            $codeLoc = $institution->lieu->localiteParent->code_officel;

            $sexe = $dn->enfant->sexe == 'M' ? "1" : "2";
            $anneeNais = new Carbon($dn->enfant->date_naissance);
            $annee = $anneeNais->year;
            $mois = $anneeNais->format('m');
            $position = substr($dn->code_declaration_naissance,7);
            $numOrdre = SifecFacade::format_nombre($position,4);
            // $niupp = $sexe.$annee.$mois.$dpt.$subdpt.$numOrdre;
            $niupp = $sexe.$annee.$mois.$codeDept.$codeLoc.$codeCec.$numOrdre;

            return $niupp;

    }

    /**
     * Récupère les informations de localisation d'une institution
     * en utilisant le système unifié de localités
     *
     * @param Institution $institution L'institution dont on veut obtenir la localisation
     * @return array Tableau contenant:
     *   - 'localite' : Libellé de la localité (ex: "COMMUNE DE BRAZZAVILLE")
     *   - 'localiteParent' : Libellé du département parent (ex: "DEPARTEMENT DE POOL")
     *   - 'inst' : Nom de l'institution
     *   - 'localisation' : Nom de la localisation à utiliser (ex: "BRAZZAVILLE")
     *   - 'departement' : Objet Localite du département (peut être null)
     *   - 'lieu' : Objet Localite de l'institution (peut être null)
     */
    public static function getLocalisationInstitution($institution)
    {
        $localite = "";
        $localiteParent = "";
        $inst = "";
        $localisation = "";
        $departement = null;
        $lieu = null;

        // Initialiser les variables par défaut
        if ($institution && $institution->lieu) {
            $lieu = $institution->lieu;
            $inst = $institution->lib_institution;

            // Remonter la hiérarchie pour obtenir le département
            $current = $lieu;
            while ($current && $current->code_type_localite !== 'TPLOC_0001') {
                $current = $current->localiteParent;
            }
            $departement = $current;

            // Déterminer le type de localité et construire les libellés appropriés
            $typeLocalite = $lieu->typelocalite->lib_type_localite ?? "";

            switch ($lieu->code_type_localite) {
                case 'TPLOC_0001': // DÉPARTEMENT
                    $localite = "DEPARTEMENT DE " . $lieu->lib_localite;
                    $localisation = $lieu->lib_localite;
                    break;

                case 'TPLOC_0002': // DISTRICT
                    $localite = "DISTRICT DE " . $lieu->lib_localite;
                    if ($departement) {
                        $localiteParent = "DEPARTEMENT DE " . $departement->lib_localite;
                    }
                    $localisation = $lieu->lib_localite;
                    break;

                case 'TPLOC_0003': // COMMUNE
                    $localite = "COMMUNE DE " . $lieu->lib_localite;
                    if ($departement) {
                        $localiteParent = "DEPARTEMENT DE " . $departement->lib_localite;
                    }
                    $localisation = $lieu->lib_localite;
                    break;

                case 'TPLOC_0004': // ARRONDISSEMENT
                    // Pour un arrondissement, on affiche la commune parent et le département
                    if ($lieu->localiteParent) {
                        $commune = $lieu->localiteParent;
                        $localite = "COMMUNE DE " . $commune->lib_localite;
                        if ($departement) {
                            $localiteParent = "DEPARTEMENT DE " . $departement->lib_localite;
                        }
                        $localisation = $commune->lib_localite;
                    } else {
                        $localisation = $lieu->lib_localite;
                    }
                    break;

                default:
                    // Pour les autres types (COMMUNAUTE URBAINE, COMMUNAUTE RURALE, etc.)
                    $localisation = $lieu->lib_localite;
                    if ($lieu->localiteParent) {
                        $parentType = $lieu->localiteParent->typelocalite->lib_type_localite ?? "";
                        $localite = strtoupper($parentType) . " DE " . $lieu->localiteParent->lib_localite;
                        if ($departement) {
                            $localiteParent = "DEPARTEMENT DE " . $departement->lib_localite;
                        }
                    }
                    break;
            }

            // Si aucune localisation n'a été trouvée, utiliser le lib_institution comme fallback
            if (empty($localisation)) {
                $localisation = $inst;
            }
        } else {
            // Si l'institution n'a pas de localité, utiliser son nom comme fallback
            if ($institution) {
                $inst = $institution->lib_institution;
                $localisation = $inst;
            }
        }

        return [
            'localite' => $localite,
            'localiteParent' => $localiteParent,
            'inst' => $inst,
            'localisation' => $localisation,
            'departement' => $departement,
            'lieu' => $lieu
        ];
    }


}
