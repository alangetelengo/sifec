<?php

namespace Modules\Reporting\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spipu\Html2Pdf\Html2Pdf;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Localite;
use Modules\Reporting\Entities\Copie;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deces\Entities\ActeDeces;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Naissance\Entities\ActeNaissance;
use Symfony\Component\HttpFoundation\Response;

class ReportingController extends Controller
{
    /** Mairies : centres d'état civil traitant les naissances (exclut pompes funèbres et centre d'hygiène). */
    private const TYPE_INSTITUTION_CEC_NAISSANCE = 'TPINS_0002';

    private const MOIS_ANNUAIRE_COURTS = [
        1 => 'janv', 2 => 'fév', 3 => 'mars', 4 => 'avril', 5 => 'mai', 6 => 'juin',
        7 => 'juil', 8 => 'aout', 9 => 'sept', 10 => 'oct', 11 => 'nov', 12 => 'déc',
    ];

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('reporting::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('reporting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('reporting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('reporting::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function genererCopie(Request $request)
    {
        $numActe = ActeNaissance::findByIdentifier($request->numero_acte) ?? ActeDeces::find($request->numero_acte) ?? ActeMariage::find($request->numero_acte);

        return response()->json($request->all());

        if($numActe == null || $numActe == ""){
            return response()->json([
                "code"=>"180",
                "message"=>"Aucun acte trouvé pour ce numéro"
            ]);
        }

        try {

            $copie = new Copie;
            $copie->numero_acte = $request->numero_acte;
            $copie->new_nom = $request->new_nom;
            $copie->new_prenom = $request->new_prenom;
            $copie->new_date_naissance = $request->new_date_naissance;
            $copie->reference_document = $request->reference_document;
            $copie->date_document = $request->date_document;
            $copie->libelle_document = $request->libelle_document;
            $copie->lieu_delivrance_document = $request->lieu_delivrance_document;
            $copie->signature_officier = $request->signature_officier;
            $copie->nom_officier = $request->nom_officier;
            $copie->save();

            return response()->json([
                "code"=>"200",
                "message"=>["reponse"=>"Copie d'acte générée avec succès"]
            ]);

        } catch (Exception $e) {
            Log::channel("sifec")->error($e->getMessage());
            return response()->json([
                "code"=> "201",
                "message"=> $e->getMessage()
            ]);
        }
    }


    public function dashbordRecette()
    {
        return view("reporting::rapports.index");
    }

    public function rapportPeriodique(Request $request)
    {
        $dateDebut = $request->input('dated', Carbon::now()->startOfMonth()->toDateString());
        $dateFin = $request->input('datef', Carbon::now()->toDateString());

        [$dateDebut, $dateFin] = $this->normalizeDates($dateDebut, $dateFin);
        $reportData = $this->buildReportData($dateDebut, $dateFin);

        return view('reporting::rapports.periodique', $reportData);
    }

    public function rapportPeriodiquePdf(Request $request)
    {
        $dateDebut = $request->input('dated', Carbon::now()->startOfMonth()->toDateString());
        $dateFin = $request->input('datef', Carbon::now()->toDateString());
        [$dateDebut, $dateFin] = $this->normalizeDates($dateDebut, $dateFin);
        $reportData = $this->buildReportData($dateDebut, $dateFin);

        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('reporting::rapports.periodique-pdf', $reportData)->render());

        return $html2pdf->output('rapport_periodique_'.$dateDebut.'_au_'.$dateFin.'.pdf');
    }

    private function normalizeDates(string $dateDebut, string $dateFin): array
    {
        if ($dateDebut > $dateFin) {
            [$dateDebut, $dateFin] = [$dateFin, $dateDebut];
        }

        return [$dateDebut, $dateFin];
    }

    private function buildReportData(string $dateDebut, string $dateFin): array
    {
        $naissances = ActeNaissance::whereDate('date_emission', '>=', $dateDebut)
            ->whereDate('date_emission', '<=', $dateFin)
            ->count();
        $mariages = ActeMariage::whereDate('date_emission', '>=', $dateDebut)
            ->whereDate('date_emission', '<=', $dateFin)
            ->count();
        $deces = ActeDeces::whereDate('date_emission', '>=', $dateDebut)
            ->whereDate('date_emission', '<=', $dateFin)
            ->count();
        $mois = [];
        $cursor = Carbon::parse($dateDebut)->startOfMonth();
        $finMois = Carbon::parse($dateFin)->startOfMonth();

        while ($cursor <= $finMois) {
            $periode = $cursor->format('Y-m');
            $mois[] = [
                'label' => $cursor->translatedFormat('F Y'),
                'naissances' => ActeNaissance::whereRaw("DATE_FORMAT(date_emission, '%Y-%m') = ?", [$periode])->count(),
                'mariages' => ActeMariage::whereRaw("DATE_FORMAT(date_emission, '%Y-%m') = ?", [$periode])->count(),
                'deces' => ActeDeces::whereRaw("DATE_FORMAT(date_emission, '%Y-%m') = ?", [$periode])->count(),
            ];
            $cursor->addMonthNoOverflow();
        }

        return [
            'dated' => $dateDebut,
            'datef' => $dateFin,
            'naissances' => $naissances,
            'mariages' => $mariages,
            'deces' => $deces,
            'totalActes' => $naissances + $deces + $mariages,
            'mois' => $mois,
        ];
    }

    /**
     * Affiche le formulaire du rapport d'exploitation des actes de naissance
     */
    public function rapportExploitationNaissance(Request $request)
    {
        $departements = Localite::where('code_type_localite', 'TPLOC_0001')->get(); // DEPARTEMENT
        $anneeActuelle = date('Y');
        $annees = range($anneeActuelle, $anneeActuelle - 10); // 10 dernières années
        
        return view('reporting::naissance.rapport-exploitation', compact('departements', 'annees', 'anneeActuelle'));
    }

    /**
     * Génère le PDF du rapport d'exploitation des actes de naissance
     */
    public function rapportExploitationNaissancePdf(Request $request)
    {
        $annee = $request->input('annee', date('Y'));
        $codeDepartement = $request->input('departement');
        $codeDistrict = $request->input('district');
        $codeArrondissement = $request->input('arrondissement');
        $codeVillage = $request->input('village');

        // Récupérer les noms et types des localités pour l'affichage
        $localiteLabels = $this->getLocaliteLabels($request);

        // Construire la requête SQL avec les filtres géographiques
        $localiteIds = [];
        if ($codeVillage) {
            $localiteIds = [$codeVillage];
        } elseif ($codeArrondissement) {
            $localiteIds = Localite::where('code_localite_parent', $codeArrondissement)->pluck('code_localite')->toArray();
            $localiteIds[] = $codeArrondissement;
        } elseif ($codeDistrict) {
            $arrondissements = Localite::where('code_localite_parent', $codeDistrict)->pluck('code_localite')->toArray();
            $localiteIds = Localite::whereIn('code_localite_parent', $arrondissements)->pluck('code_localite')->toArray();
            $localiteIds = array_merge($localiteIds, $arrondissements, [$codeDistrict]);
        } elseif ($codeDepartement) {
            $districts = Localite::where('code_localite_parent', $codeDepartement)->pluck('code_localite')->toArray();
            $arrondissements = Localite::whereIn('code_localite_parent', $districts)->pluck('code_localite')->toArray();
            $villages = Localite::whereIn('code_localite_parent', $arrondissements)->pluck('code_localite')->toArray();
            $localiteIds = array_merge($villages, $arrondissements, $districts, [$codeDepartement]);
        }

        // Requête pour récupérer les données mensuelles
        $query = "SELECT 
            MONTH(dn.date_heure_declaration) as mois,
            -- Déclarations dans les délais (<=30 jours)
            SUM(CASE WHEN p.sexe='M' AND DATEDIFF(dn.date_heure_declaration, p.date_naissance) <= 30 THEN 1 ELSE 0 END) as delai_m,
            SUM(CASE WHEN p.sexe='F' AND DATEDIFF(dn.date_heure_declaration, p.date_naissance) <= 30 THEN 1 ELSE 0 END) as delai_f,
            -- Déclarations hors délais (>30 jours)
            SUM(CASE WHEN p.sexe='M' AND DATEDIFF(dn.date_heure_declaration, p.date_naissance) > 30 THEN 1 ELSE 0 END) as hors_delai_m,
            SUM(CASE WHEN p.sexe='F' AND DATEDIFF(dn.date_heure_declaration, p.date_naissance) > 30 THEN 1 ELSE 0 END) as hors_delai_f,
            -- Actes reconstitués (jugements supplétifs ou type_declaration contient reconstitution)
            SUM(CASE WHEN p.sexe='M' AND (dn.code_jugement IS NOT NULL OR dn.type_declaration LIKE '%RECONSTITUTION%') THEN 1 ELSE 0 END) as reconstitue_m,
            SUM(CASE WHEN p.sexe='F' AND (dn.code_jugement IS NOT NULL OR dn.type_declaration LIKE '%RECONSTITUTION%') THEN 1 ELSE 0 END) as reconstitue_f,
            -- Âge de la mère (en années au moment de l'accouchement)
            MIN(TIMESTAMPDIFF(YEAR, mere.date_naissance, p.date_naissance)) as age_mere_min,
            ROUND(AVG(TIMESTAMPDIFF(YEAR, mere.date_naissance, p.date_naissance)), 1) as age_mere_moy,
            MAX(TIMESTAMPDIFF(YEAR, mere.date_naissance, p.date_naissance)) as age_mere_max
        FROM t_declaration_naissance dn
        JOIN tr_identification_personne p ON p.code_personne = dn.code_enfant
        LEFT JOIN tr_identification_personne mere ON mere.code_personne = dn.code_mere
        JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution
        JOIN tr_institution inst ON inst.code_institution = iu.code_institution
        WHERE YEAR(dn.date_heure_declaration) = ?";

        $params = [$annee];

        if (!empty($localiteIds)) {
            $placeholders = implode(',', array_fill(0, count($localiteIds), '?'));
            $query .= " AND inst.code_localite IN ($placeholders)";
            $params = array_merge($params, $localiteIds);
        }

        $query .= " GROUP BY MONTH(dn.date_heure_declaration) ORDER BY mois";

        $donneesMensuelles = DB::select($query, $params);

        // Initialiser tous les mois avec des zéros
        $mois = [];
        $nomsMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        for ($i = 1; $i <= 12; $i++) {
            $mois[$i] = [
                'nom_mois' => $nomsMois[$i-1],
                'delai_m' => 0,
                'delai_f' => 0,
                'hors_delai_m' => 0,
                'hors_delai_f' => 0,
                'reconstitue_m' => 0,
                'reconstitue_f' => 0,
                'age_mere_min' => '-',
                'age_mere_moy' => '-',
                'age_mere_max' => '-',
                'total' => 0
            ];
        }

        // Remplir avec les données récupérées
        foreach ($donneesMensuelles as $donnee) {
            $moisNum = (int)$donnee->mois;
            $total = $donnee->delai_m + $donnee->delai_f + $donnee->hors_delai_m + $donnee->hors_delai_f;
            
            $mois[$moisNum] = [
                'nom_mois' => $nomsMois[$moisNum-1],
                'delai_m' => $donnee->delai_m,
                'delai_f' => $donnee->delai_f,
                'hors_delai_m' => $donnee->hors_delai_m,
                'hors_delai_f' => $donnee->hors_delai_f,
                'reconstitue_m' => $donnee->reconstitue_m,
                'reconstitue_f' => $donnee->reconstitue_f,
                'age_mere_min' => $donnee->age_mere_min ?? '-',
                'age_mere_moy' => $donnee->age_mere_moy ?? '-',
                'age_mere_max' => $donnee->age_mere_max ?? '-',
                'total' => $total
            ];
        }

        // Calculer les totaux
        $totaux = [
            'delai_m' => 0,
            'delai_f' => 0,
            'hors_delai_m' => 0,
            'hors_delai_f' => 0,
            'reconstitue_m' => 0,
            'reconstitue_f' => 0,
            'total' => 0
        ];

        foreach ($mois as $donnee) {
            $totaux['delai_m'] += $donnee['delai_m'];
            $totaux['delai_f'] += $donnee['delai_f'];
            $totaux['hors_delai_m'] += $donnee['hors_delai_m'];
            $totaux['hors_delai_f'] += $donnee['hors_delai_f'];
            $totaux['reconstitue_m'] += $donnee['reconstitue_m'];
            $totaux['reconstitue_f'] += $donnee['reconstitue_f'];
            $totaux['total'] += $donnee['total'];
        }

        // Générer le PDF
        view()->share('tester', 'Rapport');
        $html2pdf = new Html2Pdf('L', 'A4', 'fr'); // Paysage (Landscape) pour plus d'espace
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('reporting::naissance.rapport-exploitation-pdf', array_merge(
            compact('mois', 'totaux', 'annee'),
            $localiteLabels
        ))->render());

        return $html2pdf->output("rapport_exploitation_actes_naissance_{$annee}.pdf");
    }

    /**
     * Affiche le formulaire générique du rapport d'exploitation des faits d'état civil
     */
    public function rapportExploitationFaits(Request $request)
    {
        $departements = Localite::where('code_type_localite', 'TPLOC_0001')->get(); // DEPARTEMENT
        $anneeActuelle = date('Y');
        $annees = range($anneeActuelle, $anneeActuelle - 10); // 10 dernières années
        
        return view('reporting::faits.rapport-exploitation', compact('departements', 'annees', 'anneeActuelle'));
    }

    /**
     * Génère le PDF du rapport d'exploitation selon le type de fait
     */
    public function rapportExploitationFaitsPdf(Request $request)
    {
        $typeFait = $request->input('type_fait', 'naissance');
        
        switch ($typeFait) {
            case 'mariage':
                return $this->genererRapportMariage($request);
            case 'deces':
                return $this->genererRapportDeces($request);
            case 'naissance':
            default:
                return $this->genererRapportNaissance($request);
        }
    }

    /**
     * MÉTHODES PRIVÉES POUR GÉNÉRER LES RAPPORTS PAR TYPE DE FAIT
     */

    /**
     * Génère le rapport d'exploitation pour les naissances
     */
    private function genererRapportNaissance(Request $request)
    {
        $annee = $request->input('annee', date('Y'));
        $localiteIds = $this->getLocaliteIdsRecursive($request);
        
        // Récupérer les noms des localités pour l'affichage
        $localiteLabels = $this->getLocaliteLabels($request);

        // Requête pour récupérer les données mensuelles
        $query = "SELECT 
            MONTH(dn.date_heure_declaration) as mois,
            -- Déclarations dans les délais (<=30 jours)
            SUM(CASE WHEN p.sexe='M' AND DATEDIFF(dn.date_heure_declaration, p.date_naissance) <= 30 THEN 1 ELSE 0 END) as delai_m,
            SUM(CASE WHEN p.sexe='F' AND DATEDIFF(dn.date_heure_declaration, p.date_naissance) <= 30 THEN 1 ELSE 0 END) as delai_f,
            SUM(CASE WHEN (p.sexe IS NULL OR p.sexe NOT IN ('M','F')) AND DATEDIFF(dn.date_heure_declaration, p.date_naissance) <= 30 THEN 1 ELSE 0 END) as delai_ni,
            -- Déclarations hors délais (>30 jours)
            SUM(CASE WHEN p.sexe='M' AND DATEDIFF(dn.date_heure_declaration, p.date_naissance) > 30 THEN 1 ELSE 0 END) as hors_delai_m,
            SUM(CASE WHEN p.sexe='F' AND DATEDIFF(dn.date_heure_declaration, p.date_naissance) > 30 THEN 1 ELSE 0 END) as hors_delai_f,
            SUM(CASE WHEN (p.sexe IS NULL OR p.sexe NOT IN ('M','F')) AND DATEDIFF(dn.date_heure_declaration, p.date_naissance) > 30 THEN 1 ELSE 0 END) as hors_delai_ni,
            -- Actes reconstitués (jugements supplétifs ou type_declaration contient reconstitution)
            SUM(CASE WHEN p.sexe='M' AND (dn.code_jugement IS NOT NULL OR dn.type_declaration LIKE '%RECONSTITUTION%') THEN 1 ELSE 0 END) as reconstitue_m,
            SUM(CASE WHEN p.sexe='F' AND (dn.code_jugement IS NOT NULL OR dn.type_declaration LIKE '%RECONSTITUTION%') THEN 1 ELSE 0 END) as reconstitue_f,
            SUM(CASE WHEN (p.sexe IS NULL OR p.sexe NOT IN ('M','F')) AND (dn.code_jugement IS NOT NULL OR dn.type_declaration LIKE '%RECONSTITUTION%') THEN 1 ELSE 0 END) as reconstitue_ni,
            -- Âge de la mère (en années au moment de l'accouchement)
            MIN(TIMESTAMPDIFF(YEAR, mere.date_naissance, p.date_naissance)) as age_mere_min,
            ROUND(AVG(TIMESTAMPDIFF(YEAR, mere.date_naissance, p.date_naissance)), 1) as age_mere_moy,
            MAX(TIMESTAMPDIFF(YEAR, mere.date_naissance, p.date_naissance)) as age_mere_max
        FROM t_declaration_naissance dn
        JOIN tr_identification_personne p ON p.code_personne = dn.code_enfant
        LEFT JOIN tr_identification_personne mere ON mere.code_personne = dn.code_mere
        JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution
        JOIN tr_institution inst ON inst.code_institution = iu.code_institution
        WHERE YEAR(dn.date_heure_declaration) = ?";

        $params = [$annee];

        if (!empty($localiteIds)) {
            $placeholders = implode(',', array_fill(0, count($localiteIds), '?'));
            $query .= " AND inst.code_localite IN ($placeholders)";
            $params = array_merge($params, $localiteIds);
        }

        $query .= " GROUP BY MONTH(dn.date_heure_declaration) ORDER BY mois";

        $donneesMensuelles = DB::select($query, $params);

        // Initialiser tous les mois avec des zéros
        $nomsMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $mois = [];
        for ($i = 1; $i <= 12; $i++) {
            $mois[$i] = [
                'nom_mois' => $nomsMois[$i-1],
                'delai_m' => 0,
                'delai_f' => 0,
                'delai_ni' => 0,
                'hors_delai_m' => 0,
                'hors_delai_f' => 0,
                'hors_delai_ni' => 0,
                'reconstitue_m' => 0,
                'reconstitue_f' => 0,
                'reconstitue_ni' => 0,
                'age_mere_min' => '-',
                'age_mere_moy' => '-',
                'age_mere_max' => '-',
                'total' => 0
            ];
        }

        // Remplir avec les données récupérées
        foreach ($donneesMensuelles as $donnee) {
            $moisNum = (int)$donnee->mois;
            $total = $donnee->delai_m + $donnee->delai_f + $donnee->delai_ni + 
                     $donnee->hors_delai_m + $donnee->hors_delai_f + $donnee->hors_delai_ni;
            
            $mois[$moisNum] = [
                'nom_mois' => $nomsMois[$moisNum-1],
                'delai_m' => $donnee->delai_m,
                'delai_f' => $donnee->delai_f,
                'delai_ni' => $donnee->delai_ni,
                'hors_delai_m' => $donnee->hors_delai_m,
                'hors_delai_f' => $donnee->hors_delai_f,
                'hors_delai_ni' => $donnee->hors_delai_ni,
                'reconstitue_m' => $donnee->reconstitue_m,
                'reconstitue_f' => $donnee->reconstitue_f,
                'reconstitue_ni' => $donnee->reconstitue_ni,
                'age_mere_min' => $donnee->age_mere_min ?? '-',
                'age_mere_moy' => $donnee->age_mere_moy ?? '-',
                'age_mere_max' => $donnee->age_mere_max ?? '-',
                'total' => $total
            ];
        }

        // Calculer les totaux
        $totaux = [
            'delai_m' => 0,
            'delai_f' => 0,
            'delai_ni' => 0,
            'hors_delai_m' => 0,
            'hors_delai_f' => 0,
            'hors_delai_ni' => 0,
            'reconstitue_m' => 0,
            'reconstitue_f' => 0,
            'reconstitue_ni' => 0,
            'total' => 0
        ];

        foreach ($mois as $donnee) {
            $totaux['delai_m'] += $donnee['delai_m'];
            $totaux['delai_f'] += $donnee['delai_f'];
            $totaux['delai_ni'] += $donnee['delai_ni'];
            $totaux['hors_delai_m'] += $donnee['hors_delai_m'];
            $totaux['hors_delai_f'] += $donnee['hors_delai_f'];
            $totaux['hors_delai_ni'] += $donnee['hors_delai_ni'];
            $totaux['reconstitue_m'] += $donnee['reconstitue_m'];
            $totaux['reconstitue_f'] += $donnee['reconstitue_f'];
            $totaux['reconstitue_ni'] += $donnee['reconstitue_ni'];
            $totaux['total'] += $donnee['total'];
        }

        // Générer le PDF
        view()->share('tester', 'Rapport');
        $html2pdf = new Html2Pdf('L', 'A4', 'fr'); // Paysage (Landscape) pour plus d'espace
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->pdf->SetMargins(10, 10, 10); // Réduire les marges (gauche, haut, droite)
        $html2pdf->writeHTML(view('reporting::faits.naissance-pdf', array_merge(
            compact('mois', 'totaux', 'annee'),
            $localiteLabels
        ))->render());

        $pdfBinary = $html2pdf->output("rapport_exploitation_naissances_{$annee}.pdf");
        
        return $this->pdfInlineResponse($pdfBinary, "rapport_exploitation_naissances_{$annee}.pdf");
    }

    /**
     * Génère le rapport d'exploitation pour les mariages
     */
    private function genererRapportMariage(Request $request)
    {
        $annee = $request->input('annee', date('Y'));
        $localiteIds = $this->getLocaliteIdsRecursive($request);
        $localiteLabels = $this->getLocaliteLabels($request);

        // Requête pour récupérer les données mensuelles des mariages
        $query = "SELECT 
            MONTH(dm.date_declaration_mariage) as mois,
            -- Options de mariage (Monogamie / Polygamie)
            SUM(CASE WHEN om.lib_option_mariage LIKE '%Monogam%' OR dm.code_option_mariage IN ('OPM_0001', 'OMRG_0002') THEN 1 ELSE 0 END) as monogamie,
            SUM(CASE WHEN om.lib_option_mariage LIKE '%Polygam%' OR dm.code_option_mariage IN ('OPM_0002', 'OMRG_0001') THEN 1 ELSE 0 END) as polygamie,
            -- Régimes matrimoniaux
            SUM(CASE WHEN dm.code_regime = 'RGIM_0002' OR r.lib_regime LIKE '%RSB%' OR r.lib_regime LIKE '%séparation%' OR r.lib_regime LIKE '%Séparation%' THEN 1 ELSE 0 END) as separation_biens,
            SUM(CASE WHEN dm.code_regime = 'RGIM_0001' OR (r.lib_regime LIKE '%RCA%' OR r.lib_regime LIKE '%acquêts%' OR r.lib_regime LIKE '%Acquêts%') AND r.lib_regime NOT LIKE '%conventionnelle%' THEN 1 ELSE 0 END) as communaute_acquets,
            SUM(CASE WHEN dm.code_regime = 'RGIM_0003' OR r.lib_regime LIKE '%conventionnelle%' OR r.lib_regime LIKE '%RCC%' THEN 1 ELSE 0 END) as communaute_conventionnelle,
            -- Actes reconstitués
            SUM(CASE WHEN dm.type_declaration LIKE '%RECONSTITUTION%' THEN 1 ELSE 0 END) as reconstitues,
            -- Total des déclarations du mois
            COUNT(*) as total,
            -- Âge des époux
            MIN(TIMESTAMPDIFF(YEAR, epoux.date_naissance, dm.date_prevue_mariage)) as age_epoux_min,
            ROUND(AVG(TIMESTAMPDIFF(YEAR, epoux.date_naissance, dm.date_prevue_mariage)), 1) as age_epoux_moy,
            MAX(TIMESTAMPDIFF(YEAR, epoux.date_naissance, dm.date_prevue_mariage)) as age_epoux_max,
            MIN(TIMESTAMPDIFF(YEAR, epouse.date_naissance, dm.date_prevue_mariage)) as age_epouse_min,
            ROUND(AVG(TIMESTAMPDIFF(YEAR, epouse.date_naissance, dm.date_prevue_mariage)), 1) as age_epouse_moy,
            MAX(TIMESTAMPDIFF(YEAR, epouse.date_naissance, dm.date_prevue_mariage)) as age_epouse_max
        FROM t_declaration_mariage dm
        LEFT JOIN tr_option_mariage om ON om.code_option_mariage = dm.code_option_mariage
        LEFT JOIN tr_regime r ON r.code_regime = dm.code_regime
        LEFT JOIN tr_identification_personne epoux ON epoux.code_personne = dm.code_epoux
        LEFT JOIN tr_identification_personne epouse ON epouse.code_personne = dm.code_epouse
        JOIN tr_ins_user iu ON iu.cui = dm.cui
        JOIN tr_institution inst ON inst.code_institution = iu.code_institution
        WHERE YEAR(dm.date_declaration_mariage) = ?";

        $params = [$annee];

        if (!empty($localiteIds)) {
            $placeholders = implode(',', array_fill(0, count($localiteIds), '?'));
            $query .= " AND inst.code_localite IN ($placeholders)";
            $params = array_merge($params, $localiteIds);
        }

        $query .= " GROUP BY MONTH(dm.date_declaration_mariage) ORDER BY mois";

        $donneesMensuelles = DB::select($query, $params);

        // Initialiser tous les mois
        $nomsMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $mois = [];
        for ($i = 1; $i <= 12; $i++) {
            $mois[$i] = [
                'nom_mois' => $nomsMois[$i-1],
                'monogamie' => 0,
                'polygamie' => 0,
                'communaute_conventionnelle' => 0,
                'separation_biens' => 0,
                'communaute_acquets' => 0,
                'reconstitues' => 0,
                'age_epoux_min' => '-',
                'age_epoux_moy' => '-',
                'age_epoux_max' => '-',
                'age_epouse_min' => '-',
                'age_epouse_moy' => '-',
                'age_epouse_max' => '-',
                'total' => 0
            ];
        }

        // Remplir avec les données récupérées
        foreach ($donneesMensuelles as $donnee) {
            $moisNum = (int)$donnee->mois;

            $mois[$moisNum] = [
                'nom_mois' => $nomsMois[$moisNum-1],
                'monogamie' => $donnee->monogamie,
                'polygamie' => $donnee->polygamie,
                'communaute_conventionnelle' => $donnee->communaute_conventionnelle,
                'separation_biens' => $donnee->separation_biens,
                'communaute_acquets' => $donnee->communaute_acquets,
                'reconstitues' => $donnee->reconstitues,
                'age_epoux_min' => $donnee->age_epoux_min ?? '-',
                'age_epoux_moy' => $donnee->age_epoux_moy ?? '-',
                'age_epoux_max' => $donnee->age_epoux_max ?? '-',
                'age_epouse_min' => $donnee->age_epouse_min ?? '-',
                'age_epouse_moy' => $donnee->age_epouse_moy ?? '-',
                'age_epouse_max' => $donnee->age_epouse_max ?? '-',
                'total' => $donnee->total
            ];
        }

        // Calculer les totaux
        $totaux = [
            'monogamie' => 0,
            'polygamie' => 0,
            'communaute_conventionnelle' => 0,
            'separation_biens' => 0,
            'communaute_acquets' => 0,
            'reconstitues' => 0,
            'total' => 0
        ];

        foreach ($mois as $donnee) {
            $totaux['monogamie'] += $donnee['monogamie'];
            $totaux['polygamie'] += $donnee['polygamie'];
            $totaux['communaute_conventionnelle'] += $donnee['communaute_conventionnelle'];
            $totaux['separation_biens'] += $donnee['separation_biens'];
            $totaux['communaute_acquets'] += $donnee['communaute_acquets'];
            $totaux['reconstitues'] += $donnee['reconstitues'];
            $totaux['total'] += $donnee['total'];
        }

        $ageQuery = "SELECT
            ROUND(AVG(TIMESTAMPDIFF(YEAR, epoux.date_naissance, dm.date_prevue_mariage)), 1) as age_epoux_moy,
            ROUND(AVG(TIMESTAMPDIFF(YEAR, epouse.date_naissance, dm.date_prevue_mariage)), 1) as age_epouse_moy
        FROM t_declaration_mariage dm
        LEFT JOIN tr_identification_personne epoux ON epoux.code_personne = dm.code_epoux
        LEFT JOIN tr_identification_personne epouse ON epouse.code_personne = dm.code_epouse
        JOIN tr_ins_user iu ON iu.cui = dm.cui
        JOIN tr_institution inst ON inst.code_institution = iu.code_institution
        WHERE YEAR(dm.date_declaration_mariage) = ?";

        $ageParams = [$annee];
        if (!empty($localiteIds)) {
            $placeholders = implode(',', array_fill(0, count($localiteIds), '?'));
            $ageQuery .= " AND inst.code_localite IN ($placeholders)";
            $ageParams = array_merge($ageParams, $localiteIds);
        }

        $agesAnnuels = DB::selectOne($ageQuery, $ageParams);
        $totaux['age_epoux_moy'] = $agesAnnuels->age_epoux_moy ?? '-';
        $totaux['age_epouse_moy'] = $agesAnnuels->age_epouse_moy ?? '-';

        // Générer le PDF
        view()->share('tester', 'Rapport');
        $html2pdf = new Html2Pdf('L', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->pdf->SetMargins(10, 10, 10); // Réduire les marges (gauche, haut, droite)
        $html2pdf->writeHTML(view('reporting::faits.mariage-pdf', array_merge(
            compact('mois', 'totaux', 'annee'),
            $localiteLabels
        ))->render());

        $pdfBinary = $html2pdf->output("rapport_exploitation_mariages_{$annee}.pdf");
        
        return $this->pdfInlineResponse($pdfBinary, "rapport_exploitation_mariages_{$annee}.pdf");
    }

    /**
     * Génère le rapport d'exploitation pour les décès
     */
    private function genererRapportDeces(Request $request)
    {
        $annee = $request->input('annee', date('Y'));
        $localiteIds = $this->getLocaliteIdsRecursive($request);
        $localiteLabels = $this->getLocaliteLabels($request);

        // Requête pour récupérer les données mensuelles des décès
        $query = "SELECT 
            MONTH(dd.date_heure_declaration) as mois,
            -- Déclarations dans les délais (<=24h)
            SUM(CASE WHEN p.sexe='M' AND TIMESTAMPDIFF(HOUR, dd.date_heure_deces, dd.date_heure_declaration) <= 24 THEN 1 ELSE 0 END) as delai_m,
            SUM(CASE WHEN p.sexe='F' AND TIMESTAMPDIFF(HOUR, dd.date_heure_deces, dd.date_heure_declaration) <= 24 THEN 1 ELSE 0 END) as delai_f,
            -- Déclarations hors délais (>24h)
            SUM(CASE WHEN p.sexe='M' AND TIMESTAMPDIFF(HOUR, dd.date_heure_deces, dd.date_heure_declaration) > 24 THEN 1 ELSE 0 END) as hors_delai_m,
            SUM(CASE WHEN p.sexe='F' AND TIMESTAMPDIFF(HOUR, dd.date_heure_deces, dd.date_heure_declaration) > 24 THEN 1 ELSE 0 END) as hors_delai_f,
            -- Actes reconstitués
            SUM(CASE WHEN dd.code_jugement IS NOT NULL OR dd.type_declaration LIKE '%RECONSTITUTION%' THEN 1 ELSE 0 END) as reconstitues,
            -- Âge du décédé
            MIN(TIMESTAMPDIFF(YEAR, p.date_naissance, dd.date_heure_deces)) as age_decede_min_m,
            ROUND(AVG(CASE WHEN p.sexe='M' THEN TIMESTAMPDIFF(YEAR, p.date_naissance, dd.date_heure_deces) END), 1) as age_decede_moy_m,
            MAX(CASE WHEN p.sexe='M' THEN TIMESTAMPDIFF(YEAR, p.date_naissance, dd.date_heure_deces) END) as age_decede_max_m,
            MIN(CASE WHEN p.sexe='F' THEN TIMESTAMPDIFF(YEAR, p.date_naissance, dd.date_heure_deces) END) as age_decede_min_f,
            ROUND(AVG(CASE WHEN p.sexe='F' THEN TIMESTAMPDIFF(YEAR, p.date_naissance, dd.date_heure_deces) END), 1) as age_decede_moy_f,
            MAX(CASE WHEN p.sexe='F' THEN TIMESTAMPDIFF(YEAR, p.date_naissance, dd.date_heure_deces) END) as age_decede_max_f
        FROM t_declaration_deces dd
        JOIN tr_identification_personne p ON p.code_personne = dd.code_defunt
        JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution
        JOIN tr_institution inst ON inst.code_institution = iu.code_institution
        WHERE YEAR(dd.date_heure_declaration) = ?";

        $params = [$annee];

        if (!empty($localiteIds)) {
            $placeholders = implode(',', array_fill(0, count($localiteIds), '?'));
            $query .= " AND inst.code_localite IN ($placeholders)";
            $params = array_merge($params, $localiteIds);
        }

        $query .= " GROUP BY MONTH(dd.date_heure_declaration) ORDER BY mois";

        $donneesMensuelles = DB::select($query, $params);

        // Requête pour récupérer les causes de décès par mois
        $queryCauses = "SELECT 
            MONTH(dd.date_heure_declaration) as mois,
            GROUP_CONCAT(DISTINCT cd.lib_cause_deces SEPARATOR ', ') as causes
        FROM t_declaration_deces dd
        LEFT JOIN t_ddecescause dc ON dc.code_declaration_deces = dd.code_declaration_deces
        LEFT JOIN tr_cause_deces cd ON cd.code_cause_deces = dc.code_cause_deces
        JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution
        JOIN tr_institution inst ON inst.code_institution = iu.code_institution
        WHERE YEAR(dd.date_heure_declaration) = ?";

        if (!empty($localiteIds)) {
            $placeholders = implode(',', array_fill(0, count($localiteIds), '?'));
            $queryCauses .= " AND inst.code_localite IN ($placeholders)";
        }

        $queryCauses .= " GROUP BY MONTH(dd.date_heure_declaration)";

        $causesMensuelles = DB::select($queryCauses, $params);
        $causesParMois = [];
        foreach ($causesMensuelles as $cause) {
            $causesParMois[(int)$cause->mois] = $cause->causes ?? '-';
        }

        // Initialiser tous les mois
        $nomsMois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $mois = [];
        for ($i = 1; $i <= 12; $i++) {
            $mois[$i] = [
                'nom_mois' => $nomsMois[$i-1],
                'delai_m' => 0,
                'delai_f' => 0,
                'hors_delai_m' => 0,
                'hors_delai_f' => 0,
                'reconstitues' => 0,
                'causes' => '-',
                'age_decede_min_m' => '-',
                'age_decede_moy_m' => '-',
                'age_decede_max_m' => '-',
                'age_decede_min_f' => '-',
                'age_decede_moy_f' => '-',
                'age_decede_max_f' => '-',
                'total' => 0
            ];
        }

        // Remplir avec les données récupérées
        foreach ($donneesMensuelles as $donnee) {
            $moisNum = (int)$donnee->mois;
            $total = $donnee->delai_m + $donnee->delai_f + $donnee->hors_delai_m + $donnee->hors_delai_f;
            
            $mois[$moisNum] = [
                'nom_mois' => $nomsMois[$moisNum-1],
                'delai_m' => $donnee->delai_m,
                'delai_f' => $donnee->delai_f,
                'hors_delai_m' => $donnee->hors_delai_m,
                'hors_delai_f' => $donnee->hors_delai_f,
                'reconstitues' => $donnee->reconstitues,
                'causes' => $causesParMois[$moisNum] ?? '-',
                'age_decede_min_m' => $donnee->age_decede_min_m ?? '-',
                'age_decede_moy_m' => $donnee->age_decede_moy_m ?? '-',
                'age_decede_max_m' => $donnee->age_decede_max_m ?? '-',
                'age_decede_min_f' => $donnee->age_decede_min_f ?? '-',
                'age_decede_moy_f' => $donnee->age_decede_moy_f ?? '-',
                'age_decede_max_f' => $donnee->age_decede_max_f ?? '-',
                'total' => $total
            ];
        }

        // Calculer les totaux
        $totaux = [
            'delai_m' => 0,
            'delai_f' => 0,
            'hors_delai_m' => 0,
            'hors_delai_f' => 0,
            'reconstitues' => 0,
            'total' => 0
        ];

        foreach ($mois as $donnee) {
            $totaux['delai_m'] += $donnee['delai_m'];
            $totaux['delai_f'] += $donnee['delai_f'];
            $totaux['hors_delai_m'] += $donnee['hors_delai_m'];
            $totaux['hors_delai_f'] += $donnee['hors_delai_f'];
            $totaux['reconstitues'] += $donnee['reconstitues'];
            $totaux['total'] += $donnee['total'];
        }

        // Générer le PDF
        view()->share('tester', 'Rapport');
        $html2pdf = new Html2Pdf('L', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->pdf->SetMargins(10, 10, 10); // Réduire les marges (gauche, haut, droite)
        $html2pdf->writeHTML(view('reporting::faits.deces-pdf', array_merge(
            compact('mois', 'totaux', 'annee'),
            $localiteLabels
        ))->render());

        $pdfBinary = $html2pdf->output("rapport_exploitation_deces_{$annee}.pdf");
        
        return $this->pdfInlineResponse($pdfBinary, "rapport_exploitation_deces_{$annee}.pdf");
    }

    /**
     * MÉTHODES UTILITAIRES
     */

    /**
     * Récupère les IDs de localités de manière récursive selon les filtres
     */
    private function getLocaliteIdsRecursive(Request $request)
    {
        $codeDepartement = $request->input('departement');
        $codeDistrict = $request->input('district');
        $codeArrondissement = $request->input('arrondissement');
        $codeVillage = $request->input('village');

        $localiteIds = [];
        
        if ($codeVillage) {
            $localiteIds = [$codeVillage];
        } elseif ($codeArrondissement) {
            $localiteIds = Localite::where('code_localite_parent', $codeArrondissement)->pluck('code_localite')->toArray();
            $localiteIds[] = $codeArrondissement;
        } elseif ($codeDistrict) {
            $arrondissements = Localite::where('code_localite_parent', $codeDistrict)->pluck('code_localite')->toArray();
            $localiteIds = Localite::whereIn('code_localite_parent', $arrondissements)->pluck('code_localite')->toArray();
            $localiteIds = array_merge($localiteIds, $arrondissements, [$codeDistrict]);
        } elseif ($codeDepartement) {
            $districts = Localite::where('code_localite_parent', $codeDepartement)->pluck('code_localite')->toArray();
            $arrondissements = Localite::whereIn('code_localite_parent', $districts)->pluck('code_localite')->toArray();
            $villages = Localite::whereIn('code_localite_parent', $arrondissements)->pluck('code_localite')->toArray();
            $localiteIds = array_merge($villages, $arrondissements, $districts, [$codeDepartement]);
        }

        return $localiteIds;
    }

    /**
     * Récupère les labels des localités pour l'affichage (valeur + type depuis tr_type_localite)
     */
    private function getLocaliteLabels(Request $request): array
    {
        $departement = $this->resolveLocaliteAffichage($request->input('departement'), 'Tous', 'Département');
        $district = $this->resolveLocaliteAffichage($request->input('district'));
        $arrondissement = $this->resolveLocaliteAffichage($request->input('arrondissement'));
        $village = $this->resolveLocaliteAffichage($request->input('village'));

        return [
            'departement' => $departement['value'],
            'departementType' => $departement['typeLabel'],
            'district' => $district['value'],
            'districtType' => $district['typeLabel'],
            'arrondissement' => $arrondissement['value'],
            'arrondissementType' => $arrondissement['typeLabel'],
            'village' => $village['value'],
            'villageType' => $village['typeLabel'],
        ];
    }

    /**
     * @return array{value: ?string, typeLabel: ?string}
     */
    private function resolveLocaliteAffichage(?string $codeLocalite, ?string $defaultValue = null, ?string $defaultTypeLabel = null): array
    {
        if (!$codeLocalite) {
            return [
                'value' => $defaultValue,
                'typeLabel' => $defaultTypeLabel,
            ];
        }

        $localite = Localite::with('typelocalite')->find($codeLocalite);

        if (!$localite) {
            return [
                'value' => $defaultValue,
                'typeLabel' => $defaultTypeLabel,
            ];
        }

        return [
            'value' => $localite->lib_localite,
            'typeLabel' => $this->formatTypeLocaliteLabel($localite->typelocalite?->lib_type_localite),
        ];
    }

    private function formatTypeLocaliteLabel(?string $libTypeLocalite): ?string
    {
        if (!$libTypeLocalite) {
            return null;
        }

        static $labels = [
            'DEPARTEMENT' => 'Département',
            'DISTRICT' => 'District',
            'COMMUNE' => 'Commune',
            'ARRONDISSEMENT' => 'Arrondissement',
            'COMMUNAUTE URBAINE' => 'Communauté urbaine',
            'COMMUNAUTE RURALE' => 'Communauté rurale',
            'QUARTIER' => 'Quartier',
            'VILLAGE' => 'Village',
            'NON DECLARE' => 'Non déclaré',
        ];

        return $labels[$libTypeLocalite] ?? ucfirst(strtolower($libTypeLocalite));
    }

    /**
     * Récupère les localités enfants d'une localité donnée (pour les filtres du rapport)
     */
    public function getLocalitesEnfants($codeLocalite)
    {
        $enfants = Localite::where('code_localite_parent', $codeLocalite)
            ->select('code_localite', 'lib_localite', 'code_type_localite')
            ->orderBy('lib_localite')
            ->get();
        
        return response()->json($enfants);
    }

    /**
     * Formulaire générique de l'annuaire statistique (Naissance / Mariage / Décès)
     */
    public function annuaireStatistiqueFaits(Request $request)
    {
        $departements = Localite::where('code_type_localite', 'TPLOC_0001')->get();
        $anneeActuelle = date('Y');
        $annees = range($anneeActuelle, $anneeActuelle - 10);
        $typeFait = $this->normalizeAnnuaireTypeFait($request->input('type_fait', 'naissance'));

        return view('reporting::annuaire.annuaire-statistique', compact('departements', 'annees', 'anneeActuelle', 'typeFait'));
    }

    /**
     * @deprecated Utiliser annuaireStatistiqueFaits — conservé pour compatibilité URL
     */
    public function annuaireStatistiqueNaissance(Request $request)
    {
        return redirect()->route('reporting.faits.annuaire.statistique', ['type_fait' => 'naissance']);
    }

    /**
     * Affiche l'annuaire statistique dans le viewer PDF inline
     */
    public function displayAnnuaireStatistiqueFaits(Request $request)
    {
        $request->validate([
            'type_fait' => 'required|in:naissance,mariage,deces',
            'annee' => 'required|integer|min:2000|max:2100',
            'departement' => 'required|string',
        ]);

        $typeFait = $this->normalizeAnnuaireTypeFait($request->input('type_fait'));
        $meta = $this->getAnnuaireFaitMeta($typeFait);

        $pdfRoute = route('reporting.faits.annuaire.statistique.pdf', [
            'type_fait' => $typeFait,
            'annee' => $request->input('annee'),
            'departement' => $request->input('departement'),
        ]);

        return view('reporting::annuaire.annuaire-statistique-display', [
            'pdfRoute' => $pdfRoute,
            'pdfFilename' => $meta['filename'] . '.pdf',
            'typeFait' => $typeFait,
            'titreFait' => $meta['titre_court'],
        ]);
    }

    /**
     * @deprecated Utiliser displayAnnuaireStatistiqueFaits
     */
    public function displayAnnuaireStatistiqueNaissance(Request $request)
    {
        $request->merge(['type_fait' => 'naissance']);

        return $this->displayAnnuaireStatistiqueFaits($request);
    }

    /**
     * Génère le PDF de l'annuaire statistique selon le type de fait
     */
    public function annuaireStatistiqueFaitsPdf(Request $request)
    {
        $request->validate([
            'type_fait' => 'required|in:naissance,mariage,deces',
            'annee' => 'required|integer|min:2000|max:2100',
            'departement' => 'required|string',
        ]);

        $typeFait = $this->normalizeAnnuaireTypeFait($request->input('type_fait'));
        $meta = $this->getAnnuaireFaitMeta($typeFait);

        $data = match ($typeFait) {
            'mariage' => $this->buildAnnuaireStatistiqueMariageData($request),
            'deces' => $this->buildAnnuaireStatistiqueDecesData($request),
            default => $this->buildAnnuaireStatistiqueNaissanceData($request),
        };

        $annee = $data['annee'];
        $data['titreAnnuaire'] = $meta['titre_pdf'];

        view()->share('tester', 'Annuaire');
        $html2pdf = new Html2Pdf('L', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->pdf->SetMargins(5, 5, 5);
        $html2pdf->pdf->SetAutoPageBreak(false, 0);
        $html2pdf->writeHTML(view($meta['pdf_view'], $data)->render());

        $filename = "{$meta['filename']}_{$annee}.pdf";

        return $this->pdfInlineResponse($html2pdf->output($filename, 'S'), $filename);
    }

    /**
     * @deprecated Utiliser annuaireStatistiqueFaitsPdf
     */
    public function annuaireStatistiqueNaissancePdf(Request $request)
    {
        $request->merge(['type_fait' => 'naissance']);

        return $this->annuaireStatistiqueFaitsPdf($request);
    }

    /**
     * Construit les données pivot centre × sexe × mois pour l'annuaire statistique
     */
    private function buildAnnuaireStatistiqueNaissanceData(Request $request): array
    {
        $annee = (int) $request->input('annee', date('Y'));
        $localiteIds = $this->getLocaliteIdsRecursive($request);
        $localiteLabels = $this->getLocaliteLabels($request);

        $institutions = $this->getAnnuaireInstitutions($localiteIds);

        $lookup = [];
        $typeCecNaissance = self::TYPE_INSTITUTION_CEC_NAISSANCE;

        if (!empty($localiteIds)) {
            $placeholders = implode(',', array_fill(0, count($localiteIds), '?'));
            $params = array_merge([$typeCecNaissance, $typeCecNaissance, $annee, $typeCecNaissance], $localiteIds);

            $rows = DB::select(
                "SELECT
                    inst_cec.code_institution,
                    MONTH(dn.date_heure_declaration) AS mois,
                    p.sexe,
                    COUNT(*) AS total
                FROM t_declaration_naissance dn
                JOIN tr_identification_personne p ON p.code_personne = dn.code_enfant
                JOIN tr_ins_user iu ON iu.cui = dn.code_user_institution
                JOIN tr_institution inst ON inst.code_institution = iu.code_institution
                JOIN tr_type_institution ti ON ti.code_type_institution = inst.code_type_institution
                LEFT JOIN tr_institution inst_dest ON inst_dest.code_institution = dn.code_institution_destinataire
                LEFT JOIN tr_type_institution ti_dest ON ti_dest.code_type_institution = inst_dest.code_type_institution
                JOIN tr_institution inst_cec ON inst_cec.code_institution = COALESCE(
                    CASE WHEN ti_dest.code_type_institution = ? THEN dn.code_institution_destinataire END,
                    CASE WHEN ti.code_type_institution = ? THEN inst.code_institution END
                )
                JOIN tr_type_institution ti_cec ON ti_cec.code_type_institution = inst_cec.code_type_institution
                WHERE YEAR(dn.date_heure_declaration) = ?
                  AND ti_cec.code_type_institution = ?
                  AND inst_cec.code_localite IN ($placeholders)
                  AND p.sexe IN ('M', 'F')
                GROUP BY inst_cec.code_institution, MONTH(dn.date_heure_declaration), p.sexe",
                $params
            );

            foreach ($rows as $row) {
                $code = $row->code_institution;
                $mois = (int) $row->mois;
                if (!isset($lookup[$code])) {
                    $lookup[$code] = ['M' => $this->initAnnuaireMoisValues(), 'F' => $this->initAnnuaireMoisValues()];
                }
                if ($mois >= 1 && $mois <= 12 && in_array($row->sexe, ['M', 'F'], true)) {
                    $lookup[$code][$row->sexe][$mois] = (int) $row->total;
                }
            }
        }

        $centres = [];
        $totauxMois = [
            'masculin' => $this->initAnnuaireMoisValues(),
            'feminin' => $this->initAnnuaireMoisValues(),
            'total' => $this->initAnnuaireMoisValues(),
        ];

        foreach ($institutions as $institution) {
            $code = $institution->code_institution;
            $masculin = $lookup[$code]['M'] ?? $this->initAnnuaireMoisValues();
            $feminin = $lookup[$code]['F'] ?? $this->initAnnuaireMoisValues();
            $centres[] = $this->buildAnnuaireCentreGenreRow($institution->lib_institution, $masculin, $feminin, $totauxMois);
        }

        return $this->finalizeAnnuaireGenreData($annee, $centres, $totauxMois, $localiteLabels);
    }

    /**
     * Construit les données pivot centre × mois pour l'annuaire mariage
     */
    private function buildAnnuaireStatistiqueMariageData(Request $request): array
    {
        $annee = (int) $request->input('annee', date('Y'));
        $localiteIds = $this->getLocaliteIdsRecursive($request);
        $localiteLabels = $this->getLocaliteLabels($request);
        $institutions = $this->getAnnuaireInstitutions($localiteIds);
        $lookup = [];
        $typeCec = self::TYPE_INSTITUTION_CEC_NAISSANCE;

        if (!empty($localiteIds)) {
            $placeholders = implode(',', array_fill(0, count($localiteIds), '?'));
            $params = array_merge([$typeCec, $typeCec, $annee, $typeCec], $localiteIds);

            $rows = DB::select(
                "SELECT
                    inst_cec.code_institution,
                    MONTH(dm.date_declaration_mariage) AS mois,
                    COUNT(*) AS total
                FROM t_declaration_mariage dm
                JOIN tr_ins_user iu ON iu.cui = dm.cui
                JOIN tr_institution inst ON inst.code_institution = iu.code_institution
                JOIN tr_type_institution ti ON ti.code_type_institution = inst.code_type_institution
                LEFT JOIN tr_institution inst_dest ON inst_dest.code_institution = dm.code_institution_destinataire
                LEFT JOIN tr_type_institution ti_dest ON ti_dest.code_type_institution = inst_dest.code_type_institution
                JOIN tr_institution inst_cec ON inst_cec.code_institution = COALESCE(
                    CASE WHEN ti_dest.code_type_institution = ? THEN dm.code_institution_destinataire END,
                    CASE WHEN ti.code_type_institution = ? THEN inst.code_institution END
                )
                JOIN tr_type_institution ti_cec ON ti_cec.code_type_institution = inst_cec.code_type_institution
                WHERE YEAR(dm.date_declaration_mariage) = ?
                  AND ti_cec.code_type_institution = ?
                  AND inst_cec.code_localite IN ($placeholders)
                GROUP BY inst_cec.code_institution, MONTH(dm.date_declaration_mariage)",
                $params
            );

            foreach ($rows as $row) {
                $code = $row->code_institution;
                $mois = (int) $row->mois;
                if (!isset($lookup[$code])) {
                    $lookup[$code] = $this->initAnnuaireMoisValues();
                }
                if ($mois >= 1 && $mois <= 12) {
                    $lookup[$code][$mois] = (int) $row->total;
                }
            }
        }

        $centres = [];
        $totauxMois = $this->initAnnuaireMoisValues();

        foreach ($institutions as $institution) {
            $centres[] = $this->buildAnnuaireCentreSimpleRow(
                $institution->lib_institution,
                $lookup[$institution->code_institution] ?? $this->initAnnuaireMoisValues(),
                $totauxMois
            );
        }

        return $this->finalizeAnnuaireSimpleData($annee, $centres, $totauxMois, $localiteLabels);
    }

    /**
     * Construit les données pivot centre × sexe × mois pour l'annuaire décès
     */
    private function buildAnnuaireStatistiqueDecesData(Request $request): array
    {
        $annee = (int) $request->input('annee', date('Y'));
        $localiteIds = $this->getLocaliteIdsRecursive($request);
        $localiteLabels = $this->getLocaliteLabels($request);
        $institutions = $this->getAnnuaireInstitutions($localiteIds);
        $lookup = [];
        $typeCec = self::TYPE_INSTITUTION_CEC_NAISSANCE;

        if (!empty($localiteIds)) {
            $placeholders = implode(',', array_fill(0, count($localiteIds), '?'));
            $params = array_merge([$typeCec, $typeCec, $typeCec, $annee, $typeCec], $localiteIds);

            $rows = DB::select(
                "SELECT
                    inst_cec.code_institution,
                    MONTH(dd.date_heure_declaration) AS mois,
                    p.sexe,
                    COUNT(*) AS total
                FROM t_declaration_deces dd
                JOIN tr_identification_personne p ON p.code_personne = dd.code_defunt
                JOIN tr_ins_user iu ON iu.cui = dd.code_user_institution
                JOIN tr_institution inst ON inst.code_institution = iu.code_institution
                JOIN tr_type_institution ti ON ti.code_type_institution = inst.code_type_institution
                LEFT JOIN tr_institution inst_dest ON inst_dest.code_institution = dd.code_institution_destinataire
                LEFT JOIN tr_type_institution ti_dest ON ti_dest.code_type_institution = inst_dest.code_type_institution
                LEFT JOIN tr_institution inst_cec_fb ON dd.cec_naissance IS NOT NULL AND dd.cec_naissance <> ''
                    AND UPPER(inst_cec_fb.lib_institution) LIKE CONCAT('%', UPPER(TRIM(REPLACE(REPLACE(dd.cec_naissance, 'MAIRIE CENTRALE DE', 'CENTRALE'), 'MAIRIE DE ', ''))), '%')
                LEFT JOIN tr_type_institution ti_cec_fb ON ti_cec_fb.code_type_institution = inst_cec_fb.code_type_institution
                    AND ti_cec_fb.code_type_institution = ?
                JOIN tr_institution inst_cec ON inst_cec.code_institution = COALESCE(
                    CASE WHEN ti_dest.code_type_institution = ? THEN dd.code_institution_destinataire END,
                    CASE WHEN ti.code_type_institution = ? THEN inst.code_institution END,
                    inst_cec_fb.code_institution
                )
                JOIN tr_type_institution ti_cec ON ti_cec.code_type_institution = inst_cec.code_type_institution
                WHERE YEAR(dd.date_heure_declaration) = ?
                  AND ti_cec.code_type_institution = ?
                  AND inst_cec.code_localite IN ($placeholders)
                  AND p.sexe IN ('M', 'F')
                GROUP BY inst_cec.code_institution, MONTH(dd.date_heure_declaration), p.sexe",
                $params
            );

            foreach ($rows as $row) {
                $code = $row->code_institution;
                $mois = (int) $row->mois;
                if (!isset($lookup[$code])) {
                    $lookup[$code] = ['M' => $this->initAnnuaireMoisValues(), 'F' => $this->initAnnuaireMoisValues()];
                }
                if ($mois >= 1 && $mois <= 12 && in_array($row->sexe, ['M', 'F'], true)) {
                    $lookup[$code][$row->sexe][$mois] = (int) $row->total;
                }
            }
        }

        $centres = [];
        $totauxMois = [
            'masculin' => $this->initAnnuaireMoisValues(),
            'feminin' => $this->initAnnuaireMoisValues(),
            'total' => $this->initAnnuaireMoisValues(),
        ];

        foreach ($institutions as $institution) {
            $code = $institution->code_institution;
            $masculin = $lookup[$code]['M'] ?? $this->initAnnuaireMoisValues();
            $feminin = $lookup[$code]['F'] ?? $this->initAnnuaireMoisValues();
            $centres[] = $this->buildAnnuaireCentreGenreRow($institution->lib_institution, $masculin, $feminin, $totauxMois);
        }

        return $this->finalizeAnnuaireGenreData($annee, $centres, $totauxMois, $localiteLabels);
    }

    private function normalizeAnnuaireTypeFait(?string $typeFait): string
    {
        return in_array($typeFait, ['naissance', 'mariage', 'deces'], true) ? $typeFait : 'naissance';
    }

    private function getAnnuaireFaitMeta(string $typeFait): array
    {
        return match ($typeFait) {
            'mariage' => [
                'titre_court' => 'Mariages enregistrés',
                'titre_pdf' => 'Tableaux statistique des mariages enregistrés',
                'pdf_view' => 'reporting::annuaire.annuaire-statistique-pdf-mariage',
                'filename' => 'annuaire_statistique_mariages',
            ],
            'deces' => [
                'titre_court' => 'Décès enregistrés',
                'titre_pdf' => 'Tableaux statistique des décès enregistrés',
                'pdf_view' => 'reporting::annuaire.annuaire-statistique-pdf-genre',
                'filename' => 'annuaire_statistique_deces',
            ],
            default => [
                'titre_court' => 'Naissances vivantes enregistrées',
                'titre_pdf' => 'Tableaux statistique des naissances vivantes enregistrées',
                'pdf_view' => 'reporting::annuaire.annuaire-statistique-pdf-genre',
                'filename' => 'annuaire_statistique_naissances',
            ],
        };
    }

    private function getAnnuaireInstitutions(array $localiteIds)
    {
        if (empty($localiteIds)) {
            return collect();
        }

        return Institution::query()
            ->join('tr_type_institution as ti', 'ti.code_type_institution', '=', 'tr_institution.code_type_institution')
            ->where('ti.code_type_institution', self::TYPE_INSTITUTION_CEC_NAISSANCE)
            ->whereIn('tr_institution.code_localite', $localiteIds)
            ->select('tr_institution.code_institution', 'tr_institution.lib_institution')
            ->get()
            ->sort(fn ($a, $b) => $this->compareAnnuaireCentreOrder($a->lib_institution, $b->lib_institution))
            ->values();
    }

    private function buildAnnuaireCentreGenreRow(string $nom, array $masculin, array $feminin, array &$totauxMois): array
    {
        $totalLigne = $this->initAnnuaireMoisValues();

        for ($m = 1; $m <= 12; $m++) {
            $totalLigne[$m] = $masculin[$m] + $feminin[$m];
            $totauxMois['masculin'][$m] += $masculin[$m];
            $totauxMois['feminin'][$m] += $feminin[$m];
            $totauxMois['total'][$m] += $totalLigne[$m];
        }

        $masculin['total'] = 0;
        $feminin['total'] = 0;
        for ($m = 1; $m <= 12; $m++) {
            $masculin['total'] += $masculin[$m];
            $feminin['total'] += $feminin[$m];
        }
        $totalLigne['total'] = $masculin['total'] + $feminin['total'];

        $totauxMois['masculin']['total'] += $masculin['total'];
        $totauxMois['feminin']['total'] += $feminin['total'];
        $totauxMois['total']['total'] += $totalLigne['total'];

        return [
            'nom' => $nom,
            'masculin' => $masculin,
            'feminin' => $feminin,
            'total' => $totalLigne,
            'pourcentage' => 0,
        ];
    }

    private function buildAnnuaireCentreSimpleRow(string $nom, array $ligne, array &$totauxMois): array
    {
        for ($m = 1; $m <= 12; $m++) {
            $totauxMois[$m] += $ligne[$m];
        }
        $ligne['total'] = 0;
        for ($m = 1; $m <= 12; $m++) {
            $ligne['total'] += $ligne[$m];
        }
        $totauxMois['total'] += $ligne['total'];

        return [
            'nom' => $nom,
            'ligne' => $ligne,
            'pourcentage' => 0,
        ];
    }

    private function finalizeAnnuaireGenreData(int $annee, array $centres, array $totauxMois, array $localiteLabels): array
    {
        $grandTotal = $totauxMois['total']['total'];
        if ($grandTotal > 0) {
            foreach ($centres as &$centre) {
                $centre['pourcentage'] = round(($centre['total']['total'] / $grandTotal) * 100, 1);
            }
            unset($centre);
        }

        $nbLignesTableau = (count($centres) * 3) + 3 + 1;
        $hauteurUtileMm = 118;
        $hauteurLigneMm = max(3, min(8, (int) floor($hauteurUtileMm / max(1, $nbLignesTableau))));
        $modeCompact = $hauteurLigneMm <= 4;

        return array_merge(
            compact('annee', 'centres', 'totauxMois', 'grandTotal', 'hauteurLigneMm', 'modeCompact'),
            $localiteLabels,
            ['moisCourts' => self::MOIS_ANNUAIRE_COURTS]
        );
    }

    private function finalizeAnnuaireSimpleData(int $annee, array $centres, array $totauxMois, array $localiteLabels): array
    {
        $grandTotal = $totauxMois['total'];
        if ($grandTotal > 0) {
            foreach ($centres as &$centre) {
                $centre['pourcentage'] = round(($centre['ligne']['total'] / $grandTotal) * 100, 1);
            }
            unset($centre);
        }

        $nbLignesTableau = count($centres) + 1 + 1;
        $hauteurUtileMm = 118;
        $hauteurLigneMm = max(3, min(10, (int) floor($hauteurUtileMm / max(1, $nbLignesTableau))));
        $modeCompact = $hauteurLigneMm <= 4;

        return array_merge(
            compact('annee', 'centres', 'totauxMois', 'grandTotal', 'hauteurLigneMm', 'modeCompact'),
            $localiteLabels,
            ['moisCourts' => self::MOIS_ANNUAIRE_COURTS]
        );
    }

    /**
     * Compare l'ordre d'affichage des centres : centrale, puis arrondissements 1→9, puis le reste.
     */
    private function compareAnnuaireCentreOrder(string $libA, string $libB): int
    {
        return $this->getAnnuaireCentreSortKey($libA) <=> $this->getAnnuaireCentreSortKey($libB);
    }

    /**
     * Clé de tri : [priorité, numéro arrondissement, libellé].
     */
    private function getAnnuaireCentreSortKey(string $libInstitution): array
    {
        $lib = mb_strtoupper(trim($libInstitution));

        if (str_contains($lib, 'CENTRALE')) {
            return [0, 0, $lib];
        }

        if (preg_match('/ARRONDISSEMENT\s+(\d+)/', $lib, $m)) {
            return [1, (int) $m[1], $lib];
        }

        return [2, 999, $lib];
    }

    /**
     * Initialise les compteurs mensuels d'une ligne d'annuaire (1-12 + total)
     */
    private function initAnnuaireMoisValues(): array
    {
        $values = ['total' => 0];
        for ($i = 1; $i <= 12; $i++) {
            $values[$i] = 0;
        }

        return $values;
    }

    /**
     * Formulaire du répertoire alphabétique (Naissance / Mariage / Décès)
     */
    public function repertoireAlphabetiqueFaits(Request $request)
    {
        $typeFait = $this->normalizeAnnuaireTypeFait($request->input('type_fait', 'naissance'));

        return view('reporting::faits.repertoire-alphabetique', [
            'typeFait' => $typeFait,
            'dated' => $request->input('dated'),
            'datef' => $request->input('datef'),
        ]);
    }

    /**
     * Affiche le répertoire alphabétique dans le viewer PDF inline
     */
    public function displayRepertoireAlphabetiqueFaits(Request $request)
    {
        $request->validate([
            'type_fait' => 'required|in:naissance,mariage,deces',
            'dated' => 'nullable|date',
            'datef' => 'nullable|date',
        ]);

        $typeFait = $this->normalizeAnnuaireTypeFait($request->input('type_fait'));
        $payload = $this->buildRepertoireAlphabetiquePayload($typeFait, $request);

        if ($payload === null) {
            return redirect()
                ->route('reporting.faits.repertoire.alphabetique', [
                    'type_fait' => $typeFait,
                    'dated' => $request->input('dated'),
                    'datef' => $request->input('datef'),
                ])
                ->with('warning', 'Aucune donnée trouvée pour la période sélectionnée.');
        }

        $meta = $this->getRepertoireAlphabetiqueMeta($typeFait);
        $pdfRoute = route('reporting.faits.repertoire.alphabetique.pdf', array_filter([
            'type_fait' => $typeFait,
            'dated' => $request->input('dated'),
            'datef' => $request->input('datef'),
        ], fn ($v) => $v !== null && $v !== ''));

        return view('reporting::faits.repertoire-alphabetique-display', [
            'pdfRoute' => $pdfRoute,
            'pdfFilename' => $meta['filename'].'.pdf',
            'typeFait' => $typeFait,
            'titreFait' => $meta['titre_court'],
        ]);
    }

    /**
     * Génère le PDF du répertoire alphabétique selon le type de fait
     */
    public function repertoireAlphabetiqueFaitsPdf(Request $request)
    {
        $request->validate([
            'type_fait' => 'required|in:naissance,mariage,deces',
            'dated' => 'nullable|date',
            'datef' => 'nullable|date',
        ]);

        $typeFait = $this->normalizeAnnuaireTypeFait($request->input('type_fait'));
        $payload = $this->buildRepertoireAlphabetiquePayload($typeFait, $request);

        if ($payload === null) {
            abort(404, 'Aucune donnée trouvée pour la période sélectionnée.');
        }

        $meta = $this->getRepertoireAlphabetiqueMeta($typeFait);
        $viewData = array_merge($payload['view_data'], [
            'dated' => $payload['dated'],
            'datef' => $payload['datef'],
        ]);

        view()->share('tester', 'Répertoire');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view($meta['pdf_view'], $viewData)->render());

        $filename = $meta['filename'].'.pdf';

        return $this->pdfInlineResponse($html2pdf->output($filename, 'S'), $filename);
    }

    private function getRepertoireAlphabetiqueMeta(string $typeFait): array
    {
        return match ($typeFait) {
            'mariage' => [
                'titre_court' => 'Actes de mariage',
                'pdf_view' => 'mariage::etats.repertoire',
                'filename' => 'repertoire_alphabetique_mariages',
            ],
            'deces' => [
                'titre_court' => 'Actes de décès',
                'pdf_view' => 'deces::etats.listedeces',
                'filename' => 'repertoire_alphabetique_deces',
            ],
            default => [
                'titre_court' => 'Actes de naissance',
                'pdf_view' => 'naissance::etats.repertoire',
                'filename' => 'repertoire_alphabetique_naissances',
            ],
        };
    }

    /**
     * @return array{dated: ?string, datef: ?string, view_data: array}|null
     */
    private function buildRepertoireAlphabetiquePayload(string $typeFait, Request $request): ?array
    {
        [$dated, $datef] = $this->normalizeRepertoireDates($request->input('dated'), $request->input('datef'));

        $rows = match ($typeFait) {
            'mariage' => $this->fetchRepertoireMariageRows($dated, $datef),
            'deces' => $this->fetchRepertoireDecesRows($dated, $datef),
            default => $this->fetchRepertoireNaissanceRows($dated, $datef),
        };

        if ($rows->isEmpty()) {
            return null;
        }

        $viewData = match ($typeFait) {
            'deces' => ['listes' => $rows],
            default => ['actes' => $rows],
        };

        return [
            'dated' => $dated,
            'datef' => $datef,
            'view_data' => $viewData,
        ];
    }

    /**
     * @return array{0: ?string, 1: ?string}
     */
    private function normalizeRepertoireDates(?string $dated, ?string $datef): array
    {
        $dated = $dated ?: null;
        $datef = $datef ?: null;

        if ($dated !== null && $datef !== null && $dated > $datef) {
            [$dated, $datef] = [$datef, $dated];
        }

        return [$dated, $datef];
    }

    private function fetchRepertoireNaissanceRows(?string $dated, ?string $datef)
    {
        $affectation = Auth::user()->affectationActive();
        if (!$affectation) {
            return collect();
        }

        $query = DB::table('tr_identification_personne as p')
            ->join('t_declaration_naissance as dn', 'dn.code_enfant', '=', 'p.code_personne')
            ->join('t_acte_naissance as an', 'an.code_declaration_naissance', '=', 'dn.code_declaration_naissance')
            ->join('tr_ins_user as iuser', 'an.cui', '=', 'iuser.cui')
            ->join('tr_institution as ins', 'iuser.code_institution', '=', 'ins.code_institution')
            ->select('p.nom', 'p.prenom', 'p.sexe', 'p.date_naissance', 'an.niupp')
            ->where('ins.code_institution', $affectation->code_institution);

        if ($dated !== null) {
            $query->whereDate('an.date_emission', '>=', $dated);
        }
        if ($datef !== null) {
            $query->whereDate('an.date_emission', '<=', $datef);
        }

        return $query
            ->orderBy('p.nom')
            ->orderBy('p.prenom')
            ->orderBy('p.sexe')
            ->orderBy('p.date_naissance')
            ->orderBy('an.niupp')
            ->get();
    }

    private function fetchRepertoireMariageRows(?string $dated, ?string $datef)
    {
        $affectation = Auth::user()->affectationActive();
        if (!$affectation) {
            return collect();
        }

        $query = ActeMariage::with(['declaration.epoux', 'declaration.epouse'])
            ->where('cui', $affectation->cui);

        if ($dated !== null) {
            $query->whereDate('date_emission', '>=', $dated);
        }
        if ($datef !== null) {
            $query->whereDate('date_emission', '<=', $datef);
        }

        return $query->get()->sortBy(function ($acte) {
            $nomEpoux = mb_strtolower(trim((string) optional(optional($acte->declaration)->epoux)->nom));
            $prenomEpoux = mb_strtolower(trim((string) optional(optional($acte->declaration)->epoux)->prenom));
            $nomEpouse = mb_strtolower(trim((string) optional(optional($acte->declaration)->epouse)->nom));
            $prenomEpouse = mb_strtolower(trim((string) optional(optional($acte->declaration)->epouse)->prenom));

            return $nomEpoux.' '.$prenomEpoux.'|'.$nomEpouse.' '.$prenomEpouse;
        })->values();
    }

    private function fetchRepertoireDecesRows(?string $dated, ?string $datef)
    {
        $affectation = Auth::user()->affectationActive();
        if (!$affectation) {
            return collect();
        }

        $query = DB::table('tr_identification_personne as p')
            ->join('t_declaration_deces as dd', 'dd.code_defunt', '=', 'p.code_personne')
            ->join('t_acte_deces as ad', 'ad.code_declaration_deces', '=', 'dd.code_declaration_deces')
            ->join('tr_lieu_survenance as ls', 'ls.code_lieu_survenance', '=', 'dd.code_lieu_survenance')
            ->join('tr_ins_user as iuser', 'ad.cui', '=', 'iuser.cui')
            ->join('tr_institution as ins', 'iuser.code_institution', '=', 'ins.code_institution')
            ->select(
                'p.nom',
                'p.prenom',
                'p.sexe',
                'ad.code_acte_deces',
                'dd.date_heure_deces',
                'ls.lib_lieu_survenance'
            )
            ->where('ins.code_institution', $affectation->code_institution);

        if ($dated !== null) {
            $query->whereDate('dd.date_heure_deces', '>=', $dated);
        }
        if ($datef !== null) {
            $query->whereDate('dd.date_heure_deces', '<=', $datef);
        }

        return $query
            ->orderBy('p.nom')
            ->orderBy('p.prenom')
            ->orderBy('p.sexe')
            ->orderBy('dd.date_heure_deces')
            ->orderBy('ad.code_acte_deces')
            ->get();
    }

    /**
     * Affiche la page avec le viewer PDF pour le rapport d'exploitation
     */
    public function displayRapportExploitationFaits(Request $request)
    {
        // Construire l'URL du PDF avec uniquement les paramètres utiles
        $params = $request->only([
            'type_fait',
            'annee',
            'departement',
            'district',
            'arrondissement',
            'village',
        ]);
        $params = array_filter($params, function ($value) {
            return $value !== null && $value !== '';
        });
        $pdfRoute = route('reporting.faits.rapport.exploitation.pdf', $params);
        
        return view('reporting::faits.rapport-exploitation-display', compact('pdfRoute'));
    }

    /**
     * Réponse binaire PDF avec en-têtes corrects pour affichage inline
     */
    private function pdfInlineResponse(string $pdfBinary, string $filename): Response
    {
        $safeName = preg_replace('/[^a-zA-Z0-9._\-]/', '_', $filename) ?: 'rapport.pdf';
        $safeName = trim($safeName) !== '' ? trim($safeName) : 'rapport.pdf';

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$safeName.'"',
            'Cache-Control' => 'private, must-revalidate',
        ]);
    }

    /** Ordre d'affichage des départements (tableau national client). */
    private const TABLEAU_NATIONAL_DEPARTEMENT_ORDER = [
        'LOC_0001', 'LOC_0002', 'LOC_0010', 'LOC_0012', 'LOC_0011', 'LOC_0009',
        'LOC_0008', 'LOC_0007', 'LOC_0006', 'LOC_0005', 'LOC_0004', 'LOC_0003',
    ];

    private const LOCALITE_DEPARTEMENT_CTE = <<<'SQL'
WITH RECURSIVE loc_dep AS (
    SELECT code_localite, code_localite AS code_dep
    FROM tr_localite
    WHERE code_type_localite = 'TPLOC_0001'
    UNION ALL
    SELECT l.code_localite, ld.code_dep
    FROM tr_localite l
    INNER JOIN loc_dep ld ON l.code_localite_parent = ld.code_localite
)
SQL;

    /**
     * Formulaire — tableau statistique national des actes par département
     */
    public function tableauNationalDepartements(Request $request)
    {
        $anneeActuelle = (int) date('Y');
        $annees = range($anneeActuelle, $anneeActuelle - 10);

        return view('reporting::statistique.tableau-national-departements', compact('annees', 'anneeActuelle'));
    }

    /**
     * Affichage inline (Kendo PDF Viewer) du tableau national
     */
    public function displayTableauNationalDepartements(Request $request)
    {
        $request->validate([
            'annee' => 'required|integer|min:2000|max:2100',
        ]);

        $annee = (int) $request->input('annee');
        $pdfRoute = route('reporting.statistique.tableau.national.departements.pdf', ['annee' => $annee]);
        $pdfFilename = 'tableau_national_departements_' . $annee . '.pdf';

        return view('reporting::statistique.tableau-national-departements-display', compact('pdfRoute', 'pdfFilename', 'annee'));
    }

    /**
     * PDF — tableau statistique national des actes par département
     */
    public function tableauNationalDepartementsPdf(Request $request)
    {
        $request->validate([
            'annee' => 'required|integer|min:2000|max:2100',
        ]);

        $annee = (int) $request->input('annee');
        $reportData = $this->buildTableauNationalDepartementsActesData($annee);

        $html2pdf = new Html2Pdf('L', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->pdf->SetMargins(10, 10, 10);
        $html2pdf->writeHTML(view('reporting::statistique.tableau-national-departements-pdf', $reportData)->render());

        $filename = 'tableau_national_departements_' . $annee . '.pdf';

        return $this->pdfInlineResponse($html2pdf->output($filename, 'S'), $filename);
    }

    /**
     * Agrège les actes (naissances, décès, mariages) par département pour une année.
     */
    private function buildTableauNationalDepartementsActesData(int $annee): array
    {
        $departements = Localite::query()
            ->where('code_type_localite', 'TPLOC_0001')
            ->whereIn('code_localite', self::TABLEAU_NATIONAL_DEPARTEMENT_ORDER)
            ->get()
            ->keyBy('code_localite');

        $orderedDepartements = [];
        foreach (self::TABLEAU_NATIONAL_DEPARTEMENT_ORDER as $code) {
            if ($departements->has($code)) {
                $orderedDepartements[$code] = $departements->get($code);
            }
        }

        $naissances = $this->aggregateActesNaissanceParDepartement($annee);
        $deces = $this->aggregateActesDecesParDepartement($annee);
        $mariages = $this->aggregateActesMariageParDepartement($annee);

        $lignes = [];
        $totaux = [
            'naissance' => ['h' => 0, 'f' => 0, 't' => 0],
            'deces' => ['h' => 0, 'f' => 0, 't' => 0],
            'mariage' => ['mono' => 0, 'poly' => 0, 't' => 0],
        ];

        foreach ($orderedDepartements as $code => $departement) {
            $n = $naissances[$code] ?? ['h' => 0, 'f' => 0];
            $d = $deces[$code] ?? ['h' => 0, 'f' => 0];
            $m = $mariages[$code] ?? ['mono' => 0, 'poly' => 0];

            $ligne = [
                'departement' => $departement->lib_localite,
                'code_departement' => $code,
                'naissance' => [
                    'h' => (int) $n['h'],
                    'f' => (int) $n['f'],
                    't' => (int) $n['h'] + (int) $n['f'],
                ],
                'deces' => [
                    'h' => (int) $d['h'],
                    'f' => (int) $d['f'],
                    't' => (int) $d['h'] + (int) $d['f'],
                ],
                'mariage' => [
                    'mono' => (int) $m['mono'],
                    'poly' => (int) $m['poly'],
                    't' => (int) $m['mono'] + (int) $m['poly'],
                ],
            ];

            $totaux['naissance']['h'] += $ligne['naissance']['h'];
            $totaux['naissance']['f'] += $ligne['naissance']['f'];
            $totaux['naissance']['t'] += $ligne['naissance']['t'];
            $totaux['deces']['h'] += $ligne['deces']['h'];
            $totaux['deces']['f'] += $ligne['deces']['f'];
            $totaux['deces']['t'] += $ligne['deces']['t'];
            $totaux['mariage']['mono'] += $ligne['mariage']['mono'];
            $totaux['mariage']['poly'] += $ligne['mariage']['poly'];
            $totaux['mariage']['t'] += $ligne['mariage']['t'];

            $lignes[] = $ligne;
        }

        foreach ($lignes as &$ligne) {
            $ligne['naissance']['taux'] = $this->calculTauxTableauNational($ligne['naissance']['t'], $totaux['naissance']['t']);
            $ligne['deces']['taux'] = $this->calculTauxTableauNational($ligne['deces']['t'], $totaux['deces']['t']);
            $ligne['mariage']['taux'] = $this->calculTauxTableauNational($ligne['mariage']['t'], $totaux['mariage']['t']);
        }
        unset($ligne);

        $totaux['naissance']['taux'] = $totaux['naissance']['t'] > 0 ? 100.0 : null;
        $totaux['deces']['taux'] = $totaux['deces']['t'] > 0 ? 100.0 : null;
        $totaux['mariage']['taux'] = $totaux['mariage']['t'] > 0 ? 100.0 : null;

        return [
            'annee' => $annee,
            'lignes' => $lignes,
            'total' => $totaux,
        ];
    }

    private function calculTauxTableauNational(int $valeur, int $totalNational): ?float
    {
        if ($totalNational <= 0) {
            return null;
        }

        if ($valeur <= 0) {
            return 0.0;
        }

        return round(($valeur / $totalNational) * 100, 2);
    }

    private function aggregateActesNaissanceParDepartement(int $annee): array
    {
        $sql = self::LOCALITE_DEPARTEMENT_CTE . '
            SELECT
                ld.code_dep,
                SUM(CASE WHEN p.sexe = \'M\' THEN 1 ELSE 0 END) AS h,
                SUM(CASE WHEN p.sexe = \'F\' THEN 1 ELSE 0 END) AS f
            FROM t_acte_naissance an
            INNER JOIN t_declaration_naissance dn ON dn.code_declaration_naissance = an.code_declaration_naissance
            INNER JOIN tr_identification_personne p ON p.code_personne = dn.code_enfant
            LEFT JOIN tr_ins_user iu ON iu.cui = an.cui
            INNER JOIN tr_institution inst ON inst.code_institution = COALESCE(an.code_institution, iu.code_institution)
            INNER JOIN loc_dep ld ON ld.code_localite = inst.code_localite
            WHERE an.deleted_at IS NULL
              AND an.statut = 0
              AND an.date_emission IS NOT NULL
              AND YEAR(an.date_emission) = ?
              AND p.sexe IN (\'M\', \'F\')
            GROUP BY ld.code_dep';

        return $this->indexAggregateRowsByDepartement(DB::select($sql, [$annee]));
    }

    private function aggregateActesDecesParDepartement(int $annee): array
    {
        $sql = self::LOCALITE_DEPARTEMENT_CTE . '
            SELECT
                ld.code_dep,
                SUM(CASE WHEN p.sexe = \'M\' THEN 1 ELSE 0 END) AS h,
                SUM(CASE WHEN p.sexe = \'F\' THEN 1 ELSE 0 END) AS f
            FROM t_acte_deces ad
            INNER JOIN t_declaration_deces dd ON dd.code_declaration_deces = ad.code_declaration_deces
            INNER JOIN tr_identification_personne p ON p.code_personne = dd.code_defunt
            LEFT JOIN tr_ins_user iu ON iu.cui = ad.cui
            INNER JOIN tr_institution inst ON inst.code_institution = COALESCE(ad.code_institution, iu.code_institution)
            INNER JOIN loc_dep ld ON ld.code_localite = inst.code_localite
            WHERE ad.deleted_at IS NULL
              AND ad.statut = 0
              AND ad.date_emission IS NOT NULL
              AND YEAR(ad.date_emission) = ?
              AND p.sexe IN (\'M\', \'F\')
            GROUP BY ld.code_dep';

        return $this->indexAggregateRowsByDepartement(DB::select($sql, [$annee]));
    }

    private function aggregateActesMariageParDepartement(int $annee): array
    {
        $sql = self::LOCALITE_DEPARTEMENT_CTE . '
            SELECT
                ld.code_dep,
                SUM(CASE WHEN om.lib_option_mariage LIKE \'%Monogam%\' OR dm.code_option_mariage IN (\'OPM_0001\', \'OMRG_0002\') THEN 1 ELSE 0 END) AS mono,
                SUM(CASE WHEN om.lib_option_mariage LIKE \'%Polygam%\' OR dm.code_option_mariage IN (\'OPM_0002\', \'OMRG_0001\') THEN 1 ELSE 0 END) AS poly
            FROM t_acte_mariage am
            INNER JOIN t_declaration_mariage dm ON dm.code_declaration_mariage = am.code_declaration_mariage
            LEFT JOIN tr_option_mariage om ON om.code_option_mariage = dm.code_option_mariage
            LEFT JOIN tr_ins_user iu ON iu.cui = am.cui
            INNER JOIN tr_institution inst ON inst.code_institution = COALESCE(am.code_institution, iu.code_institution)
            INNER JOIN loc_dep ld ON ld.code_localite = inst.code_localite
            WHERE am.deleted_at IS NULL
              AND am.statut = 0
              AND am.date_emission IS NOT NULL
              AND YEAR(am.date_emission) = ?
            GROUP BY ld.code_dep';

        $indexed = [];
        foreach (DB::select($sql, [$annee]) as $row) {
            $indexed[$row->code_dep] = [
                'mono' => (int) $row->mono,
                'poly' => (int) $row->poly,
            ];
        }

        return $indexed;
    }

    /**
     * @param array<int, object> $rows
     * @return array<string, array{h: int, f: int}>
     */
    private function indexAggregateRowsByDepartement(array $rows): array
    {
        $indexed = [];
        foreach ($rows as $row) {
            $indexed[$row->code_dep] = [
                'h' => (int) $row->h,
                'f' => (int) $row->f,
            ];
        }

        return $indexed;
    }

    /**
     * Affichage d'une cellule numérique (0 → tiret sauf ligne total).
     */
    public static function fmtCellTableauNational(int $value, bool $isTotalRow = false): string
    {
        if (!$isTotalRow && $value === 0) {
            return '-';
        }

        return (string) $value;
    }

    /**
     * Affichage d'un taux (%).
     */
    public static function fmtTauxTableauNational(?float $taux): string
    {
        if ($taux === null) {
            return '-';
        }

        return number_format($taux, 2, ',', '') . '%';
    }
}
