<?php

namespace App\Technodev;


use Exception;
use App\Sifec\Sifec;
use Illuminate\Support\Str;
use App\Models\PaiementTaxe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Modules\Deces\Entities\ActeDeces;
use Modules\Notification\Jobs\SendSmsJob;
use Modules\Mobile\Entities\PaiementDetail;
use Modules\Mobile\Entities\DemandeDocument;
use Modules\Mobile\Entities\PaiementDocument;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Mobile\Entities\DetailDemandeDocument;

class TechnoDev {

    public function momoLoginToken(){
        $encoded = self::getEncodedBasicString();
        $headers = [
            "Authorization"=>"Basic ".$encoded,
            "Ocp-Apim-Subscription-Key"=>config('technodev.payment_provider.momo.headers.token.ocp_apim_subscription_key'),
            "Content-Type"=>"application/json"
        ];

        $response = Http::withHeaders($headers)->post(config('technodev.payment_provider.momo.endpoints.token_uri'));
        if($response->status() == 200){
            $data = json_decode($response->body());
            $token = $data->access_token;
            return $token;
        }
        Log::channel("technodev")->debug(json_decode($response->body()));
        return null;
    }

    public function getEncodedBasicString():string{
        $user_key = config('technodev.payment_provider.momo.headers.api_keys.user_id');
        $api_key = config('technodev.payment_provider.momo.headers.api_keys.api_key');
        return base64_encode($user_key.":".$api_key);
    }

    /**
     * @params (array $data) array(amount,invoice_code,number,payer_message,payee_message)
     */
    public function momoPay(array $data){
        $token = self::momoLoginToken();

        if($token == null){
            Log::channel("technodev")->error("erreur lors de la récupération du token");
            return ["code"=>"180","msg"=>"Une erreur est survenue lors du traitement"];
        }

        $headers = [
            "Authorization"=>"Bearer ".$token,
            "Ocp-Apim-Subscription-Key"=>config('technodev.payment_provider.momo.headers.token.ocp_apim_subscription_key'),
            "Content-Type"=>"application/json",
            "X-Reference-Id"=>$data["uui4"],
            "X-Target-Environment"=>config('technodev.payment_provider.momo.headers.token.x_target_environment'),
            "Accept"=>"application/json"
        ];

        $params = [
            "amount"=>$data["amount"],
            "currency"=>"XAF",
            "externalId"=>$data["invoice_code"],
            "payer"=>[
                "partyIdType"=>"MSISDN",
                "partyId"=>"242".$data["number"]
            ],
            "payerMessage"=>$data["payer_message"],
            "payeeNote"=>$data["payee_message"]
        ];

        $response = Http::asJson()->withHeaders($headers)->post(config('technodev.payment_provider.momo.endpoints.pay_uri'),$params);

        if($response->status() >= 200 && $response->status() < 210){
            $body = $response->body();
            Log::channel("technodev")->info($body);
            DB::beginTransaction();
            try{
                $p = PaiementDetail::create([
                    "payer_number"=>$data["number"],
                    "invoice_code"=>$data["invoice_code"],
                    "x_reference_id"=>$data["uui4"],
                    "code_demande_document"=>$data["commande_id"],
                    "total_amount"=>$data["amount"],
                    "payment_methode"=>"Mobile Money"
                ]);
                DB::commit();
                if($p instanceof PaiementDetail){
                    return ["code"=>"200","msg"=>"Votre paiement va être traité, merci pour votre confiance"];
                }
            }catch(Exception $e){
                DB::rollBack();
                Log::channel("sifec")->info("Paiement Mobile Money ".$e->getMessage());
                return ["code"=>"200","msg"=>"Votre paiement va être traité, merci pour votre confiance","data"=>$response->body()];
            }
            return ["code"=>"200","msg"=>"Votre paiement va être traité, merci pour votre confiance"];
        }
        Log::channel("sifec")->debug("Une erreur est survenue lors du paiement de la commande ".$data["invoice_code"]);
        return ["code"=>"201","msg"=>"Une erreur est survenue lors du traitement"];
    }

    public function getNonConfirmes(){
        $demande = DemandeDocument::where("statut","En traitement")->first();

        if($demande != null){
            if($demande->code_type_acte == "TAC_0001"){
                $acteNaissance = ActeNaissance::where("code_declaration_naissance",$demande->numero_acte_demande)->first();
                if($acteNaissance != null){
                    if(self::updatePayment($demande,$acteNaissance,"code_declaration_naissance")){
                        return ["code"=>"200","msg"=>"Commande mise à jour avec succès"];
                    }
                }
            }

            if($demande->code_type_acte == "TAC_0002"){
                $acteDeces = ActeDeces::where("code_declaration_deces",$demande->numero_acte_demande)->first();
                if($acteDeces != null){
                    if(self::updatePayment($demande,$acteDeces,"code_declaration_deces")){
                        return ["code"=>"200","msg"=>"Commande mise à jour avec succès"];
                    }
                }
            }
        }
    }

