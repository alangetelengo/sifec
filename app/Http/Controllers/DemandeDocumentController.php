<?php

namespace App\Http\Controllers;

use App\Services\DemandeDocumentService;
use App\Services\OtpDemandeDocumentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Modules\Mobile\Entities\DemandeDocument;
use Modules\Mobile\Entities\TypeActe;
use Modules\Mobile\Entities\TypeDocumentDemande;
use Modules\Naissance\Entities\ActeNaissance;

class DemandeDocumentController extends Controller
{
    protected $demandeService;

    protected $otpService;

    public function __construct(DemandeDocumentService $demandeService, OtpDemandeDocumentService $otpService)
    {
        $this->demandeService = $demandeService;
        $this->otpService = $otpService;
    }

    /**
     * Liste des demandes avec filtres et onglets portail/sur site
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $affectation = $user->affectationActive();
        $institution = $affectation->institution;

        $query = DemandeDocument::with(['typeActe', 'typeDocumentDemande', 'institution', 'signataire.user.personne'])
            ->where('code_institution', $institution->code_institution);

        // Filtre par origine (onglets)
        $origine = $request->get('origine', 'tous');
        if ($origine !== 'tous') {
            $query->where('origine_demande', $origine);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par type document
        if ($request->filled('type_document')) {
            $query->where('code_type_document_demande', $request->type_document);
        }

        // Filtre par type acte
        if ($request->filled('type_acte')) {
            $query->where('code_type_acte', $request->type_acte);
        }

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->whereDate('date_demande', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_demande', '<=', $request->date_fin);
        }

        // Recherche par numéro acte ou nom demandeur
        if ($request->filled('recherche')) {
            $recherche = $request->recherche;
            $query->where(function ($q) use ($recherche) {
                $q->where('numero_acte', 'like', "%{$recherche}%")
                    ->orWhere('nom_demandeur', 'like', "%{$recherche}%")
                    ->orWhere('code_demande_document', 'like', "%{$recherche}%");
            });
        }

        $demandes = $query->orderBy('date_demande', 'desc')->paginate(50);

        // Données pour les filtres
        $typesDocuments = TypeDocumentDemande::all();
        $typesActes = TypeActe::all();
        $statuts = [
            'En attente de paiement',
            'En traitement',
            'En attente de signature',
            'Traitée',
            'Livrée',
            'Rejetée',
            'Expirée',
        ];

        // Statistiques
        $stats = [
            'en_traitement' => DemandeDocument::where('code_institution', $institution->code_institution)
                ->where('statut', 'En traitement')->count(),
            'en_attente_signature' => DemandeDocument::where('code_institution', $institution->code_institution)
                ->where('statut', 'En attente de signature')->count(),
            'traitees_aujourdhui' => DemandeDocument::where('code_institution', $institution->code_institution)
                ->where('statut', 'Traitée')
                ->whereDate('date_signature', today())->count(),
            'expirees' => DemandeDocument::where('code_institution', $institution->code_institution)
                ->where('statut', 'Expirée')->count(),
        ];

        return view('demande-document.index', compact(
            'demandes',
            'typesDocuments',
            'typesActes',
            'statuts',
            'origine',
            'stats'
        ));
    }

    /**
     * Formulaire de création d'une demande sur site
     */
    public function create()
    {
        $typesDocuments = TypeDocumentDemande::all();
        $typesActes = TypeActe::all();

        return view('demande-document.create', compact('typesDocuments', 'typesActes'));
    }

    /**
     * Enregistrer une nouvelle demande sur site
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_acte' => 'required|string',
            'code_type_acte' => 'required|exists:tr_type_acte,code_type_acte',
            'code_type_document_demande' => 'required|exists:tr_type_document_demande,code_type_document_demande',
            'nom_demandeur' => 'required|string|max:75',
            'prenom_demandeur' => 'nullable|string|max:75',
            'sexe_demandeur' => 'required|in:M,F',
            'telephone_demandeur' => 'required|string|max:13',
            'email_demandeur' => 'nullable|email|max:70',
            'observations' => 'nullable|string',
        ]);

        // Forcer la casse selon les standards SIFEC
        $validated['nom_demandeur'] = strtoupper($validated['nom_demandeur']);
        if (! empty($validated['prenom_demandeur'])) {
            $validated['prenom_demandeur'] = ucwords(strtolower($validated['prenom_demandeur']));
        }

        try {
            // Vérifier que l'acte existe
            $acte = $this->demandeService->rechercherActe(
                $validated['code_type_acte'],
                $validated['numero_acte']
            );

            if (! $acte) {
                flash()->error("L'acte spécifié n'a pas été trouvé.");

                return back()->withInput();
            }

            $demande = $this->demandeService->creerDemandeSurSite($validated, Auth::user());

            flash()->success("Demande créée avec succès. Code: {$demande->code_demande_document}");

            return redirect()->route('demandeDocument.show', $demande->code_demande_document);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur création demande', ['error' => $e->getMessage()]);
            flash()->error('Erreur lors de la création de la demande.');

            return back()->withInput();
        }
    }

    /**
     * Détail d'une demande
     */
    public function show($code)
    {
        $demande = DemandeDocument::with([
            'typeActe',
            'typeDocumentDemande',
            'institution',
            'institutionUser.user.personne',
            'signataire.user.personne',
        ])->where('code_demande_document', $code)->firstOrFail();

        // Récupérer l'acte concerné
        $acte = $demande->getActeConcerne();

        return view('demande-document.show', compact('demande', 'acte'));
    }

