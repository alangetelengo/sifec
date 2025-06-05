<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Mariage\Entities\Signature;

class SignatureController extends Controller
{

    public function upload(Request $request){
        $donnees = $request->all();

        // Log::channel("sifec")->info($donnees);

        $numeroActe = $request->numero_acte;
        $etat = $request->etat;
        $am = ActeMariage::find($numeroActe);
        //verification de l'acte
        if($etat == 1){

            if($am == null){
                return response()->json([
                    "etat"=> "Non trouvé"
                ]);

            }else{
                //vérifier si l'acte est déjà signé
                $signature = Signature::where('code_declaration_mariage',$am->code_declaration_mariage)
                                        ->where("signature_epoux","!=",null)
                                        ->where("signature_temoin_premier_epoux","!=",null)
                                        ->where("signature_temoin_deuxieme_epoux","!=",null)
                                        ->where("signature_epouse","!=",null)
                                        ->where("signature_temoin_premier_epouse","!=",null)
                                        ->where("signature_temoin_deuxieme_epouse","!=",null)
                                        ->first();
                if($signature != null){
                    return response()->json([
                        "etat"=> "Trouvé et signé"
                    ]);
                }
                return response()->json([
                    "etat"=> "Trouvé"
                ]);
            }

        }
        //enregistrement de la signature
        if($etat == 2){


            $msg = "";

            if(isset($request->signature_epoux)){
                $signatureEpoux =  $request->signature_epoux;

                 //vérification des signatures
                $signExist = Signature::where('code_declaration_mariage',$am->code_declaration_mariage)->first();
                //vérification de la signature de l'époux
                if($signExist == null){
                    //enregistre de la premiere signature donc epoux
                    $signature = new Signature;
                    $signature->code_signature_mariage = Sifec::genererCodeUniqueReferentiel(new Signature(),"code_signature_mariage",4,"CSM_");
                    $signature->code_declaration_mariage = $am->code_declaration_mariage;
                    $signature->signature_epoux = $signatureEpoux;
                    if($signature->save()){

                        $msg = "traitement réussi";
                    }else{
                        $msg = "traitement échoué";
                    }
                }else{
                    //update de la signature de l'époux
                    $signExist->signature_epoux = $signatureEpoux;
                    $signExist->save();
                    $msg = "traitement réussi";
                }


            }
            if(isset($request->signature_epouse)){
                //recuperation de la signature courante
                $lastSign = Signature::where("code_declaration_mariage",$am->code_declaration_mariage)->first();
                //update signature epouse
                $lastSign->signature_epouse = $request->signature_epouse;
                if($lastSign->save()){

                    $msg = "traitement réussi";
                }else{
                    $msg = "traitement échoué";
                }
            }
            if(isset($request->signature_temoin_premier_epoux)){
                //recuperation de la signature courante
                $lastSign = Signature::where("code_declaration_mariage",$am->code_declaration_mariage)->first();
                //update signature temoin premier epoux
                $lastSign->signature_temoin_premier_epoux = $request->signature_temoin_premier_epoux;
                if($lastSign->save()){

                    $msg = "traitement réussi";
                }else{
                    $msg = "traitement échoué";
                }
            }

            if(isset($request->signature_temoin_deuxieme_epoux)){
                //recuperation de la signature courante
                $lastSign = Signature::where("code_declaration_mariage",$am->code_declaration_mariage)->first();
                //update signature temoin deuxieme epoux
                $lastSign->signature_temoin_deuxieme_epoux = $request->signature_temoin_deuxieme_epoux;
                if($lastSign->save()){

                    $msg = "traitement réussi";
                }else{
                    $msg = "traitement échoué";
                }
            }
            if(isset($request->signature_temoin_premier_epouse)){
                //recuperation de la signature courante
                $lastSign = Signature::where("code_declaration_mariage",$am->code_declaration_mariage)->first();
                //update signature temoin premier epouse
                $lastSign->signature_temoin_premier_epouse = $request->signature_temoin_premier_epouse;
                if($lastSign->save()){

                    $msg = "traitement réussi";
                }else{
                    $msg = "traitement échoué";
                }
            }



            if(isset($request->signature_temoin_deuxieme_epouse)){
                //recuperation de la signature courante
                $lastSign = Signature::where("code_declaration_mariage",$am->code_declaration_mariage)->first();
                //update signature temoin deuxieme epouse
                $lastSign->signature_temoin_deuxieme_epouse = $request->signature_temoin_deuxieme_epouse;
                if($lastSign->save()){

                    $msg = "traitement réussi";
                }else{
                    $msg = "traitement échoué";
                }
            }


            return response()->json([
                "message"=> $msg
                //"traitement réussi"
            ]);



            // $exists = [];
            // $reussies = [];
            // $echecs = [];
            // if($donnees > 0){
            //     foreach($donnees as $donnee){
            //         $sm = Signature::where("code_declaration_mariage",$donnee["code_declaration_mariage"])->first();
            //         if($sm == null){

            //             try {

            //                 $code = Sifec::genererCodeUniqueReferentiel(new Signature(),"code_signature_mariage",4,"CSM_");
            //                 $donnee["code_signature_mariage"] = $code;
            //                 Signature::create($donnee);


            //                 $reussies+=$donnee;
            //             }catch (Exception $e) {
            //                 Log::channel("sifec")->info($e->getMessage());
            //                 array_push($echecs,$e->getMessage());
            //             }
            //         }else{
            //             $exists+=$donnee;
            //         }
            //     }
            // }
            // return response()->json([
            //     // Log::channel("sifec")->error()
            //     "message"=>"traitement réussie",
            //     "reussies"=>count($reussies),
            //     "existes"=>count($exists),
            //     "echecs"=>count($echecs)
            // ]);
        }
    }
}
