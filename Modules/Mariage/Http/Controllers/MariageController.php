<?php

namespace Modules\Mariage\Http\Controllers;

use Locale;
use Exception;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Referentiel\Entities\Regime;
use Illuminate\Support\Facades\Validator;
use Modules\Referentiel\Entities\Commune;
use Modules\Referentiel\Entities\District;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Filiation;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\Profession;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\TypeDocument;
use Modules\Referentiel\Entities\OptionMariage;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Mariage\Services\MouvementMariageService;
use Modules\Notification\Services\NotificationService;
use Modules\Mariage\Services\DeclarationMariageService;
use Modules\Referentiel\Entities\SituationMatrimoniale;

class MariageController extends Controller
{
    public function index()
    {
        $dms = Auth::user()->affectationActive()->institution->declarationsMariages();

        return view('mariage::declaration.index', compact("dms"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $situationMatrimoniales = SituationMatrimoniale::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $regimes = Regime::all();
        $optionmariages = OptionMariage::all();
        $LieuCeremonie = ["Centre d'état civil", "Hors centre d'état civil"];
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $lieuNaissances = Localite::where('code_type_localite', 'TPLOC_0003')->get();
        $cecNaissances = Institution::where("code_type_institution", "TPIN_0002")->get();
        $communes = Localite::where('code_type_localite','TPLOC_0003')->Orwhere('code_type_localite','TPLOC_0002')->get();
        $arrondissements = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartiers = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();

        return view('mariage::declaration.create', compact("arrondissements", "quartiers", "situationMatrimoniales", "filiations", "typedocuments", "regimes", "optionmariages", "LieuCeremonie", "professions", "nationalites", "countries", "lieuNaissances", "cecNaissances", "communes"));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // Log::channel("sifec")->info($request->all());
        // dd($request->all());
        // Validation des données
        $validation = $this->validerDonneesMariage($request);
        if ($validation !== true) {
            return $validation;
        }

        try {
            // Enregistrement de la déclaration
            $declarationService = new DeclarationMariageService();
            $resultatEnregistrement = $declarationService->enregistrer($request, Auth::user());

            // Si le service retourne une réponse JSON (erreur), on la retourne directement
            if ($resultatEnregistrement instanceof \Illuminate\Http\JsonResponse) {
                return $resultatEnregistrement;
            }

            $declaration = $resultatEnregistrement;

            // Log du succès pour debug
            Log::channel("sifec")->info("Déclaration de mariage enregistrée avec succès", [
                'code_declaration' => $declaration->code_declaration_mariage ?? 'N/A',
                'user_id' => Auth::id()
            ]);

            return response()->json([
                "code" => "200",
                "message" => "La déclaration de mariage a été enregistrée avec succès",
                "data" => [
                    "code_declaration" => $declaration->code_declaration_mariage ?? null
                ]
            ]);

        } catch (Exception $e) {
            Log::channel("sifec")->error("Erreur dans le contrôleur de déclaration de mariage: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);

            return response()->json([
                "code" => "90",
                "message" => "Une erreur inattendue s'est produite lors de l'enregistrement"
            ], 500);
        }
    }

    /**
     * Valide les données de la déclaration de mariage
     */
    private function validerDonneesMariage($request)
    {
        // Nettoyer et préparer les données
        $this->nettoyerDonneesMariage($request);

        // Log::channel("sifec")->info("Validation des données de mariage", $request->all());
        // Règles de validation de base - toujours requises
        $rules = [
            // Informations de base du mariage
            "type_declaration" => ["required", "string"],
            "lieu_ceremonie_mariage" => ["required", "string", "min:2"],
            "option_mariage" => ["required"],
            "regime_mariage" => ["required"],

            // Informations époux - toujours requises
            "nom_epoux" => ["required", "string", "min:2"],
            "prenom_epoux" => ["required", "string", "min:2"],
            "date_naissance_epoux" => ["required", "date"],
            "lieu_naissance_epoux" => ["required", "string", "min:2"],
            "code_profession_epoux" => ["required"],
            "sit_matrimoniale_epoux" => ["required"],
            "nom_pere_epoux" => ["required", "string", "min:2"],
            "nom_mere_epoux" => ["required", "string", "min:2"],

            // Informations épouse - toujours requises
            "nom_epouse" => ["required", "string", "min:2"],
            "prenom_epouse" => ["required", "string", "min:2"],
            "date_naissance_epouse" => ["required", "date"],
            "lieu_naissance_epouse" => ["required", "string", "min:2"],
            "code_profession_epouse" => ["required"],
            "sit_matrimoniale_epouse" => ["required"],
            "nom_pere_epouse" => ["required", "string", "min:2"],
            "nom_mere_epouse" => ["required", "string", "min:2"],

            // Informations famille
            "chef_famille" => ["required", "string", "min:2"],
            "filiation" => ["required"],

            // Témoins époux - toujours requis
            "nom_t_epoux_1" => ["required", "string", "min:2"],
            "date_naissance_t_epoux_1" => ["required", "date"],
            // "code_profession_t_epoux_1" => ["required"],
            // "code_nationalite_t_epoux_1" => ["required"],
            // "code_localite_t_epoux_1" => ["required"],

            "nom_t_epoux_2" => ["required", "string", "min:2"],
            "date_naissance_t_epoux_2" => ["required", "date"],
            // "code_profession_t_epoux_2" => ["required"],
            // "code_nationalite_t_epoux_2" => ["required"],
            // "code_localite_t_epoux_2" => ["required"],

            // Témoins épouse - toujours requis
            "nom_t_epouse_1" => ["required", "string", "min:2"],
            "date_naissance_t_epouse_1" => ["required", "date"],
            // "code_profession_t_epouse_1" => ["required"],
            // "code_nationalite_t_epouse_1" => ["required"],
            // "code_localite_t_epouse_1" => ["required"],

            "nom_t_epouse_2" => ["required", "string", "min:2"],
            "date_naissance_t_epouse_2" => ["required", "date"],
            // "code_profession_t_epouse_2" => ["required"],
            // "code_nationalite_t_epouse_2" => ["required"]
            // "code_localite_t_epouse_2" => ["required"]
        ];

        // Ajouter les règles conditionnelles selon le type de déclaration
        if ($request->type_declaration === 'DECLARATION DE MARIAGE') {
            $rules["date_ceremonie_mariage"] = ["required", "date"];
        }

        // Si date_prevue_mariage n'est pas fournie, utiliser date_ceremonie_mariage
        if (!$request->has('date_prevue_mariage') || empty($request->date_prevue_mariage)) {
            $request->merge(['date_prevue_mariage' => $request->date_ceremonie_mariage]);
        }

        // Règles conditionnelles pour les actes de naissance
        // Si les personnes sont trouvées via recherche, les actes peuvent être optionnels
        if (!$this->personneTrouveeViaRecherche($request, 'epoux')) {
            $rules["num_acte_naissance_epoux"] = ["required", "string", "min:5"];
            $rules["date_emission_acte_naissance_epoux"] = ["required", "date"];
        }

        if (!$this->personneTrouveeViaRecherche($request, 'epouse')) {
            $rules["num_acte_naissance_epouse"] = ["required", "string", "min:5"];
            $rules["date_emission_acte_naissance_epouse"] = ["required", "date"];
        }

        // Règles conditionnelles pour les situations matrimoniales
        if ($request->sit_matrimoniale_epoux === 'SMAT_0003' || $request->sit_matrimoniale_epoux === 'SMAT_0004') {
            // Si marié, numéro d'acte de mariage requis
            $rules["numero_acte_mariage_epoux"] = ["required", "string", "min:5"];
        }

        if ($request->sit_matrimoniale_epouse === 'SMAT_0003' || $request->sit_matrimoniale_epouse === 'SMAT_0004') {
            // Si mariée, numéro d'acte de mariage requis
            $rules["numero_acte_mariage_epouse"] = ["required", "string", "min:5"];
        }

        // Règles conditionnelles pour les pré-mariages
        if ($request->sit_matrimoniale_epoux === 'SMAT_0001') {
            $rules["date_pre_mariage_epoux"] = ["required", "date"];
            $rules["parent_paternel_epoux"] = ["required", "string", "min:2"];
            $rules["parent_maternel_epoux"] = ["required", "string", "min:2"];
        }

        if ($request->sit_matrimoniale_epouse === 'SMAT_0001') {
            $rules["date_pre_mariage_epouse"] = ["required", "date"];
            $rules["parent_paternel_epouse"] = ["required", "string", "min:2"];
            $rules["parent_maternel_epouse"] = ["required", "string", "min:2"];
        }

        // Les localités des témoins sont maintenant toujours requises dans les règles de base

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::channel("sifec")->error("Erreurs de validation", [
                'errors' => $validator->errors()->toArray(),
                'data' => $request->all()
            ]);

            // Créer un message d'erreur plus détaillé
            $errorMessage = $this->creerMessageErreurValidation($validator->errors());

            return response()->json([
                "code" => "150",
                "message" => "Données de validation manquantes ou invalides",
                "errors" => $validator->errors()->toArray(),
                "details" => $errorMessage,
                "suggestion" => "Veuillez vérifier que tous les champs obligatoires sont remplis correctement"
            ], 400);
        }

        // Validation des dates d'édition d'acte de naissance
        $dateValidation = $this->validerDatesActeNaissance($request);
        if ($dateValidation !== true) {
            return $dateValidation;
        }

        return true;
    }

    /**
     * Nettoie et prépare les données de mariage
     */
    private function nettoyerDonneesMariage($request)
    {
        // Définir des valeurs par défaut pour les champs optionnels
        $defaults = [
            'type_declaration' => 'DECLARATION DE MARIAGE',
            'type_mariage' => 'NORMAL',
            'nbre_enfant' => 0,
            'montant_dot' => 50000,
            'examens_prenuptiaux' => 1,
            'persister_marier_epoux' => 0,
            'persister_marier_epouse' => 0,
            'avis_epouse' => 0,
            'cec_approuver' => 'NON',
            'tribunal_approuver' => 'NON'
        ];

        // Appliquer les valeurs par défaut si les champs sont vides
        foreach ($defaults as $field => $defaultValue) {
            if (!$request->has($field) || $request->$field === '' || $request->$field === null) {
                $request->merge([$field => $defaultValue]);
            }
        }

        // Nettoyer les chaînes de caractères
        $stringFields = [
            'nom_epoux', 'prenom_epoux', 'lieu_naissance_epoux', 'nom_pere_epoux', 'nom_mere_epoux',
            'nom_epouse', 'prenom_epouse', 'lieu_naissance_epouse', 'nom_pere_epouse', 'nom_mere_epouse',
            'chef_famille', 'lieu_ceremonie_mariage', 'adresse_celebration_mariage',
            'nom_t_epoux_1', 'nom_t_epoux_2', 'nom_t_epouse_1', 'nom_t_epouse_2'
        ];

        foreach ($stringFields as $field) {
            if ($request->has($field) && $request->$field) {
                $request->merge([$field => trim($request->$field)]);
            }
        }

        // Gérer les champs conditionnels
        $this->gererChampsConditionnels($request);
    }

    /**
     * Gère les champs conditionnels selon les situations
     */
    private function gererChampsConditionnels($request)
    {
        // Si pas de situation matrimoniale spécifiée, utiliser célibataire par défaut
        if (!$request->sit_matrimoniale_epoux) {
            $request->merge(['sit_matrimoniale_epoux' => 'SMAT_0001']);
        }

        if (!$request->sit_matrimoniale_epouse) {
            $request->merge(['sit_matrimoniale_epouse' => 'SMAT_0001']);
        }

        // Si pas d'option de mariage, utiliser monogamie par défaut
        if (!$request->option_mariage) {
            $request->merge(['option_mariage' => 'OMRG_0002']);
        }

        // Si pas de régime, utiliser communauté réduite aux acquêts par défaut
        if (!$request->regime_mariage) {
            $request->merge(['regime_mariage' => 'RGIM_0002']);
        }

        // Gérer les champs de pré-mariage
        if ($request->sit_matrimoniale_epoux === 'SMAT_0001') {
            // Célibataire - champs pré-mariage requis
            if (!$request->date_pre_mariage_epoux) {
                $request->merge(['date_pre_mariage_epoux' => $request->date_prevue_mariage]);
            }
        }

        if ($request->sit_matrimoniale_epouse === 'SMAT_0001') {
            // Célibataire - champs pré-mariage requis
            if (!$request->date_pre_mariage_epouse) {
                $request->merge(['date_pre_mariage_epouse' => $request->date_prevue_mariage]);
            }
        }

        // Gérer les champs d'actes de mariage
        if (in_array($request->sit_matrimoniale_epoux, ['SMAT_0003', 'SMAT_0004'])) {
            // Marié - acte de mariage requis
            if (!$request->numero_acte_mariage_epoux) {
                $request->merge(['numero_acte_mariage_epoux' => '']);
            }
        }

        if (in_array($request->sit_matrimoniale_epouse, ['SMAT_0003', 'SMAT_0004'])) {
            // Mariée - acte de mariage requis
            if (!$request->numero_acte_mariage_epouse) {
                $request->merge(['numero_acte_mariage_epouse' => '']);
            }
        }
    }

    /**
     * Crée un message d'erreur détaillé pour la validation
     */
    private function creerMessageErreurValidation($errors)
    {
        $messages = [];

        foreach ($errors->toArray() as $field => $fieldErrors) {
            $fieldName = $this->getFieldDisplayName($field);
            $messages[] = "• {$fieldName}: " . implode(', ', $fieldErrors);
        }

        return "Champs manquants ou invalides:\n" . implode("\n", $messages);
    }

    /**
     * Retourne le nom d'affichage d'un champ
     */
    private function getFieldDisplayName($field)
    {
        $fieldNames = [
            'nom_epoux' => 'Nom de l\'époux',
            'prenom_epoux' => 'Prénom de l\'époux',
            'date_naissance_epoux' => 'Date de naissance de l\'époux',
            'lieu_naissance_epoux' => 'Lieu de naissance de l\'époux',
            'code_profession_epoux' => 'Profession de l\'époux',
            'sit_matrimoniale_epoux' => 'Situation matrimoniale de l\'époux',
            'nom_pere_epoux' => 'Nom du père de l\'époux',
            'nom_mere_epoux' => 'Nom de la mère de l\'époux',
            'nom_epouse' => 'Nom de l\'épouse',
            'prenom_epouse' => 'Prénom de l\'épouse',
            'date_naissance_epouse' => 'Date de naissance de l\'épouse',
            'lieu_naissance_epouse' => 'Lieu de naissance de l\'épouse',
            'code_profession_epouse' => 'Profession de l\'épouse',
            'sit_matrimoniale_epouse' => 'Situation matrimoniale de l\'épouse',
            'nom_pere_epouse' => 'Nom du père de l\'épouse',
            'nom_mere_epouse' => 'Nom de la mère de l\'épouse',
            'chef_famille' => 'Chef de famille',
            'filiation' => 'Filiation',
            'option_mariage' => 'Option de mariage',
            'regime_mariage' => 'Régime de mariage',
            'date_prevue_mariage' => 'Date prévue du mariage',
            'lieu_ceremonie_mariage' => 'Lieu de la cérémonie',
            'num_acte_naissance_epoux' => 'Numéro d\'acte de naissance de l\'époux',
            'num_acte_naissance_epouse' => 'Numéro d\'acte de naissance de l\'épouse',
            'date_emission_acte_naissance_epoux' => 'Date d\'émission de l\'acte de naissance de l\'époux',
            'date_emission_acte_naissance_epouse' => 'Date d\'émission de l\'acte de naissance de l\'épouse',
            'numero_acte_mariage_epoux' => 'Numéro d\'acte de mariage de l\'époux',
            'numero_acte_mariage_epouse' => 'Numéro d\'acte de mariage de l\'épouse',
            'date_pre_mariage_epoux' => 'Date de pré-mariage de l\'époux',
            'date_pre_mariage_epouse' => 'Date de pré-mariage de l\'épouse',
            'parent_paternel_epoux' => 'Parent paternel de l\'époux',
            'parent_maternel_epoux' => 'Parent maternel de l\'époux',
            'parent_paternel_epouse' => 'Parent paternel de l\'épouse',
            'parent_maternel_epouse' => 'Parent maternel de l\'épouse',
            'nom_t_epoux_1' => 'Nom du témoin 1 de l\'époux',
            'nom_t_epoux_2' => 'Nom du témoin 2 de l\'époux',
            'nom_t_epouse_1' => 'Nom du témoin 1 de l\'épouse',
            'nom_t_epouse_2' => 'Nom du témoin 2 de l\'épouse',
            'date_naissance_t_epoux_1' => 'Date de naissance du témoin 1 de l\'époux',
            'date_naissance_t_epoux_2' => 'Date de naissance du témoin 2 de l\'époux',
            'date_naissance_t_epouse_1' => 'Date de naissance du témoin 1 de l\'épouse',
            'date_naissance_t_epouse_2' => 'Date de naissance du témoin 2 de l\'épouse',
            'code_localite_t_epoux_1' => 'Localité du témoin 1 de l\'époux',
            'code_localite_t_epoux_2' => 'Localité du témoin 2 de l\'époux',
            'code_localite_t_epouse_1' => 'Localité du témoin 1 de l\'épouse',
            'code_localite_t_epouse_2' => 'Localité du témoin 2 de l\'épouse',
            'code_profession_t_epoux_1' => 'Profession du témoin 1 de l\'époux',
            'code_profession_t_epoux_2' => 'Profession du témoin 2 de l\'époux',
            'code_profession_t_epouse_1' => 'Profession du témoin 1 de l\'épouse',
            'code_profession_t_epouse_2' => 'Profession du témoin 2 de l\'épouse',
            'code_nationalite_t_epoux_1' => 'Nationalité du témoin 1 de l\'époux',
            'code_nationalite_t_epoux_2' => 'Nationalité du témoin 2 de l\'époux',
            'code_nationalite_t_epouse_1' => 'Nationalité du témoin 1 de l\'épouse',
            'code_nationalite_t_epouse_2' => 'Nationalité du témoin 2 de l\'épouse',
            'date_ceremonie_mariage' => 'Date de la cérémonie de mariage'
        ];

        return $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field));
    }

