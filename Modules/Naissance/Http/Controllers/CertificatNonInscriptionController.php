<?php

namespace Modules\Naissance\Http\Controllers;

use App\Sifec\Sifec;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Naissance\Services\MouvementService;
use Modules\Notification\Notifications\DeclarationEnvoyeeCentreNotification;
use Modules\Notification\Services\NotificationService;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Filiation;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\LieuSurvenance;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Mouvement;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\Profession;
use Modules\Referentiel\Entities\SituationMatrimoniale;
use Modules\Referentiel\Entities\TypeDocument;
use Spipu\Html2Pdf\Html2Pdf;

class CertificatNonInscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $affectationActive = (is_object($user) && method_exists($user, 'affectationActive')) ? $user->affectationActive() : null;
        $certificats = collect();
        $institution = $affectationActive ? $affectationActive->institution : null;
        if (! $institution || $institution->code_type_institution !== 'TPINS_0002') {
            flash()->error("Accès réservé aux agents du centre d'état civil.");

            return back();
        }
        $certificats = Declarationnaissance::where('type_declaration', [], 'CERTIFICAT DE NON INSCRIPTION')
            ->where('code_user_institution', $affectationActive ? $affectationActive->cui : null)
            ->get();

        return view('naissance::certificat-non-inscription.index', compact('certificats'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $dateNaissance = $request->date_naissance_enfant;
        $dateNaissanceConvertis = Carbon::create($dateNaissance);
        $date = date('Y-m-d');
        $dateNaissanceNow = Carbon::create($date);
        $ageEnfant = $dateNaissanceConvertis->diffInYears($dateNaissanceNow);

        $nbreJourNaissance = $dateNaissanceConvertis->diffInDays($dateNaissanceNow);

        $title = '';
        $type_declaration = '';

        if ($nbreJourNaissance < 30) {
            $title = 'Créer une déclaration de naissance';
            $type_declaration = 'DECLARATION DE NAISSANCE';
        }

        if ($nbreJourNaissance > 30) {
            $title = 'Créer un certificat de non inscription';
            $type_declaration = 'CERTIFICAT DE NON INSCRIPTION';
        }

        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite', 'TPLOC_0002')->Orwhere('code_type_localite', 'TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $lieuSurvenances = LieuSurvenance::whereNotIn('lib_lieu_survenance', ['Avion', 'Navire', 'Etranger'])->get();
        $filiations = Filiation::all();
        $typedocuments = TypeDocument::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $communes = Localite::where('code_type_localite', 'TPLOC_0003')->Orwhere('code_type_localite', 'TPLOC_0002')->get();
        $arrondissements = Localite::where('code_type_localite', 'TPLOC_0004')->Orwhere('code_type_localite', 'TPLOC_0005')->get();
        $quartiers = Localite::where('code_type_localite', 'TPLOC_0007')->Orwhere('code_type_localite', 'TPLOC_0008')->get();
        $countries = collect(json_decode(file_get_contents(public_path('codes_pays.json'))));
        $departements = Departement::all();

        return view('naissance::declaration.create', compact('title', 'dateNaissance', 'departements', 'countries', 'communes', 'arrondissements',
            'typedocuments', 'instructions', 'filiations', 'localites', 'professions',
            'nationalites', 'situationMatrimoniales', 'lieuSurvenances', 'quartiers',
            'type_declaration', 'ageEnfant'));
    }

    public function etat($id)
    {
        // $duplicata = Duplicata::find($id);
        $certificat = Declarationnaissance::find($id);

        $dateNaissEnfant = Carbon::create($certificat->enfant->date_naissance);
        $dateNow = Carbon::create(date('Y-m-y'));
        $ageEnfant = $dateNow->diffInYears($dateNaissEnfant);

        if ($certificat == null) {
            flash()->error('Certificat indisponible');

            return back();
        }

        DB::beginTransaction();

        try {
            view()->share('tester', [], 'Vincent');
            $html2pdf = new Html2Pdf('P', 'A4', 'fr');
            $html2pdf->setDefaultFont('Arial');
            $verificationUrl = URL::signedRoute('verification.declaration', ['code' => $certificat->code_declaration_naissance]);
            $qrCode = $verificationUrl;
            $html2pdf->writeHTML(view('naissance::etats.certificat_non_inscription', compact('certificat', 'ageEnfant', 'qrCode'))->render());
            DB::commit();

            return $html2pdf->output($certificat->code_certif_dest.'.pdf');

        } catch (Exception $e) {
            DB::rollBack();
            flash()->error($e->getMessage());

            return back();
        }
    }

    public function show($id)
    {
        $certificat = Declarationnaissance::with(Declarationnaissance::relationsPourEagerLoadCertificatDetail())->findOrFail($id);

        return view('naissance::certificat-non-inscription.show', compact('certificat'));
    }

    public function envoyerAuTribunal(Request $request, MouvementService $mouvementService, NotificationService $notificationService)
    {
        $certificat = Declarationnaissance::findOrFail($request->code_declaration_naissance);
        $user = Auth::user();
        $tribunal = $certificat->institution->institutionParent;
        $codeMouvement = 'MOUV_0006'; // Code mouvement pour certificat de non inscription
        $statut = 'Envoyée';
        $observation = $request->observation ?? null;

        DB::beginTransaction();
        try {
            // Utilise la méthode générique pour l'envoi
            [$success, $message] = $mouvementService->envoyerDeclaration(
                $user,
                $certificat,
                $codeMouvement,
                $statut,
                $observation
            );
            if (! $success) {
                DB::rollBack();

                return response()->json([
                    'code' => '90',
                    'message' => $message,
                ]);
            }

            // mise à jour de confirmation du dossier par le centre d'état civil
            $certificat->cec_approuver = 'OUI';
            $certificat->cec_approuve_par = $user->affectationActive()->cui;
            $certificat->save();

            // recuperer le code_institution_destinataire pour la notification de l'envoi du certificat
            $codeInstitutionDestinataire = $certificat->code_institution_destinataire;
            $institutionDestinataire = Institution::find($codeInstitutionDestinataire);

            // Utilisation du NotificationService pour notifier tous les agents du tribunal
            try {
                $notificationService->notifierAgentsInstitution(
                    $institutionDestinataire,
                    new DeclarationEnvoyeeCentreNotification(
                        $certificat,
                        $institutionDestinataire,
                        'envoyée', [], 'Un certificat de non inscription a été envoyé à votre institution.')
                );
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel('sifec')->info($e->getMessage());

                return response()->json([
                    'code' => '90',
                    'message' => 'Erreur lors de la notification aux agents du tribunal : '.$e->getMessage(),
                ]);
            }

            DB::commit();

            return response()->json([
                'code' => '200',
                'message' => 'Certificat envoyé au tribunal et notification envoyée aux agents du tribunal.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->info($e->getMessage());

            return response()->json([
                'code' => '90',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function ajouterPiece(Request $request, $id)
    {
        $request->validate([
            'piece_declarant' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'piece_pere' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'piece_mere' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);
        $certificat = Declarationnaissance::findOrFail($id);
        $uploadPath = public_path('app/pieces');
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $hasPiece = false;
        // Pièce du déclarant
        if ($request->hasFile('piece_declarant') && $request->file('piece_declarant')->isValid()) {
            // Supprimer l'ancienne pièce si elle existe
            $oldPath = $certificat->piece_declarant;
            if ($oldPath && file_exists(public_path($oldPath))) {
                @unlink(public_path($oldPath));
            }
            $imageName = $certificat->code_declarant.'_declarant.'.$request->file('piece_declarant')->extension();
            $request->file('piece_declarant')->move($uploadPath, $imageName);
            $certificat->piece_declarant = 'app/pieces/'.$imageName;
            $hasPiece = true;
        }
        // Pièce du père
        if ($request->hasFile('piece_pere') && $request->file('piece_pere')->isValid()) {
            $oldPath = $certificat->piece_pere;
            if ($oldPath && file_exists(public_path($oldPath))) {
                @unlink(public_path($oldPath));
            }
            $imageName = $certificat->code_declarant.'_pere.'.$request->file('piece_pere')->extension();
            $request->file('piece_pere')->move($uploadPath, $imageName);
            $certificat->piece_pere = 'app/pieces/'.$imageName;
            $hasPiece = true;
        }
        // Pièce de la mère
        if ($request->hasFile('piece_mere') && $request->file('piece_mere')->isValid()) {
            $oldPath = $certificat->piece_mere;
            if ($oldPath && file_exists(public_path($oldPath))) {
                @unlink(public_path($oldPath));
            }
            $imageName = $certificat->code_declarant.'_mere.'.$request->file('piece_mere')->extension();
            $request->file('piece_mere')->move($uploadPath, $imageName);
            $certificat->piece_mere = 'app/pieces/'.$imageName;
            $hasPiece = true;
        }
        if ($hasPiece) {
            $certificat->save();
            flash()->success('Pièce(s) ajoutée(s) avec succès.');
        } else {
            flash()->error('Aucune pièce valide n\'a été ajoutée.');
        }

        return redirect()->back();
    }
}
