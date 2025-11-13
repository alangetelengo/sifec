<?php

namespace Modules\Authentification\Http\Controllers;

use App\Models\User;
use App\Models\UserAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Entities\Declarationnaissance;

class AuthentificationController extends Controller
{
    public function index()
    {
        $lun = date('Y-m-d', strtotime('last week monday'));
        $mar = date('Y-m-d', strtotime($lun.'+1 day'));
        $mer = date('Y-m-d', strtotime($lun.'+2 day'));
        $jeu = date('Y-m-d', strtotime($lun.'+3 day'));
        $ven = date('Y-m-d', strtotime($lun.'+4 day'));
        $sam = date('Y-m-d', strtotime($lun.'+5 day'));
        $dim = date('Y-m-d', strtotime($lun.'+6 day'));

        // dd($dim);

        $pr = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_naissance WHERE date(t_declaration_naissance.created_at) = '".$lun."'"));
        $dx = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_naissance WHERE date(t_declaration_naissance.created_at) = '".$mar."'"));
        $tr = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_naissance WHERE date(t_declaration_naissance.created_at) = '".$mer."'"));
        $qt = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_naissance WHERE date(t_declaration_naissance.created_at) = '".$jeu."'"));
        $cq = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_naissance WHERE date(t_declaration_naissance.created_at) = '".$ven."'"));
        $sx = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_naissance WHERE date(t_declaration_naissance.created_at) = '".$sam."'"));
        $sp = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_naissance WHERE date(t_declaration_naissance.created_at) = '".$dim."'"));

        // $declarationsN = [$pr[0]->TOTAL, $dx[0]->TOTAL, $tr[0]->TOTAL, $qt[0]->TOTAL, $cq[0]->TOTAL, $sx[0]->TOTAL, $sp[0]->TOTAL];

        $prd = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_deces WHERE date(t_declaration_deces.created_at) = '".$lun."'"));
        $dxd = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_deces WHERE date(t_declaration_deces.created_at) = '".$mar."'"));
        $trd = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_deces WHERE date(t_declaration_deces.created_at) = '".$mer."'"));
        $qtd = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_deces WHERE date(t_declaration_deces.created_at) = '".$jeu."'"));
        $cqd = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_deces WHERE date(t_declaration_deces.created_at) = '".$ven."'"));
        $sxd = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_deces WHERE date(t_declaration_deces.created_at) = '".$sam."'"));
        $spd = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_declaration_deces WHERE date(t_declaration_deces.created_at) = '".$dim."'"));

        $pra = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_naissance WHERE date(t_acte_naissance.created_at) = '".$lun."'"));
        $dxa = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_naissance WHERE date(t_acte_naissance.created_at) = '".$mar."'"));
        $tra = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_naissance WHERE date(t_acte_naissance.created_at) = '".$mer."'"));
        $qta = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_naissance WHERE date(t_acte_naissance.created_at) = '".$jeu."'"));
        $cqa = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_naissance WHERE date(t_acte_naissance.created_at) = '".$ven."'"));
        $sxa = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_naissance WHERE date(t_acte_naissance.created_at) = '".$sam."'"));
        $spa = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_naissance WHERE date(t_acte_naissance.created_at) = '".$dim."'"));

        $prb = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_deces WHERE date(t_acte_deces.created_at) = '".$lun."'"));
        $dxb = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_deces WHERE date(t_acte_deces.created_at) = '".$mar."'"));
        $trb = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_deces WHERE date(t_acte_deces.created_at) = '".$mer."'"));
        $qtb = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_deces WHERE date(t_acte_deces.created_at) = '".$jeu."'"));
        $cqb = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_deces WHERE date(t_acte_deces.created_at) = '".$ven."'"));
        $sxb = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_deces WHERE date(t_acte_deces.created_at) = '".$sam."'"));
        $spb = (DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_deces WHERE date(t_acte_deces.created_at) = '".$dim."'"));

        return view('admin.dashboard.index', compact('pr','dx','tr','qt','cq','sx','sp','prd','dxd','trd','qtd','cqd','sxd','spd','pra','dxa','tra','qta','cqa','sxa','spa','prb','dxb','trb','qtb','cqb','sxb','spb','lun','dim'));
    }


    public function carte()
    {
        $statdeces=DeclarationDeces::where(["type_declaration"=>"CERTIFICAT DE TRANSCRIPTION"])->count("code_declaration_deces");

        $statannuel=DeclarationDeces::where(["type_declaration"=>"CERTIFICAT DE TRANSCRIPTION"])
        ->where("date_heure_declaration", 'like', "%".date("Y")."%")
        ->count("code_declaration_deces");

        $statmois=DeclarationDeces::where(["type_declaration"=>"CERTIFICAT DE TRANSCRIPTION"])
        ->where("date_heure_declaration", 'like', "%".date("Y-m")."%")
        ->count("code_declaration_deces");



        $statnais=Declarationnaissance::where(["type_declaration"=>"CERTIFICAT DE TRANSCRIPTION"])->count("code_declaration_naissance");

        $statnaisannuel=Declarationnaissance::where(["type_declaration"=>"CERTIFICAT DE TRANSCRIPTION"])
                                    ->where("created_at", 'like', "%".date("Y")."%")
                                    ->count("code_declaration_naissance");


        $statnaismois=Declarationnaissance::where(["type_declaration"=>"CERTIFICAT DE TRANSCRIPTION"])
        ->where("created_at", 'like', "%".date("Y-m")."%")
        ->count("code_declaration_naissance");

        return view('admin.dashboard.carte', compact("statdeces",'statannuel','statmois','statnais','statnaisannuel','statnaismois'));
    }

    public function authentification(Request $request)
    {
       $email = $request->email;
       $password = $request->password;

        if($email == null){
            toastr()->error("L'adresse mail est obligatoire");
            return redirect()->back();
        }
        if($password == null){
            toastr()->error("Le mot de passe est obligatoire");
            return redirect()->back();
        }

        $user = User::whereEmail($email)->first();
        if($user == null){
            // Audit trail pour tentative de connexion avec email inexistant
            UserAuditTrail::log('UNKNOWN', 'login_failed', "Tentative de connexion avec email inexistant: {$email}");
            toastr()->error("Cette adresse mail n'est pas reconnue");
            return redirect()->back()->withInput();
        }
        if(! Hash::check($password, $user->password)){
            // Audit trail pour mot de passe incorrect
            UserAuditTrail::log($user->code_user, 'login_failed', "Mot de passe incorrect");
            toastr()->error("Le mot de passe est incorrect");
            return redirect()->back()->withInput();
        }

        if($user->status == 0){
            toastr()->error("Votre compte n'est pas disponible, veuillez contacter l'administrateur principal");
            return redirect()->back()->withInput();
        }

        if($user->affectationActive() == null){
            toastr()->error("Votre compte n'est affecté à aucun centre d'état civil");
            return back();
        }

        // ==========================================
        // VÉRIFICATION 2FA
        // ==========================================

        // Si l'utilisateur a la 2FA activée
        if ($user->hasTwoFactorEnabled()) {
            // Stocker les informations en session
            session([
                '2fa:user:id' => $user->code_user,
                '2fa:remember' => $request->filled('remember'),
                '2fa:timestamp' => now()->timestamp
            ]);

            // Rediriger vers la vérification 2FA
            toastr()->info("Veuillez entrer votre code de vérification.");
            return redirect()->route('two-factor.verify');
        }

        // Connexion normale si pas de 2FA
        Auth::login($user, $request->filled('remember'));

        // Audit trail pour connexion réussie
        UserAuditTrail::log($user->code_user, 'login', "Connexion réussie");

        toastr()->success("Connexion réussie");

        return redirect()->route('dashboard.index');
        // return redirect()->route('home.index');


    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);
        if($user == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }
        $request->validate([
            "email" =>  ["required","email"],
            "password" =>  ["required"],
            "status" =>  ["required"]
        ]);

        $email = $request->email;
        $pseudo = $request->pseudo;
        $status = $request->status;
        $password = $request->password;
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;

        if( ! Hash::check($password, $user->password)){
            toastr()->error("Ce mot de passe n'existe pas pour ce compte");
            return back();
        }
        if($new_password !== $confirm_password){
            toastr()->error("Les deux mots de passe ne correspondent pas");
            return back();
        }

        $user->email = $email;
        $user->password = Hash::make($new_password);
        $user->pseudo = $pseudo;
        $user->status = $status;
        $user->save();

        toastr()->success("Compte modifié avec succès");
        return back();

    }

