<?php

namespace Modules\Tribunal\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Tribunal\Services\TribunalDeclarationService;


class TribunalController extends Controller
{
    /**
     * Affiche la liste des dossiers à traiter par le tribunal
     */
    public function index()
    {
        $codeInstitution = Auth::user()->affectationActive()->code_institution;

        $dossiersNaissance = Declarationnaissance::where("code_institution_destinataire", $codeInstitution)->get();
        $dossiersDeces = DeclarationDeces::where("code_institution_destinataire", $codeInstitution)->get();
        $dossiersMariage = DeclarationMariage::where("code_institution_destinataire", $codeInstitution)->get();
        // Suppression de la gestion des rectifications
        // $dossiersRectification = \Modules\Rectification\Entities\Rectification::where("code_institution", $codeInstitution)->get();

        $dossiers = collect();

        foreach ($dossiersNaissance as $dossier) {
            $dossiers->push($this->formatDossier($dossier, 'naissance'));
        }
        foreach ($dossiersDeces as $dossier) {
            $dossiers->push($this->formatDossier($dossier, 'deces'));
        }
        foreach ($dossiersMariage as $dossier) {
            $dossiers->push($this->formatDossier($dossier, 'mariage'));
        }

        // Optionnel : trier par date de création, du plus récent au plus ancien
        $dossiers = $dossiers->sortByDesc('created_at')->values();
        return view('tribunal::documents.index', compact('dossiers'));
    }

    //recuperer les dossiers de rectification
    public function dossiersRectification()
    {
        $codeInstitution = Auth::user()->affectationActive()->code_institution;
        $rectifications = \Modules\Rectification\Entities\Rectification::where("code_institution_destinataire", $codeInstitution)->get();

        // Préparer les données enrichies pour la vue
        $rectificationsData = $rectifications->map(function($rectification) {
            $dernierMouvement = $rectification->mouvementRectification && $rectification->mouvementRectification->count() > 0
                ? $rectification->mouvementRectification->sortBy('created_at')->last()
                : null;
            $documentImporte = $rectification->code_requisition !== null;

            // Détermination du module selon le type d'acte
            $module = '';
            if ($rectification->typeActe) {
                switch ($rectification->typeActe->code_type_acte) {
                    case 'TPA_0001':
                        $module = 'naissance';
                        break;
                    case 'TPA_0002':
                        $module = 'mariage';
                        break;
                    case 'TPA_0003':
                        $module = 'deces';
                        break;
                    default:
                        $module = 'rectification';
                }
            }

            $id = $rectification->numero_acte;

            return [
                'rectification' => $rectification,
                'dernierMouvement' => $dernierMouvement,
                'documentImporte' => $documentImporte,
                'module' => $module,
                'id' => $id,
            ];
        });

        return view('tribunal::documents.rectification', [
            'rectificationsData' => $rectificationsData
        ]);
    }

      /**
     * Affiche le formulaire d'import de document
     */
    public function create($type, $id)
    {

        if ($type === 'naissance') {
            $objet = \Modules\Naissance\Entities\Declarationnaissance::find($id);
        } elseif ($type === 'mariage') {
            $objet = \Modules\Mariage\Entities\DeclarationMariage::find($id);
        } elseif ($type === 'deces') {
            $objet = \Modules\Deces\Entities\DeclarationDeces::find($id);
        } else {
            toastr()->error("Type de déclaration inconnu");
            return back();
        }

        if ($objet == null) {
            toastr()->error("Impossible de charger cette page");
            return back();
        }

        $typeRequisitions = \App\Models\TypeRequisition::all();
        $typeJugements = \App\Models\TypeJugement::all();
        $mode = 'declaration';

        return view('tribunal::documents.importer', compact("objet", "type", "typeRequisitions", "typeJugements", "mode"));
    }