    public function updatePayment(DemandeDocument $commande,$model,$field){
        if($commande != null){
            $pd = PaiementDetail::where("code_demande_document",$commande->code_demande_document)->first();
            if($pd instanceof PaiementDetail){
                $res = self::getStatus($pd->x_reference_id);
                $pd->extra_col_2 = $res["data"]->financialTransactionId;

                $status = $res["data"]->status;

                if($status=="SUCCESSFUL"){
                    $pd->statut_payment = "success";
                    $pd->save();
                    $commande->statut = "Traité";
                    $commande->save();
                    self::generateLink($commande,$model,$field);
                    return true;
                }
                if($status=="FAILED"){
                    $pd->statut_payment = "failed";
                    $pd->save();
                    $commande->statut = "Réjeté";
                    $commande->save();
                    return true;
                }
            }
        }
    }

   public function generateLink(DemandeDocument $demandeDocument,$acte,$field){
        $lien_telechargement = "";
        if($demandeDocument != null){

           try {
                $detailDocument = new DetailDemandeDocument;
                $code = Sifec::genererCodeUniqueReferentiel($detailDocument,"code_detail_demande_document",4,"DDD_");
                $detailDocument->code_demande_document = $demandeDocument->code_demande_document;
                $detailDocument->code_detail_demande_document = $code;
                $otp = substr(time(),4);
                $detailDocument->code_otp = $otp;
                //Lien naissance copie et duplicata
                if($demandeDocument->code_type_acte == "TAC_0001" && $demandeDocument->code_type_document_demande == "TDD_0001"){

                    if(env("ENV") == "local"){
                        $lien_telechargement = env("LIEN_LOCAL_DEMANDE_COPIE_ACTE_NAISSANCE");
                        $lien_telechargement = str_replace(":id",$acte->$field,$lien_telechargement);
                    }else{
                        $lien_telechargement = env("LIEN_EN_LIGNE_DEMANDE_COPIE_ACTE_NAISSANCE");
                        $lien_telechargement = str_replace(":id",$acte->$field,$lien_telechargement);
                    }
                }
                if($demandeDocument->code_type_acte == "TAC_0001" && $demandeDocument->code_type_document_demande == "TDD_0002"){
                    if(env("ENV") == "local"){
                        $lien_telechargement = env("LIEN_LOCAL_DEMANDE_DUPLICATA_ACTE_NAISSANCE");
                        $lien_telechargement = str_replace(":id",$acte->$field,$lien_telechargement);
                    }else{
                        $lien_telechargement = env("LIEN_EN_LIGNE_DEMANDE_DUPLICATA_ACTE_NAISSANCE");
                        $lien_telechargement = str_replace(":id",$acte->$field,$lien_telechargement);
                    }
                }
                // //Lien décès copie et duplicata
                // if($demandeDocument->code_type_acte == "TAC_0002" && $demandeDocument->code_type_document_demande == "TDD_0001"){
                //     $lien_telechargement = route("demande.acteNaissance.display",$acte->niupp);
                // }
                // if($demandeDocument->code_type_acte == "TAC_0002" && $demandeDocument->code_type_document_demande == "TDD_0002"){
                //     $lien_telechargement = route("demande.ActeNaissance.generate.duplicata",$acte->niupp);
                // }
                $detailDocument->lien_telechargement = $lien_telechargement;
                $detailDocument->nombre_telechargement = 0;
                $detailDocument->save();
                // TEMPLATE SMS POUR TELECHARGER LE DOCUMENT
                $template = config("sifec.sms.templates.actions.demande_document");
                $template = str_replace(":nom_demandeur",$demandeDocument->nom_demandeur,$template);
                $template = str_replace(":type_document",$demandeDocument->typeDocumentDemande->lib_type_document_demande,$template);
                $template = str_replace(":type_acte",$demandeDocument->typeActe->lib_type_acte,$template);
                $template = str_replace(":code_otp",$otp,$template);
                // $template = str_replace(":lien",$lien_telechargement,$template);
                dispatch(new SendSmsJob($demandeDocument->telephone_demander,$template));
           } catch (Exception $e) {
                Log::channel("sifec")->error($e->getMessage());
           }

            return true;

        }
    }