    public function statGenreDep()
    {
        $annee = request('annee');
        if ($annee != "") {
            $annee = request('annee');
        }else{
            $annee = date('Y');
        }

        $join = "SELECT count(*) AS GENRE FROM t_declaration_naissance, tr_identification_personne, tr_ins_user, tr_institution, tr_localite
        WHERE t_declaration_naissance.code_enfant = tr_identification_personne.code_personne
        AND tr_ins_user.cui = t_declaration_naissance.code_user_institution
        AND tr_institution.code_institution = tr_ins_user.code_institution
        AND tr_localite.code_localite = tr_institution.code_localite
        AND YEAR(t_declaration_naissance.date_heure_declaration) = '".$annee."'";

        $brazzah = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0026' OR tr_localite.code_localite_parent = 'LOC_0026')
        ");

        $brazzaf = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0026' OR tr_localite.code_localite_parent = 'LOC_0026')
        ");

        $pnh = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0016' OR tr_localite.code_localite_parent = 'LOC_0016')
        ");

        $pnf = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0016' OR tr_localite.code_localite_parent = 'LOC_0016')
        ");

        $likoualah = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0003' OR tr_localite.code_localite_parent = 'LOC_0003')
        ");

        $likoualaf = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0003' OR tr_localite.code_localite_parent = 'LOC_0003')
        ");

        $sanghah = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0004' OR tr_localite.code_localite_parent = 'LOC_0004')
        ");

        $sanghaf = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0004' OR tr_localite.code_localite_parent = 'LOC_0004')
        ");

        $cuvetteoh = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0005' OR tr_localite.code_localite_parent = 'LOC_0005')
        ");

        $cuvetteof = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0005' OR tr_localite.code_localite_parent = 'LOC_0005')
        ");

        $cuvetteh = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0006' OR tr_localite.code_localite_parent = 'LOC_0006')
        ");

        $cuvettef = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0006' OR tr_localite.code_localite_parent = 'LOC_0006')
        ");

        $plateauh = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0007' OR tr_localite.code_localite_parent = 'LOC_0007')
        ");

        $plateauf = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0007' OR tr_localite.code_localite_parent = 'LOC_0007')
        ");

        $poolh = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0008' OR tr_localite.code_localite_parent = 'LOC_0008')
        ");

        $poolf = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0008' OR tr_localite.code_localite_parent = 'LOC_0008')
        ");

        $lekoumouh = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0009' OR tr_localite.code_localite_parent = 'LOC_0009')
        ");

        $lekoumouf = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0009' OR tr_localite.code_localite_parent = 'LOC_0009')
        ");

        $bouenzah = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0010' OR tr_localite.code_localite_parent = 'LOC_0010')
        ");

        $bouenzaf = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0010' OR tr_localite.code_localite_parent = 'LOC_0010')
        ");

        $niarih = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0011' OR tr_localite.code_localite_parent = 'LOC_0011')
        ");

        $niarif = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0011' OR tr_localite.code_localite_parent = 'LOC_0011')
        ");

        $kouilouh = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0012' OR tr_localite.code_localite_parent = 'LOC_0012')
        ");

        $kouilouf = DB::select("
        ".$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0012' OR tr_localite.code_localite_parent = 'LOC_0012')
        ");



        // dd($kouilouf);

        return view('admin.dashboard.statistiques', compact("annee", "brazzah", "brazzaf",

        "pnh",


        "pnf",


        "likoualah",


        "likoualaf",


        "sanghah",


        "sanghaf",

        "cuvetteoh",


        "cuvetteof",


        "cuvetteh",


        "cuvettef",


        "plateauh",


        "plateauf",

        "poolh",


        "poolf",


        "lekoumouh",

        "lekoumouf",

        "bouenzah",


        "bouenzaf",


        "niarih",

        "niarif",


        "kouilouh",


        "kouilouf"
    ));
    }




    public function home(){
        return view('home');
    }
}
