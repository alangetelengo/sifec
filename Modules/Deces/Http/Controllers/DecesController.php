<?php

namespace Modules\Deces\Http\Controllers;

use App\Sifec\Sifec;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Deces\Entities\ActeDeces;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Deces\Entities\MouvementDeces;
use Modules\Deces\Services\DeclarationDecesService;
use Modules\Deces\Services\MouvementService;
use Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification;
use Modules\Notification\Services\NotificationService;
use Modules\Referentiel\Entities\CauseDeces;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Filiation;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\LieuSurvenance;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\Profession;
use Modules\Referentiel\Entities\Regime;
use Modules\Referentiel\Entities\Religion;
use Modules\Referentiel\Entities\SituationMatrimoniale;
use Modules\Referentiel\Entities\TypeDocument;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Component\HttpFoundation\Response;

class DecesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {

        $instructions = Sifec::niveauInstructions();
        // Mettre en ordre DESC la liste des déclarations
        $declarations = Auth::user()->institution()->declarationsDecesQuery()->orderByDesc('date_heure_declaration')
            ->limit(2000)
            ->get();
        $title = 'Liste des certificats de décès';
        $button = 'Enregistrer un certificat de décès';

        return view('deces::declaration.index', compact('declarations', 'instructions', 'title', 'button'));
    }

    public function displayCertifatNonInscription($id)
    {
        $acte = DeclarationDeces::where('code_declaration_deces', $id)->first();
        if ($acte == null) {
            flash()->error('Vous ne pouvez pas généré un certificat de non incription de décès');

            return back();
        }

        DB::beginTransaction();

        try {
            view()->share('tester', [], 'Alange');
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML(view('deces::etats.certificats.certificat_non_inscription', compact('acte'))->render());
            DB::commit();

            return $html2pdf->output($acte->code_declaration_deces.'.pdf');

        } catch (Exception $e) {
            DB::rollBack();
            flash()->error($e->getMessage());

            return back();
        }
    }

    public function certificatConstatation()
    {
        $constatationdeces = DeclarationDeces::all();

        return view('deces::certificat-constatation-deces.index', compact('constatationdeces'));
    }

    public function voirEtat(Request $request, string $id)
    {
        $ddc = DeclarationDeces::where('code_declaration_deces', $id)->first();
        if ($ddc === null) {
            abort(404);
        }

        $contexte = $request->query('contexte');
        if ($contexte && ! in_array($contexte, ['formation_sanitaire', 'centre_hygiene', 'pompe_funebre'], true)) {
            $contexte = null;
        }

        $retour = $request->query('from') === 'acte' ? 'acte' : 'declaration';

        $routeParams = ['id' => $id];
        if ($contexte) {
            $routeParams['contexte'] = $contexte;
        }
        $pdfUrl = route('declarationDeces.etat', $routeParams);

        $titrePage = match ($contexte) {
            'formation_sanitaire' => 'Certificat de décès',
            'centre_hygiene' => 'Certificat de constatation de décès',
            'pompe_funebre' => 'Déclaration de décès',
            default => 'Document PDF',
        };

        return view('deces::declaration.voir-etat-pdf', compact('ddc', 'pdfUrl', 'retour', 'titrePage'));
    }

    public function etat(Request $request, $id)
    {
        $contexteForcage = $request->query('contexte');
        if ($contexteForcage && ! in_array($contexteForcage, ['formation_sanitaire', 'centre_hygiene', 'pompe_funebre'], true)) {
            $contexteForcage = null;
        }

        $ddc = DeclarationDeces::with([
            'institution', 'institutionDestinataire', 'institutionUser.institution',
            'defunt', 'pere', 'mere', 'declarant', 'religion', 'situationMat', 'regime', 'conjoint', 'filiation', 'lieuDeces', 'lieuSurvenance',
        ])->where('code_declaration_deces', $id)->first();

        if ($ddc === null) {
            abort(404);
        }

        $dat1 = Carbon::create($ddc->created_at);
        $dateDeces = Carbon::create($ddc->date_heure_deces);
        $diffJour = $dateDeces->diffInDays($dat1);

        view()->share('tester', [], 'Alange');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');

        $typeDeclaration = $ddc->libelleAffichageType();
        $contexteEffectif = $contexteForcage ?? $ddc->contexte_affichage ?? $ddc->contexteCertificatOrigine();

        if ($contexteEffectif === 'centre_hygiene'
            || ($ddc->type_declaration === 'CERTIFICAT DE CONSTATATION DE DECES' && $contexteForcage !== 'pompe_funebre')) {
            $html2pdf->writeHTML(view('deces::etats.certificats.certificat_constatation_deces', compact('ddc'))->render());

            return $this->pdfInlineResponse(
                $html2pdf->output($ddc->code_declaration_deces.'.pdf'),
                $ddc->code_declaration_deces.'.pdf'
            );
        }

        $html2pdf->writeHTML(view('deces::etats.declaration', compact('ddc', 'diffJour', 'typeDeclaration', 'contexteForcage'))->render());

        return $this->pdfInlineResponse(
            $html2pdf->output($ddc->code_declaration_deces.'.pdf'),
            $ddc->code_declaration_deces.'.pdf'
        );
    }

    private function pdfInlineResponse(string $pdfBinary, string $filename): Response
    {
        $safeName = preg_replace('/[^a-zA-Z0-9._\-]/', '_', $filename) ?: 'document.pdf';
        $safeName = trim($safeName) !== '' ? trim($safeName) : 'document.pdf';

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$safeName.'"',
            'Cache-Control' => 'private, must-revalidate',
        ]);
    }

    public function create()
    {
        $title = 'Enregistrer un certificat de décès';

        $type_declaration = 'DECLARATION DE DECES';
        $cecMariage = Institution::where('code_type_institution', 'TPINS_0002')->get();
        $instructions = Sifec::niveauInstructions();
        // $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $localites = Localite::where('code_type_localite', 'TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $religions = Religion::all();
        $lieusurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $regimes = Regime::all();
        $causesDeces = CauseDeces::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $typedocuments = TypeDocument::all();
        $arrondissement = Localite::where('code_type_localite', 'TPLOC_0004')->Orwhere('code_type_localite', 'TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite', 'TPLOC_0007')->Orwhere('code_type_localite', 'TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path('codes_pays.json'))));
        $departements = Departement::all();

        return view('deces::declaration.create', compact('title', 'type_declaration', 'quartierVillages', 'cecMariage', 'departements', 'countries', 'arrondissement', 'instructions', 'typedocuments', 'causesDeces', 'regimes', 'localites', 'professions', 'nationalites', 'situationMatrimoniales', 'religions', 'lieusurvenances', 'filiations'));
    }

    // creer une autorisation de transfert de dépouille
    public function createTransfertDepouille()
    {
        $title = 'Créer une autorisation de transfert de dépouille';

        $type_declaration = 'AUTORISATION DE TRANSFERT DE DEPOUILLE';
        $cecMariage = Institution::where('code_type_institution', 'TPINS_0002')->get();
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite', 'TPLOC_0002')->Orwhere('code_type_localite', 'TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $religions = Religion::all();
        $lieusurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $regimes = Regime::all();
        $causesDeces = CauseDeces::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $typedocuments = TypeDocument::all();
        $arrondissement = Localite::where('code_type_localite', 'TPLOC_0004')->Orwhere('code_type_localite', 'TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite', 'TPLOC_0007')->Orwhere('code_type_localite', 'TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path('codes_pays.json'))));
        $departements = Departement::all();

        return view('deces::declaration.create', compact('title', 'type_declaration', 'quartierVillages', 'cecMariage', 'departements', 'countries', 'arrondissement', 'instructions', 'typedocuments', 'causesDeces', 'regimes', 'localites', 'professions', 'nationalites', 'situationMatrimoniales', 'religions', 'lieusurvenances', 'filiations'));
    }

    public function certificatNonIscription()
    {
        $title = 'Créer un certificat de non inscription';

        $type_declaration = 'CERTIFICAT DE NON INSCRIPTION';
        $cecMariage = Institution::where('code_type_institution', 'TPINS_0002')->get();
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite', 'TPLOC_0002')->Orwhere('code_type_localite', 'TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $religions = Religion::all();
        $lieusurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $regimes = Regime::all();
        $causesDeces = CauseDeces::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $typedocuments = TypeDocument::all();
        $arrondissement = Localite::where('code_type_localite', 'TPLOC_0004')->Orwhere('code_type_localite', 'TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite', 'TPLOC_0007')->Orwhere('code_type_localite', 'TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path('codes_pays.json'))));
        $departements = Departement::all();

        return view('deces::declaration.create', compact('title', 'type_declaration', 'quartierVillages', 'cecMariage', 'departements', 'countries', 'arrondissement', 'instructions', 'typedocuments', 'causesDeces', 'regimes', 'localites', 'professions', 'nationalites', 'situationMatrimoniales', 'religions', 'lieusurvenances', 'filiations'));
    }

    public function declarationTardive()
    {
        $title = 'Créer une déclaration tardive de décès';
        $type_declaration = 'DECLARATION TARDIVE';
        $datedeces = request('date_deces');

        $cecMariage = Institution::where('code_type_institution', 'TPINS_0002')->get();
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite', 'TPLOC_0002')->Orwhere('code_type_localite', 'TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $religions = Religion::all();
        $lieusurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $regimes = Regime::all();
        $causesDeces = CauseDeces::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $typedocuments = TypeDocument::all();
        $arrondissement = Localite::where('code_type_localite', 'TPLOC_0004')->Orwhere('code_type_localite', 'TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite', 'TPLOC_0007')->Orwhere('code_type_localite', 'TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path('codes_pays.json'))));
        $departements = Departement::all();

        return view('deces::declaration.create', compact('type_declaration', 'cecMariage', 'title', 'datedeces', 'departements', 'arrondissement', 'quartierVillages', 'countries', 'instructions', 'typedocuments', 'causesDeces', 'regimes', 'localites', 'professions', 'nationalites', 'situationMatrimoniales', 'religions', 'lieusurvenances', 'filiations'));

    }

    public function store(Request $request, DeclarationDecesService $service)
    {
        try {
            // Enregistrement de la déclaration
            $resultatEnregistrement = $service->enregistrer($request, Auth::user());

            // Si le service retourne une réponse JSON (erreur), on la retourne directement
            if ($resultatEnregistrement instanceof JsonResponse) {
                return $resultatEnregistrement;
            }

            $declaration = $resultatEnregistrement;

            // Gestion du mouvement après enregistrement réussi
            $resultatMouvement = $this->gererMouvementDeclaration($request, $declaration);
            if ($resultatMouvement !== true) {
                return $resultatMouvement;
            }

            return response()->json([
                'code' => '200',
                'message' => 'La déclaration de décès a été enregistrée avec succès',
            ]);

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur dans le contrôleur de déclaration de décès: '.$e->getMessage());

            return response()->json([
                'code' => '90',
                'message' => "Une erreur inattendue s'est produite lors de l'enregistrement",
            ]);
        }
    }

    /**
     * Gère le mouvement de la déclaration
     */
    private function gererMouvementDeclaration($request, $declaration)
    {
        $mappingTypeEvenement = [
            'DECLARATION DE DECES' => 'declaration_deces',
            'DECLARATION TARDIVE' => 'declaration_tardive',
            'CERTIFICAT DE CONSTATATION DE DECES' => 'certificat_constatation_deces',
            'CERTIFICAT DE NON INSCRIPTION' => 'certificat_non_inscription',
            "CERTIFICAT DE DESTRUCTION DE L'ACTE" => 'certificat_destruction',
            'FICHE DE TRANSCRIPTION' => 'fiche_transcription',
        ];

        $typeDeclaration = $request->input('type_declaration', 'DECLARATION DE DECES');
        $typeEvenement = $mappingTypeEvenement[$typeDeclaration] ?? 'declaration_deces';

        try {
            $mouvementService = app(MouvementService::class);
            $result = $mouvementService->ajouterEvenementDeclaration(Auth::user(), $declaration, $typeEvenement);

            if (! $result[0]) {
                return response()->json([
                    'code' => '91',
                    'message' => "Erreur lors de l'enregistrement du mouvement: ".$result[1],
                ]);
            }

            return true;

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la gestion du mouvement: '.$e->getMessage());

            return response()->json([
                'code' => '91',
                'message' => "Erreur lors de l'enregistrement du mouvement",
            ]);
        }
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        $declaration = DeclarationDeces::where('code_declaration_deces', $id)->first();
        if ($declaration == null) {
            flash()->error('Déclaration de décès non trouvée');

            return redirect()->route('declarationDeces.index');
        }

        return view('deces::declaration.show', compact('declaration'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $dd = DeclarationDeces::find($id);
        $cecMariage = Institution::where('code_type_institution', [], 'TPINS_0002')->get();
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite', 'TPLOC_0002')->Orwhere('code_type_localite', 'TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $religions = Religion::all();
        $lieusurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $regimes = Regime::all();
        $causesDeces = CauseDeces::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $typedocuments = TypeDocument::all();
        $arrondissement = Localite::where('code_type_localite', 'TPLOC_0004')->Orwhere('code_type_localite', 'TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite', 'TPLOC_0007')->Orwhere('code_type_localite', 'TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path('codes_pays.json'))));
        $departements = Departement::all();

        // return view('deces::declaration.edit',compact("departements", "causesDeces","regimes","typedocuments","arrondissement","countries","localites","instructions","professions","nationalites","situationMatrimoniales","religions","lieusurvenances","filiations","declaration"));

        return view('deces::declaration.edit', compact('dd', 'quartierVillages', 'cecMariage', 'departements', 'countries', 'arrondissement', 'instructions', 'typedocuments', 'causesDeces', 'regimes', 'localites', 'professions', 'nationalites', 'situationMatrimoniales', 'religions', 'lieusurvenances', 'filiations'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id, DeclarationDecesService $service)
    {
        try {
            $declaration = $service->update($request, $id, Auth::user());

            return response()->json([
                'code' => '200',
                'message' => 'Document modifié avec succès',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'code' => '150',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $declaradeces = DeclarationDeces::find($request->code_declaration);
        $declaradeces->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déclaration suprimée avec succès !',
        ]);
    }

    public function statParCause()
    {

        $declarations = Auth::user()->institution()->declarationsDeces();

        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_declaration_deces;
        }
        $array = implode("','", $liste); // liste des codes déclarati

        $datas = DB::select("SELECT tr_arrondissement.lib_arrondissement AS arrondissement, tr_cause_deces.lib_cause_deces, COUNT(tr_cause_deces.lib_cause_deces) AS TOTAL
        FROM t_declaration_deces
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
        JOIN t_ddecescause ON t_ddecescause.code_declaration_deces = t_declaration_deces.code_declaration_deces
        JOIN tr_cause_deces ON tr_cause_deces.code_cause_deces = t_ddecescause.code_cause_deces

        JOIN t_adresse_personne ON t_adresse_personne.code_personne = tr_identification_personne.code_personne
        JOIN tr_arrondissement ON tr_arrondissement.code_arrondissement= t_adresse_personne.code_arrondissement

        WHERE  MONTH(t_declaration_deces.date_heure_deces) = MONTH(CURDATE())
        AND t_declaration_deces.code_declaration_deces IN ('".$array."')
        GROUP BY tr_cause_deces.lib_cause_deces,tr_arrondissement.lib_arrondissement");

        // ORDER BY tr_arrondissement.lib_arrondissement
        // /  dd($datas);
        return view('deces::statistiques.declarationCausesDeces', compact('datas'));
    }

    public function statParCauseEtat()
    {
        $declarations = Auth::user()->institution()->declarationsDeces();

        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_declaration_deces;
        }
        $array = implode("','", $liste);

        $datas = DB::select("SELECT tr_arrondissement.lib_arrondissement AS arrondissement, tr_cause_deces.lib_cause_deces, COUNT(tr_cause_deces.lib_cause_deces) AS TOTAL
        FROM t_declaration_deces
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
        JOIN t_ddecescause ON t_ddecescause.code_declaration_deces = t_declaration_deces.code_declaration_deces
        JOIN tr_cause_deces ON tr_cause_deces.code_cause_deces = t_ddecescause.code_cause_deces

        JOIN t_adresse_personne ON t_adresse_personne.code_personne = tr_identification_personne.code_personne
        JOIN tr_arrondissement ON tr_arrondissement.code_arrondissement= t_adresse_personne.code_arrondissement

        WHERE  MONTH(t_declaration_deces.date_heure_deces) = MONTH(CURDATE())
        AND t_declaration_deces.code_declaration_deces IN ('".$array."')
        GROUP BY tr_cause_deces.lib_cause_deces,tr_arrondissement.lib_arrondissement");

        view()->share('tester', 'Vincent');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.statistiques.declarationCauseEtat', compact('datas'))->render());

        return $html2pdf->output('statParCauses.pdf');
    }

    public function statParTrancheAge()
    {
        $mesdeclarations = Auth::user()->institution()->declarationsDeces();
        $liste = [];
        foreach ($mesdeclarations as $key) {
            $liste[] = $key->code_declaration_deces;
        }
        $tab = implode("','", $liste);

        $donnees = DB::select("SELECT tr_identification_personne.date_naissance FROM t_declaration_deces
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
        WHERE MONTH(t_declaration_deces.date_heure_deces) = MONTH(CURDATE())
        -- AND t_declaration_deces.code_declaration_deces IN ('".$tab."')
        ");
        $moin18 = [];
        $dix829 = [];
        $trent65 = [];
        $pruss65 = [];
        $age = 0;

        foreach ($donnees as $key) {
            $diff = date_diff(date_create(date('Y-m-d', strtotime($key->date_naissance))), date_create(date('Y-m-d')));
            $age = (int) $diff->y;
            if ($age < 18) {
                $moin18[] = $age;
            }
            if ($age >= 18 && $age < 30) {
                $dix829[] = $age;
            }
            if ($age >= 30 && $age < 66) {
                $trent65[] = $age;
            }
            if ($age >= 66) {
                $pruss65[] = $age;
            }
        }

        $moinsde18 = count($moin18);
        $de18a29 = count($dix829);
        $de30a65 = count($trent65);
        $plusde65 = count($pruss65);

        $total = $moinsde18 + $de18a29 + $de30a65 + $plusde65;

        return view('deces::statistiques.tranchesage', compact('moinsde18', 'de18a29', 'de30a65', 'plusde65', 'total'));
    }

    public function statParTrancheAgeEtat()
    {
        $mesdeclarations = Auth::user()->institution()->declarationsDeces();
        $liste = [];
        foreach ($mesdeclarations as $key) {
            $liste[] = $key->code_declaration_deces;
        }
        $tab = implode("','", $liste);

        $donnees = DB::select("SELECT tr_identification_personne.date_naissance FROM t_declaration_deces
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_deces.code_defunt
        WHERE MONTH(t_declaration_deces.date_heure_deces) = MONTH(CURDATE())
        -- AND t_declaration_deces.code_declaration_deces IN ('".$tab."')
        ");
        $moin18 = [];
        $dix829 = [];
        $trent65 = [];
        $pruss65 = [];
        $age = 0;

        foreach ($donnees as $key) {
            $diff = date_diff(date_create(date('Y-m-d', strtotime($key->date_naissance))), date_create(date('Y-m-d')));
            $age = (int) $diff->y;
            if ($age < 18) {
                $moin18[] = $age;
            }
            if ($age >= 18 && $age < 30) {
                $dix829[] = $age;
            }
            if ($age >= 30 && $age < 66) {
                $trent65[] = $age;
            }
            if ($age >= 66) {
                $pruss65[] = $age;
            }
        }

        $moinsde18 = count($moin18);
        $de18a29 = count($dix829);
        $de30a65 = count($trent65);
        $plusde65 = count($pruss65);

        $tout = (int) $moinsde18 + (int) $de18a29 + (int) $de30a65 + (int) $plusde65;

        view()->share('tester', 'Vincent');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.statistiques.trancheAgeEtat', compact('moinsde18', 'de18a29', 'de30a65', 'plusde65', 'tout'))->render());

        return $html2pdf->output('statParAge.pdf');
    }

    public function mouvement(MouvementService $mouvement, Request $request)
    {
        $dn = DeclarationDeces::find($request->code_declaration_deces);
        if ($dn == null) {
            return response()->json([
                'code' => '183',
                'message' => ['error' => 'Aucun document trouvé pour ce code'],
                'flashAlert' => [
                    'type' => 'error',
                    'title' => 'Erreur',
                    'message' => 'Aucun document trouvé pour ce code',
                ],
            ]);
        }

        // Mapping entre le libellé du type de déclaration et le type d'événement attendu
        $mappingTypeEvenement = [
            'DECLARATION DE DECES' => 'declaration_deces',
            'DECLARATION TARDIVE' => 'declaration_tardive',
            'CERTIFICAT DE CONSTATATION DE DECES' => 'certificat_constatation_deces',
            'CERTIFICAT DE NON INSCRIPTION' => 'certificat_non_inscription',
            "CERTIFICAT DE DESTRUCTION DE L'ACTE" => 'certificat_destruction',
            'FICHE DE TRANSCRIPTION' => 'fiche_transcription',
        ];
        $typeDeclaration = $dn->type_declaration;
        $typeEvenement = $mappingTypeEvenement[$typeDeclaration] ?? 'declaration_deces';

        $statut = 'Envoyée';
        $observation = $request->observation;

        try {
            DB::transaction(function () use ($mouvement, $dn, $observation, $typeEvenement) {

                [$ok, $statutResult] = $mouvement->envoyerDeclaration(Auth::user(), $dn, $typeEvenement, 'Envoyée', $observation);

                if (! $ok) {
                    Log::channel('sifec')->info($statutResult);
                    throw new Exception($statutResult ?: 'Opération a échouée');
                }

                // recuperer le code_institution_destinataire pour la notification de l'envoi de la déclaration
                $codeInstitutionDestinataire = $dn->code_institution_destinataire;
                $institutionDestinataire = Institution::find($codeInstitutionDestinataire);

                // Notification centralisée via le module Notification
                NotificationService::notifierAgentsInstitution(
                    $institutionDestinataire,
                    new DeclarationEnvoyeeCentreNotification(
                        $dn,
                        $institutionDestinataire,
                        'envoyée'
                    )
                );

            });

            return response()->json([
                'code' => '200',
                'message' => "Cette déclaration a été $statut avec succès",
                'flashAlert' => [
                    'type' => 'success',
                    'title' => 'Succès',
                    'message' => "Cette déclaration a été $statut avec succès",
                ],
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur transaction mouvement : '.$e->getMessage());

            return response()->json([
                'code' => '500',
                'message' => "Erreur lors de l'envoi au centre d'état civil : ".$e->getMessage(),
                'flashAlert' => [
                    'type' => 'error',
                    'title' => 'Erreur',
                    'message' => "Erreur lors de l'envoi au centre d'état civil : ".$e->getMessage(),
                ],
            ]);
        }
    }

    public function mouvementEdit(Request $request, $id)
    {
        $mvtd = MouvementDeces::find($id);

        if ($mvtd == '' || $mvtd == null) {
            return response()->json([
                'code' => '183',
                'message' => ['Aucune donnée trouvée'],
            ]);
        }

        DB::beginTransaction();
        try {

            $mvtd->motif_renvoi = $request->motif_renvoi;
            $mvtd->observation = trim($request->observation);
            $mvtd->save();

            // update (lu et approuve) du déclarant
            $dd = DeclarationDeces::find($mvtd->code_declaration_deces);
            $dd->declarant_approuver = 'NON';
            $dd->save();

            DB::commit();

            return response()->json([
                'code' => '200',
                'message' => 'Modification effectuée avec succès',
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'code' => '183',
                'message' => ['error' => $e->getMessage()],
            ]);
        }

    }

    public function mouvementDelete($id)
    {
        $mvtd = MouvementDeces::find($id);

        if ($mvtd == '' || $mvtd == null) {
            return response()->json([
                'code' => '183',
                'message' => ['Aucune donnée trouvée'],
            ]);
        }

        try {
            $mvtd->delete();

            return response()->json([
                'code' => '200',
                'message' => 'Opération effectuée avec succès',
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'code' => '183',
                'message' => ['error' => $e->getMessage()],
            ]);
        }
    }

    public function rechercheDefunt(Request $request)
    {
        $personne = Sifec::rechercherPersonne($request->numero_acte_naissance);

        if ($personne == null) {
            return response()->json([
                'code' => '99',
                'message' => "Aucun numéro d'acte trouvé",
            ]);
        }

        $enfant = $personne->declaration->enfant ?? null;
        $pere = $personne->declaration->pere ?? null;
        $mere = $personne->declaration->mere ?? null;
        $institution = ($personne->institutionUser && $personne->institutionUser->institution) ? $personne->institutionUser->institution : null;

        if (! $enfant) {
            return response()->json([
                'code' => '99',
                'message' => "Informations de l'enfant introuvables",
            ]);
        }

        return response()->json([
            'code' => '200',
            // Informations du défunt
            'nom' => $enfant->nom ?? null,
            'prenom' => $enfant->prenom ?? null,
            'sexe' => $enfant->sexe ?? null,
            'date_naissance' => $enfant->date_naissance ?? null,
            'niveau_instruction' => $enfant->niveau_instruction ?? null,
            'lieu_naissance' => $enfant->code_localite ?? null,
            'dateEmisAN' => $enfant->date_naissance ?? null,
            'cec_naissance' => $institution ? $institution->lib_institution : null,
            'code_cec_naissance' => $institution ? $institution->code_institution : null,
            'code_nationalite' => ($enfant->nationalite ?? null) ? $enfant->nationalite->code_nationalite : null,
            'code_profession' => ($enfant->profession ?? null) ? $enfant->profession->code_profession : null,
            'code_situation_matrimoniale' => ($enfant->situationMatrimoniale ?? null) ? $enfant->situationMatrimoniale->code_situation_matrimoniale : null,
            'code_religion' => ($enfant->religion ?? null) ? $enfant->religion->code_religion : null,

            // Informations du père (détaillées)
            'nom_pere' => $pere ? $pere->nom : null,
            'prenom_pere' => $pere ? $pere->prenom : null,
            'date_naissance_pere' => $pere ? $pere->date_naissance : null,
            'code_localite_pere' => $pere ? $pere->code_localite : null,
            'code_nationalite_pere' => ($pere && $pere->nationalite) ? $pere->nationalite->code_nationalite : null,
            'code_profession_pere' => ($pere && $pere->profession) ? $pere->profession->code_profession : null,
            'niveau_instruction_pere' => $pere ? $pere->niveau_instruction : null,
            'code_type_document_pere' => $pere ? $pere->code_type_document : null,
            'numero_document_pere' => $pere ? $pere->numero_document : null,
            'telephone_pere' => $pere ? $pere->telephone : null,
            'email_pere' => $pere ? $pere->email : null,
            'pere' => $pere ? $pere->nomcomplet() : null,

            // Informations de la mère (détaillées)
            'nom_mere' => $mere ? $mere->nom : null,
            'prenom_mere' => $mere ? $mere->prenom : null,
            'date_naissance_mere' => $mere ? $mere->date_naissance : null,
            'code_localite_mere' => $mere ? $mere->code_localite : null,
            'code_nationalite_mere' => ($mere && $mere->nationalite) ? $mere->nationalite->code_nationalite : null,
            'code_profession_mere' => ($mere && $mere->profession) ? $mere->profession->code_profession : null,
            'niveau_instruction_mere' => $mere ? $mere->niveau_instruction : null,
            'code_type_document_mere' => $mere ? $mere->code_type_document : null,
            'numero_document_mere' => $mere ? $mere->numero_document : null,
            'telephone_mere' => $mere ? $mere->telephone : null,
            'email_mere' => $mere ? $mere->email : null,
            'mere' => $mere ? $mere->nomcomplet() : null,
        ]);
    }

    public function autorisationtransfert()
    {
        $instructions = Sifec::niveauInstructions();
        $declarations = Auth::user()->institution()->declarationsDeces();

        return view('deces::declaration.autorisationtransfert', compact('declarations', 'instructions'));
    }

    public function autorisationtransfertetat($id)
    {
        $ddc = DeclarationDeces::find($id);
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('deces::etats.transfertetatdepouille', compact('ddc'))->render());

        return $html2pdf->output('Autorisation.pdf');
    }

    public function storePiece(Request $request, $code, $type)
    {
        $request->validate([
            'piece' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $declaration = DeclarationDeces::where('code_declaration_deces', $code)->firstOrFail();
        $uploadPath = public_path('app/pieces');
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        try {
            if ($request->hasFile('piece') && $request->file('piece')->isValid()) {
                $file = $request->file('piece');
                $extension = $file->getClientOriginalExtension();
                $imageName = $declaration->code_declaration_deces.'_'.$type.'_'.time().'.'.$extension;

                // Supprimer l'ancienne pièce si elle existe
                $oldPath = $declaration->{'piece_'.$type};
                if ($oldPath && file_exists(public_path($oldPath))) {
                    @unlink(public_path($oldPath));
                }

                $file->move($uploadPath, $imageName);
                $declaration->{'piece_'.$type} = 'app/pieces/'.$imageName;
                $declaration->save();

                return response()->json([
                    'code' => '200',
                    'message' => 'Pièce enregistrée avec succès.',
                    'file_path' => 'app/pieces/'.$imageName,
                ]);
            } else {
                return response()->json([
                    'code' => '400',
                    'message' => 'Erreur lors de l\'upload du fichier.',
                ], 400);
            }
        } catch (Exception $e) {
            Log::error('Erreur upload pièce: '.$e->getMessage());

            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'enregistrement de la pièce: '.$e->getMessage(),
            ], 500);
        }
    }

    public function verificationActe(Request $request, $code)
    {
        if ($request->filled('verif_email')) {
            abort(404);
        }

        $acte = ActeDeces::with(['declaration.defunt', 'declaration.declarant', 'declaration.pere', 'declaration.mere'])
            ->where('code_acte_deces', $code)
            ->first();

        if (! $acte) {
            abort(404);
        }

        return view('deces::verification.acte', compact('acte'));
    }
}