    public function getStatus($reference){
        $token = self::momoLoginToken();

        if($token == null){
            Log::channel("sifec")->error("erreur lors de la récupération du token");
            return ["code"=>"180","msg"=>"Une erreur est survenue lors du traitement"];
        }

        $headers = [
            "Authorization"=>"Bearer ".$token,
            "Ocp-Apim-Subscription-Key"=>config('technodev.payment_provider.momo.headers.token.ocp_apim_subscription_key'),
            "Content-Type"=>"application/json",
            "X-Reference-Id"=>$reference,
            "X-Target-Environment"=>config('technodev.payment_provider.momo.headers.token.x_target_environment'),
            "Accept"=>"application/json"
        ];

        $response = Http::withHeaders($headers)->get(config('technodev.payment_provider.momo.endpoints.pay_status').$reference);
        if($response->status() > 199 && $response->status() < 210){
            $body = $response->body();
            Log::channel("sifec")->info($body);
            return ["code"=>"200","data"=>json_decode($body)];
        }
    }

    public function ampay(array $donnee){
        $endpoint = config('technodev.payment_provider.airtel.endpoints.pay_uri');
        $transid = uniqid("GC");
        $data = [
            'merchantID'=>config('technodev.payment_provider.airtel.login_data.merchant_id'),
            'merchantPWD'=>config('technodev.payment_provider.airtel.login_data.merchant_pass'),
            'transID'=>$transid,
            'amount'=>$donnee["amount"],
            'action'=>config('technodev.payment_provider.airtel.static_params.action'),
            'msisdn'=>$donnee["number"],
            'callbackUrl'=>config('technodev.payment_provider.airtel.endpoints.callback_url')
        ];
        $response = Http::asForm()->post($endpoint,$data);
        if($response->status()==200){
            $body = (string) $response->body();
            $cut = explode(",",$body);
            $token = trim($cut[2]);
            DB::beginTransaction();
            try{
                $p = PaiementDetail::create([
                    "payer_number"=>"242".$donnee["number"],
                    "invoice_code"=>$donnee["invoice_code"],
                    "x_reference_id"=>$token,
                    "code_demande_document"=>$donnee["code_demande_document"],
                    "total_amount"=>$donnee["amount"],
                    "payment_methode"=>"Airtel Money"
                ]);
                DB::commit();
                if($p instanceof PaiementDetail){
                    return ["code"=>"200","msg"=>"Votre paiement va être traité, merci pour votre confiance"];
                }
            }catch(Exception $e){
                DB::rollBack();
                Log::channel("technodev")->info("Paiement Airtel Money ".$e->getMessage());
                return ["code"=>"201","msg"=>"Votre paiement va être traité, merci pour votre confiance","data"=>$response->body()];
            }

        }
        Log::channel("technodev")->info("Paiement Airtel Money ".$response->body());
       return ["code"=>"189","msg"=>"La transaction ne peut pas être traitée"];
    }

    public function updateAmPayment(DemandeDocument $commande, array $data){
        if($commande != null){
            $pd = PaiementDetail::where("code_demande_document",$commande->code_demande_document)->first();
            if($pd instanceof PaiementDetail){
                $pd->extra_col_2 = $data["payment_id"];
                //$pd->statut_payment = ($data["status"]=="200" ? "SUCCESSFUL":"FAILED");
                $status = $data["status"];
               // $pd->save();
                // if($status=="200"){
                //     $commande->id_statut_paiement = 4;
                //     $commande->save();
                //     return true;
                // }else{
                //     $commande->id_statut_paiement = 7;
                //     $commande->save();
                // }

                if($status=="SUCCESSFUL"){
                    $pd->statut_payment = "success";
                    $pd->save();
                    $commande->statut = "Traité";
                    $commande->save();
                    return true;
                }
                if($status=="FAILED"){
                    $pd->statut_payment = "failed";
                    $pd->save();
                    $commande->statut = "Réjeté";
                    $commande->save();
                    return true;
                }
            }
        }
    }

    public function sendSms($to,$content){
        $data = array(
            "client"=>"mukinayiseth",
            "password"=>"1234567890",
            "phone"=>"242".$to,
            "from"=>"technodev",
            "text"=>$content
        );

        $req = Http::asForm()->get("https://api.wirepick.com/httpsms/send",$data);
        return $req->body();
    }

    public function transact(array $data){
        if(Str::startsWith($data["number"], '06')){
            return self::momoPay($data);
        }

        if(Str::startsWith($data["number"], '05') || Str::startsWith($data["number"], '04')){
            return self::ampay($data);
        }

        return ["code"=>"190","msg"=>"Numéro de téléphone non supporté"];
    }
}
