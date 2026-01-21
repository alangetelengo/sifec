<?php

namespace App\Http\Controllers;

use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Support\Facades\DB;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Localite;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Remonte la hiérarchie des localités pour trouver la commune ou le district
     * @param string|null $codeLocalite
     * @return string|null Code de la commune (TPLOC_0003) ou du district (TPLOC_0002)
     */
    private function getCommuneOrDistrictCode($codeLocalite)
    {
        if (!$codeLocalite) {
            return null;
        }

        $localite = Localite::find($codeLocalite);
        if (!$localite) {
            return null;
        }

        // Remonter la hiérarchie jusqu'à trouver une commune ou un district
        $current = $localite;
        while ($current) {
            // Si c'est une commune (TPLOC_0003) ou un district (TPLOC_0002), retourner son code
            if ($current->code_type_localite === 'TPLOC_0003' || $current->code_type_localite === 'TPLOC_0002') {
                return $current->code_localite;
            }
            // Remonter au parent
            $current = $current->localiteParent;
        }

        return null;
    }

    /**
     * Vérifie si une localité (ou un de ses parents) appartient à la commune/district spécifié
     * @param Localite|null $localite
     * @param string $codeCommuneOrDistrict
     * @return bool
     */
    private function isInCommuneOrDistrict($localite, $codeCommuneOrDistrict)
    {
        if (!$localite) {
            return false;
        }

        $current = $localite;
        while ($current) {
            // Si la localité elle-même est la commune/district recherché
            if ($current->code_localite === $codeCommuneOrDistrict) {
                return true;
            }

            // Si c'est un arrondissement (TPLOC_0004)
            if ($current->code_type_localite === 'TPLOC_0004' && $current->localiteParent) {
                // L'arrondissement appartient à une commune, vérifier si c'est la commune recherchée
                $commune = $current->localiteParent;
                if ($commune->code_localite === $codeCommuneOrDistrict) {
                    return true;
                }
            }
            // Si c'est une communauté urbaine (TPLOC_0005)
            elseif ($current->code_type_localite === 'TPLOC_0005' && $current->localiteParent) {
                // La communauté urbaine appartient à un district, vérifier si c'est le district recherché
                $district = $current->localiteParent;
                if ($district->code_localite === $codeCommuneOrDistrict) {
                    return true;
                }
            }
            // Remonter au parent pour continuer la recherche
            $current = $current->localiteParent;
        }

        return false;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }

    public function tableaudebord()
    {
        $fonction = Auth()->user()->AffectationActive()->fonction->code_fonction;

        if ($fonction == 'FONC_0002' || $fonction == 'FONC_0004') {
            $mairie = Auth()->user()->AffectationActive()->institution->lib_institution;
            $cui = Auth()->user()->AffectationActive()->cui;
            $codeinst = Auth()->user()->AffectationActive()->institution->code_institution;
            $codeinstfille = Auth()->user()->AffectationActive()->institution->code_institution;

            $insts = Institution::where('code_institution_parent', $codeinst)->get();

            // TOUTES LES DECLARATIONS
            $declarationcumul = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            ");

            $declarationannee = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND EXTRACT(YEAR FROM t_declaration_naissance.date_heure_declaration) = EXTRACT(YEAR FROM CURRENT_DATE)
            ");

            $declarationmois = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND EXTRACT(MONTH FROM t_declaration_naissance.date_heure_declaration) = EXTRACT(MONTH FROM CURRENT_DATE)
            ");

            $declarationsemaine = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND EXTRACT(WEEK FROM t_declaration_naissance.date_heure_declaration) = EXTRACT(WEEK FROM CURRENT_DATE)
            ");

            $declarationjour = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND to_date(t_declaration_naissance.date_heure_declaration::TEXT,'YYYY-MM-DD')= to_date(CURRENT_DATE::TEXT,'YYYY-MM-DD')
            ");

            // DECLARATIONS ENVOYEES
            $denvoyercum = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            ");

            $denvoyeran = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            AND EXTRACT(YEAR FROM t_declaration_naissance.date_heure_declaration) = EXTRACT(YEAR FROM CURRENT_DATE)
            ");

            $denvoyermois = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            AND EXTRACT(MONTH FROM t_declaration_naissance.date_heure_declaration) = EXTRACT(MONTH FROM CURRENT_DATE)
            ");

            $denvoyersemaine = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            AND EXTRACT(WEEK FROM t_declaration_naissance.date_heure_declaration) = EXTRACT(WEEK FROM CURRENT_DATE)
            ");

            $denvoyerjour = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            AND to_date(t_declaration_naissance.date_heure_declaration::TEXT,'YYYY-MM-DD')= to_date(CURRENT_DATE::TEXT,'YYYY-MM-DD')
            ");

            // Actes de naissance produits
            $acteproduits = ActeNaissance::where("cui",$cui)->get()->count();
            $acteannee = ActeNaissance::where("cui",$cui)->whereYear('created_at', date('Y'))->get()->count();
            $actemois = ActeNaissance::where("cui",$cui)->whereMonth('created_at', date('m'))->get()->count();
            $actesemaine = ActeNaissance::where("cui",$cui)->whereRaw('EXTRACT(WEEK FROM t_acte_naissance.created_at) = ' . date('W'))->get()->count();
            $actesjour = ActeNaissance::where("cui",$cui)->where('created_at','LIKE' ,'%'.date('Y-m-d').'%')->get()->count();

            // Actes de naissance validés
            $validesv = "";
            $acteproduitsv = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->get()->count();
            $acteanneev = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->whereYear('created_at', date('Y'))->get()->count();
            $actemoisv = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->whereMonth('created_at', date('m'))->get()->count();
            $actesemainev =ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->whereRaw('EXTRACT(WEEK FROM t_acte_naissance.created_at) = ' . date('W'))->get()->count();;
            $actesjourv = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->where('created_at','LIKE' ,'%'.date('Y-m-d').'%')->get()->count();

            // Actes de non validés
            $validesn = "";
            $acteproduitsn = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->get()->count();
            $acteanneen = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->whereYear('created_at', date('Y'))->get()->count();
            $actemoisn = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->whereMonth('created_at', date('m'))->get()->count();
            $actesemainen = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->whereRaw('EXTRACT(WEEK FROM t_acte_naissance.created_at) = ' . date('W'))->get()->count();;
            $actesjourn = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->where('created_at','LIKE' ,'%'.date('Y-m-d').'%')->get()->count();

            return view("admin.dashboard.tableau_mairie", compact('mairie','acteproduits','acteannee','actemois','actesemaine','actesjour','acteproduitsv','acteanneev','actemoisv','actesemainev','actesjourv','acteproduitsn','acteanneen','actemoisn','actesemainen','actesjourn','insts', 'declarationcumul','declarationannee','declarationmois','declarationsemaine','declarationjour','denvoyercum','denvoyeran','denvoyermois','denvoyersemaine','denvoyerjour'));

        }elseif ($fonction == "FONC_0013") {
            // Récupérer la localité de l'institution de l'utilisateur
            $userInstitution = Auth()->user()->AffectationActive()->institution;
            $codeLocalite = $userInstitution->code_localite;

            // Remonter la hiérarchie pour trouver la commune ou le district
            $code = $this->getCommuneOrDistrictCode($codeLocalite);

            if (!$code) {
                toastr()->error("Impossible de déterminer la commune ou le district de l'institution");
                return redirect()->back();
            }

            $fonction = Auth()->user()->AffectationActive()->fonction->code_fonction;

            $institutions = Institution::with('lieu')->get();

            $liste = [];
            $mesinstitutions = [];
            foreach ($institutions as $key) {
                // Utiliser la nouvelle méthode pour vérifier si l'institution est dans la commune/district
                if ($this->isInCommuneOrDistrict($key->lieu, $code)) {
                    $liste[] = $key->code_institution;
                    $mesinstitutions[] = $key->lib_institution;
                }
            }

            // dd($mesinstitutions);

            $array = implode("','",$liste);

             // TOUTES LES ACTES
             $acteproduits = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             ");

             $acteannee = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND EXTRACT(YEAR FROM t_acte_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $actemois = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND EXTRACT(MONTH FROM t_declaration_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $actesemaine = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND WEEK(t_acte_naissance.created_at) = WEEK(CURDATE())
             AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $actesjour = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND date(t_acte_naissance.created_at) = CURDATE()
             ");

             // ACTES VALIDES
             $acteproduitsv = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '1'
             ");

             $acteanneev = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '1'
            AND EXTRACT(YEAR FROM t_acte_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $actemoisv = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '1'
             AND EXTRACT(MONTH FROM t_acte_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $actesemainev = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '1'
             AND WEEK(t_acte_naissance.created_at) = WEEK(CURDATE())
             AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $actesjourv = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '1'
             AND date(t_acte_naissance.created_at) = CURDATE()
             ");


             // ACTES NON VALIDES
             $acteproduitsn = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '0'
             ");

             $acteanneen = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '0'
            AND EXTRACT(YEAR FROM t_acte_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $actemoisn = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '0'
             AND EXTRACT(MONTH FROM t_acte_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $actesemainen = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '0'
             AND WEEK(t_acte_naissance.created_at) = WEEK(CURDATE())
             AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $actesjourn = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '0'
             AND date(t_acte_naissance.created_at) = CURDATE()
             ");

              ///////////////////////////////////////////////////////////////////////////////
            //   DECES
             // TOUTES LES ACTES
             $dacteproduits = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             ");

             $dacteannee = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
            AND EXTRACT(YEAR FROM t_acte_deces.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $dactemois = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND EXTRACT(MONTH FROM t_acte_deces.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $dactesemaine = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND WEEK(t_acte_deces.created_at) = WEEK(CURDATE())
             AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $dactesjour = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND date(t_acte_deces.created_at) = CURDATE()
             ");

             // ACTES VALIDES
             $dacteproduitsv = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '1'
             ");

             $dacteanneev = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '1'
            AND EXTRACT(YEAR FROM t_acte_deces.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $dactemoisv = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '1'
             AND EXTRACT(MONTH FROM t_acte_deces.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $dactesemainev = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '1'
             AND WEEK(t_acte_deces.created_at) = WEEK(CURDATE())
             AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $dactesjourv = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '1'
             AND date(t_acte_deces.created_at) = CURDATE()
             ");


             // ACTES NON VALIDES
             $dacteproduitsn = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '0'
             ");

             $dacteanneen = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '0'
            AND EXTRACT(YEAR FROM t_acte_deces.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $dactemoisn = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '0'
             AND EXTRACT(MONTH FROM t_acte_deces.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $dactesemainen = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '0'
             AND WEEK(t_acte_deces.created_at) = WEEK(CURDATE())
             AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $dactesjourn = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '0'
             AND date(t_acte_deces.created_at) = CURDATE()
             ");

            // dd($array);
            return view("admin.dashboard.tableau_prefet", compact('mesinstitutions','acteproduits','acteannee','actemois','actesemaine','actesjour','acteproduitsv','acteanneev','actemoisv','actesemainev','actesjourv','acteproduitsn','acteanneen','actemoisn','actesemainen','actesjourn', 'dacteproduits','dacteannee','dactemois','dactesemaine','dactesjour','dacteproduitsv','dacteanneev','dactemoisv','dactesemainev','dactesjourv','dacteproduitsn','dacteanneen','dactemoisn','dactesemainen','dactesjourn'));
        }else{
            toastr('Tableau de bord non disponible', 'warning');
            return redirect()->back();
        }

    }

    public function impressiontableau()
    {
        $fonction = Auth()->user()->AffectationActive()->fonction->code_fonction;

        if ($fonction == 'FONC_0002' || $fonction == 'FONC_0004') {
            $mairie = Auth()->user()->AffectationActive()->institution->lib_institution;
            $cui = Auth()->user()->AffectationActive()->cui;
            $codeinst = Auth()->user()->AffectationActive()->institution->code_institution;
            $codeinstfille = Auth()->user()->AffectationActive()->institution->code_institution;

            $insts = Institution::where('code_institution_parent', $codeinst)->get();

            // TOUTES LES DECLARATIONS
            $declarationcumul = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            ");

            $declarationannee = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND EXTRACT(YEAR FROM t_declaration_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND YEAR(t_declaration_naissance.date_heure_declaration) = YEAR(CURDATE())
            ");

            $declarationmois = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND EXTRACT(MONTH FROM t_declaration_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
            AND MONTH(t_declaration_naissance.date_heure_declaration) = MONTH(CURDATE())
            ");

            $declarationsemaine = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND WEEK(t_declaration_naissance.date_heure_declaration) = WEEK(CURDATE())
            AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
            ");

            $declarationjour = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND date(t_declaration_naissance.date_heure_declaration) = CURDATE()
            ");

            // DECLARATIONS ENVOYEES
            $denvoyercum = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            ");

            $denvoyeran = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND t_mouvement_naissance.statut = 'Envoyée'
                    AND EXTRACT(YEAR FROM t_declaration_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND YEAR(t_declaration_naissance.date_heure_declaration) = YEAR(CURDATE())
            ");

            $denvoyermois = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            AND EXTRACT(MONTH FROM t_declaration_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
            AND MONTH(t_declaration_naissance.date_heure_declaration) = MONTH(CURDATE())
            ");

            $denvoyersemaine = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            AND WEEK(t_declaration_naissance.date_heure_declaration) = WEEK(CURDATE())
            AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
            ");

            $denvoyerjour = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution_parent = '".Auth()->user()->AffectationActive()->institution->code_institution."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            AND date(t_declaration_naissance.date_heure_declaration) = CURDATE()
            ");

            // Actes de naissance produits
            $acteproduits = ActeNaissance::where("cui",$cui)->get()->count();
            $acteannee = ActeNaissance::where("cui",$cui)->whereYear('created_at', date('Y'))->get()->count();
            $actemois = ActeNaissance::where("cui",$cui)->whereMonth('created_at', date('m'))->get()->count();
            $actesemaine = ActeNaissance::where("cui",$cui)->whereRaw('WEEK(created_at) = ' . date('W'))->get()->count();
            $actesjour = ActeNaissance::where("cui",$cui)->where('created_at','LIKE' ,'%'.date('Y-m-d').'%')->get()->count();

            // Actes de naissance validés
            $validesv = "";
            $acteproduitsv = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->get()->count();
            $acteanneev = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->whereYear('created_at', date('Y'))->get()->count();
            $actemoisv = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->whereMonth('created_at', date('m'))->get()->count();
            $actesemainev =ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->whereRaw('WEEK(created_at) = ' . date('W'))->get()->count();;
            $actesjourv = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->where('created_at','LIKE' ,'%'.date('Y-m-d').'%')->get()->count();

            // Actes de non validés
            $validesn = "";
            $acteproduitsn = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->get()->count();
            $acteanneen = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->whereYear('created_at', date('Y'))->get()->count();
            $actemoisn = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->whereMonth('created_at', date('m'))->get()->count();
            $actesemainen = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->whereRaw('WEEK(created_at) = ' . date('W'))->get()->count();;
            $actesjourn = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->where('created_at','LIKE' ,'%'.date('Y-m-d').'%')->get()->count();

            view()->share("tester", "Vincent");
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML(view('naissance::etats.tableaudebord', compact('mairie','acteproduits','acteannee','actemois','actesemaine','actesjour','acteproduitsv','acteanneev','actemoisv','actesemainev','actesjourv','acteproduitsn','acteanneen','actemoisn','actesemainen','actesjourn','insts', 'declarationcumul','declarationannee','declarationmois','declarationsemaine','declarationjour','denvoyercum','denvoyeran','denvoyermois','denvoyersemaine','denvoyerjour'))->render());

            return $html2pdf->output("tableaudebord.pdf");


        }else{
            toastr('Tableau de bord non disponible', 'warning');
            return redirect()->back();
        }
    }

    public function impressiontableauprefet()
    {
            // Récupérer la localité de l'institution de l'utilisateur
            $userInstitution = Auth()->user()->AffectationActive()->institution;
            $codeLocalite = $userInstitution->code_localite;

            // Remonter la hiérarchie pour trouver la commune ou le district
            $code = $this->getCommuneOrDistrictCode($codeLocalite);

            if (!$code) {
                toastr()->error("Impossible de déterminer la commune ou le district de l'institution");
                return redirect()->back();
            }

            $fonction = Auth()->user()->AffectationActive()->fonction->code_fonction;

            $institutions = Institution::with('lieu')->get();

            $liste = [];
            $mesinstitutions = [];
            foreach ($institutions as $key) {
                // Utiliser la nouvelle méthode pour vérifier si l'institution est dans la commune/district
                if ($this->isInCommuneOrDistrict($key->lieu, $code)) {
                    $liste[] = $key->code_institution;
                    $mesinstitutions[] = $key->lib_institution;
                }
            }

            // dd($mesinstitutions);

            $array = implode("','",$liste);

             // TOUTES LES ACTES
             $acteproduits = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             ");

             $acteannee = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
            AND EXTRACT(YEAR FROM t_acte_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $actemois = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND EXTRACT(MONTH FROM t_acte_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $actesemaine = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND EXTRACT(WEEK FROM t_acte_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $actesjour = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
            AND to_date(t_acte_naissance.created_at::TEXT,'YYYY-MM-DD')= to_date(CURRENT_DATE::TEXT,'YYYY-MM-DD')
             ");

             // ACTES VALIDES
             $acteproduitsv = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '1'
             ");

             $acteanneev = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '1'
            AND EXTRACT(YEAR FROM t_acte_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $actemoisv = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '1'
             AND EXTRACT(MONTH FROM t_acte_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $actesemainev = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '1'
             AND EXTRACT(WEEK FROM t_acte_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $actesjourv = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '1'
            AND to_date(t_acte_naissance.created_at::TEXT,'YYYY-MM-DD')= to_date(CURRENT_DATE::TEXT,'YYYY-MM-DD')
             ");


             // ACTES NON VALIDES
             $acteproduitsn = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '0'
             ");

             $acteanneen = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '0'
            AND EXTRACT(YEAR FROM t_acte_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $actemoisn = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '0'
             AND EXTRACT(MONTH FROM t_acte_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $actesemainen = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '0'
             AND EXTRACT(WEEK FROM t_acte_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $actesjourn = DB::select("SELECT count(*) as total
             FROM t_acte_naissance
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_naissance.approbation_mairie = '0'
            AND to_date(t_acte_naissance.created_at::TEXT,'YYYY-MM-DD')= to_date(CURRENT_DATE::TEXT,'YYYY-MM-DD')
             ");

              ///////////////////////////////////////////////////////////////////////////////
            //   DECES
             // TOUTES LES ACTES
             $dacteproduits = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             ");

             $dacteannee = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
            AND EXTRACT(YEAR FROM t_acte_deces.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $dactemois = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND EXTRACT(MONTH FROM t_acte_deces.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $dactesemaine = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND EXTRACT(WEEK FROM t_acte_deces.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $dactesjour = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
            AND to_date(t_acte_deces.created_at::TEXT,'YYYY-MM-DD')= to_date(CURRENT_DATE::TEXT,'YYYY-MM-DD')
             ");

             // ACTES VALIDES
             $dacteproduitsv = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '1'
             ");

             $dacteanneev = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '1'
            AND EXTRACT(YEAR FROM t_acte_deces.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $dactemoisv = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '1'
             AND EXTRACT(MONTH FROM t_acte_deces.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $dactesemainev = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '1'
             AND EXTRACT(WEEK FROM t_acte_deces.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $dactesjourv = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '1'
            AND to_date(t_acte_deces.created_at::TEXT,'YYYY-MM-DD')= to_date(CURRENT_DATE::TEXT,'YYYY-MM-DD')
             ");


             // ACTES NON VALIDES
             $dacteproduitsn = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '0'
             ");

             $dacteanneen = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '0'
            AND EXTRACT(YEAR FROM t_acte_deces.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
             ");

             $dactemoisn = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '0'
             AND EXTRACT(MONTH FROM t_acte_deces.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
             ");

             $dactesemainen = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '0'
             AND WEEK(t_acte_deces.created_at) = WEEK(CURDATE())
             AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
             ");

             $dactesjourn = DB::select("SELECT count(*) as total
             FROM t_acte_deces
             JOIN tr_ins_user ON tr_ins_user.cui = t_acte_deces.cui
             JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
             WHERE tr_institution.code_institution IN ('".$array."')
             AND t_acte_deces.approbation_pompe_funebre = '0'
            AND to_date(t_acte_deces.created_at::TEXT,'YYYY-MM-DD')= to_date(CURRENT_DATE::TEXT,'YYYY-MM-DD')
             ");

            // dd($array);
            // return view("admin.dashboard.tableau_prefet", compact('mesinstitutions','acteproduits','acteannee','actemois','actesemaine','actesjour','acteproduitsv','acteanneev','actemoisv','actesemainev','actesjourv','acteproduitsn','acteanneen','actemoisn','actesemainen','actesjourn', 'dacteproduits','dacteannee','dactemois','dactesemaine','dactesjour','dacteproduitsv','dacteanneev','dactemoisv','dactesemainev','dactesjourv','dacteproduitsn','dacteanneen','dactemoisn','dactesemainen','dactesjourn'));

            view()->share("tester", "Vincent");
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML(view('naissance::etats.tableaudebordprefet', compact('mesinstitutions','acteproduits','acteannee','actemois','actesemaine','actesjour','acteproduitsv','acteanneev','actemoisv','actesemainev','actesjourv','acteproduitsn','acteanneen','actemoisn','actesemainen','actesjourn', 'dacteproduits','dacteannee','dactemois','dactesemaine','dactesjour','dacteproduitsv','dacteanneev','dactemoisv','dactesemainev','dactesjourv','dacteproduitsn','dacteanneen','dactemoisn','dactesemainen','dactesjourn'))->render());

            return $html2pdf->output("tableaudebord.pdf");

    }

    public function impressiondetails($id)
    {
        $fonction = Auth()->user()->AffectationActive()->fonction->code_fonction;

        if ($fonction == 'FONC_0002' || $fonction == 'FONC_0004') {
            $mairie = Auth()->user()->AffectationActive()->institution->lib_institution;
            $cui = Auth()->user()->AffectationActive()->cui;
            $codeinst = Auth()->user()->AffectationActive()->institution->code_institution;
            $codeinstfille = Auth()->user()->AffectationActive()->institution->code_institution;

            $insts = Institution::where('code_institution_parent', $codeinst)->get();

            // TOUTES LES DECLARATIONS
            $declarationcumul = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution = '".$id."'
            ");

            $declarationannee = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution = '".$id."'
            AND EXTRACT(YEAR FROM t_declaration_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND YEAR(t_declaration_naissance.date_heure_declaration) = YEAR(CURDATE())
            ");

            $declarationmois = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution = '".$id."'
            AND EXTRACT(MONTH FROM t_declaration_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
            AND MONTH(t_declaration_naissance.date_heure_declaration) = MONTH(CURDATE())
            ");

            $declarationsemaine = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution = '".$id."'
            AND WEEK(t_declaration_naissance.date_heure_declaration) = WEEK(CURDATE())
            AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
            ");

            $declarationjour = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            WHERE tr_institution.code_institution = '".$id."'
            AND date(t_declaration_naissance.date_heure_declaration) = CURDATE()
            ");

            // DECLARATIONS ENVOYEES
            $denvoyercum = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            ");

            $denvoyeran = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_mouvement_naissance.statut = 'Envoyée'
                    AND EXTRACT(YEAR FROM t_declaration_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND YEAR(t_declaration_naissance.date_heure_declaration) = YEAR(CURDATE())
            ");

            $denvoyermois = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            AND EXTRACT(MONTH FROM t_declaration_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
            AND MONTH(t_declaration_naissance.date_heure_declaration) = MONTH(CURDATE())
            ");

            $denvoyersemaine = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            AND WEEK(t_declaration_naissance.date_heure_declaration) = WEEK(CURDATE())
            AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
            ");

            $denvoyerjour = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_mouvement_naissance ON t_mouvement_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_mouvement_naissance.statut = 'Envoyée'
            AND date(t_declaration_naissance.date_heure_declaration) = CURDATE()
            ");

            // Actes de naissance produits
            // $ = ActeNaissance::where("cui",$cui)->get()->count();
            // $ = ActeNaissance::where("cui",$cui)->whereYear('created_at', date('Y'))->get()->count();
            // $ = ActeNaissance::where("cui",$cui)->whereMonth('created_at', date('m'))->get()->count();
            // $ = ActeNaissance::where("cui",$cui)->whereRaw('WEEK(created_at) = ' . date('W'))->get()->count();
            // $ = ActeNaissance::where("cui",$cui)->where('created_at','LIKE' ,'%'.date('Y-m-d').'%')->get()->count();

            // Actes de naissance validés
            // $validesv = "";
            // $ = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->get()->count();
            // $ = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->whereYear('created_at', date('Y'))->get()->count();
            // $ = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->whereMonth('created_at', date('m'))->get()->count();
            // $ =ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->whereRaw('WEEK(created_at) = ' . date('W'))->get()->count();;
            // $ = ActeNaissance::where("cui",$cui)->where('approbation_mairie',1)->where('created_at','LIKE' ,'%'.date('Y-m-d').'%')->get()->count();

            // Actes de non validés
            // $validesn = "";
            // $ = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->get()->count();
            // $ = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->whereYear('created_at', date('Y'))->get()->count();
            // $ = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->whereMonth('created_at', date('m'))->get()->count();
            // $ = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->whereRaw('WEEK(created_at) = ' . date('W'))->get()->count();;
            // $ = ActeNaissance::where("cui",$cui)->where('approbation_mairie',0)->where('created_at','LIKE' ,'%'.date('Y-m-d').'%')->get()->count();

            // TOUTES LES ACTES
            $acteproduits = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            ");

            $acteannee = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND EXTRACT(YEAR FROM t_declaration_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND YEAR(t_acte_naissance.created_at) = YEAR(CURDATE())
            ");

            $actemois = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND EXTRACT(MONTH FROM t_declaration_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
            AND MONTH(t_acte_naissance.created_at) = MONTH(CURDATE())
            ");

            $actesemaine = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND WEEK(t_acte_naissance.created_at) = WEEK(CURDATE())
            AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
            ");

            $actesjour = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND date(t_acte_naissance.created_at) = CURDATE()
            ");

            // ACTES VALIDES
            $acteproduitsv = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_acte_naissance.approbation_mairie = '1'
            ");

            $acteanneev = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_acte_naissance.approbation_mairie = '1'
                    AND EXTRACT(YEAR FROM t_declaration_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND YEAR(t_acte_naissance.created_at) = YEAR(CURDATE())
            ");

            $actemoisv = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_acte_naissance.approbation_mairie = '1'
            AND EXTRACT(MONTH FROM t_declaration_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
            AND MONTH(t_acte_naissance.created_at) = MONTH(CURDATE())
            ");

            $actesemainev = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_acte_naissance.approbation_mairie = '1'
            AND WEEK(t_acte_naissance.created_at) = WEEK(CURDATE())
            AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
            ");

            $actesjourv = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_acte_naissance.approbation_mairie = '1'
            AND date(t_acte_naissance.created_at) = CURDATE()
            ");


            // ACTES NON VALIDES
            $acteproduitsn = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_acte_naissance.approbation_mairie = '0'
            ");

            $acteanneen = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_acte_naissance.approbation_mairie = '0'
                    AND EXTRACT(YEAR FROM t_declaration_naissance.created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
            AND YEAR(t_acte_naissance.created_at) = YEAR(CURDATE())
            ");

            $actemoisn = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_acte_naissance.approbation_mairie = '0'
            AND EXTRACT(MONTH FROM t_declaration_naissance.created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
            AND MONTH(t_acte_naissance.created_at) = MONTH(CURDATE())
            ");

            $actesemainen = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_acte_naissance.approbation_mairie = '0'
            AND WEEK(t_acte_naissance.created_at) = WEEK(CURDATE())
            AND EXTRACT(WEEK FROM t_declaration_naissance.created_at) = EXTRACT(WEEK FROM CURRENT_DATE)
            ");

            $actesjourn = DB::select("SELECT count(*) as total
            FROM t_declaration_naissance
            JOIN tr_ins_user ON tr_ins_user.cui = t_declaration_naissance.code_user_institution
            JOIN tr_institution  ON tr_institution.code_institution = tr_ins_user.code_institution
            JOIN t_acte_naissance ON t_acte_naissance.code_declaration_naissance = t_declaration_naissance.code_declaration_naissance
            WHERE tr_institution.code_institution = '".$id."'
            AND t_acte_naissance.approbation_mairie = '0'
            AND date(t_acte_naissance.created_at) = CURDATE()
            ");

            $hopital = Institution::find($id)->lib_institution;
            // dd($hopital);


            view()->share("tester", "Vincent");
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML(view('naissance::etats.tableaudeborddetails', compact('mairie','acteproduits','acteannee','actemois','actesemaine','actesjour','acteproduitsv','acteanneev','actemoisv','actesemainev','actesjourv','acteproduitsn','acteanneen','actemoisn','actesemainen','actesjourn','insts', 'declarationcumul','declarationannee','declarationmois','declarationsemaine','declarationjour','denvoyercum','denvoyeran','denvoyermois','denvoyersemaine','denvoyerjour','hopital'))->render());

            return $html2pdf->output("tableaudebord.pdf");


        }else{
            toastr('Tableau de bord non disponible', 'warning');
            return redirect()->back();
        }
    }
}
