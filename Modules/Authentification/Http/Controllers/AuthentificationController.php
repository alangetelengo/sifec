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
use App\Services\MetierDashboardService;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\Declarationnaissance;

class AuthentificationController extends Controller
{
    public function index()
    {
        // ── Institution de l'utilisateur connecté ──────────────────────────
        $user        = Auth::user();
        $affectation = $user ? $user->affectationActive() : null;
        $institution = $affectation ? $affectation->institution : null;

        $codeInstitution     = $institution ? $institution->code_institution : null;
        $libInstitution      = $institution ? $institution->lib_institution   : 'Système';

        $typeIns             = $institution ? $institution->typeInstitution : null;
        $codeTypeInstitution = $typeIns ? $typeIns->code_type_institution : null;

        $categorie           = $typeIns ? $typeIns->typeCategorieInstitution : null;
        $codeTypeCategorie   = $categorie ? $categorie->code_type_categorie_ins : null;

        if ($user && $affectation && $institution) {
            $institution->loadMissing(['institutionParent', 'lieu', 'typeInstitution.typeCategorieInstitution', 'institutionsEnfants']);
            $metier = app(MetierDashboardService::class)->resolve($user);
            if ($metier !== null) {
                return view($metier['view'], $metier['data']);
            }
        }

        /*
         * Stratégie de filtrage par institution :
         *   TCINS_0002 (Tribunal) et null (super admin) → vue globale, pas de filtre
         *   Tous les autres → données filtrées par code_institution
         */
        $vueGlobale = in_array($codeTypeCategorie, ['TCINS_0002', null]) || $codeInstitution === null;
        $ins        = $vueGlobale ? '' : "AND code_institution = '{$codeInstitution}'";

        // ── Calcul de la semaine ───────────────────────────────────────────
        $lun = date('Y-m-d', strtotime('last week monday'));
        $mar = date('Y-m-d', strtotime($lun.'+1 day'));
        $mer = date('Y-m-d', strtotime($lun.'+2 day'));
        $jeu = date('Y-m-d', strtotime($lun.'+3 day'));
        $ven = date('Y-m-d', strtotime($lun.'+4 day'));
        $sam = date('Y-m-d', strtotime($lun.'+5 day'));
        $dim = date('Y-m-d', strtotime($lun.'+6 day'));

        // ── Helper : requête quotidienne ───────────────────────────────────
        $q = function(string $table, string $date, string $whereExtra = '') {
            return DB::select("SELECT COUNT(*) AS TOTAL FROM {$table} WHERE date(created_at) = '{$date}' {$whereExtra}");
        };

        // ── Déclarations de naissance ──────────────────────────────────────
        $pr  = $q('t_declaration_naissance', $lun, $ins);
        $dx  = $q('t_declaration_naissance', $mar, $ins);
        $tr  = $q('t_declaration_naissance', $mer, $ins);
        $qt  = $q('t_declaration_naissance', $jeu, $ins);
        $cq  = $q('t_declaration_naissance', $ven, $ins);
        $sx  = $q('t_declaration_naissance', $sam, $ins);
        $sp  = $q('t_declaration_naissance', $dim, $ins);

        // ── Déclarations de décès ──────────────────────────────────────────
        $prd = $q('t_declaration_deces', $lun, $ins);
        $dxd = $q('t_declaration_deces', $mar, $ins);
        $trd = $q('t_declaration_deces', $mer, $ins);
        $qtd = $q('t_declaration_deces', $jeu, $ins);
        $cqd = $q('t_declaration_deces', $ven, $ins);
        $sxd = $q('t_declaration_deces', $sam, $ins);
        $spd = $q('t_declaration_deces', $dim, $ins);

        // ── Actes de naissance ─────────────────────────────────────────────
        $pra = $q('t_acte_naissance', $lun, $ins);
        $dxa = $q('t_acte_naissance', $mar, $ins);
        $tra = $q('t_acte_naissance', $mer, $ins);
        $qta = $q('t_acte_naissance', $jeu, $ins);
        $cqa = $q('t_acte_naissance', $ven, $ins);
        $sxa = $q('t_acte_naissance', $sam, $ins);
        $spa = $q('t_acte_naissance', $dim, $ins);

        // ── Actes de décès ─────────────────────────────────────────────────
        $prb = $q('t_acte_deces', $lun, $ins);
        $dxb = $q('t_acte_deces', $mar, $ins);
        $trb = $q('t_acte_deces', $mer, $ins);
        $qtb = $q('t_acte_deces', $jeu, $ins);
        $cqb = $q('t_acte_deces', $ven, $ins);
        $sxb = $q('t_acte_deces', $sam, $ins);
        $spb = $q('t_acte_deces', $dim, $ins);

        // ── Déclarations de mariage ────────────────────────────────────────
        $insMar = $vueGlobale ? '' : "AND cui IN (SELECT cui FROM tr_ins_user WHERE code_institution = '{$codeInstitution}')";
        $prm  = $q('t_declaration_mariage', $lun, $insMar);
        $dxm  = $q('t_declaration_mariage', $mar, $insMar);
        $trm  = $q('t_declaration_mariage', $mer, $insMar);
        $qtm  = $q('t_declaration_mariage', $jeu, $insMar);
        $cqm  = $q('t_declaration_mariage', $ven, $insMar);
        $sxm  = $q('t_declaration_mariage', $sam, $insMar);
        $spm  = $q('t_declaration_mariage', $dim, $insMar);

        // ── Actes de mariage ───────────────────────────────────────────────
        $prma = $q('t_acte_mariage', $lun, $ins);
        $dxma = $q('t_acte_mariage', $mar, $ins);
        $trma = $q('t_acte_mariage', $mer, $ins);
        $qtma = $q('t_acte_mariage', $jeu, $ins);
        $cqma = $q('t_acte_mariage', $ven, $ins);
        $sxma = $q('t_acte_mariage', $sam, $ins);
        $spma = $q('t_acte_mariage', $dim, $ins);

        return view('admin.dashboard.index', compact(
            'pr','dx','tr','qt','cq','sx','sp',
            'prd','dxd','trd','qtd','cqd','sxd','spd',
            'pra','dxa','tra','qta','cqa','sxa','spa',
            'prb','dxb','trb','qtb','cqb','sxb','spb',
            'prm','dxm','trm','qtm','cqm','sxm','spm',
            'prma','dxma','trma','qtma','cqma','sxma','spma',
            'lun','dim',
            'codeTypeCategorie','codeTypeInstitution','libInstitution','vueGlobale'
        ));
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

        if ($user->must_change_password) {
            return redirect()->route('first-login-password.show')
                ->with('first_login_notice', true);
        }

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

    /**
     * Formulaire obligatoire après connexion si must_change_password (compte créé avec mot de passe provisoire).
     */
    public function showFirstLoginPassword()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        if (! $user->must_change_password) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.first-login-password');
    }

    /**
     * Enregistre le nouveau mot de passe et lève l'obligation de changement.
     */
    public function updateFirstLoginPassword(Request $request)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        if (! $user->must_change_password) {
            return redirect()->route('dashboard.index');
        }

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Le mot de passe provisoire est obligatoire.',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
            'new_password.confirmed' => 'La confirmation ne correspond pas.',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe provisoire est incorrect.'])->withInput();
        }

        if ($request->new_password === '123456') {
            return back()->withErrors(['new_password' => 'Vous devez choisir un mot de passe différent du mot de passe provisoire (123456).'])->withInput();
        }

        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors(['new_password' => 'Le nouveau mot de passe doit être différent du mot de passe provisoire.'])->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->must_change_password = false;
        $user->save();

        UserAuditTrail::log($user->code_user, 'password_change', 'Mot de passe personnel défini (première connexion après compte provisoire)');

        toastr()->success('Votre mot de passe a été enregistré. Bienvenue sur SIFEC.');

        return redirect()->route('dashboard.index');
    }
}