    /**
     * Enregistre l'import d'un document (réquisition ou jugement)
     */
    public function store(Request $request, $type, $id, \Modules\Tribunal\Services\TribunalDeclarationService $service)
    {
        if ($type === 'naissance') {
            $declaration = \Modules\Naissance\Entities\Declarationnaissance::findOrFail($id);
            $module = 'naissance';
        } elseif ($type === 'mariage') {
            $declaration = \Modules\Mariage\Entities\DeclarationMariage::findOrFail($id);
            $module = 'mariage';
        } elseif ($type === 'deces') {
            $declaration = \Modules\Deces\Entities\DeclarationDeces::findOrFail($id);
            $module = 'deces';
        } else {
            toastr()->error("Type de déclaration inconnu");
            return back();
        }

        $request->validate([
            'type_document' => 'required|in:requisition,jugement',
            'date_document' => 'required|date',
            'document_importer' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'code_type_requisition' => 'required_if:type_document,requisition',
            'code_type_jugement'    => 'required_if:type_document,jugement',
        ]);

        try {
            $documentPath = null;
            DB::transaction(function () use ($declaration, $request, $module, $type, $service, &$documentPath) {
                $user = Auth::user();
                [$ok, $message, $documentPath] = $service->importerDocument($declaration, $request, $module, $user);
                if (!$ok) {
                    throw new \Exception($message);
                }
            });
            toastr()->success("Document importé, mouvement tracé !");
            // Rediriger vers la même page avec le chemin du document importé
            return redirect()->back()->with('document_importe', $documentPath);
        } catch (Exception $e) {
            Log::channel('sifec')->error($e->getMessage());
            toastr()->error($e->getMessage());
            return back()->withInput();
        }
    }

    private function formatDossier($dossier, $module)
    {
        if ($module === 'naissance') {
            $identite = $dossier->enfant ? $dossier->enfant->nom . ' ' . $dossier->enfant->prenom : '';
            $type_declaration = $dossier->type_declaration ?? $dossier->lib_mouvement ?? 'N/A';
        } elseif ($module === 'deces') {
            $identite = $dossier->defunt ? $dossier->defunt->nom . ' ' . $dossier->defunt->prenom : '';
            $type_declaration = $dossier->type_declaration ?? $dossier->lib_mouvement ?? 'N/A';
        } elseif ($module === 'mariage') {
            $identite = $dossier->epoux ? $dossier->epoux->nom . ' ' . $dossier->epoux->prenom : '';
            $type_declaration = $dossier->type_declaration ?? $dossier->lib_mouvement ?? 'N/A';
        } else {
            $identite = '';
            $type_declaration = 'N/A';
        }

        // Récupérer le dernier mouvement du dossier
        $dernierMouvement = $dossier->mouvements()->latest('created_at')->first();

        // Déterminer si un document a été importé (réquisition ou jugement)
        $documentImporte = false;
        $statut = 'importée'; // statut par défaut

        if ($dossier->requisition && $dossier->requisition->document_requisition) {
            $documentImporte = true;
            $statut = $dossier->requisition->statut ?? 'importée';
        } elseif ($dossier->jugement && $dossier->jugement->document_jugement) {
            $documentImporte = true;
            $statut = $dossier->jugement->statut ?? 'importée';
        }

        return [
            'id' => $dossier->getKey(),
            'module' => $module,
            'identite' => $identite,
            'type_declaration' => $type_declaration,
            'created_at' => $dossier->created_at,
            'tribunal_approuver' => $dossier->tribunal_approuver,
            'requisition' => $dossier->requisition ?? null,
            'jugement' => $dossier->jugement ?? null,
            'dernierMouvement' => $dernierMouvement,
            'documentImporte' => $documentImporte,
            'statut' => $statut,
        ];
    }

    public function show($type, $id)
    {
        if ($type === 'naissance') {
            $declaration = Declarationnaissance::findOrFail($id);
        } elseif ($type === 'deces') {
            $declaration = DeclarationDeces::findOrFail($id);
        } else {
            toastr()->error('Type de déclaration inconnu');
            return redirect()->back();
        }

        // dd($type);

        // Exemple : message de succès si besoin
        // toastr()->success('Déclaration chargée avec succès !');

        return view('tribunal::documents.show', compact('declaration', 'type'));
    }