    /**
     * Générer le PDF et passer en attente de signature
     */
    public function passerEnAttenteSignature($code)
    {
        Log::channel('sifec')->info('Début génération PDF demande', [
            'code' => $code,
            'user_id' => auth()->id(),
        ]);

        $demande = DemandeDocument::findOrFail($code);

        Log::channel('sifec')->info('Demande trouvée', [
            'code' => $code,
            'statut' => $demande->statut,
            'peut_etre_generee' => $demande->peutEtreGeneree(),
        ]);

        try {
            // Vérifier que la demande peut être générée
            if (! $demande->peutEtreGeneree()) {
                Log::channel('sifec')->warning('Demande ne peut pas être générée', [
                    'code' => $code,
                    'statut' => $demande->statut,
                ]);

                $message = "La demande doit être en statut 'En traitement' pour générer le PDF.";

                if (request()->ajax() || request()->wantsJson()) {
                    return response()->json(['message' => $message], 400);
                }

                flash()->error($message);

                return back();
            }

            // Générer le PDF
            Log::channel('sifec')->info('Appel génération PDF');
            $cheminPdf = $this->demandeService->genererDocumentPDF($demande);

            Log::channel('sifec')->info('PDF généré', [
                'chemin' => $cheminPdf,
            ]);

            // Passer en attente de signature
            $this->demandeService->passerEnAttenteSignature($demande);

            Log::channel('sifec')->info('Demande passée en attente signature', [
                'code' => $code,
            ]);

            $message = 'Document généré avec succès. En attente de signature.';

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'code_demande' => $code,
                    'statut' => $demande->statut,
                ], 200);
            }

            flash()->success($message);

            return redirect()->route('demandeDocument.show', $code);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur génération PDF demande', [
                'code' => $code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $message = 'Erreur lors de la génération du document: '.$e->getMessage();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['message' => $message], 500);
            }

            flash()->error($message);

            return back();
        }
    }

    /**
     * Initier la signature (génération OTP)
     */
    public function initierSignature(Request $request)
    {
        $codesDemandes = $request->input('demandes', []);

        if (empty($codesDemandes)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune demande sélectionnée',
            ]);
        }

        // Récupérer les demandes et vérifier les permissions
        $demandes = DemandeDocument::whereIn('code_demande_document', $codesDemandes)->get();

        $permissionsManquantes = [];
        foreach ($demandes as $demande) {
            $permission = $demande->getPermissionSignature();
            if (! Gate::allows($permission)) {
                $permissionsManquantes[] = $demande->getLibelleTypeDocument().' de '.$demande->getLibelleTypeActe();
            }
        }

        if (! empty($permissionsManquantes)) {
            Log::channel('sifec')->warning('Tentative signature sans permissions suffisantes', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'permissions_manquantes' => $permissionsManquantes,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas les droits pour signer : '.implode(', ', $permissionsManquantes),
            ], 403);
        }

        Log::channel('sifec')->info('Initier signature demandes', [
            'user_id' => auth()->id(),
            'nb_demandes' => count($codesDemandes),
        ]);

        [$success, $message, $otp] = $this->otpService->genererOtp($codesDemandes);

        return response()->json([
            'success' => $success,
            'message' => $message,
            'otp' => config('app.debug') ? $otp : null,
        ]);
    }

    /**
     * Valider la signature avec OTP
     */
    public function validerSignature(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        Log::channel('sifec')->info('Validation signature OTP', [
            'user_id' => auth()->id(),
            'ip' => $ipAddress,
        ]);

        [$success, $message] = $this->otpService->validerOtpEtSigner(
            $request->otp,
            $ipAddress,
            $userAgent
        );

        if ($success) {
            flash()->success($message);

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('demandeDocument.index'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $message,
        ]);
    }

    /**
     * Rejeter une demande
     */
    public function rejeter(Request $request, $code)
    {
        $request->validate([
            'motif' => 'required|string|min:10',
        ]);

        $demande = DemandeDocument::findOrFail($code);

        try {
            $this->demandeService->rejeterDemande($demande, $request->motif);
            flash()->success('Demande rejetée.');

            return redirect()->route('demandeDocument.show', $code);
        } catch (Exception $e) {
            flash()->error('Erreur lors du rejet de la demande.');

            return back();
        }
    }

    /**
     * Marquer comme livrée
     */
    /**
     * Remettre une demande expirée dans le circuit (génération PDF + signature).
     */
    public function preparerRenouvellement($code)
    {
        Gate::authorize('module.demande_document');

        $demande = DemandeDocument::findOrFail($code);
        $user = Auth::user();
        $affectation = $user->affectationActive();
        if (! $affectation || $demande->code_institution !== $affectation->institution->code_institution) {
            abort(403, 'Accès non autorisé pour cette demande.');
        }

        try {
            $this->demandeService->preparerRenouvellementApresExpiration($demande);
            flash()->success('Demande remise en « En traitement ». Générez à nouveau le PDF puis procédez à la signature.');

            return redirect()->route('demandeDocument.show', $code);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Renouvellement demande document', [
                'code' => $code,
                'error' => $e->getMessage(),
            ]);
            flash()->error($e->getMessage());

            return back();
        }
    }

    public function marquerLivree($code)
    {
        $demande = DemandeDocument::findOrFail($code);

        try {
            $this->demandeService->marquerLivree($demande);

            $message = 'Demande marquée comme livrée.';

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'code_demande' => $code,
                    'statut' => $demande->statut,
                ], 200);
            }

            flash()->success($message);

            return redirect()->route('demandeDocument.show', $code);
        } catch (Exception $e) {
            $message = "Erreur: la demande doit être traitée avant d'être marquée livrée.";

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['message' => $message], 400);
            }

            flash()->error($message);

            return back();
        }
    }

    /**
     * Télécharger le PDF
     */
    public function telechargerPdf($code)
    {
        $demande = DemandeDocument::findOrFail($code);

        if (empty($demande->chemin_document) || ! file_exists($demande->chemin_document)) {
            flash()->error('Document PDF non disponible.');

            return back();
        }

        return response()->download($demande->chemin_document);
    }

    /**
     * Recherche AJAX d'un acte pour vérification
     */
    public function rechercherActe(Request $request)
    {
        try {
            $numeroActe = $request->input('numero_acte');
            $codeTypeActe = $request->input('code_type_acte');

            if (empty($numeroActe) || empty($codeTypeActe)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paramètres manquants (numéro d\'acte et type d\'acte requis)',
                ], 400);
            }

            $acte = $this->demandeService->rechercherActe($codeTypeActe, $numeroActe);

            if (! $acte) {
                Log::channel('sifec')->warning('Acte non trouvé lors de la recherche', [
                    'numero_acte' => $numeroActe,
                    'code_type_acte' => $codeTypeActe,
                    'user_id' => auth()->id(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Acte non trouvé pour le numéro : '.$numeroActe,
                ], 404);
            }

            // Extraire les informations selon le type d'acte
            $info = [];
            if ($acte instanceof ActeNaissance) {
                $info = [
                    'nom' => $acte->declaration->enfant->nom ?? '',
                    'prenom' => $acte->declaration->enfant->prenom ?? '',
                    'sexe' => $acte->declaration->enfant->sexe ?? '',
                    'date_naissance' => $acte->declaration->enfant->date_naissance ?? '',
                ];
            } elseif ($acte instanceof ActeMariage) {
                $info = [
                    'nom' => 'Acte de mariage',
                    'prenom' => '',
                    'date' => $acte->date_celebration ?? '',
                ];
            } elseif ($acte instanceof ActeDeces) {
                $info = [
                    'nom' => $acte->declaration->personne->nom ?? '',
                    'prenom' => $acte->declaration->personne->prenom ?? '',
                    'date' => $acte->declaration->date_deces ?? '',
                ];
            }

            Log::channel('sifec')->info('Acte trouvé avec succès', [
                'numero_acte' => $numeroActe,
                'type_acte' => $codeTypeActe,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'acte' => $info,
            ]);

        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur lors de la recherche d\'acte', [
                'numero_acte' => $request->input('numero_acte'),
                'code_type_acte' => $request->input('code_type_acte'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification de l\'acte. Veuillez réessayer.',
            ], 500);
        }
    }
}
