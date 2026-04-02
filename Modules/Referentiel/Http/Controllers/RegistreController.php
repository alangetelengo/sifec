<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Deces\Entities\ActeDeces;
use Illuminate\Support\Facades\Validator;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Notification\Jobs\SendSmsJob;
use Modules\Referentiel\Entities\Registre;
use Illuminate\Contracts\Support\Renderable;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\TypeRegistre;
use Modules\Notification\Jobs\CreationRegistreJob;
use Modules\Notification\Jobs\ValidationRegistreJob;
use Modules\Notification\Services\NotificationService;

class RegistreController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // dispatch(new ValidationRegistreJob("OBELA","152ffgfg","alfed520","obela.sifec@gmail.com"));

        $modules = Auth::user()->modules();
        $registres = collect([]);
        $typeRegistres = collect([]);
        $typeRegistres_vide=collect([]);

        if($modules->count() > 0){
            $inst = Auth::user()->affectationActive();

            foreach($modules as $m){
                switch($m->code_module){
                    case "MOD_0002":
                    case "MOD_0004":
                    case "MOD_0005":

                        $typeRegistres = TypeRegistre::whereIn("code_type_registre",["TPRG_0001","TPRG_0002","TPRG_0003"])->get();
                        // $registres = $typeRegistres->map->registres->flatten()->where("cui",$inst->cui);
                        $registres = $inst->institution->registres();

                        if($inst->institution->lib_institution == "MAIRIE CENTRALE"){
                            $typeRegistres = TypeRegistre::all();
                            $registres = $typeRegistres->map->registres->flatten()->where("cui",$inst->cui);
                        }
                        //le cas de mairie qui n'est pas relié à une pompe funebre
                        if($inst->institution->lieu->localiteParent->pompes_funebres == 0){
                            $typeRegistres = TypeRegistre::all();
                            // $registres = $typeRegistres->map->registres->flatten()->where("cui",$inst->cui);
                            $registres = $inst->institution->registres();
                        }
                        //le cas des ambassades
                        if($inst->institution->lieu->typeLocalite->code_type_localite == "TPLOC_0009"){
                            $typeRegistres = TypeRegistre::all();
                            // $registres = $typeRegistres->map->registres->flatten()->where("cui",$inst->cui);
                            $registres = $inst->institution->registres();
                        }


                        $typeRegistres_vide=$typeRegistres;
                        $registre_naissance_last=Registre::where("code_type_registre","TPRG_0001")
                                                                 ->where("date_fermeture",">",date("Y-m-d"))
                                                                 ->orWhere("nombre_acte_prevu","<","nombre_acte_transcrit")
                                                                 ->count();

                       if($registre_naissance_last>0)
                         {
                            $typeRegistres_vide=TypeRegistre::whereIn("code_type_registre",["TPRG_0002","TPRG_0003"])->get();
                         }

                        break;

                    case "MOD_0003":

                        $typeRegistres = TypeRegistre::whereIn("code_type_registre",["TPRG_0004"])->get();
                        // $registres = $typeRegistres->map->registres->flatten()->where("cui",$inst->cui);
                        $registres = $inst->institution->registres();

                        $typeRegistres_vide=$typeRegistres;
                        $registre_deces_last=Registre::where("code_type_registre","TPRG_0004")
                                                        ->where("date_fermeture",">",date("Y-m-d"))
                                                        ->orWhere("nombre_acte_prevu","<","nombre_acte_transcrit")
                                                        ->count();

                    if($registre_deces_last>0)
                    {
                    $typeRegistres_vide=TypeRegistre::whereNotIn("code_type_registre",["TPRG_0004","TPRG_0001","TPRG_0002","TPRG_0003"])->get();
                    }
                    break;
                }

            }
        }

        return view('referentiel::registre.index',compact("registres", "typeRegistres","typeRegistres_vide"));
    }


    public function store(Request $request)
    {
        $annee = date("Y");
        $code_cec = Auth::user()->affectationActive()->cui;

       //$prefix = $request->prefix.$code_cec;
       // $coderegistre =   Sifec::genererCodeUfniqueReferentiel(new Registre(),"code_registre",3,$request->prefix);

        $request->validate([
            "lib_registre" => ["string"],
            "code_type_registre" => ["required","string"],
            "nbre_acte_prevu" => ["required"],
            "statut" => ["required"]
        ]);

        $registreActif = Registre::where(["statut"=>1,"code_type_registre"=>$request->code_type_registre,"cui"=>$code_cec])->first();

        if($registreActif != null){
            toastr()->warning("Un registre valide est encore en cours");
            return back();
        }

        DB::beginTransaction();

        try{

            $registre = new Registre();
            $registre->code_registre = Sifec::genererCodeUniqueReferentiel($registre,"code_registre",2,"REG_"); //$code_registre;
            $registre->lib_registre = $request->lib_registre;
            $registre->code_type_registre =  $request->code_type_registre;
            $registre->nombre_acte_prevu = $request->nbre_acte_prevu;
            $registre->date_ouverture = date("Y-m-d");
            $registre->date_fermeture = date("Y-12-31");
            $registre->statut = $request->statut;
            $registre->cui = Auth::user()->affectationActive()->cui;
            $registre->identifiant_registre = $request->prefix.Auth::user()->institution()->code_institution.date("dmYHis");
            $registre->save();

            // Envoi de notification au tribunal de ressort (parent du CEC)
            $institution = optional($registre->institutionUser)->institution;
            $tribunal = $institution ? optional($institution)->institutionParent : null;
            $validateur = $tribunal ? $tribunal->validateur() : null;

            if ($tribunal && $validateur) {
                $otp = substr(time(), 2);

                $temp = config("sifec.sms.templates.actions.creation_registre");
                $temp = str_replace(":tribunal", $validateur->nom, $temp);
                $temp = str_replace(":code_registre", $registre->numeroOrdreRegistre(), $temp);
                $temp = str_replace(":cec", Auth::user()->affectationActive()->institution->lib_institution, $temp);
                $temp = str_replace(":type_registre", $registre->typeRegistre->lib_type_registre, $temp);
                $temp = str_replace(":code_otp", $otp, $temp);

                $contactValidateur = optional($validateur->contacts)->first();
                $telephone = $contactValidateur ? $contactValidateur->indicatif . $contactValidateur->telephone : null;

                if ($telephone) {
                    SifecFacade::sendSms($telephone, $temp);
                    dispatch(new SendSmsJob($telephone, $temp));
                }

                $emailTribunal = $contactValidateur ? ($contactValidateur->email_professionnelle ?? null) : null;
                if ($emailTribunal) {
                    dispatch(new CreationRegistreJob(
                        $validateur->nom,
                        $registre->typeRegistre->lib_type_registre,
                        $registre->numeroOrdreRegistre(),
                        Auth::user()->affectationActive()->institution->lib_institution,
                        $emailTribunal
                    ));
                }
            }


            DB::commit();

            toastr()->success("Registre enregistré avec succès");
            return redirect()->route("registre.index");

        }catch(Exception $e){
            DB::rollBack();
            Log::channel("sifec")->error($e->getMessage());
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();

        }
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $registre = Registre::find($id);

        if($registre == null){
            toastr()->error("Impossible de charger cette page");
        }

        $registre->delete();
        toastr()->success("Registre supprimé avec succès");
        return redirect()->route("registre.index");

    }


    public function sendOtp($id){
        $reg = Registre::where("code_registre",$id)->first();
        if($reg == null){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun registre trouvé pour ce code $reg->numeroOrdreRegistre()"
            ]);
        }

        try {

            $otp = substr(time(),2);

            $temp = config("sifec.sms.templates.actions.paraphage_registre");
            $temp = str_replace(":tribunal",Auth::user()->personne->nomcomplet(),$temp);
            $temp = str_replace(":code_registre",$reg->numeroOrdreRegistre(),$temp);
            $temp = str_replace(":code_otp",$otp,$temp);

            $reg->otp_paraphage = $otp;
            $reg->save();

            $contact = Auth::user()->personne->contacts->first();


            // Log::channel("sifec")->info($contact->email_professionnelle);
            // dd("ok");

            if($contact != null){
                $indicatif = $contact->indicatif;

                // if($indicatif != "+242"){
                //     SifecFacade::infobipSms($contact->indicatif.$contact->telephone, $temp);
                // }else{
                //     dispatch(new SendSmsJob($contact->indicatif.$contact->telephone,$temp));
                //     // dispatch(new SendSmsJob("+242066835332",$temp));
                // }
                // SifecFacade::infobipSms($contact->indicatif.$contact->telephone, $temp);
                SifecFacade::sendSms($contact->indicatif.$contact->telephone, $temp);
                dispatch(new SendSmsJob($contact->indicatif.$contact->telephone,$temp));

                dispatch(new ValidationRegistreJob(Auth::user()->personne->nomcomplet(),$otp,$reg->getcode(),$contact->email_professionnelle));
                // dispatch(new ValidationRegistreJob(Auth::user()->personne->nomcomplet(),$otp,$reg->getcode(),"alangetelengo87@gmail.com"));

            }

            return response()->json([
                "code"=>"200",
                "message"=>"SMS envoyé avec succès"
            ]);


        } catch (Exception $e) {
            return response()->json([
                "code"=>"181",
                "message"=>$e->getMessage()
            ]);
        }

    }
    public function validateOtp(Request $request){
        $rules = [
            "otp_paraphage"=>["required","numeric"],
            "code_registre"=>["required","string"]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun registre trouvé pour ce code"
            ]);
        }

        if(!Gate::allows("module.fonctionnalites.parapher")){
            return response()->json([
                "code"=>"181",
                "message"=>"Vous n'êtes pas autorisé à parapher un registre"
            ]);
        }

        $code_reg = $request->code_registre;
        $otp = $request->otp_paraphage;

        $reg = Registre::where("code_registre",$code_reg)->first();
        if($reg == null){
            return response()->json([
                "code"=>"182",
                "message"=>"Aucun registre trouvé pour ce code $code_reg"
            ]);
        }

        if($otp != $reg->otp_paraphage){
            return response()->json([
                "code"=>"183",
                "message"=>"Code otp incorrect ou Expiré"
            ]);
        }

        try {

            $otp = substr(time(),2);
            $reg->sceau = $reg->institutionUser->institution->institutionParent->sceau;
            $reg->otp_paraphage = $otp;
            $reg->signature_tribunal = Auth::user()->personne->signature;
            $reg->approbation_tribunal = Auth::user()->affectationActive()->cui;
            $reg->statut = 1;

            $reg->save();

            return response()->json([
                "code"=>"200",
                "message"=>"Registre de ".$reg->typeRegistre->lib_type_registre." est validé avec succès"
            ]);


        } catch (Exception $e) {
            return response()->json([
                "code"=>"183",
                "message"=>$e->getMessage()
            ]);
        }

    }

    public function cloturerRegistre(Request $request)
    {
        $rules = [
            "date_cloture"=>["required","date"],
            "code_registre"=>["required","string"]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun registre trouvé pour ce code"
            ]);
        }

        // if(!Gate::allows("module.fonctionnalites.registre.cloture")){
        //     return response()->json([
        //         "code"=>"181",
        //         "message"=>"Vous n'êtes pas autorisé à clôturer un registre"
        //     ]);
        // }

        $code_reg = $request->code_registre;
        $datecloture = $request->date_cloture;

        $reg = Registre::where("code_registre",$code_reg)->first();

        if($reg == null){
            return response()->json([
                "code"=>"182",
                "message"=>"Aucun registre trouvé pour ce code $code_reg"
            ]);
        }

        try {

            $reg->signature_cloture_cec = Auth::user()->personne->signature;
            $reg->cloture_cec = Auth::user()->affectationActive()->cui;
            $reg->date_fermeture = $datecloture;
            $reg->statut = 0;
            $reg->save();

            return response()->json([
                "code"=>"200",
                "message"=>"Registre de ".$reg->typeRegistre->lib_type_registre." est clôturé avec succès"
            ]);


        } catch (Exception $e) {
            return response()->json([
                "code"=>"183",
                "message"=>$e->getMessage()
            ]);
        }


    }

    public function registreNaissance($id)
    {
        $registre = Registre::find($id);
        $dummy = "XXXXXXXXXXXXXXXX";

        // Récupérer les actes triés par position dans le registre (4 derniers caractères du code_acte dans t_feuillet_registre)
        // Les 4 derniers caractères du code_acte représentent la position dans le registre
        $actesRegistre = ActeNaissance::where("code_registre", $id)
            ->join('t_feuillet_registre', 't_acte_naissance.niupp', '=', 't_feuillet_registre.code_acte')
            ->select('t_acte_naissance.*')
            ->orderByRaw('CAST(RIGHT(t_feuillet_registre.code_acte, 4) AS UNSIGNED) ASC')
            ->get();

        return view('referentiel::registre.registre_acte_naissance', compact('registre','actesRegistre','dummy'));
    }

    public function feuilletRN($id)
    {
        $acte = ActeNaissance::findByIdentifier($id);
        $dummy = "XXXXXXXXXXXXXXXX";
        return view('referentiel::registre.feuillet_acte_naissance', compact('acte','dummy'));
    }

    public function registreMariage($id)
    {
        $registre = Registre::find($id);

        if($registre == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }

        $actes = ActeMariage::where("code_registre", $id)->get();
        return view("referentiel::registre.registre_acte_mariage", compact("actes","registre"));
    }

    public function feuilletRM($id)
    {
        $acte = ActeMariage::find($id);

        if($acte == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }

        return view('referentiel::registre.feuillet_acte_mariage',compact("acte"));
    }

    public function registreDeces($id)
    {
        $registre = Registre::find($id);

        // Récupérer les actes triés par position dans le registre (8 derniers caractères du code_acte_deces dans t_feuillet_registre)
        // Les 8 derniers caractères du code_acte_deces représentent la position dans le registre (ex: AD_00000001 → position 1)
        $actesRegistre = ActeDeces::where("code_registre", $id)
            ->join('t_feuillet_registre', 't_acte_deces.code_acte_deces', '=', 't_feuillet_registre.code_acte')
            ->select('t_acte_deces.*')
            ->orderByRaw('CAST(RIGHT(t_feuillet_registre.code_acte, 8) AS UNSIGNED) ASC')
            ->get();

        return view('referentiel::registre.registre_acte_deces', compact('registre','actesRegistre'));
    }

    public function feuilletRD($id)
    {
        $acteReg = ActeDeces::find($id);
        return view('referentiel::registre.feuillet_acte_deces', compact('acteReg'));
    }

    public function registresTribunal()
    {
        $inst = Auth::user()->affectationActive()->institution;
        $registres = $inst->descendants()->map->registres()->flatten();
        $typeRegistres = TypeRegistre::all();
        $typeRegistres_vide = [];

        return view('referentiel::registre.index',compact("registres", "typeRegistres","typeRegistres_vide"));
    }


    //ajout de feuilles du registre au cours de la même année
    public function AddFeuilletRegistre(Request $request)
    {
        $rules = [
            "nbrefeuillets"=>["required"],
            "code_registre"=>["required","string"]
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun registre trouvé pour ce code"
            ]);
        }

        // if(!Gate::allows("module.fonctionnalites.registre.cloture")){
        //     return response()->json([
        //         "code"=>"181",
        //         "message"=>"Vous n'êtes pas autorisé à clôturer un registre"
        //     ]);
        // }

        $code_reg = $request->code_registre;
        $nbrefeuillets = $request->nbrefeuillets;

        $reg = Registre::where("code_registre",$code_reg)->first();
        if($reg == null){
            return response()->json([
                "code"=>"182",
                "message"=>"Aucun registre trouvé pour ce code $code_reg"
            ]);
        }

        try {

            $reg->nombre_acte_prevu = $nbrefeuillets + $reg->nombre_acte_prevu;
            $reg->statut = 1;
            $reg->save();

            //type de registre
            $typeRegistre = $reg->typeRegistre->lib_type_registre;

            //notifier le président du tribunal pour des feuillets ajoutés au registre (notification à titre d'information)
            if ($reg->institutionUser && $reg->institutionUser->institution && $reg->institutionUser->institution->institutionParent) {
                NotificationService::notifierFeuilletRegistreAjoute(
                    $reg->institutionUser->institution->institutionParent,
                    new \Modules\Notification\Notifications\FeuilletRegistreAjouteNotification($reg, $nbrefeuillets)
                );
            }

            return response()->json([
                "code"=>"200",
                "message"=>"REGISTRE DE $typeRegistre $nbrefeuillets feuillets ajouté avec succès"
            ]);

        } catch (Exception $e) {
            return response()->json([
                "code"=>"183",
                "message"=>$e->getMessage()
            ]);
        }


    }


}
