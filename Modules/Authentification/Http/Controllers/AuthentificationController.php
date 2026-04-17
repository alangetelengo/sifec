<?php

namespace Modules\Authentification\Http\Controllers;

use App\Models\User;
use App\Models\UserAuditTrail;
use App\Services\MetierDashboardService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\Declarationnaissance;

class AuthentificationController extends Controller
{
    /**
     * Types de dossier naissance comptés au tableau de bord (formation + pièces tribunal / transcription).
     *
     * @return list<string>
     */
    private static function typesDeclarationNaissanceDashboard(): array
    {
        return [
            'DECLARATION DE NAISSANCE',
            'CERTIFICAT DE NAISSANCE',
            'CERTIFICAT DE NON INSCRIPTION',
            "CERTIFICAT DE DESTRUCTION DE L'ACTE",
            'FICHE DE TRANSCRIPTION',
            'CERTIFICAT DE TRANSCRIPTION',
        ];
    }

    private static function sqlInDeclarationNaissanceTypes(): string
    {
        $escaped = array_map(
            static fn (string $t) => str_replace("'", "''", $t),
            self::typesDeclarationNaissanceDashboard()
        );

        return "'".implode("','", $escaped)."'";
    }

    public function index()
    {
        // ── Institution de l'utilisateur connecté ──────────────────────────
        $user = Auth::user();
        $affectation = $user ? $user->affectationActive() : null;
        $institution = $affectation ? $affectation->institution : null;

        $codeInstitution = $institution ? $institution->code_institution : null;
        $libInstitution = $institution ? $institution->lib_institution : 'Système';

        $typeIns = $institution ? $institution->typeInstitution : null;
        $codeTypeInstitution = $typeIns ? $typeIns->code_type_institution : null;

        $categorie = $typeIns ? $typeIns->typeCategorieInstitution : null;
        $codeTypeCategorie = $categorie ? $categorie->code_type_categorie_ins : null;

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
        $ins = $vueGlobale ? '' : "AND code_institution = '{$codeInstitution}'";
        /*
         * Déclarations rattachées au CEC : établissement d’origine OU destinataire du flux (formation → centre).
         * Les actes sont filtrés via la déclaration liée pour le même périmètre.
         */
        $insDeclNaissancePerim = $vueGlobale
            ? ''
            : " AND (code_institution = '{$codeInstitution}' OR code_institution_destinataire = '{$codeInstitution}') ";
        $insDeclDecesPerim = $vueGlobale
            ? ''
            : " AND (code_institution = '{$codeInstitution}' OR code_institution_destinataire = '{$codeInstitution}') ";
        $insDeclMariagePerim = $vueGlobale
            ? ''
            : " AND (code_institution = '{$codeInstitution}' OR code_institution_destinataire = '{$codeInstitution}') ";

        // ── Calcul de la semaine ───────────────────────────────────────────
        $lun = date('Y-m-d', strtotime('last week monday'));
        $mar = date('Y-m-d', strtotime($lun.'+1 day'));
        $mer = date('Y-m-d', strtotime($lun.'+2 day'));
        $jeu = date('Y-m-d', strtotime($lun.'+3 day'));
        $ven = date('Y-m-d', strtotime($lun.'+4 day'));
        $sam = date('Y-m-d', strtotime($lun.'+5 day'));
        $dim = date('Y-m-d', strtotime($lun.'+6 day'));

        // ── Helper : requête quotidienne (table simple) ───────────────────
        $q = function (string $table, string $date, string $whereExtra = '') {
            return DB::select("SELECT COUNT(*) AS TOTAL FROM {$table} WHERE DATE(created_at) = '{$date}' {$whereExtra}");
        };

        /*
         * Périmètre CEC / institution : décompte des déclarations « générées » (type métier final)
         * et des actes effectivement signés par l’officier (CUI approbation renseigné), pas les seules créations en base.
         */
        $inTypesNaissance = self::sqlInDeclarationNaissanceTypes();
        $filtreDeclNaissance = " AND type_declaration IN ({$inTypesNaissance}) AND (deleted_at IS NULL) ";
        $filtreActeNaissance = " AND an.approbation_mairie IS NOT NULL AND an.approbation_mairie <> '' AND (an.deleted_at IS NULL) ";
        $filtreDeclDeces = " AND type_declaration IN ('DECLARATION DE DECES', 'DECLARATION TARDIVE') AND (deleted_at IS NULL) ";
        $filtreActeDeces = " AND ad.approbation_pompe_funebre IS NOT NULL AND ad.approbation_pompe_funebre <> '' AND (ad.deleted_at IS NULL) ";
        $filtreDeclMariage = " AND type_declaration = 'DECLARATION DE MARIAGE' AND (deleted_at IS NULL) ";
        $filtreActeMariage = " AND am.approbation_mairie IS NOT NULL AND am.approbation_mairie <> '' AND (am.deleted_at IS NULL) ";

        $qActeNaissanceJour = function (string $date) use ($vueGlobale, $codeInstitution, $filtreActeNaissance, $inTypesNaissance) {
            if ($vueGlobale) {
                return DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_naissance an WHERE DATE(an.created_at) = '{$date}' {$filtreActeNaissance}");
            }

            return DB::select("
                SELECT COUNT(*) AS TOTAL
                FROM t_acte_naissance an
                INNER JOIN t_declaration_naissance dn ON dn.code_declaration_naissance = an.code_declaration_naissance
                WHERE DATE(an.created_at) = '{$date}'
                {$filtreActeNaissance}
                AND (dn.code_institution = '{$codeInstitution}' OR dn.code_institution_destinataire = '{$codeInstitution}')
                AND dn.type_declaration IN ({$inTypesNaissance})
                AND (dn.deleted_at IS NULL)
            ");
        };

        $qActeDecesJour = function (string $date) use ($vueGlobale, $codeInstitution, $filtreActeDeces) {
            if ($vueGlobale) {
                return DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_deces ad WHERE DATE(ad.created_at) = '{$date}' {$filtreActeDeces}");
            }

            return DB::select("
                SELECT COUNT(*) AS TOTAL
                FROM t_acte_deces ad
                INNER JOIN t_declaration_deces dd ON dd.code_declaration_deces = ad.code_declaration_deces
                WHERE DATE(ad.created_at) = '{$date}'
                {$filtreActeDeces}
                AND (dd.code_institution = '{$codeInstitution}' OR dd.code_institution_destinataire = '{$codeInstitution}')
                AND dd.type_declaration IN ('DECLARATION DE DECES', 'DECLARATION TARDIVE')
                AND (dd.deleted_at IS NULL)
            ");
        };

        $qActeMariageJour = function (string $date) use ($vueGlobale, $codeInstitution, $filtreActeMariage) {
            if ($vueGlobale) {
                return DB::select("SELECT COUNT(*) AS TOTAL FROM t_acte_mariage am WHERE DATE(am.created_at) = '{$date}' {$filtreActeMariage}");
            }

            return DB::select("
                SELECT COUNT(*) AS TOTAL
                FROM t_acte_mariage am
                INNER JOIN t_declaration_mariage dm ON dm.code_declaration_mariage = am.code_declaration_mariage
                WHERE DATE(am.created_at) = '{$date}'
                {$filtreActeMariage}
                AND (dm.code_institution = '{$codeInstitution}' OR dm.code_institution_destinataire = '{$codeInstitution}')
                AND dm.type_declaration = 'DECLARATION DE MARIAGE'
                AND (dm.deleted_at IS NULL)
            ");
        };

        // ── Déclarations de naissance ──────────────────────────────────────
        $pr = $q('t_declaration_naissance', $lun, $insDeclNaissancePerim.$filtreDeclNaissance);
        $dx = $q('t_declaration_naissance', $mar, $insDeclNaissancePerim.$filtreDeclNaissance);
        $tr = $q('t_declaration_naissance', $mer, $insDeclNaissancePerim.$filtreDeclNaissance);
        $qt = $q('t_declaration_naissance', $jeu, $insDeclNaissancePerim.$filtreDeclNaissance);
        $cq = $q('t_declaration_naissance', $ven, $insDeclNaissancePerim.$filtreDeclNaissance);
        $sx = $q('t_declaration_naissance', $sam, $insDeclNaissancePerim.$filtreDeclNaissance);
        $sp = $q('t_declaration_naissance', $dim, $insDeclNaissancePerim.$filtreDeclNaissance);

        // ── Déclarations de décès ──────────────────────────────────────────
        $prd = $q('t_declaration_deces', $lun, $insDeclDecesPerim.$filtreDeclDeces);
        $dxd = $q('t_declaration_deces', $mar, $insDeclDecesPerim.$filtreDeclDeces);
        $trd = $q('t_declaration_deces', $mer, $insDeclDecesPerim.$filtreDeclDeces);
        $qtd = $q('t_declaration_deces', $jeu, $insDeclDecesPerim.$filtreDeclDeces);
        $cqd = $q('t_declaration_deces', $ven, $insDeclDecesPerim.$filtreDeclDeces);
        $sxd = $q('t_declaration_deces', $sam, $insDeclDecesPerim.$filtreDeclDeces);
        $spd = $q('t_declaration_deces', $dim, $insDeclDecesPerim.$filtreDeclDeces);

        // ── Actes de naissance ─────────────────────────────────────────────
        $pra = $qActeNaissanceJour($lun);
        $dxa = $qActeNaissanceJour($mar);
        $tra = $qActeNaissanceJour($mer);
        $qta = $qActeNaissanceJour($jeu);
        $cqa = $qActeNaissanceJour($ven);
        $sxa = $qActeNaissanceJour($sam);
        $spa = $qActeNaissanceJour($dim);

        // ── Actes de décès ─────────────────────────────────────────────────
        $prb = $qActeDecesJour($lun);
        $dxb = $qActeDecesJour($mar);
        $trb = $qActeDecesJour($mer);
        $qtb = $qActeDecesJour($jeu);
        $cqb = $qActeDecesJour($ven);
        $sxb = $qActeDecesJour($sam);
        $spb = $qActeDecesJour($dim);

        // ── Déclarations de mariage ────────────────────────────────────────
        $prm = $q('t_declaration_mariage', $lun, $insDeclMariagePerim.$filtreDeclMariage);
        $dxm = $q('t_declaration_mariage', $mar, $insDeclMariagePerim.$filtreDeclMariage);
        $trm = $q('t_declaration_mariage', $mer, $insDeclMariagePerim.$filtreDeclMariage);
        $qtm = $q('t_declaration_mariage', $jeu, $insDeclMariagePerim.$filtreDeclMariage);
        $cqm = $q('t_declaration_mariage', $ven, $insDeclMariagePerim.$filtreDeclMariage);
        $sxm = $q('t_declaration_mariage', $sam, $insDeclMariagePerim.$filtreDeclMariage);
        $spm = $q('t_declaration_mariage', $dim, $insDeclMariagePerim.$filtreDeclMariage);

        // ── Actes de mariage ───────────────────────────────────────────────
        $prma = $qActeMariageJour($lun);
        $dxma = $qActeMariageJour($mar);
        $trma = $qActeMariageJour($mer);
        $qtma = $qActeMariageJour($jeu);
        $cqma = $qActeMariageJour($ven);
        $sxma = $qActeMariageJour($sam);
        $spma = $qActeMariageJour($dim);

        // ── Cumuls (cartes du haut : tout le périmètre, sans filtre sur la semaine) ──
        $cumulDeclN = (int) (DB::select(
            "SELECT COUNT(*) AS TOTAL FROM t_declaration_naissance WHERE 1=1 {$insDeclNaissancePerim}{$filtreDeclNaissance}"
        )[0]->TOTAL ?? 0);
        $cumulDeclD = (int) (DB::select(
            "SELECT COUNT(*) AS TOTAL FROM t_declaration_deces WHERE 1=1 {$insDeclDecesPerim}{$filtreDeclDeces}"
        )[0]->TOTAL ?? 0);
        $cumulDeclM = (int) (DB::select(
            "SELECT COUNT(*) AS TOTAL FROM t_declaration_mariage WHERE 1=1 {$insDeclMariagePerim}{$filtreDeclMariage}"
        )[0]->TOTAL ?? 0);

        if ($vueGlobale) {
            $cumulActeN = (int) (DB::select(
                "SELECT COUNT(*) AS TOTAL FROM t_acte_naissance an WHERE 1=1 {$filtreActeNaissance}"
            )[0]->TOTAL ?? 0);
            $cumulActeD = (int) (DB::select(
                "SELECT COUNT(*) AS TOTAL FROM t_acte_deces ad WHERE 1=1 {$filtreActeDeces}"
            )[0]->TOTAL ?? 0);
            $cumulActeM = (int) (DB::select(
                "SELECT COUNT(*) AS TOTAL FROM t_acte_mariage am WHERE 1=1 {$filtreActeMariage}"
            )[0]->TOTAL ?? 0);
        } else {
            $cumulActeN = (int) (DB::select("
                SELECT COUNT(*) AS TOTAL
                FROM t_acte_naissance an
                INNER JOIN t_declaration_naissance dn ON dn.code_declaration_naissance = an.code_declaration_naissance
                WHERE 1=1 {$filtreActeNaissance}
                AND (dn.code_institution = '{$codeInstitution}' OR dn.code_institution_destinataire = '{$codeInstitution}')
                AND dn.type_declaration IN ({$inTypesNaissance})
                AND (dn.deleted_at IS NULL)
            ")[0]->TOTAL ?? 0);
            $cumulActeD = (int) (DB::select("
                SELECT COUNT(*) AS TOTAL
                FROM t_acte_deces ad
                INNER JOIN t_declaration_deces dd ON dd.code_declaration_deces = ad.code_declaration_deces
                WHERE 1=1 {$filtreActeDeces}
                AND (dd.code_institution = '{$codeInstitution}' OR dd.code_institution_destinataire = '{$codeInstitution}')
                AND dd.type_declaration IN ('DECLARATION DE DECES', 'DECLARATION TARDIVE')
                AND (dd.deleted_at IS NULL)
            ")[0]->TOTAL ?? 0);
            $cumulActeM = (int) (DB::select("
                SELECT COUNT(*) AS TOTAL
                FROM t_acte_mariage am
                INNER JOIN t_declaration_mariage dm ON dm.code_declaration_mariage = am.code_declaration_mariage
                WHERE 1=1 {$filtreActeMariage}
                AND (dm.code_institution = '{$codeInstitution}' OR dm.code_institution_destinataire = '{$codeInstitution}')
                AND dm.type_declaration = 'DECLARATION DE MARIAGE'
                AND (dm.deleted_at IS NULL)
            ")[0]->TOTAL ?? 0);
        }

        return view('admin.dashboard.index', compact(
            'pr', 'dx', 'tr', 'qt', 'cq', 'sx', 'sp',
            'prd', 'dxd', 'trd', 'qtd', 'cqd', 'sxd', 'spd',
            'pra', 'dxa', 'tra', 'qta', 'cqa', 'sxa', 'spa',
            'prb', 'dxb', 'trb', 'qtb', 'cqb', 'sxb', 'spb',
            'prm', 'dxm', 'trm', 'qtm', 'cqm', 'sxm', 'spm',
            'prma', 'dxma', 'trma', 'qtma', 'cqma', 'sxma', 'spma',
            'cumulDeclN', 'cumulActeN', 'cumulDeclD', 'cumulActeD', 'cumulDeclM', 'cumulActeM',
            'lun', 'dim',
            'codeTypeCategorie', 'codeTypeInstitution', 'libInstitution', 'vueGlobale'
        ));
    }

    public function carte()
    {
        $statdeces = DeclarationDeces::where(['type_declaration' => 'CERTIFICAT DE TRANSCRIPTION'])->count('code_declaration_deces');

        $statannuel = DeclarationDeces::where(['type_declaration' => 'CERTIFICAT DE TRANSCRIPTION'])
            ->where('date_heure_declaration', 'like', '%'.date('Y').'%')
            ->count('code_declaration_deces');

        $statmois = DeclarationDeces::where(['type_declaration' => 'CERTIFICAT DE TRANSCRIPTION'])
            ->where('date_heure_declaration', 'like', '%'.date('Y-m').'%')
            ->count('code_declaration_deces');

        $statnais = Declarationnaissance::where(['type_declaration' => 'CERTIFICAT DE TRANSCRIPTION'])->count('code_declaration_naissance');

        $statnaisannuel = Declarationnaissance::where(['type_declaration' => 'CERTIFICAT DE TRANSCRIPTION'])
            ->where('created_at', 'like', '%'.date('Y').'%')
            ->count('code_declaration_naissance');

        $statnaismois = Declarationnaissance::where(['type_declaration' => 'CERTIFICAT DE TRANSCRIPTION'])
            ->where('created_at', 'like', '%'.date('Y-m').'%')
            ->count('code_declaration_naissance');

        $defaultChartDebut = now()->subDays(30)->format('Y-m-d');
        $defaultChartFin = now()->format('Y-m-d');

        return view('admin.dashboard.carte', compact(
            'statdeces',
            'statannuel',
            'statmois',
            'statnais',
            'statnaisannuel',
            'statnaismois',
            'defaultChartDebut',
            'defaultChartFin'
        ));
    }

    public function authentification(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ],
            [
                'email.required' => "L'adresse mail est obligatoire.",
                'email.email' => "L'adresse mail n'est pas valide.",
                'password.required' => 'Le mot de passe est obligatoire.',
            ]
        );

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::whereEmail($email)->first();
        if ($user === null) {
            // Audit trail pour tentative de connexion avec email inexistant
            UserAuditTrail::log('UNKNOWN', 'login_failed', [], "Tentative de connexion avec email inexistant: {$email}");

            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => "Cette adresse mail n'est pas reconnue"]);
        }
        if (! Hash::check($password, $user->password)) {
            // Audit trail pour mot de passe incorrect
            UserAuditTrail::log($user->code_user, 'login_failed', [], 'Mot de passe incorrect');

            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['password' => 'Le mot de passe est incorrect.']);
        }

        if ($user->status == 0) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => "Votre compte n'est pas disponible, veuillez contacter l'administrateur principal"]);
        }

        if ($user->affectationActive() === null) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => "Votre compte n'est affecté à aucun centre d'état civil"]);
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
                '2fa:timestamp' => now()->timestamp,
            ]);

            // Rediriger vers la vérification 2FA
            flash()->info('Veuillez entrer votre code de vérification.');

            return redirect()->route('two-factor.verify');
        }

        // Connexion normale si pas de 2FA
        Auth::login($user, $request->filled('remember'));

        // Audit trail pour connexion réussie
        UserAuditTrail::log($user->code_user, 'login', [], 'Connexion réussie');

        if ($user->must_change_password) {
            return redirect()->route('first-login-password.show')
                ->with('first_login_notice', true);
        }

        flash()->success('Connexion réussie');

        return redirect()->route('dashboard.index');
        // return redirect()->route('home.index');

    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);
        if ($user == null) {
            flash()->error('Impossible de charger cette page');

            return back();
        }
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'status' => ['required'],
        ]);

        $email = $request->email;
        $pseudo = $request->pseudo;
        $status = $request->status;
        $password = $request->password;
        $new_password = $request->new_password;
        $confirm_password = $request->confirm_password;

        if (! Hash::check($password, $user->password)) {
            flash()->error("Ce mot de passe n'existe pas pour ce compte");

            return back();
        }
        if ($new_password !== $confirm_password) {
            flash()->error('Les deux mots de passe ne correspondent pas');

            return back();
        }

        $user->email = $email;
        $user->password = Hash::make($new_password);
        $user->pseudo = $pseudo;
        $user->status = $status;
        $user->save();

        flash()->success('Compte modifié avec succès');

        return back();

    }

    public function statGenreDep()
    {
        $annee = request('annee');
        if ($annee != '') {
            $annee = request('annee');
        } else {
            $annee = date('Y');
        }

        $join = "SELECT count(*) AS GENRE FROM t_declaration_naissance, tr_identification_personne, tr_ins_user, tr_institution, tr_localite
        WHERE t_declaration_naissance.code_enfant = tr_identification_personne.code_personne
        AND tr_ins_user.cui = t_declaration_naissance.code_user_institution
        AND tr_institution.code_institution = tr_ins_user.code_institution
        AND tr_localite.code_localite = tr_institution.code_localite
        AND YEAR(t_declaration_naissance.date_heure_declaration) = '".$annee."'";

        $brazzah = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0026' OR tr_localite.code_localite_parent = 'LOC_0026')
        ");

        $brazzaf = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0026' OR tr_localite.code_localite_parent = 'LOC_0026')
        ");

        $pnh = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0016' OR tr_localite.code_localite_parent = 'LOC_0016')
        ");

        $pnf = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0016' OR tr_localite.code_localite_parent = 'LOC_0016')
        ");

        $likoualah = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0003' OR tr_localite.code_localite_parent = 'LOC_0003')
        ");

        $likoualaf = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0003' OR tr_localite.code_localite_parent = 'LOC_0003')
        ");

        $sanghah = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0004' OR tr_localite.code_localite_parent = 'LOC_0004')
        ");

        $sanghaf = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0004' OR tr_localite.code_localite_parent = 'LOC_0004')
        ");

        $cuvetteoh = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0005' OR tr_localite.code_localite_parent = 'LOC_0005')
        ");

        $cuvetteof = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0005' OR tr_localite.code_localite_parent = 'LOC_0005')
        ");

        $cuvetteh = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0006' OR tr_localite.code_localite_parent = 'LOC_0006')
        ");

        $cuvettef = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0006' OR tr_localite.code_localite_parent = 'LOC_0006')
        ");

        $plateauh = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0007' OR tr_localite.code_localite_parent = 'LOC_0007')
        ");

        $plateauf = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0007' OR tr_localite.code_localite_parent = 'LOC_0007')
        ");

        $poolh = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0008' OR tr_localite.code_localite_parent = 'LOC_0008')
        ");

        $poolf = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0008' OR tr_localite.code_localite_parent = 'LOC_0008')
        ");

        $lekoumouh = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0009' OR tr_localite.code_localite_parent = 'LOC_0009')
        ");

        $lekoumouf = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0009' OR tr_localite.code_localite_parent = 'LOC_0009')
        ");

        $bouenzah = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0010' OR tr_localite.code_localite_parent = 'LOC_0010')
        ");

        $bouenzaf = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0010' OR tr_localite.code_localite_parent = 'LOC_0010')
        ");

        $niarih = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0011' OR tr_localite.code_localite_parent = 'LOC_0011')
        ");

        $niarif = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0011' OR tr_localite.code_localite_parent = 'LOC_0011')
        ");

        $kouilouh = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'M'
        AND (tr_localite.code_localite = 'LOC_0012' OR tr_localite.code_localite_parent = 'LOC_0012')
        ");

        $kouilouf = DB::select('
        '.$join."
        AND tr_identification_personne.SEXE = 'F'
        AND (tr_localite.code_localite = 'LOC_0012' OR tr_localite.code_localite_parent = 'LOC_0012')
        ");

        // dd($kouilouf);

        return view('admin.dashboard.statistiques', compact('annee', 'brazzah', 'brazzaf',

            'pnh',

            'pnf',

            'likoualah',

            'likoualaf',

            'sanghah',

            'sanghaf',

            'cuvetteoh',

            'cuvetteof',

            'cuvetteh',

            'cuvettef',

            'plateauh',

            'plateauf',

            'poolh',

            'poolf',

            'lekoumouh',

            'lekoumouf',

            'bouenzah',

            'bouenzaf',

            'niarih',

            'niarif',

            'kouilouh', [], 'kouilouf'));
    }

    public function home()
    {
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

        flash()->success('Votre mot de passe a été enregistré. Bienvenue sur SIFEC.');

        return redirect()->route('dashboard.index');
    }
}