    /**
     * Affiche le certificat PDF venant du centre d'état civil (naissance ou décès)
     */
    public function voirCertificat($type, $id)
    {
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');

        if ($type === 'naissance') {
            $certificat = Declarationnaissance::findOrFail($id);

            //vérifier si le certificat est un certificat de destruction de l'acte
            if($certificat->type_declaration === "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                $html2pdf->writeHTML(view('naissance::etats.certificat_destruction', compact( 'certificat'))->render());
                return $html2pdf->output($certificat->code_declaration_naissance . '.pdf');
            }

            $html2pdf->writeHTML(view('naissance::etats.certificat_non_inscription', compact( 'certificat'))->render());
            return $html2pdf->output($certificat->code_declaration_naissance . '.pdf');
        } elseif ($type === 'deces') {
            $certificat = DeclarationDeces::findOrFail($id);
            $html2pdf->writeHTML(view('deces::etats.certificats.certificat_non_inscription', compact('certificat'))->render());
            return $html2pdf->output($certificat->code_declaration_deces . '.pdf');
        }elseif("mariage"){
            $dm = DeclarationMariage::findOrFail($id);
            $html2pdf->writeHTML(view('mariage::etats.DeclarationMariage', compact('dm'))->render());
            return $html2pdf->output($dm->code_declaration_deces . '.pdf');
        } else {
            abort(404);
        }
    }

    /**
     * Télécharge le document importé (réquisition ou jugement)
     */
    public function voirDocument($type, $id)
    {
        if ($type === 'naissance') {
            $declaration = \Modules\Naissance\Entities\Declarationnaissance::findOrFail($id);
        } elseif ($type === 'mariage') {
            $declaration = \Modules\Mariage\Entities\DeclarationMariage::findOrFail($id);
        } elseif ($type === 'deces') {
            $declaration = \Modules\Deces\Entities\DeclarationDeces::findOrFail($id);
        } else {
            abort(404);
        }

        if ($declaration->requisition && $declaration->requisition->document_requisition) {
            return response()->download(public_path($declaration->requisition->document_requisition));
        }
        if ($declaration->jugement && $declaration->jugement->document_jugement) {
            return response()->download(public_path($declaration->jugement->document_jugement));
        }
        abort(404);
    }

    //affiche le détail d'un certificat
    public function detailCertificat($type, $id)
    {
        if ($type === 'naissance') {
            $certificat = Declarationnaissance::findOrFail($id);
            if($certificat->type_declaration === "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                return view('naissance::certificat-destruction.show', compact('certificat'));
            }
            if($certificat->type_declaration === "CERTIFICAT DE NON INSCRIPTION"){
                return view('naissance::certificat-non-inscription.show', compact('certificat'));
            }
            if($certificat->type_declaration === "FICHE DE TRANSCRIPTION"){
                return view('naissance::certificat-destruction.show', compact('certificat'));
            }

        }elseif($type === 'mariage'){
            $declaration  = DeclarationMariage::findOrFail($id);
            if($declaration ->type_declaration === "DISPENSE"){
                return view('mariage::declaration.show', compact('declaration'));
            }
            return view('mariage::declaration.show', compact('declaration'));
        }elseif($type === 'deces'){
            $certificat = DeclarationDeces::findOrFail($id);
            if($certificat->type_declaration === "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                return "en cours de traitement";
                //return view('deces::certificat-destruction.show', compact('certificat'));
            }
            if($certificat->type_declaration === "CERTIFICAT DE NON INSCRIPTION"){
                return "en cours de traitement";
                //return view('deces::certificat-destruction.show', compact('certificat'));
            }

            return view('deces::certificat-non-inscription.show', compact('certificat'));
        }else{
            toastr()->error("Impossible de charger cette page");
            return back();
        }
    }

    /**
     * Renvoie le certificat/document au centre d'état civil (après correction, refus, etc.)
     */
    public function renvoyerCertificat(Request $request, TribunalDeclarationService $service)
    {

        $id = $request->input('id');
        $module = $request->input('module');
        $observation = $request->input('observation') ?? "Le dossier du certificat de $module a été renvoyé à votre institution.";
        if ($module === 'naissance') {
            $declaration = \Modules\Naissance\Entities\Declarationnaissance::findOrFail($id);
        } elseif ($module === 'deces') {
            $declaration = \Modules\Deces\Entities\DeclarationDeces::findOrFail($id);
        }
         elseif ($module === 'mariage') {
            $declaration = \Modules\Mariage\Entities\DeclarationMariage::findOrFail($id);
        } else {
            return response()->json(['code' => '400', 'message' => 'Module inconnu']);
        }
        $user = Auth::user();
        [$ok, $result] = $service->renvoyerCertificat($declaration, $user, $observation);
        if (!$ok) {
            return response()->json(['code' => '400', 'message' => $result]);
        }
        return response()->json(['code' => '200', 'message' => 'Certificat renvoyé au centre d\'état civil avec succès']);
    }