    /**
     * Vérifie si une personne a été trouvée via la recherche en base de données
     */
    private function personneTrouveeViaRecherche($request, $typePersonne)
    {
        // Vérifier plusieurs indicateurs possibles
        $indicators = [
            "personne_trouvee_{$typePersonne}",
            "code_{$typePersonne}",
            "{$typePersonne}_trouve",
            "recherche_{$typePersonne}_effectuee"
        ];

        foreach ($indicators as $indicator) {
            if ($request->has($indicator) && $request->$indicator) {
                return true;
            }
        }

        // Vérifier si un code personne existe (indique une recherche effectuée)
        if ($request->has("code_{$typePersonne}") && !empty($request->{"code_{$typePersonne}"})) {
            return true;
        }

        return false;
    }

    /**
     * Valider les dates d'édition d'acte de naissance
     */
    private function validerDatesActeNaissance($request)
    {
        // Validation pour l'époux
        if ($request->date_naissance_epoux && $request->date_emission_acte_naissance_epoux) {
            $dateNaissanceEpoux = \Carbon\Carbon::parse($request->date_naissance_epoux);
            $dateEmissionActeEpoux = \Carbon\Carbon::parse($request->date_emission_acte_naissance_epoux);

            if ($dateEmissionActeEpoux->lt($dateNaissanceEpoux)) {
                return response()->json([
                    "code" => "151",
                    "message" => "La date d'édition de l'acte de naissance de l'époux ne peut pas être antérieure à sa date de naissance"
                ], 400);
            }
        }

        // Validation pour l'épouse
        if ($request->date_naissance_epouse && $request->date_emission_acte_naissance_epouse) {
            $dateNaissanceEpouse = \Carbon\Carbon::parse($request->date_naissance_epouse);
            $dateEmissionActeEpouse = \Carbon\Carbon::parse($request->date_emission_acte_naissance_epouse);

            if ($dateEmissionActeEpouse->lt($dateNaissanceEpouse)) {
                return response()->json([
                    "code" => "152",
                    "message" => "La date d'édition de l'acte de naissance de l'épouse ne peut pas être antérieure à sa date de naissance"
                ], 400);
            }
        }

        return true;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $declaration = DeclarationMariage::with(['epoux', 'epouse', 'institution', 'mouvements'])
            ->where('code_declaration_mariage', $id)
            ->firstOrFail();

        return view('mariage::declaration.show', compact('declaration'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $declaration = DeclarationMariage::with(['epoux', 'epouse', 'temoinHommeEpoux', 'temoinFemmeEpoux', 'temoinHommeEpouse', 'temoinFemmeEpouse'])
            ->where('code_declaration_mariage', $id)
            ->firstOrFail();

        // Ajouter toutes les variables nécessaires pour le formulaire
        $situationMatrimoniales = SituationMatrimoniale::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $regimes = Regime::all();
        $optionmariages = OptionMariage::all();
        $LieuCeremonie = Localite::where('code_type_localite', 'TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $lieuNaissances = Localite::where('code_type_localite', 'TPLOC_0003')->get();
        $cecNaissances = Institution::where("code_type_institution", "TPIN_0002")->get();
        $communes = Localite::where('code_type_localite','TPLOC_0003')->Orwhere('code_type_localite','TPLOC_0002')->get();
        $arrondissements = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartiers = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();

        return view('mariage::declaration.edit', compact('declaration', 'arrondissements', 'quartiers', 'situationMatrimoniales', 'filiations', 'typedocuments', 'regimes', 'optionmariages', 'LieuCeremonie', 'professions', 'nationalites', 'countries', 'lieuNaissances', 'cecNaissances', 'communes'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        try {
            $declaration = DeclarationMariage::where('code_declaration_mariage', $id)->firstOrFail();

            // Vérifier si le dossier a été envoyé au tribunal
            $dejaEnvoyeAuTribunal = $declaration->mouvements->contains('code_mouvement', 'MOUV_2008');
            if ($dejaEnvoyeAuTribunal) {
                return response()->json([
                    'code' => '403',
                    'message' => 'Ce dossier a déjà été envoyé au tribunal. Aucune modification n\'est autorisée.'
                ], 403);
            }

            // Validation des données
            $validator = Validator::make($request->all(), [
                'type_declaration' => 'required|string',
                'nom_epoux' => 'required|string|min:2',
                'prenom_epoux' => 'required|string|min:2',
                'date_naissance_epoux' => 'required|date',
                'lieu_naissance_epoux' => 'required|string',
                'nom_epouse' => 'required|string|min:2',
                'prenom_epouse' => 'required|string|min:2',
                'date_naissance_epouse' => 'required|date',
                'lieu_naissance_epouse' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'code' => '400',
                    'message' => 'Données de validation manquantes ou invalides',
                    'errors' => $validator->errors()
                ], 400);
            }

            DB::beginTransaction();

            // Mise à jour des informations de base
            $declaration->update([
                'type_declaration' => $request->type_declaration,
                'date_prevue_mariage' => $request->date_prevue_mariage,
                'lieu_ceremonie_mariage' => $request->lieu_ceremonie_mariage,
            ]);

            // Mise à jour des informations de l'époux
            if ($declaration->epoux) {
                $declaration->epoux->update([
                    'nom' => $request->nom_epoux,
                    'prenom' => $request->prenom_epoux,
                    'date_naissance' => $request->date_naissance_epoux,
                    'lieu_naissance' => $request->lieu_naissance_epoux,
                    'profession' => $request->profession_epoux,
                    'domicile' => $request->domicile_epoux,
                ]);
            }

            // Mise à jour des informations de l'épouse
            if ($declaration->epouse) {
                $declaration->epouse->update([
                    'nom' => $request->nom_epouse,
                    'prenom' => $request->prenom_epouse,
                    'date_naissance' => $request->date_naissance_epouse,
                    'lieu_naissance' => $request->lieu_naissance_epouse,
                    'profession' => $request->profession_epouse,
                    'domicile' => $request->domicile_epouse,
                ]);
            }

            // Mise à jour des témoins si fournis
            if ($request->nom_temoin_homme_epoux && $declaration->temoinHommeEpoux) {
                $declaration->temoinHommeEpoux->update([
                    'nom' => $request->nom_temoin_homme_epoux,
                    'prenom' => $request->prenom_temoin_homme_epoux,
                    'profession' => $request->profession_temoin_homme_epoux,
                ]);
            }

            if ($request->nom_temoin_femme_epoux && $declaration->temoinFemmeEpoux) {
                $declaration->temoinFemmeEpoux->update([
                    'nom' => $request->nom_temoin_femme_epoux,
                    'prenom' => $request->prenom_temoin_femme_epoux,
                    'profession' => $request->profession_temoin_femme_epoux,
                ]);
            }

            if ($request->nom_temoin_homme_epouse && $declaration->temoinHommeEpouse) {
                $declaration->temoinHommeEpouse->update([
                    'nom' => $request->nom_temoin_homme_epouse,
                    'prenom' => $request->prenom_temoin_homme_epouse,
                    'profession' => $request->profession_temoin_homme_epouse,
                ]);
            }

            if ($request->nom_temoin_femme_epouse && $declaration->temoinFemmeEpouse) {
                $declaration->temoinFemmeEpouse->update([
                    'nom' => $request->nom_temoin_femme_epouse,
                    'prenom' => $request->prenom_temoin_femme_epouse,
                    'profession' => $request->profession_temoin_femme_epouse,
                ]);
            }

            // Gestion des pièces jointes
            if ($request->hasFile('piece_epoux')) {
                $file = $request->file('piece_epoux');
                $filename = 'piece_epoux_' . $declaration->code_declaration_mariage . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/mariage'), $filename);
                $declaration->update(['piece_epoux' => 'uploads/mariage/' . $filename]);
            }

            if ($request->hasFile('piece_epouse')) {
                $file = $request->file('piece_epouse');
                $filename = 'piece_epouse_' . $declaration->code_declaration_mariage . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/mariage'), $filename);
                $declaration->update(['piece_epouse' => 'uploads/mariage/' . $filename]);
            }

            if ($request->hasFile('piece_temoins')) {
                $file = $request->file('piece_temoins');
                $filename = 'piece_temoins_' . $declaration->code_declaration_mariage . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/mariage'), $filename);
                $declaration->update(['piece_temoins' => 'uploads/mariage/' . $filename]);
            }

            // Gestion des pièces des témoins individuels
            if ($request->hasFile('piece_temoin_homme_epoux')) {
                $file = $request->file('piece_temoin_homme_epoux');
                $filename = 'piece_temoin_homme_epoux_' . $declaration->code_declaration_mariage . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/mariage'), $filename);
                $declaration->update(['piece_temoin_homme_epoux' => 'uploads/mariage/' . $filename]);
            }

            if ($request->hasFile('piece_temoin_femme_epoux')) {
                $file = $request->file('piece_temoin_femme_epoux');
                $filename = 'piece_temoin_femme_epoux_' . $declaration->code_declaration_mariage . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/mariage'), $filename);
                $declaration->update(['piece_temoin_femme_epoux' => 'uploads/mariage/' . $filename]);
            }

            if ($request->hasFile('piece_temoin_homme_epouse')) {
                $file = $request->file('piece_temoin_homme_epouse');
                $filename = 'piece_temoin_homme_epouse_' . $declaration->code_declaration_mariage . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/mariage'), $filename);
                $declaration->update(['piece_temoin_homme_epouse' => 'uploads/mariage/' . $filename]);
            }

            if ($request->hasFile('piece_temoin_femme_epouse')) {
                $file = $request->file('piece_temoin_femme_epouse');
                $filename = 'piece_temoin_femme_epouse_' . $declaration->code_declaration_mariage . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/mariage'), $filename);
                $declaration->update(['piece_temoin_femme_epouse' => 'uploads/mariage/' . $filename]);
            }

            DB::commit();

            return response()->json([
                'code' => '200',
                'message' => 'Déclaration de mariage mise à jour avec succès'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel("sifec")->error("Erreur lors de la mise à jour de la déclaration de mariage: " . $e->getMessage());

            return response()->json([
                'code' => '500',
                'message' => 'Une erreur est survenue lors de la mise à jour'
            ], 500);
        }
    }

    /**
     * Gérer les mouvements de déclaration de mariage
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mouvement(Request $request)
    {
        $dm = DeclarationMariage::find($request->code_declaration_mariage);
        if($dm == null){
            return response()->json([
                "code"=>"180",
                "message"=>["error" => "Aucun document trouvé pour ce code"]
            ]);
        }
        $statut = 'Envoyée';
        $observation = $request->observation;

        try {
            DB::transaction(function () use ($dm, $observation, $request) {
                $mouvementService = new MouvementMariageService();
                [$ok, $statutResult] = $mouvementService->envoyerDeclaration($dm, 'dispense_mariage', $dm->institution->institutionParent, Auth::user(), 'Envoyée', $observation);

                if(!$ok){
                    Log::channel('sifec')->info($statutResult);
                    throw new Exception($statutResult ?: "Opération a échouée");
                }

                // Récupérer le code_institution_destinataire pour la notification de l'envoi de la déclaration
                $codeInstitutionDestinataire = $dm->code_institution_destinataire;
                $institutionDestinataire = Institution::find($codeInstitutionDestinataire);

                // Notification centralisée via le module Notification
                if($institutionDestinataire) {
                    \Modules\Notification\Services\NotificationService::notifierAgentsInstitution(
                        $institutionDestinataire,
                        new \Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification(
                            $dm,
                            $institutionDestinataire,
                            'envoyée'
                        )
                    );
                }
            });

            return response()->json([
                "code"=>"200",
                "message"=>"Cette déclaration a été $statut avec succès"
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur transaction mouvement : ' . $e->getMessage());
            return response()->json([
                "code"=>"500",
                "message"=>"Erreur lors de l'envoi au centre d'état civil : " . $e->getMessage()
            ]);
        }
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

    public function recherchePersonne(Request $request)
    {
        $niupp = $request->numero_acte_naissance;
        $type_personne = $request->type_personne; // 'epoux' ou 'epouse'

        // Recherche de l'identité de base
        $identite = SifecFacade::rechercheIdentite($niupp);
        $identiteData = $identite->getData(true);

        if ($identiteData['code'] !== '200') {
            return $identite; // Retourner l'erreur de base
        }

        // Vérifier l'âge minimum requis
        $verificationAge = $this->verifierAgeMinimum($identiteData['date_naissance'] ?? null, $type_personne);
        if (!$verificationAge['valide']) {
            return response()->json([
                'code' => '400',
                'message' => $verificationAge['message'],
                'age_actuel' => $verificationAge['age_actuel'] ?? null,
                'age_minimum' => $verificationAge['age_minimum'] ?? null
            ], 400);
        }

        // Vérifier d'abord si la personne est décédée
        $statutPersonne = $this->verifierStatutPersonne($niupp);

        // Si la personne est décédée, retourner une erreur
        if ($statutPersonne['statut'] === 'decede') {
            $identiteData['situation_matrimoniale'] = [
                'statut' => 'decede',
                'message' => 'Cette personne est décédée. Un mariage posthume n\'est pas autorisé.',
                'actes' => [],
                'conjoint' => null
            ];
            return response()->json($identiteData);
        }

        // Vérifier la situation matrimoniale et les actes de mariage
        $situationMatrimoniale = $this->verifierSituationMatrimoniale($niupp, $type_personne);

        // Fusionner les données
        $identiteData['situation_matrimoniale'] = $situationMatrimoniale;

        return response()->json($identiteData);
    }

    /**
     * Vérifier l'âge minimum requis pour le mariage
     * Époux : >= 21 ans
     * Épouse : >= 18 ans
     */
    private function verifierAgeMinimum($dateNaissance, $type_personne)
    {
        if (!$dateNaissance) {
            return [
                'valide' => false,
                'message' => 'La date de naissance est requise pour vérifier l\'âge.',
                'age_actuel' => null,
                'age_minimum' => null
            ];
        }

        try {
            // Log pour déboguer
            Log::info('Vérification âge - Date de naissance reçue: ' . $dateNaissance);

            // Parser la date de naissance
            $dateNaissanceCarbon = \Carbon\Carbon::parse($dateNaissance);

            // Vérifier que la date est valide (pas dans le futur)
            if ($dateNaissanceCarbon->isFuture()) {
                return [
                    'valide' => false,
                    'message' => 'La date de naissance ne peut pas être dans le futur.',
                    'age_actuel' => 0,
                    'age_minimum' => $type_personne === 'epoux' ? 21 : 18
                ];
            }

            // Calculer l'âge
            $ageActuel = $dateNaissanceCarbon->diffInYears(\Carbon\Carbon::now());

            // Log pour déboguer
            Log::info('Vérification âge - Âge calculé: ' . $ageActuel);

            // Définir l'âge minimum selon le type de personne
            $ageMinimum = $type_personne === 'epoux' ? 21 : 18;
            $typePersonneLibelle = $type_personne === 'epoux' ? 'époux' : 'épouse';

            if ($ageActuel < $ageMinimum) {
                return [
                    'valide' => false,
                    'message' => "L'âge minimum requis pour un(e) {$typePersonneLibelle} est de {$ageMinimum} ans. L'âge actuel est de {$ageActuel} an(s).",
                    'age_actuel' => $ageActuel,
                    'age_minimum' => $ageMinimum
                ];
            }

            return [
                'valide' => true,
                'message' => "L'âge est conforme ({$ageActuel} ans, minimum requis : {$ageMinimum} ans).",
                'age_actuel' => $ageActuel,
                'age_minimum' => $ageMinimum
            ];
        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification de l\'âge: ' . $e->getMessage());
            return [
                'valide' => false,
                'message' => 'Erreur lors de la vérification de l\'âge. Veuillez vérifier la date de naissance.',
                'age_actuel' => null,
                'age_minimum' => null
            ];
        }
    }

    /**
     * Vérifier le statut d'une personne (vivante ou décédée)
     */
    private function verifierStatutPersonne($niupp)
    {
        try {
            // Rechercher l'acte de naissance
            $acteNaissance = \App\Sifec\Sifec::rechercherPersonne($niupp);

            if (!$acteNaissance) {
                return [
                    'statut' => 'inconnu',
                    'message' => 'Personne non trouvée'
                ];
            }

            // Récupérer le code de la personne
            $codePersonne = $acteNaissance->declaration->enfant->code_personne ?? null;

            if (!$codePersonne) {
                return [
                    'statut' => 'inconnu',
                    'message' => 'Code personne non trouvé'
                ];
            }

            // Vérifier le statut de la personne dans la table Personne
            $personne = \Modules\Referentiel\Entities\Personne::where('code_personne', $codePersonne)->first();

            if (!$personne) {
                return [
                    'statut' => 'inconnu',
                    'message' => 'Personne non trouvée dans le référentiel'
                ];
            }

            // Vérifier si la personne est décédée
            if ($personne->statut_personne === 'DECEDE') {
                return [
                    'statut' => 'decede',
                    'message' => 'Cette personne est décédée',
                    'date_deces' => $this->getDateDeces($codePersonne)
                ];
            }

            return [
                'statut' => 'vivant',
                'message' => 'Personne vivante'
            ];

        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification du statut de la personne: ' . $e->getMessage());
            return [
                'statut' => 'erreur',
                'message' => 'Erreur lors de la vérification du statut'
            ];
        }
    }

    /**
     * Récupérer la date de décès d'une personne
     */
    private function getDateDeces($codePersonne)
    {
        try {
            // Rechercher l'acte de décès de cette personne
            $acteDeces = \Modules\Deces\Entities\ActeDeces::whereHas('declaration', function($query) use ($codePersonne) {
                $query->where('code_defunt', $codePersonne);
            })->first();

            if ($acteDeces) {
                return $acteDeces->declaration->date_heure_deces ?? null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la date de décès: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Vérifier la situation matrimoniale d'une personne
     * Vérifie directement dans t_declaration_mariage si la personne est déjà mariée
     */
    private function verifierSituationMatrimoniale($niupp, $type_personne)
    {
        try {
            // Rechercher l'acte de naissance pour obtenir le code_personne
            $acteNaissance = \App\Sifec\Sifec::rechercherPersonne($niupp);

            if (!$acteNaissance || !$acteNaissance->declaration || !$acteNaissance->declaration->enfant) {
                // Si on ne trouve pas la personne, on considère qu'elle est célibataire
                return [
                    'statut' => 'celibataire',
                    'message' => 'Aucune déclaration de mariage trouvée. La personne est célibataire.',
                    'actes' => [],
                    'conjoint' => null
                ];
            }

            // Récupérer le code de la personne depuis l'acte de naissance
            $codePersonne = $acteNaissance->declaration->enfant->code_personne ?? null;

            if (!$codePersonne) {
                // Pas de code personne, considérer comme célibataire
                return [
                    'statut' => 'celibataire',
                    'message' => 'Aucune déclaration de mariage trouvée. La personne est célibataire.',
                    'actes' => [],
                    'conjoint' => null
                ];
            }

            // Rechercher directement dans t_declaration_mariage si la personne est déjà mariée
            $declarationsMariage = \Modules\Mariage\Entities\DeclarationMariage::with(['optionMariage', 'epoux', 'epouse'])
                ->where(function($query) use ($codePersonne) {
                    $query->where('code_epoux', $codePersonne)
                          ->orWhere('code_epouse', $codePersonne);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Si aucune déclaration trouvée, la personne est célibataire
            if ($declarationsMariage->isEmpty()) {
                return [
                    'statut' => 'celibataire',
                    'message' => 'Aucune déclaration de mariage trouvée. La personne est célibataire.',
                    'actes' => [],
                    'conjoint' => null
                ];
            }

            // Prendre la déclaration la plus récente
            $derniereDeclaration = $declarationsMariage->first();

            // Déterminer le nom du conjoint
            $conjoint = null;
            if ($type_personne === 'epoux' && $derniereDeclaration->epouse) {
                $conjoint = $derniereDeclaration->epouse->nom . ' ' . $derniereDeclaration->epouse->prenom;
            } else if ($type_personne === 'epouse' && $derniereDeclaration->epoux) {
                $conjoint = $derniereDeclaration->epoux->nom . ' ' . $derniereDeclaration->epoux->prenom;
            }

            // Vérifier l'option de mariage
            $optionMariage = $derniereDeclaration->optionMariage;

            // Si pas d'option de mariage, considérer comme célibataire (données incomplètes)
            if (!$optionMariage) {
                Log::warning('Déclaration de mariage sans option de mariage: ' . $derniereDeclaration->code_declaration_mariage);
                return [
                    'statut' => 'celibataire',
                    'message' => 'Aucune déclaration de mariage valide trouvée. La personne est considérée comme célibataire.',
                    'actes' => [],
                    'conjoint' => null
                ];
            }

            // Vérifier le type de mariage
            if ($optionMariage->code_option_mariage == "OMRG_0002") {
                // Monogamie - empêcher un nouveau mariage
                return [
                    'statut' => 'marie_monogamie',
                    'message' => 'La personne est déjà mariée en monogamie. Un nouveau mariage nécessite un divorce ou un décès de l\'époux/épouse actuel(le).',
                    'conjoint' => $conjoint,
                    'declaration_mariage' => [
                        'code_declaration' => $derniereDeclaration->code_declaration_mariage,
                        'date_prevue_mariage' => $derniereDeclaration->date_prevue_mariage,
                        'option_mariage' => $optionMariage->lib_option_mariage,
                        'code_option_mariage' => $optionMariage->code_option_mariage,
                    ],
                    'actes' => $declarationsMariage->map(function($declaration) {
                        return [
                            'code_declaration' => $declaration->code_declaration_mariage,
                            'date_prevue_mariage' => $declaration->date_prevue_mariage,
                            'option_mariage' => $declaration->optionMariage ? $declaration->optionMariage->lib_option_mariage : 'Non spécifiée',
                        ];
                    })
                ];
            } else {
                // Polygamie - permettre un nouveau mariage
                return [
                    'statut' => 'polygame',
                    'message' => 'La personne est déjà mariée en polygamie. Un nouveau mariage est possible.',
                    'conjoint' => $conjoint,
                    'declaration_mariage' => [
                        'code_declaration' => $derniereDeclaration->code_declaration_mariage,
                        'date_prevue_mariage' => $derniereDeclaration->date_prevue_mariage,
                        'option_mariage' => $optionMariage->lib_option_mariage,
                        'code_option_mariage' => $optionMariage->code_option_mariage,
                    ],
                    'actes' => $declarationsMariage->map(function($declaration) {
                        return [
                            'code_declaration' => $declaration->code_declaration_mariage,
                            'date_prevue_mariage' => $declaration->date_prevue_mariage,
                            'option_mariage' => $declaration->optionMariage ? $declaration->optionMariage->lib_option_mariage : 'Non spécifiée',
                        ];
                    })
                ];
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la vérification de la situation matrimoniale: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'niupp' => $niupp,
                'type_personne' => $type_personne
            ]);
            // En cas d'erreur, considérer comme célibataire pour permettre le processus
            return [
                'statut' => 'celibataire',
                'message' => 'Impossible de vérifier la situation matrimoniale. La personne est considérée comme célibataire.',
                'actes' => [],
                'conjoint' => null
            ];
        }
    }

    /**
     * Rechercher les actes de mariage d'une personne par son numéro d'acte de naissance
     */
    private function rechercherActesMariageParPersonne($niupp)
    {
        try {
            // Rechercher l'acte de naissance par son numéro d'acte de naissance
            $acteNaissance = \App\Sifec\Sifec::rechercherPersonne($niupp);

            if (!$acteNaissance) {
                return collect([]);
            }

            // Vérifier que la déclaration existe
            if (!$acteNaissance->declaration) {
                Log::warning('Acte de naissance sans déclaration: ' . $niupp);
                return collect([]);
            }

            // Récupérer le code de la personne depuis l'acte de naissance
            $codePersonne = $acteNaissance->declaration->enfant->code_personne ?? null;

            if (!$codePersonne) {
                Log::warning('Code personne introuvable pour l\'acte de naissance: ' . $niupp);
                return collect([]);
            }

            // Rechercher les actes de mariage où cette personne est époux ou épouse
            // Charger toutes les relations nécessaires
            $actesMariage = \Modules\Mariage\Entities\ActeMariage::with([
                'declaration.optionMariage',
                'declaration.epoux',
                'declaration.epouse'
            ])
            ->whereHas('declaration', function($query) use ($codePersonne) {
                $query->where('code_epoux', $codePersonne)
                      ->orWhere('code_epouse', $codePersonne);
            })
            ->orderBy('date_emission', 'desc')
            ->get();

            return $actesMariage;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche des actes de mariage: ' . $e->getMessage(), [
                'niupp' => $niupp,
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Déterminer la situation d'une personne mariée en monogamie
     */
    private function determinerSituationMonogamie($acte)
    {
        // Vérifier si l'acte est annulé ou si il y a un divorce/décès
        // Cette logique dépend de votre structure de données
        // Pour l'instant, on considère que si l'acte existe, la personne est mariée

        return [
            'statut' => 'marie_monogamie',
            'message' => 'La personne est déjà mariée en monogamie. Un nouveau mariage nécessite un divorce ou un décès de l\'époux/épouse.'
        ];
    }

    /**
     * Déterminer le nom du conjoint d'une personne
     */
    private function determinerConjoint($acte, $type_personne)
    {
        try {
            $declaration = $acte->declaration ?? null;

            if (!$declaration) {
                return null;
            }

            if ($type_personne === 'epoux') {
                // Si on recherche l'époux, retourner le nom de l'épouse
                if ($declaration->epouse) {
                    return $declaration->epouse->nom . ' ' . $declaration->epouse->prenom;
                }
            } else if ($type_personne === 'epouse') {
                // Si on recherche l'épouse, retourner le nom de l'époux
                if ($declaration->epoux) {
                    return $declaration->epoux->nom . ' ' . $declaration->epoux->prenom;
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la détermination du conjoint: ' . $e->getMessage(), [
                'code_acte' => $acte->code_acte_mariage ?? 'inconnu',
                'type_personne' => $type_personne
            ]);
            return null;
        }
    }

    public function getRegime()
    {
        $id = request('optionmariage');
        $regimes = collect();
        // OMRG_* : codes métier historiques ; OPM_* : seed OptionMariageSeeder (Monogamie puis Polygamie)
        if (in_array($id, ['OMRG_0001', 'OPM_0002'], true)) {
            $regimes = Regime::where('code_regime', 'RGIM_0002')->get();
        } elseif (in_array($id, ['OMRG_0002', 'OPM_0001'], true)) {
            $regimes = Regime::all();
        }

        return $regimes;
    }

    /**
     * Rechercher des témoins par critères
     */
    public function rechercheTemoin(Request $request)
    {
        try {
            $nom = $request->input('nom');
            $prenom = $request->input('prenom');
            $sexe = $request->input('sexe');
            $dateNaissance = $request->input('date_naissance');
            $lieuNaissance = $request->input('lieu_naissance');

            if (!$nom || trim($nom) === '') {
                return response()->json([
                    'code' => '400',
                    'message' => 'Le nom est obligatoire pour la recherche'
                ], 400);
            }

            // Construire la requête de recherche
            $query = \Modules\Referentiel\Entities\Personne::with(['localite', 'nationalite', 'profession'])
                ->where('nom', 'LIKE', '%' . $nom . '%');

            // Ajouter les critères optionnels
            if ($prenom && trim($prenom) !== '') {
                $query->where('prenom', 'LIKE', '%' . $prenom . '%');
            }

            if ($sexe && $sexe !== '') {
                $query->where('sexe', $sexe);
            }

            if ($dateNaissance && $dateNaissance !== '') {
                $query->where('date_naissance', $dateNaissance);
            }

            if ($lieuNaissance && $lieuNaissance !== '') {
                $query->where('code_localite', $lieuNaissance);
            }

            // Condition obligatoire : âge > 18 ans pour les témoins
            $dateLimite = now()->subYears(18)->format('Y-m-d');
            $query->where('date_naissance', '<=', $dateLimite);

            // Limiter les résultats à 20 pour éviter les surcharges
            $resultats = $query->limit(20)->get();

            // Formater les résultats
            $resultatsFormates = $resultats->map(function($personne) {
                return [
                    'code_personne' => $personne->code_personne,
                    'nom' => $personne->nom,
                    'prenom' => $personne->prenom,
                    'sexe' => $personne->sexe,
                    'date_naissance' => $personne->date_naissance,
                    'lieu_naissance' => $personne->code_localite,
                    'lib_lieu_naissance' => $personne->localite ? $personne->localite->lib_localite : null,
                    'code_nationalite' => $personne->code_nationalite,
                    'lib_nationalite' => $personne->nationalite ? $personne->nationalite->lib_nationalite : null,
                    'code_profession' => $personne->code_profession,
                    'lib_profession' => $personne->profession ? $personne->profession->lib_profession : null,
                ];
            });

            return response()->json([
                'code' => '200',
                'message' => 'Recherche effectuée avec succès',
                'resultats' => $resultatsFormates
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la recherche de témoin: ' . $e->getMessage());

            return response()->json([
                'code' => '500',
                'message' => 'Une erreur s\'est produite lors de la recherche'
            ], 500);
        }
    }


    /**
     * Envoyer une déclaration de mariage au tribunal
     *
     * @param Request $request
     * @param string $id Code de la déclaration de mariage
     * @param MouvementMariageService $mouvementService
     * @param NotificationService $notificationService
     * @return \Illuminate\Http\JsonResponse
     */
    public function envoyerAuTribunal(Request $request, $id, MouvementMariageService $mouvementService, NotificationService $notificationService)
    {
        // Recherche de la déclaration
        $declaration = DeclarationMariage::find($id);
        if (!$declaration) {
            return response()->json([
                "code" => "90",
                "message" => "Aucune déclaration de mariage trouvée avec le code : " . $id
            ], 404);
        }

        $user = Auth::user();

        // Vérifications préalables
        if (!$declaration->institution || !$declaration->institution->institutionParent) {
            return response()->json([
                "code" => "90",
                "message" => "Institution ou tribunal parent introuvable pour cette déclaration"
            ], 400);
        }

        if (!$mouvementService->peutEtreEnvoyee($declaration)) {
            return response()->json([
                "code" => "90",
                "message" => "Cette déclaration ne peut pas être envoyée au tribunal (déjà approuvée par le centre)"
            ], 400);
        }


        // Préparation des données pour l'envoi
        $tribunal = $declaration->institution->institutionParent;
        $observation = $request->observation ?? null;

        DB::beginTransaction();
        try {
            // Envoi de la déclaration au tribunal
            [$success, $message] = $mouvementService->envoyerDeclaration(
                $declaration,
                'dispense_mariage',
                $tribunal,
                $user,
                'Envoyée',
                $observation
            );

            if (!$success) {
                DB::rollBack();
                return response()->json([
                    "code" => "90",
                    "message" => $message
                ]);
            }

            // Notification aux agents du tribunal
            $institutionDestinataire = Institution::find($declaration->code_institution_destinataire);
            $notificationService->notifierAgentsInstitution(
                $institutionDestinataire,
                new \Modules\Notification\Notifications\DeclarationMariageEnvoyeeNotification(
                    $declaration,
                    $institutionDestinataire,
                    'envoyée'
                )
            );

            // Notification aux agents du centre d'état civil (institution d'origine)
            $institutionOrigine = $declaration->institution;
            $notificationService->notifierAgentsInstitution(
                $institutionOrigine,
                new \Modules\Notification\Notifications\DeclarationMariageEnvoyeeNotification(
                    $declaration,
                    $institutionOrigine,
                    'envoyée'
                )
            );

            DB::commit();

            return response()->json([
                "code" => "200",
                "message" => "Formulaire type de mariage envoyé au tribunal avec succès."
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur lors de l\'envoi au tribunal', [
                'code_declaration' => $declaration->code_declaration_mariage,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                "code" => "90",
                "message" => "Erreur lors de l'envoi au tribunal : " . $e->getMessage()
            ]);
        }
    }

    /**
     * Publier les bans de mariage
     */
    public function publierBanMariage($id, Request $request)
    {
        try {
            $declaration = DeclarationMariage::findOrFail($id);
            $mouvementService = new MouvementMariageService();

            [$success, $message] = $mouvementService->publierBanMariage($declaration, Auth::user(), $request->observation);

            if ($success) {
                return response()->json([
                    "code" => "200",
                    "message" => ["reponse" => $message]
                ]);
            } else {
                return response()->json([
                    "code" => "500",
                    "message" => ["error" => $message]
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                "code" => "500",
                "message" => ["error" => $e->getMessage()]
            ]);
        }
    }

    /**
     * Célébrer le mariage
     */
    public function celebrerMariage($id, Request $request)
    {
        try {
            $declaration = DeclarationMariage::findOrFail($id);
            $mouvementService = new MouvementMariageService();

            [$success, $message] = $mouvementService->celebrerMariage($declaration, Auth::user(), $request->observation);

            if ($success) {
                return response()->json([
                    "code" => "200",
                    "message" => ["reponse" => $message]
                ]);
            } else {
                return response()->json([
                    "code" => "500",
                    "message" => ["error" => $message]
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                "code" => "500",
                "message" => ["error" => $e->getMessage()]
            ]);
        }
    }

    /**
     * Confirmer un dossier individuel (déclaration de mariage)
     */
    public function confirmerDossier(Request $request, MouvementMariageService $mouvementService)
    {
        try {
            DB::beginTransaction();
            $declaration = DeclarationMariage::findOrFail($request->code_declaration_mariage);
            $user = Auth::user();
            $affectation = $user->affectationActive();
            $observation = $request->observation;
            $statut = "Confirmée";


            [$ok, $result] = $mouvementService->confirmerDeclaration(
                $affectation,
                $declaration,
                $statut,
                $observation
            );

            if (!$ok) {
                DB::rollBack();
                Log::channel('sifec')->info($result);
                throw new Exception($result ?: "Erreur lors de la confirmation du dossier");
            }

            // La notification est gérée dans le service MouvementMariageService::confirmerDeclaration()

            DB::commit();
            return response()->json([
                "code" => "200",
                "message" => "Dossier confirmé avec succès"
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur confirmation dossier : ' . $e->getMessage());
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors de la confirmation du dossier: " . $e->getMessage()]
            ]);
        }
    }

    /**
     * Confirmer plusieurs dossiers en bulk (déclarations de mariage)
     */
    public function confirmerDossiersBulk(Request $request, MouvementMariageService $mouvementService)
    {
        try {
            $codes = $request->codes;
            $observation = $request->observation;
            $user = Auth::user();
            $affectation = $user->affectationActive();
            $statut = "Confirmée";

            $declarations = DeclarationMariage::whereIn('code_declaration_mariage', $codes)->get();

            if ($declarations->count() === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ['Aucun dossier à confirmer']
                ]);
            }

            $confirmes = 0;
            $erreurs = [];

            foreach ($declarations as $declaration) {
                [$ok, $result] = $mouvementService->confirmerDeclaration(
                    $affectation,
                    $declaration,
                    $statut,
                    $observation
                );
                if ($ok) {
                    $confirmes++;
                } else {
                    $erreurs[] = $declaration->code_declaration_mariage . ' : ' . $result;
                }
            }

            if (count($erreurs) > 0 && $confirmes === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ["Aucun dossier n'a pu être confirmé", ...$erreurs]
                ]);
            }

            $msg = $confirmes . ' dossier(s) confirmé(s) avec succès';
            if (count($erreurs) > 0) {
                $msg .= '. ' . count($erreurs) . ' dossier(s) en erreur';
            }

            return response()->json([
                'code' => '200',
                'message' => $msg
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur confirmation dossiers bulk : ' . $e->getMessage());
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors de la confirmation des dossiers: " . $e->getMessage()]
            ]);
        }
    }

    /**
     * Renvoyer un dossier individuel (déclaration de mariage)
     */
    public function renvoyerDossier(Request $request, MouvementMariageService $mouvementService)
    {
        try {
            DB::beginTransaction();
            $declaration = DeclarationMariage::findOrFail($request->code_declaration_mariage);
            $user = Auth::user();
            $affectation = $user->affectationActive();
            $observation = $request->observation;

            [$ok, $result] = $mouvementService->renvoyerAuCentre(
                $affectation,
                $declaration,
                $observation
            );

            if (!$ok) {
                DB::rollBack();
                Log::channel('sifec')->info($result);
                throw new Exception($result ?: "Erreur lors du renvoi du dossier");
            }

            DB::commit();
            return response()->json([
                'code' => '200',
                'message' => ['Dossier renvoyé avec succès']
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur renvoi dossier : ' . $e->getMessage());
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors du renvoi du dossier: " . $e->getMessage()]
            ]);
        }
    }

    /**
     * Renvoyer plusieurs dossiers en bulk (déclarations de mariage)
     */
    public function renvoyerDossiersBulk(Request $request, MouvementMariageService $mouvementService)
    {
        try {
            $codes = $request->codes;
            $observation = $request->observation;
            $user = Auth::user();
            $affectation = $user->affectationActive();

            $declarations = DeclarationMariage::whereIn('code_declaration_mariage', $codes)->get();

            if ($declarations->count() === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ['Aucun dossier à renvoyer']
                ]);
            }

            $renvoyes = 0;
            $erreurs = [];

            foreach ($declarations as $declaration) {
                [$ok, $result] = $mouvementService->renvoyerAuCentre(
                    $affectation,
                    $declaration,
                    $observation
                );
                if ($ok) {
                    $renvoyes++;
                } else {
                    $erreurs[] = $declaration->code_declaration_mariage . ' : ' . $result;
                }
            }

            if (count($erreurs) > 0 && $renvoyes === 0) {
                return response()->json([
                    'code' => '400',
                    'message' => ["Aucun dossier n'a pu être renvoyé", ...$erreurs]
                ]);
            }

            $msg = $renvoyes . ' dossier(s) renvoyé(s) avec succès';
            if (count($erreurs) > 0) {
                $msg .= '. ' . count($erreurs) . ' dossier(s) en erreur';
            }

            return response()->json([
                'code' => '200',
                'message' => $msg
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur renvoi dossiers bulk : ' . $e->getMessage());
            return response()->json([
                'code' => '500',
                'message' => ["Erreur lors du renvoi des dossiers: " . $e->getMessage()]
            ]);
        }
    }

    /**
     * Rejeter une déclaration de mariage
     */
    public function rejeterDeclaration($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'motif_rejet' => 'required|string|min:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "code" => "400",
                "message" => $validator->errors()
            ], 400);
        }

        try {
            $declaration = DeclarationMariage::findOrFail($id);
            $mouvementService = new MouvementMariageService();

            [$success, $message] = $mouvementService->rejeterDeclaration($declaration, Auth::user(), $request->motif_rejet);

            if ($success) {
                return response()->json([
                    "code" => "200",
                    "message" => ["reponse" => $message]
                ]);
            } else {
                return response()->json([
                    "code" => "500",
                    "message" => ["error" => $message]
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                "code" => "500",
                "message" => ["error" => $e->getMessage()]
            ]);
        }
    }

    /**
     * Obtenir l'historique des mouvements d'une déclaration
     */
    public function historiqueMovements($id)
    {
        try {
            $declaration = DeclarationMariage::findOrFail($id);
            $mouvementService = new MouvementMariageService();

            $historique = $mouvementService->obtenirHistoriqueMouvements($declaration);

            return response()->json([
                "code" => "200",
                "data" => $historique,
                "message" => ["reponse" => "Historique récupéré avec succès"]
            ]);

        } catch (Exception $e) {
            return response()->json([
                "code" => "500",
                "message" => ["error" => $e->getMessage()]
            ]);
        }
    }

    /**
     * Upload d'une pièce d'identité pour une déclaration de mariage
     */
    public function storePiece(Request $request, $code, $type)
    {
        $request->validate([
            'piece' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $declaration = DeclarationMariage::where('code_declaration_mariage', $code)->firstOrFail();
        $uploadPath = public_path('app/pieces');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        try {
            if ($request->hasFile('piece') && $request->file('piece')->isValid()) {
                $file = $request->file('piece');
                $extension = $file->getClientOriginalExtension();
                $imageName = $declaration->code_declaration_mariage . '_' . $type . '_' . time() . '.' . $extension;

                // Supprimer l'ancienne pièce si elle existe
                $oldPath = $declaration->{'piece_' . $type};
                if ($oldPath && file_exists(public_path($oldPath))) {
                    @unlink(public_path($oldPath));
                }

                $file->move($uploadPath, $imageName);
                $declaration->{'piece_' . $type} = 'app/pieces/' . $imageName;
                $declaration->save();

                return response()->json([
                    'code' => '200',
                    'message' => 'Pièce enregistrée avec succès.',
                    'file_path' => 'app/pieces/' . $imageName
                ]);
            } else {
                return response()->json([
                    'code' => '400',
                    'message' => 'Erreur lors de l\'upload du fichier.'
                ], 400);
            }
        } catch (Exception $e) {
            Log::error('Erreur upload pièce mariage: ' . $e->getMessage());
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'enregistrement de la pièce: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérification de l'acte de mariage (lien signé, accès public via QR code)
     */
    public function verificationActe(Request $request, $code)
    {
        if ($request->filled('verif_email')) {
            abort(404);
        }

        $acte = ActeMariage::with([
            'declaration.epoux',
            'declaration.epouse',
            'retrait'
        ])->where('code_acte_mariage', $code)->first();

        if (!$acte) {
            abort(404);
        }

        return view('mariage::verification.acte', compact('acte'));
    }

    /**
     * Vérification de la déclaration de mariage (lien signé, accès public via QR code)
     */
    public function verificationDeclaration(Request $request, $code)
    {
        if ($request->filled('verif_email')) {
            abort(404);
        }

        $declaration = DeclarationMariage::with([
            'epoux', 'epouse', 'acte.retrait'
        ])->where('code_declaration_mariage', $code)->first();

        if (!$declaration) {
            abort(404);
        }

        return view('mariage::verification.declaration', compact('declaration'));
    }
}
