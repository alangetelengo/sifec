<?php

namespace Modules\Naissance\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Sifec\Sifec;
use App\Models\Jugement;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use App\Helpers\LocaliteHelper;
use App\Models\InstitutionUser;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Referentiel\Entities\Document;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Filiation;
use Illuminate\Contracts\Support\Renderable;
use Modules\Referentiel\Entities\Profession;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\TypeDocument;
use Modules\Referentiel\Entities\Arrondissement;
use Modules\Referentiel\Entities\LieuSurvenance;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Notification\Services\NotificationService;
use Modules\Referentiel\Entities\SituationMatrimoniale;
use Modules\Naissance\Http\Requests\StoreAdoptionRequest;
use Modules\Naissance\Services\DeclarationNaissanceService;
use Modules\Naissance\Http\Requests\StoreDeclarationNaissanceRequest;
use Modules\Naissance\Http\Requests\UpdateDeclarationNaissanceRequest;
use Modules\Naissance\Services\MouvementService;
use Illuminate\Support\Facades\URL;

class NaissanceController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $title = "Liste des déclarations de naissance";
        $button = "Créer déclaration";
        $institution = $user->institution();

        $codeCategorie = optional(optional(optional($user->affectationActive())->institution)->typeInstitution)->typeCategorieInstitution;
        $codeCategorie = $codeCategorie ? $codeCategorie->code_type_categorie_ins : null;

        if ($codeCategorie == 'TCINS_0003') {
            $title = "Liste des certificats de déclaration de naissance";
            $button = "Créer certificat de déclaration";
        } elseif ($codeCategorie == 'TCINS_0001') {
            $title = "Liste des déclarations de naissance";
            $button = "Créer déclaration";
        }


        $declarations = Declarationnaissance::query()
            ->with(['enfant', 'declarant', 'pere', 'mere', 'mouvements'])
            ->when($institution, function ($query) use ($institution) {
                return $query->where('code_institution', $institution->code_institution);
            })
            ->where('type_declaration', '!=', 'CERTIFICAT DE NON INSCRIPTION')
            ->orderByDesc('date_heure_declaration')
            ->limit(2000)
            ->get();

        return view('naissance::declaration.index', compact('declarations', 'title', 'button'));
    }

    public function etat(Request $request, $id)
    {
        $contexteForcage = $request->query('contexte');
        if ($contexteForcage && !in_array($contexteForcage, ['formation_sanitaire', 'centre_etat_civil'])) {
            $contexteForcage = null;
        }

        $typeDCertifatD = "CERTIFICAT DE DESTRUCTION DE L'ACTE";
        $typeDPaternite = "DECLARATION DE PATERNITE";
        $typeDCertifatN = "CERTIFICAT DE NON INSCRIPTION";
        $typeDNais = "DECLARATION DE NAISSANCE";
        $typeCertifDeclaration = "CERTIFICAT DE NAISSANCE";
        $typeJSup = "JUGEMENT SUPPLETIF";
        $typeJHomo = "JUGEMENT D'HOMOLOGATION";
        $typeFMNais = "FICHE DE MATERNITE";
        $typeFTA = "FICHE DE TRANSCRIPTION DE L'ACTE";


        $declarationsD = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeDCertifatD)->first();
        $declarationP = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeDPaternite)->first();
        $declarationsN = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeDCertifatN)->first();
        $declarations = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeDNais)->first();
        $certifDeclaration = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeCertifDeclaration)->first();
        $jugementSuppletif = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeJSup)->first();
        $jugementHomologation = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeJHomo)->first();
        $ficheMat = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeFMNais)->first();
        $ficheTransActe = Declarationnaissance::where("code_declaration_naissance",$id)->where("type_declaration",$typeFTA)->first();

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');

        $dummy = "XXXXXXXXXXXXXXXX";

        if($declarations != null){
            $typeDeclaration = $declarations->type_declaration;
            $qrCode = $this->generateDeclarationQr($declarations->code_declaration_naissance);
            $html2pdf->writeHTML(view('naissance::etats.declaration', compact("dummy", "qrCode", "typeDeclaration", "contexteForcage"),["dn"=>$declarations])->render());
           return $html2pdf->output($declarations->code_declaration_naissance.".pdf");
        }

        if($certifDeclaration != null){
            $typeDeclaration = $certifDeclaration->type_declaration;
            $qrCode = $this->generateDeclarationQr($certifDeclaration->code_declaration_naissance);
            $html2pdf->writeHTML(view('naissance::etats.declaration', compact("dummy", "qrCode", "typeDeclaration", "contexteForcage"),["dn"=>$certifDeclaration])->render());
            return $html2pdf->output($certifDeclaration->code_declaration_naissance.".pdf");
        }

        if($declarationsN != null){

            $dateNaissEnfant = Carbon::create($declarationsN->enfant->date_naissance);
            $dateNow = Carbon::create(date("Y-m-y"));
            $ageEnfant = $dateNow->diffInYears($dateNaissEnfant);
            $qrCode = $this->generateDeclarationQr($declarationsN->code_declaration_naissance);

            $html2pdf->writeHTML(view('naissance::etats.certificat_non_inscription', compact("dummy","ageEnfant","qrCode"),["certificat"=>$declarationsN])->render());
            return $html2pdf->output($declarationsN->code_declaration_naissance.".pdf");
        }
        if($jugementHomologation != null){
            $typeDeclaration = $jugementHomologation->type_declaration;
            $qrCode = $this->generateDeclarationQr($jugementHomologation->code_declaration_naissance);
            $html2pdf->writeHTML(view('naissance::etats.declaration', compact("dummy","qrCode","typeDeclaration","contexteForcage"),["dn"=>$jugementHomologation])->render());
            return $html2pdf->output($jugementHomologation->code_declaration_naissance.".pdf");
        }
        if($jugementSuppletif != null){
            $typeDeclaration = $jugementSuppletif->type_declaration;
            $qrCode = $this->generateDeclarationQr($jugementSuppletif->code_declaration_naissance);
            $html2pdf->writeHTML(view('naissance::etats.declaration', compact("dummy","qrCode","typeDeclaration","contexteForcage"),["dn"=>$jugementSuppletif])->render());
            return $html2pdf->output($jugementSuppletif->code_declaration_naissance.".pdf");
        }
        if($declarationP != null){
            $qrCode = $this->generateDeclarationQr($declarationP->code_declaration_naissance);
            $html2pdf->writeHTML(view('naissance::etats.certificat_de_transcription', compact("dummy","qrCode"),["certificat"=>$declarationP])->render());
            return $html2pdf->output($declarationP->code_declaration_naissance.".pdf");
        }
        if($declarationsD != null){
            $qrCode = $this->generateDeclarationQr($declarationsD->code_declaration_naissance);
            $html2pdf->writeHTML(view('naissance::etats.certificat_destruction', compact("dummy","qrCode"),["certificat"=>$declarationsD])->render());
            return $html2pdf->output($declarationsD->code_declaration_naissance.".pdf");
        }
        if($ficheMat != null){
            $typeDeclaration = $ficheMat->type_declaration;
            $qrCode = $this->generateDeclarationQr($ficheMat->code_declaration_naissance);
            $html2pdf->writeHTML(view('naissance::etats.declaration', compact("dummy","qrCode","typeDeclaration","contexteForcage"),["dn"=>$ficheMat])->render());
            return $html2pdf->output($ficheMat->code_declaration_naissance.".pdf");
        }
        if($ficheTransActe != null){
            $typeDeclaration = $ficheTransActe->type_declaration;
            $qrCode = $this->generateDeclarationQr($ficheTransActe->code_declaration_naissance);
            $html2pdf->writeHTML(view('naissance::etats.declaration', compact("dummy","qrCode","typeDeclaration","contexteForcage"),["dn"=>$ficheTransActe])->render());
            return $html2pdf->output($ficheTransActe->code_declaration_naissance.".pdf");
        }
    }

    // Recherche d'une personne
    public function recherchePersonne(Request $request)
    {
        if($request->ajax()){

        if($request->nom==null)
        {
            $nom="";
        }else{
            $nom=$request->nom;
        }

        if($request->prenom==null)
        {
            $prenom="";
        }else{
            $prenom=$request->prenom;
        }

        if($request->telephone==null)
        {
            $telephone="";
        }else{
            $telephone=$request->telephone;
        }


        if(empty($request->statut))
        {
            $personnes =   DB::select('SELECT *, c.telephone as phone FROM `tr_identification_personne` t
                                       LEFT JOIN t_document d  ON t.code_personne=d.code_personne
                                       LEFT JOIN t_adresse_personne a on t.code_personne=a.code_personne
                                       LEFT JOIN t_contact_personne c on t.code_personne=c.code_personne

                                       WHERE   t.sexe like "%'.$request->sexe.'%"
                                               and t.nom like "%'.$request->nom.'%"
                                               and t.prenom like "%'.$request->prenom.'%"
                                               and c.telephone like "%'.$telephone.'%"
           ');

        }else
        {
            $personnes =   DB::select('SELECT *, c.telephone as phone FROM `tr_identification_personne` t
                                        LEFT JOIN t_document d  ON t.code_personne=d.code_personne
                                        LEFT JOIN t_adresse_personne a on t.code_personne=a.code_personne
                                        LEFT JOIN t_contact_personne c on t.code_personne=c.code_personne
                                        WHERE   t.sexe like "%'.$request->sexe.'%"
                                                and t.nom like "%'.$nom.'%"
                                                and t.prenom like "%'.$prenom.'%"
                                                and t.statut_personne = "'.$request->statut.'"
                                                and c.telephone like "%'.$telephone.'%"
           ');
        }

        return response()->json([
               "personnes" => $personnes
            ]);

       }
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $user = Auth::user();
        $title = "Créer une déclaration de naissance";
        $type_declaration = "DECLARATION DE NAISSANCE";
        $ageEnfant = 0;
        $dateNaissance = request("date_naissance_enfant");

        if($user->affectationActive()->fonction->code_fonction == "FONC_0006") //si c'est un agent de formation sanitaire
        {
            $title = "Créer un certificat de déclaration de naissance";
            $type_declaration = "CERTIFICAT DE NAISSANCE";
        }

        if($user->affectationActive()->fonction->code_fonction == "FONC_0014") //si c'est un agent de la mairie centrale
        {
            $title = "Créer un certificat de transcription de l'acte";
            $type_declaration = "CERTIFICAT DE TRANSCRIPTION";
        }

        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $communes = Localite::where('code_type_localite','TPLOC_0003')->Orwhere('code_type_localite','TPLOC_0002')->get();
        $arrondissements = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartiers = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();
        return view('naissance::declaration.create',compact("title","ageEnfant","dateNaissance","departements","countries","communes","arrondissements","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartiers","type_declaration"));
    }

    public function affaireSociale()
    {

        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();


        $dummy = "XXXXXXXXXXXXXXXX";

        return view('naissance::enfant-abandonne.create ',compact("dummy","departements","countries","arrondissement","quartierVillages","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances"));

    }

    public function enfantTrouve()
    {

        $instructions = Sifec::niveauInstructions();
        $localites = Localite::all();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Arrondissement::all();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        $dummy = "XXXXXXXXXXXXXXXX";

        return view('naissance::enfant-trouver.create',compact("dummy","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances"));

    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreDeclarationNaissanceRequest $request, DeclarationNaissanceService $service)
    {
        try {
            // Log::channel("sifec")->info($request->all());
            // dd($request->all());
            // Enregistrement de la déclaration
            $resultatEnregistrement = $service->enregistrer($request, Auth::user());

            // Si le service retourne une réponse JSON (erreur), on la logue et on la retourne
            if ($resultatEnregistrement instanceof \Illuminate\Http\JsonResponse) {
                $data = $resultatEnregistrement->getData(true);
                Log::channel('sifec')->warning('Erreur enregistrement déclaration naissance', [
                    'code' => $data['code'] ?? null,
                    'message' => $data['message'] ?? null,
                    'personne_declaree' => $request->personne_declaree ?? null,
                ]);
                return $resultatEnregistrement;
            }

            $declaration = $resultatEnregistrement;

            // Gestion du mouvement après enregistrement réussi
            $resultatMouvement = $this->gererMouvementDeclaration($request, $declaration);
            if ($resultatMouvement !== true) {
                return $resultatMouvement;
            }

            return response()->json([
                "code" => "200",
                "message" => "La déclaration a été enregistrée avec succès"
            ]);

        } catch (Exception $e) {
            Log::channel("sifec")->error("Erreur dans le contrôleur de déclaration de naissance: " . $e->getMessage());
            return response()->json([
                "code" => "90",
                "message" => "Une erreur inattendue s'est produite lors de l'enregistrement"
            ]);
        }
    }

    /**
     * Gère le mouvement de la déclaration
     */
    private function gererMouvementDeclaration($request, $declaration)
    {
        $mappingTypeEvenement = [
            'DECLARATION DE NAISSANCE' => 'declaration_naissance',
            'CERTIFICAT DE NON INSCRIPTION' => 'certificat_non_inscription',
            "CERTIFICAT DE DESTRUCTION DE L'ACTE" => 'certificat_destruction',
            'FICHE DE MATERNITE' => 'fiche_maternite',
            'CERTIFICAT DE TRANSCRIPTION' => 'fiche_transcription',
            'JUGEMENT SUPPLETIF' => 'jugement_suppletif',
            "JUGEMENT D'HOMOLOGATION" => 'jugement_homologation',
            "JUGEMENT D'ADOPTION" => 'jugement_adoption',
        ];

        $typeDeclaration = $request->input('type_declaration', 'DECLARATION DE NAISSANCE');
        $typeEvenement = $mappingTypeEvenement[$typeDeclaration] ?? 'declaration_naissance';

        try {
            $mouvementService = app(MouvementService::class);
            $result = $mouvementService->ajouterEvenementDeclaration(Auth::user(), $declaration, $typeEvenement);

            if (!$result[0]) {
                return response()->json([
                    "code" => "91",
                    "message" => "Erreur lors de l'enregistrement du mouvement: " . $result[1]
                ]);
            }

            return true;

        } catch (Exception $e) {
            Log::channel("sifec")->error("Erreur lors de la gestion du mouvement: " . $e->getMessage());
            return response()->json([
                "code" => "91",
                "message" => "Erreur lors de l'enregistrement du mouvement"
            ]);
        }
    }


    public function show($id)
    {
        $declaration = Declarationnaissance::find($id);
        if($declaration == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }

        return view('naissance::declaration.show', compact("declaration"));
    }

    public function edit($id)
    {
        $dn = Declarationnaissance::find($id);
        if($dn==null)
        {
            toastr()->error("Impossible de charger cette page");
            return back();
        }
        $fnUser = Auth::user()->affectationActive()->fonction->code_fonction;
        $typeDeclaration = $dn->type_declaration;
        $title = " MODIFICATION ".$typeDeclaration;
        $date = Carbon::create(date("Y-m-y"));
        $dateNaissEnfant = Carbon::create($dn->enfant->date_naissance);
        $ageEnfant = $date->diffInYears($dateNaissEnfant);
        $instructions = Sifec::niveauInstructions();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $localites = Localite::where('code_type_localite','TPLOC_0002')
            ->orWhere('code_type_localite','TPLOC_0003')->get();
        $arrondissements = Localite::where('code_type_localite','TPLOC_0004')
            ->orWhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')
            ->orWhere('code_type_localite','TPLOC_0008')->get();
        $departementsDropdown = LocaliteHelper::getDepartementsDropdown();
        $communesDropdown = LocaliteHelper::getLocalitesDropdown('TPLOC_0003');
        $arrondissementsDropdown = LocaliteHelper::getLocalitesDropdown('TPLOC_0004');
        $quartiersDropdown = LocaliteHelper::getLocalitesDropdown('TPLOC_0007');
        $adressePere = $dn->pere ? $dn->pere->adresses->last() : null;
        $commune = $adressePere ? LocaliteHelper::getLocaliteByType($adressePere->code_localite, 'TPLOC_0003') : null;
        $arrondissement = $adressePere ? LocaliteHelper::getLocaliteByType($adressePere->code_localite, 'TPLOC_0004') : null;
        $quartier = $adressePere ? LocaliteHelper::getLocaliteByType($adressePere->code_localite, 'TPLOC_0007') : null;
        $adresseMere = $dn->mere ? $dn->mere->adresses->last() : null;
        $communeMere = $adresseMere ? LocaliteHelper::getLocaliteByType($adresseMere->code_localite, 'TPLOC_0003') : null;
        $arrondissementMere = $adresseMere ? LocaliteHelper::getLocaliteByType($adresseMere->code_localite, 'TPLOC_0004') : null;
        $quartierMere = $adresseMere ? LocaliteHelper::getLocaliteByType($adresseMere->code_localite, 'TPLOC_0007') : null;
        $adresseDeclarant = $dn->declarant ? $dn->declarant->adresses->last() : null;
        $communeDeclarant = $adresseDeclarant ? LocaliteHelper::getLocaliteByType($adresseDeclarant->code_localite, 'TPLOC_0003') : null;
        $arrondissementDeclarant = $adresseDeclarant ? LocaliteHelper::getLocaliteByType($adresseDeclarant->code_localite, 'TPLOC_0004') : null;
        $quartierDeclarant = $adresseDeclarant ? LocaliteHelper::getLocaliteByType($adresseDeclarant->code_localite, 'TPLOC_0007') : null;
        $filiations = Filiation::all();
        return view('naissance::declaration.edit', compact(
            "typeDeclaration",
            "ageEnfant","dn","title","countries",
            "typedocuments","instructions","professions","nationalites",
            "situationMatrimoniales","lieuSurvenances",
            "localites","arrondissements",
            "departementsDropdown","communesDropdown","arrondissementsDropdown","quartiersDropdown",
            "commune","arrondissement","quartier",
            "communeMere","arrondissementMere","quartierMere",
            "communeDeclarant","arrondissementDeclarant","quartierDeclarant",
            "filiations"
        ));
    }


    public function update(UpdateDeclarationNaissanceRequest $request, $id, DeclarationNaissanceService $service)
    {
        // Log::channel('sifec')->info($request->all());
        // dd($request->all());
        try {
            $declaration = $service->update($request, $id, Auth::user());
        return response()->json([
                "code" => "200",
                "message" => "Document modifié avec succès"
            ]);
        } catch (Exception $e) {
        return response()->json([
                "code" => "150",
                "message" => $e->getMessage()
            ]);
        }
    }

    public function mouvement(MouvementService $mouvement ,Request $request)
    {
        $dn = Declarationnaissance::find($request->code_declaration_naissance);
        if($dn == null){
            return response()->json([
                "code"=>"183",
                "message"=>["error" => "Aucun document trouvé pour ce code"]
            ]);
        }
        $statut = 'Envoyée';
        $observation = $request->observation;

        try {
            DB::transaction(function () use ($mouvement, $dn, $observation, $request) {
                [$ok, $statutResult] =  $mouvement->envoyerDeclaration(Auth::user(), $dn, 'MOUV_0001', 'Envoyée', $observation);

                if(!$ok){
                    Log::channel('sifec')->info($statutResult);
                    throw new Exception($statutResult ?: "Opération a échouée");
                }

                //recuperer le code_institution_destinataire pour la notification de l'envoi de la déclaration
                $codeInstitutionDestinataire = $dn->code_institution_destinataire;
                $institutionDestinataire = Institution::find($codeInstitutionDestinataire);

                // Notification centralisée via le module Notification
                NotificationService::notifierAgentsInstitution(
                    $institutionDestinataire,
                    new \Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification(
                        $dn,
                        $institutionDestinataire,
                        'envoyée'
                    )
                );

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

    public function mouvementEdit(Request $request, $id)
    {
        $mvtn = MouvementNaissance::find($id);


        if($mvtn =="" || $mvtn == null){
            return response()->json([
                "code"=>"183",
                "message"=>["Aucune donnée trouvée"]
            ]);
        }

        DB::beginTransaction();
        try {

            $mvtn->motif_renvoi = $request->motif_renvoi;
            $mvtn->observation = trim($request->observation);
            $mvtn->save();

            //update (lu et approuve) du déclarant
            $dn = Declarationnaissance::find($mvtn->code_declaration_naissance);
            $dn->approuver = "NON";
            $dn->save();
            DB::commit();
            return response()->json([
                "code"=>"200",
                "message"=>"Modification effectuée avec succès"
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>$e->getMessage()]
            ]);
        }

    }

    public function mouvementDelete($id)
    {
        $mvtn = MouvementNaissance::find($id);


        if($mvtn =="" || $mvtn == null){
            return response()->json([
                "code"=>"183",
                "message"=>["Aucune donnée trouvée"]
            ]);
        }

        try {
            $mvtn->delete();
            return response()->json([
                "code"=>"200",
                "message"=>"Opération effectuée avec succès"
            ]);

        }  catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                "code"=>"183",
                "message"=>["error"=>$e->getMessage()]
            ]);
        }
    }

    public function statParSexe()
    {

        $declarations = Auth::user()->institution()->declarationsNaissances();
        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_declaration_naissance;
        }
        $array = implode("','",$liste);

        $datas = DB::select("SELECT tr_identification_personne.sexe, COUNT(*) AS TOTAL
        FROM t_declaration_naissance
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        -- AND code_declaration_naissance IN ('".$array."')
        AND MONTH(t_declaration_naissance.date_heure_declaration) = MONTH(CURDATE())
        GROUP BY tr_identification_personne.sexe");

        return view('naissance::statistiques.declarationSexe', compact('datas'));
    }

    public function statParSexeEtat()
    {
        $declarations = Auth::user()->institution()->declarationsNaissances();
        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_declaration_naissance;
        }
        $array = implode("','",$liste);

        $datas = DB::select("SELECT tr_identification_personne.sexe, COUNT(*) AS TOTAL
        FROM t_declaration_naissance
        JOIN tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        -- AND code_declaration_naissance IN ('".$array."')
        AND MONTH(t_declaration_naissance.date_heure_declaration) = MONTH(CURDATE())
        GROUP BY tr_identification_personne.sexe");

        view()->share("tester", "Alange");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.statistiques.declarationParsexe', compact("datas"))->render());

        return $html2pdf->output("declarationParsexe.pdf");
    }

    public function statParSexeActe()
    {

        $declarations = Auth::user()->affectations()->get();
        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_institution;
        }
        $array = implode("','",$liste);

        $datas = DB::select("SELECT sexe, COUNT(*) AS TOTAL
        FROM t_acte_naissance
        JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
        JOIN tr_identification_personne tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
        JOIN tr_institution ON tr_institution.code_institution = tr_ins_user.code_institution
        WHERE  tr_institution.code_institution IN ('".$array."')
        AND MONTH(t_acte_naissance.date_emission) = MONTH(CURDATE())
        GROUP BY tr_identification_personne.sexe");

        return view('naissance::statistiques.acteNaissanceSexe', compact('datas'));
    }

    public function statParSexeActeEtat(){
        $declarations = Auth::user()->affectations()->get();
        $liste = [];
        foreach ($declarations as $key) {
            $liste[] = $key->code_institution;
        }
        $array = implode("','",$liste);

        $datas = DB::select("SELECT sexe, COUNT(*) AS TOTAL
        FROM t_acte_naissance
        JOIN t_declaration_naissance ON t_declaration_naissance.code_declaration_naissance = t_acte_naissance.code_declaration_naissance
        JOIN tr_identification_personne tr_identification_personne ON tr_identification_personne.code_personne = t_declaration_naissance.code_enfant
        JOIN tr_ins_user ON tr_ins_user.cui = t_acte_naissance.cui
        JOIN tr_institution ON tr_institution.code_institution = tr_ins_user.code_institution
        WHERE t_acte_naissance.supprimer = 0
        AND tr_institution.code_institution IN ('".$array."')
        AND MONTH(t_acte_naissance.date_emission) = MONTH(CURDATE())
        GROUP BY tr_identification_personne.sexe");

        view()->share("tester", "Vincent");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(view('naissance::etats.statistiques.acteNaissanceSexeEtat', compact("datas"))->render());

        return $html2pdf->output("declarationParsexe.pdf");
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

    public function searchLocalite($id)
    {
        $qv = Sifec::communesDistricts($id);
        // return $qv->flatten();
        return response()->json([
            "localites" => $qv->flatten()
        ]);
    }


    public function searchArrondissement()
    {
        $id =request('id');
        $arrond = Sifec::ArrondissementComUrb($id);
        return response()->json($arrond->flatten());
    }

    public function searchQuartier()
    {
        $id = request('id');
        $quartiers = Sifec::Quartier($id);
        return response()->json($quartiers->flatten());
    }

    public function searchInstitution()
    {
        $id = request('id');

        $institutions = Sifec::institutions($id,"TPINS_0002");
        //cas de owando
        // $institutions = Institution::where("code_localite",$id)->get();
        Log::channel("sifec")->info($institutions);
        return response()->json($institutions);
    }


    public function tardive()
    {
        $title = "Créer une déclaration tardive de naissance";

        $type_declaration = "DECLARATION TARDIVE DE NAISSANCE";
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        return view('naissance::declaration.create',compact("title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","type_declaration"));

    }
    public function paternite()
    {
        $title = "Créer une déclaration de paternité";

        $type_declaration = "DECLARATION DE PATERNITE";
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        return view('naissance::declaration.create',compact("title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","type_declaration"));

    }


    public function storeImporter(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'file' => 'required|mimes:pdf|max:2048',
            'code_type_document'=>'required|string',
            'codeparent'=>'required|string',
            'numero_document'=>'required|string'
        ]);
        if(!$validator->fails()){
            return response()->json(['code'=>0,'error'=>$validator->errors()->toArray()]);
        }
        $fdoc = Document::where("numero_document", $request->numero_document)->first();
        if ($fdoc == null) {
            return response()->json(['code'=>1,'msg'=>'Le système ne retrouve pas ce document']);
        }
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                DB::beginTransaction();
                try{
                    $chemin_document = $file->store("document");
                    $fdoc->image_document = $chemin_document;
                    $fdoc->save();
                    DB::commit();
                    return response()->json(['code'=>2,"codeparent"=>$request->codeparent, 'msg'=>'Enregistement de la pièce effectué avec succès']);
                }catch(Exception $e){
                    DB::rollBack();
                    return response()->json(['code'=>1, 'msg'=> $e->getMessage()]);
                }
            } else {
                return response()->json(['code'=>1, 'msg'=> 'Le fichier importé est corrompu ou inaccessible.']);
            }
        } else {
            return response()->json(['code'=>1, 'msg'=> 'Aucun fichier importé reçu.']);
        }
    }

    // Retrouver le document de la personne par son code
    public function getDocument($id)
    {
        $documents = Document::where("code_personne",$id)->get();
        $out = "";
        $count = 1;
        foreach($documents as $document){
            $out .= "
                <tr style='width: 100px;'>
                <td>".$count ++. "</td>
                <td>".($document->numero_document)."</td>
                <td>".$document->typeDocument->lib_type_document."</td>
                <td>
                    <div class='btn-group btn-group-xs'>
                    <a href='".route("declarationNaissance.show.document",$document->code_document)."' class='btn btn-success shadow btn-xs sharp me-1' title='Voir la pièce'><i class='fas fa-eye'></i></a>
                        <form action='".route("declarationNaissance.destroy.document",$document->code_document)."' method='post'>

                            <button class='btn btn-danger shadow btn-xs sharp' type='submit'><i class='fa fa-trash'></i></button>
                        </form>
                    </div>
                </td>
                </tr>
           ";
        }

        return $out;
    }


    public function showDocument($id)
    {
        $doc = Document::find($id);

        if($doc == null){
            toastr()->error("Document indisponible");
            return back();
        }

        if(Document::find($id)->image_document == ""){

            toastr()->warning("Image de la pièce est indisponible dans le système.<br> Veuillez Scanner la pièce","Gestion des documents");
            return back();
        }
        return Storage::download(Document::find($id)->image_document, Document::find($id)->typeDocument->lib_type_document);
    }


    public function deleteDocument($id)
    {
        return $id;
    }

    //enfant a adopter
    public function adoption(Request $request,$id)
    {

        $request->validate([
            "code_jugement"=> ["required"],
            "numero_acte_naissance"=> ["required"]
        ]);

        $dn = Declarationnaissance::find($id);

        if($dn == null){
            toastr()->error("Document indisponible");
            return back();
        }
        //enfant déjà adopté
        if($dn->code_adoptant != null || $dn->code_adoptant != ""){
            toastr()->error("Cet enfant a déjà été adopté, veuiller contacter l'administrateur");
            return back();
        }

        $title = "Adoption d'un enfant";
        $dateNaissanceConvertis = Carbon::create($dn->enfant->date_naissance);
        $date = date("Y-m-d");
        $dateNaissanceNow = Carbon::create($date);
        $ageEnfant = $dateNaissanceConvertis->diffInYears($dateNaissanceNow);
        $tgis = Institution::where("code_type_institution","TPINS_0001")->get();

        $typeDeclaration = "DECLARATION DE NAISSANCE";
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        return view('naissance::adoption.adopter',compact("dn","tgis","title","departements","ageEnfant","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","typeDeclaration"));

    }

    //enregistrement adoption pleniere d'un enfant
    public function storeAdoptionPleniere(StoreAdoptionRequest $request, DeclarationNaissanceService $service)
    {
        try {
            $declaration = $service->adopter($request, Auth::user());
            return response()->json([
                "code" => "200",
                "message" => "La déclaration enregistrée avec succès"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "code" => "99",
                "message" => ["error" => $e->getMessage()]
            ]);
        }
    }


    //on va creer un certificat de non inscription à partir du jugement venant du tribunal de ressort
    public function createDeclarationJugement($code_jugement)
    {

        //rechercher le jugement
        $jugement = Jugement::find($code_jugement);
        if($jugement == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }

        $user = Auth::user();
        $title = "";
        $type_declaration = "";
        if($jugement->type_jugement == "JUGEMENT SUPPLETIF"){
            $title = "Créer un certificat de non inscription/jugement supplétif";
            $type_declaration = $jugement->type_jugement;
        }
        if($jugement->type_jugement == "JUGEMENT D'HOMOLOGATION"){
            $title = "Créer déclaration/jugement d'homologation ou de paternité";
            $type_declaration = $jugement->type_jugement;
        }
        if($jugement->type_jugement == "JUGEMENT D'ANNULATION D'ACTE"){
            $title = "Créer déclaration/jugement d'annulation de l'acte";
            $type_declaration = $jugement->type_jugement;

            //vérification de l'acte
            $an = ActeNaissance::find($jugement->numero_ancien_acte);
            return view('naissance::acte.annuler',compact("jugement","an"));
        }
        if($jugement->type_jugement == "JUGEMENT D'ADOPTION"){
            $title = "";
            $type_declaration = $jugement->type_jugement;

            //vérification de l'acte
            $an = ActeNaissance::find($jugement->numero_ancien_acte);
            return view('naissance::adoption.indentification_acte',compact("jugement","an"));
        }



        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        $tgis = Institution::where("code_type_institution","TPINS_0001")->get();

        return view('naissance::jugementsupletif.create',compact("tgis","jugement","type_declaration","title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages"));
    }

    public function jugementhomologation()
    {
        $user = Auth::user();
        $title = "Créer déclaration/jugement d'homologation ou de paternité";
        $type_declaration = "JUGEMENT D'HOMOLOGATION";

        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::all();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path("codes_pays.json"))));
        $departements = Departement::all();

        $tgis = Institution::where("code_type_institution","TPINS_0001")->get();

        // return view('naissance::jugementsupletif.create',compact("tgis","title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","type_declaration"));

        return view('naissance::jugementhomologation.create',compact("tgis","title","departements","countries","arrondissement","typedocuments","instructions","filiations","localites","professions","nationalites","situationMatrimoniales","lieuSurvenances","quartierVillages","type_declaration"));
    }



    public function storeJugementHomologation(Request $request, DeclarationNaissanceService $service)
    {
        $rules = [
            "nom_enfant"=>["required"],
            "date_naissance_enfant"=>["required","date"],
            "code_situation_matrimoniale"=>["required"],
            "sexe_enfant"=>["required"],
            "heure_naissance_enfant"=>["required","max:5","min:5"],
            "nombre_enfant"=>["required","numeric"]
        ];

        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json([
                "code"=>"150",
                "message"=>$validator->errors()
            ]);
        }

        // Vérification des âges
        $dateNaissancePere = Carbon::create($request->date_naissance_pere);
        $dateNaissanceEnfant = Carbon::create($request->date_naissance_enfant);
        $dateNaissanceMere = Carbon::create($request->date_naissance_mere);
        $differenceAgeEnfantPere = $dateNaissancePere->diffInYears($dateNaissanceEnfant);
        $differenceAgeEnfantMere = $dateNaissanceMere->diffInYears($dateNaissanceEnfant);
        if($differenceAgeEnfantPere < 14){
            return response()->json([
                "code"=>"99",
                "message"=>["age_pere"=>"La différence d'age entre père et enfant doit être supérieure ou égale à 14 ans"]
            ]);
        }
        if($differenceAgeEnfantMere < 12){
            return response()->json([
                "code"=>"99",
                "message"=>["age_mere"=>"La différence d'age entre mère et enfant doit être supérieure ou égale à 12 ans"]
            ]);
        }

        try {
            $user = Auth::user();
            $dn = $service->creerViaJugement($request, $user);
            return response()->json([
                "code"=>"200",
                "message"=>"Le document enregistré avec succès"
            ]);
        } catch (\Exception $e) {
            \Log::channel("sifec")->error($e->getMessage());
            return response()->json([
                "code"=>"99",
                "message"=>["error" =>$e->getMessage()]
            ]);
        }
    }



    //juste
    public function storePiece(Request $request, $code, $type)
    {
        $request->validate([
            'piece' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $declaration = Declarationnaissance::where('code_declaration_naissance', $code)->firstOrFail();
        $uploadPath = public_path('app/pieces');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        try {
            if ($request->hasFile('piece') && $request->file('piece')->isValid()) {
                $file = $request->file('piece');
                $extension = $file->getClientOriginalExtension();
                $imageName = $declaration->code_declaration_naissance . '_' . $type . '_' . time() . '.' . $extension;

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
            Log::error('Erreur upload pièce: ' . $e->getMessage());
            return response()->json([
                'code' => '500',
                'message' => 'Erreur lors de l\'enregistrement de la pièce: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verificationDeclaration(Request $request, $code)
    {
        if ($request->filled('verif_email')) {
            abort(404);
        }

        $declaration = Declarationnaissance::with(['enfant', 'pere', 'mere', 'declarant', 'mouvements', 'acte.retrait'])
            ->where('code_declaration_naissance', $code)
            ->first();

        if (!$declaration) {
            abort(404);
        }

        return view('naissance::verification.declaration', compact('declaration'));
    }

    public function verificationActe(Request $request, $niupp)
    {
        if ($request->filled('verif_email')) {
            abort(404);
        }

        $acte = ActeNaissance::with(['declaration.enfant', 'declaration.pere', 'declaration.mere', 'declaration.declarant', 'retrait'])
            ->where('niupp', $niupp)
            ->first();

        if (!$acte) {
            abort(404);
        }

        return view('naissance::verification.acte', compact('acte'));
    }

    private function generateDeclarationQr(string $code): string
    {
        return URL::signedRoute('verification.declaration', ['code' => $code]);
    }
}