    /**
     * Action AJAX : envoi officiel du dossier traité au centre d'état civil (après import)
     */
    public function envoyerOfficiel(Request $request, TribunalDeclarationService $service)
    {
        $id = $request->input('id');
        $module = $request->input('module');
        $typeDocument = $request->input('type_document');
        if ($module === 'naissance') {
            $declaration = Declarationnaissance::findOrFail($id);
        } elseif ($module === 'deces') {
            $declaration = DeclarationDeces::findOrFail($id);
        } elseif ($module === 'mariage') {
            $declaration = DeclarationMariage::findOrFail($id);
        }

        else {
            return response()->json(['code' => '400', 'message' => 'Module inconnu']);
        }

        $user = Auth::user();
        [$ok, $result] = $service->envoyerOfficiel($declaration, $user, $typeDocument);

        if (!$ok) {
            return response()->json(['code' => '400', 'message' => $result]);
        }

        return response()->json(['code' => '200', 'message' => $result]);
    }

    /**
     * Récupère le nom de la réquisition pour l'affichage dans le modal
     */
    public function getNomRequisition(Request $request)
    {
        $id = $request->input('id');
        $module = $request->input('module');

        try {
            if ($module === 'naissance') {
                $declaration = Declarationnaissance::findOrFail($id);
            } elseif ($module === 'mariage') {
                $declaration = DeclarationMariage::findOrFail($id);
            } elseif ($module === 'deces') {
                $declaration = DeclarationDeces::findOrFail($id);
            } else {
                return response()->json(['success' => false, 'message' => 'Module inconnu']);
            }

            if ($declaration->requisition) {
                $nomDocument = "Réquisition N° " . $declaration->requisition->num_requisition;
                if ($declaration->requisition->typeRequisition) {
                    $nomDocument .= " - " . $declaration->requisition->typeRequisition->lib_type_requisition;
                }
                return response()->json([
                    'success' => true,
                    'nom_document' => $nomDocument,
                    'type_document' => $declaration->requisition->typeRequisition ? $declaration->requisition->typeRequisition->lib_type_requisition : 'Réquisition'
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Aucune réquisition trouvée']);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la récupération']);
        }
    }

    /**
     * Récupère le nom du jugement pour l'affichage dans le modal
     */
    public function getNomJugement(Request $request)
    {
        $id = $request->input('id');
        $module = $request->input('module');

        try {
            if ($module === 'naissance') {
                $declaration = Declarationnaissance::findOrFail($id);
            } elseif ($module === 'mariage') {
                $declaration = DeclarationMariage::findOrFail($id);
            } elseif ($module === 'deces') {
                $declaration = DeclarationDeces::findOrFail($id);
            } else {
                return response()->json(['success' => false, 'message' => 'Module inconnu']);
            }

            if ($declaration->jugement) {
                $nomDocument = "Jugement N° " . $declaration->jugement->num_jugement;
                if ($declaration->jugement->typeJugement) {
                    $nomDocument .= " - " . $declaration->jugement->typeJugement->lib_type_jugement;
                }
                return response()->json([
                    'success' => true,
                    'nom_document' => $nomDocument,
                    'type_document' => $declaration->jugement->typeJugement ? $declaration->jugement->typeJugement->lib_type_jugement : 'Jugement'
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Aucun jugement trouvé']);
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la récupération']);
        }
    }

     /**
     * Confirme un document (déclaration ou acte) pour un module donné
     * @param \Illuminate\Http\Request $request
     *   - code_document : l'ID de la déclaration ou de l'acte
     *   - module : naissance|mariage|deces
     *   - mode : 'declaration' (par défaut) ou 'acte'
     *   - observation : (optionnel)
     */
    public function confirmerDocument(Request $request)
    {

        $request->validate([
            'code_document' => 'required',
            'module' => 'required|in:naissance,mariage,deces',
            'mode' => 'nullable|in:declaration,acte',
            // 'observation' => 'nullable|string',
        ]);

        $mode = $request->input('mode', 'declaration');
        $module = $request->input('module');
        $id = $request->input('code_document');
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $observation = $request->observation;
        $statut= "Confirmée";

        // Récupération de l'objet selon le module et le mode
        if ($module === 'naissance') {
            $objet = $mode === 'acte'
                ? \Modules\Naissance\Entities\ActeNaissance::findByIdentifier($id)
                :  \Modules\Naissance\Entities\Declarationnaissance::find($id);
            if(!$objet){
                return response()->json([
                    'code' => '404',
                    'message' => ['Document non trouvé.']
                ]);
            }

            [$ok, $result] = app(\Modules\Naissance\Services\MouvementService::class)->confirmerDeclarationNaissance(
                $affectation,
                $objet,
                $statut,
                $observation
            );

            if (!$ok) {
                return response()->json([
                    'code' => '400',
                    'message' => $result
                ]);
            }
        } elseif ($module === 'mariage') {
            $objet = $mode === 'acte'
                ? \Modules\Mariage\Entities\ActeMariage::find($id)
                : \Modules\Mariage\Entities\DeclarationMariage::find($id);

                if(!$objet){
                    return response()->json([
                        'code' => '404',
                        'message' => ['Document non trouvé.']
                    ]);
                }
                [$ok, $result] = app(\Modules\Mariage\Services\MouvementMariageService::class)->confirmerDeclaration(
                    $affectation,
                    $objet,
                    $statut,
                    $observation
                );

                if (!$ok) {
                    return response()->json([
                        'code' => '400',
                        'message' => $result
                    ]);
                }
        } elseif ($module === 'deces') {
            $objet = $mode === 'acte'
                ? \Modules\Deces\Entities\ActeDeces::find($id)
                : \Modules\Deces\Entities\DeclarationDeces::find($id);

            if (!$objet) {
                return response()->json([
                    'code' => '404',
                    'message' => ['Document non trouvé.']
                ]);
            }

            [$ok, $result] = app(\Modules\Deces\Services\MouvementService::class)->confirmerDeclarationDeces(
                $affectation,
                $objet,
                $statut,
                $observation
            );

            if (!$ok) {
                return response()->json([
                    'code' => '400',
                    'message' => $result
                ]);
            }
        } else {
            return response()->json([
                'code' => '400',
                'message' => ['Module inconnu.']
            ]);
        }

        return response()->json([
            'code' => '200',
            'message' => ['Document confirmé avec succès.']
        ]);
    }


    //ajouter les nouvelles methodes ici

    /**
     * Affiche l'historique des imports de documents
     */
    public function historique(Request $request)
    {
        // À compléter : charger l'historique réel
        return view('tribunal::documents.historique');
    }

    /**
     * Affiche la liste des documents envoyés
     */
    public function envoyes(Request $request)
    {
        // À compléter : charger les documents envoyés
        return view('tribunal::documents.envoyes');
    }

    /**
     * Affiche les statistiques du tribunal
     */
    public function statistiques(Request $request)
    {
        // À compléter : charger les statistiques
        return view('tribunal::documents.statistiques');
    }

    /**
     * Génère et affiche le PDF du certificat de non inscription (pour l'iframe)
     */
    public function certificatPdf(Request $request, $id)
    {

        try {
            $module = $request->input('module');
            //rechercher la déclaration selon le module et le type de déclaration
            if($module === 'naissance'){
                $certificat = Declarationnaissance::findOrFail($id);
                if($certificat->type_declaration === "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
                    $html2pdf->setDefaultFont('Arial');
                    $html2pdf->writeHTML(view('naissance::etats.certificat_destruction', compact('certificat'))->render());
                    return $html2pdf->output($certificat->code_declaration_naissance . '.pdf');
                }
                if($certificat->type_declaration === "FICHE DE TRANSCRIPTION" || $certificat->type_declaration === "CERTIFICAT DE TRANSCRIPTION"){
                    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
                    $html2pdf->setDefaultFont('Arial');
                    $html2pdf->writeHTML(view('naissance::etats.certificat_de_transcription', compact('certificat'))->render());
                    return $html2pdf->output($certificat->code_declaration_naissance . '.pdf');
                }
                if($certificat->type_declaration === "CERTIFICAT DE NON INSCRIPTION"){
                    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
                    $html2pdf->setDefaultFont('Arial');
                    $html2pdf->writeHTML(view('naissance::etats.certificat_non_inscription', compact('certificat'))->render());
                    return $html2pdf->output($certificat->code_declaration_naissance . '.pdf');
                }

                abort(404, 'Aucun modèle PDF pour ce type de déclaration naissance.');

            }elseif($module === 'mariage'){
                $dm = DeclarationMariage::findOrFail($id);

                if($dm->type_declaration === "DISPENSE"){
                    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
                    $html2pdf->setDefaultFont('Arial');
                    $html2pdf->writeHTML(view('mariage::etats.DeclarationMariage', compact('dm'))->render());
                    return $html2pdf->output($dm->code_declaration_mariage . '.pdf');
                }




            }elseif($module === 'deces'){
                $certificat = DeclarationDeces::findOrFail($id);
                if($certificat->type_declaration === "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
                    $html2pdf->setDefaultFont('Arial');
                    $html2pdf->writeHTML(view('deces::etats.certificat_destruction', compact('certificat'))->render());
                    return $html2pdf->output($certificat->code_declaration_deces . '.pdf');
                }
                $dateDeceEnfant = Carbon::create($certificat->date_heure_deces);
                $dateNow = Carbon::now();
                $ageDeceEnfant = $dateNow->diffInYears($dateDeceEnfant);
                $html2pdf = new Html2Pdf('P', 'A4', 'fr');
                $html2pdf->setDefaultFont('Arial');
                $html2pdf->writeHTML(view('deces::etats.certificats.certificat_non_inscription', compact('certificat', 'ageDeceEnfant'))->render());
                return $html2pdf->output($certificat->code_declaration_deces . '.pdf');
            }else{
                toastr()->error('Type de déclaration inconnu');
                return back();
            }



            // $dateNaissEnfant = Carbon::create($certificat->enfant->date_naissance);
            // $dateNow = Carbon::now();
            // $ageEnfant = $dateNow->diffInYears($dateNaissEnfant);
            // $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            // $html2pdf->setDefaultFont('Arial');
            // $html2pdf->writeHTML(view('naissance::etats.certificat_non_inscription', compact('certificat', 'ageEnfant'))->render());
            // return $html2pdf->output($certificat->code_declaration_naissance . '.pdf');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response('Erreur lors de la génération du PDF', 500);
        }
    }

    /**
     * Affiche le formulaire d'import d'un document du tribunal à partir d'un code de déclaration ou d'acte
     * @param string $type (naissance|mariage|deces)
     * @param string $code (code de déclaration ou code d'acte)
     * @param string $mode ('declaration' ou 'acte')
     */
    public function importDocumentTribunal($type, $code)
    {

        // Récupération de la déclaration ou de l'acte selon le mode
        if ($type === 'naissance') {
            $objet = \Modules\Naissance\Entities\Declarationnaissance::find($code);
        } elseif ($type === 'mariage') {
            $objet = \Modules\Mariage\Entities\DeclarationMariage::find($code);
        } elseif ($type === 'deces') {
            $objet = \Modules\Deces\Entities\DeclarationDeces::find($code);
        } else {
            toastr()->error('Type inconnu');
            return back();
        }

        if (!$objet) {
            toastr()->error('Aucun enregistrement trouvé pour ce code');
            return back();
        }

        $typeRequisitions = \App\Models\TypeRequisition::all();
        $typeJugements = \App\Models\TypeJugement::all();

        return view('tribunal::documents.importer', [
            'objet' => $objet,
            'type' => $type,
            'mode' => 'declaration',
            'typeRequisitions' => $typeRequisitions,
            'typeJugements' => $typeJugements,
        ]);
    }


}
