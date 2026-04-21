<?php

namespace Modules\Authentification\Http\Controllers;

use App\Mail\TwoFactorBulkMailable;
use App\Models\InstitutionUser;
use App\Models\User;
use App\Models\UserAuditTrail;
use App\Sifec\Sifec;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Modules\Authentification\Entities\Fonctionnalite;
use Modules\Authentification\Entities\Module;
use Modules\Referentiel\Entities\Commune;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\District;
use Modules\Referentiel\Entities\Document;
use Modules\Referentiel\Entities\Fonction;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Profession;
use Modules\Referentiel\Entities\SituationMatrimoniale;
use Modules\Referentiel\Entities\TypeDocument;
use Modules\Referentiel\Entities\TypeInstitution;
use PragmaRX\Google2FA\Google2FA;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereHas('personne');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('institution')) {
            $query->whereHas('affectations', function ($q) use ($request) {
                $q->where('active', 1)
                    ->whereHas('institution', function ($q2) use ($request) {
                        $q2->where('lib_institution', $request->institution);
                    });
            });
        }

        if ($request->filled('fonction')) {
            $query->whereHas('affectations', function ($q) use ($request) {
                $q->where('active', 1)
                    ->whereHas('fonction', function ($q2) use ($request) {
                        $q2->where('lib_fonction', $request->fonction);
                    });
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('email_professionnel', 'like', "%{$search}%")
                    ->orWhere('pseudo', 'like', "%{$search}%")
                    ->orWhereHas('personne', function ($q2) use ($search) {
                        $q2->where('nom', 'like', "%{$search}%")
                            ->orWhere('prenom', 'like', "%{$search}%");
                    });
            });
        }

        $perPage = $request->input('per_page', 15);
        $users = $query->with(['personne', 'affectations.institution', 'affectations.fonction'])
            ->paginate($perPage)
            ->appends($request->except('page'));

        // Pour les filtres
        $allUsers = User::whereHas('personne')->with(['affectations.institution', 'affectations.fonction'])->get();

        return view('authentification::utilisateur.index', compact('users', 'allUsers'));
    }

    public function create()
    {
        $situationMatrimoniales = SituationMatrimoniale::all();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $niveauInstructions = Sifec::niveauInstructions();
        $typeDocuments = TypeDocument::all();
        $fonctions = Fonction::all();
        $institutions = Institution::all();
        $typeInstitutions = TypeInstitution::all();
        $departements = Departement::all();
        $localites = Localite::where('code_type_localite', 'TPLOC_0002')->Orwhere('code_type_localite', 'TPLOC_0003')->get();

        return view('authentification::utilisateur.create', compact('professions', 'nationalites', 'situationMatrimoniales', 'niveauInstructions', 'typeDocuments', 'fonctions', 'institutions', 'typeInstitutions', 'departements', 'localites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string'],
            'sexe' => ['required', 'string'],
            'date_naissance' => ['required'],
            'code_nationalite' => ['required'],
            'adresse' => ['required'],
            'numero_document' => ['required', 'string'],
            'code_type_document' => ['required'],
            'code_fonction' => ['required'],
            'code_institution' => ['required'],
            'email' => ['required', 'email', 'max:255', Rule::unique('tr_user', 'email')],
            'email_professionnel' => ['nullable', 'email', 'max:255', Rule::unique('tr_user', 'email_professionnel')],
        ]);

        // dd($request->all());

        DB::beginTransaction();
        try {

            $lieu_naissance = Localite::find($request->code_localite)->lib_localite;

            $requestUniqueString = $request->nom.$request->prenom.$request->sexe.$request->date_naissance.$lieu_naissance;

            // dd($requestUniqueString);

            $personne = Personne::where('personne_string', $requestUniqueString)->first();

            if ($personne == null) {

                $personne = new Personne;
                $personne->code_personne = Sifec::genererCodeUniqueReferentiel($personne, 'code_personne', 8, 'PRS_');
                $personne->nom = $request->nom;
                $personne->prenom = $request->prenom;
                $personne->sexe = $request->sexe;
                $personne->date_naissance = Carbon::parse($request->date_naissance);
                $personne->niveau_instruction = $request->niveau_instruction;
                $personne->code_nationalite = $request->code_nationalite;
                $personne->lieu_naissance = Localite::find($request->code_localite)->lib_localite;
                $personne->code_localite = $request->code_localite;
                $personne->telephone = $request->pseudo;
                $personne->adresse = $request->adresse;
                $personne->personne_string = $requestUniqueString;
                $personne->save();

                $document = new Document;
                $document->code_document = Sifec::genererCodeUniqueReferentiel($document, 'code_document', 8, 'DOC_');
                $document->numero_document = $request->numero_document;
                $document->code_type_document = $request->code_type_document;
                $document->code_personne = $personne->code_personne;
                $document->save();
            }

            $existeCompte = User::where('code_personne', $personne->code_personne)->first();

            if ($existeCompte == null) {
                $user = new User;
                $user->code_user = Sifec::genererCodeUniqueReferentiel($user, 'code_user', 8, 'USR_');
                $user->pseudo = $request->pseudo;
                $user->email = $request->email;
                $user->email_professionnel = $request->filled('email_professionnel') ? $request->email_professionnel : null;
                $user->password = Hash::make('123456');
                $user->code_personne = $personne->code_personne;
                $user->status = 1;
                $user->must_change_password = true;
                $user->save();
            }

            // Vérification du poste libre dans cette institution
            $poste = InstitutionUser::where('code_fonction', $request->code_fonction)->where('code_institution', $request->code_institution)->first();

            // if($poste !=null){

            //     $libPoste = $poste->fonction->lib_fonction;

            //     if($poste->active == 1){
            //         flash()->error("Ce poste <strong> $libPoste </strong> est déjà occupé pour cette institution !", [], "Gestion d'utilisateur");
            //         return back()->withInput();
            //      }
            // }

            $insUser = new InstitutionUser;
            $insUser->cui = Sifec::genererCodeUniqueReferentiel($insUser, 'cui', 8, 'CUI_');
            $insUser->code_institution = $request->code_institution;
            $insUser->code_user = $user->code_user;
            $insUser->code_fonction = $request->code_fonction;
            $insUser->active = 1;
            $insUser->save();

            DB::commit();

            flash()->success('Utilisateur crée avec succès');

            return redirect()->route('utilisateur.index');

        } catch (Exception $e) {
            Log::channel('sifec')->error($e->getMessage());
            DB::rollBack();
            flash()->error($e->getMessage());

            return back()->withInput();
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
        return view('authentification::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {

        $user = User::query()
            ->with([
                'personne',
                'affectations' => static function ($q) {
                    $q->with(['fonction', 'institution']);
                },
            ])
            ->find($id);

        if ($user == null) {
            flash()->error("Impossible d'effectuer cette opération", [], 'Gestion des modules');

            return back();
        }

        $situationMatrimoniales = SituationMatrimoniale::all();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $niveauInstructions = Sifec::niveauInstructions();
        $typeDocuments = TypeDocument::all();
        $fonctions = Fonction::all();
        $institutions = Institution::all();
        $typeInstitutions = TypeInstitution::all();

        return view('authentification::utilisateur.edit', compact('user', 'professions', 'nationalites', 'situationMatrimoniales', 'niveauInstructions', 'typeDocuments', 'fonctions', 'institutions', 'typeInstitutions'));

    }

    /**
     * Enregistrement d’un nouveau document d’identité (formulaire isolé sur la page édition).
     */
    public function updateDocument(Request $request, string $id)
    {
        $user = User::find($id);
        if ($user === null || $user->personne === null) {
            flash()->error('Utilisateur ou personne introuvable.');

            return redirect()->back();
        }

        $request->validate([
            'code_type_document' => ['required', 'string'],
            'numero_document' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $document = new Document;
            $document->code_document = Sifec::genererCodeUniqueReferentiel($document, 'code_document', 8, [], 'DOC_');
            $document->numero_document = $request->numero_document;
            $document->code_type_document = $request->code_type_document;
            $document->code_personne = $user->personne->code_personne;
            $document->save();

            DB::commit();
            flash()->success('Nouveau document d’identité enregistré.');
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error($e->getMessage());

            return redirect()->back()->withInput();
        }

        return redirect()->route('utilisateur.edit', $user->code_user);
    }

    /**
     * Mise à jour fonction + centre (affectation active uniquement).
     */
    public function updateAffectation(Request $request, string $id)
    {
        $user = User::find($id);
        if ($user === null) {
            flash()->error('Utilisateur introuvable.');

            return redirect()->back();
        }

        $aff = $user->affectationActive();
        if ($aff === null) {
            flash()->error('Aucune affectation active : impossible de modifier le poste ou le centre.');

            return redirect()->back();
        }

        $insUser = InstitutionUser::find($aff->cui);
        if ($insUser === null) {
            flash()->error('Enregistrement d’affectation introuvable.');

            return redirect()->back();
        }

        $request->validate([
            'code_fonction' => ['required', 'string'],
            'code_institution' => ['required', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $insUser->code_institution = $request->code_institution;
            $insUser->code_fonction = $request->code_fonction;
            $insUser->save();

            DB::commit();
            flash()->success('Affectation mise à jour.');
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error($e->getMessage());

            return redirect()->back()->withInput();
        }

        return redirect()->route('utilisateur.edit', $user->code_user);
    }

    /**
     * Mise à jour compte (e-mails, statut) + coordonnées personne modifiables sur cette page.
     */
    public function updateCompte(Request $request, string $id)
    {
        $user = User::find($id);
        if ($user === null) {
            flash()->error('Utilisateur introuvable.');

            return redirect()->back();
        }

        $personne = Personne::find($user->code_personne);
        if ($personne === null) {
            flash()->error('Personne introuvable pour cet utilisateur.');

            return redirect()->back();
        }

        $request->validate([
            'code_nationalite' => ['required', 'string'],
            'adresse' => ['required', 'string'],
            'telephone' => ['nullable', 'string', 'max:32'],
            'niveau_instruction' => ['nullable', 'string'],
            'email' => ['required', 'email', 'max:255', Rule::unique('tr_user', [], 'email')->ignore($user->code_user, 'code_user')],
            'email_professionnel' => ['nullable', 'email', 'max:255', Rule::unique('tr_user', 'email_professionnel')->ignore($user->code_user, 'code_user')],
            'active' => ['required', 'in:0,1'],
        ]);

        DB::beginTransaction();
        try {
            $personne->code_nationalite = $request->code_nationalite;
            $personne->adresse = $request->adresse;
            $personne->telephone = $request->telephone;
            if ($request->filled('niveau_instruction')) {
                $personne->niveau_instruction = $request->niveau_instruction;
            }
            $personne->save();

            $user->email = $request->email;
            $user->email_professionnel = $request->filled('email_professionnel') ? $request->email_professionnel : null;
            $user->status = (bool) (int) $request->active;
            $user->save();

            DB::commit();
            flash()->success('Compte et coordonnées mis à jour.');
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error($e->getMessage());

            return redirect()->back()->withInput();
        }

        return redirect()->route('utilisateur.edit', $user->code_user);
    }

    public function profile($id)
    {

        $user = User::query()->with(['personne.contacts'])->find($id);
        // charger les permissions
        $permissions = Fonctionnalite::all();
        if ($user == null) {
            flash()->error("Impossible d'effectuer cette opération", [], 'Gestion des utilisateurs');

            return back();
        }

        return view('authentification::utilisateur.profile', compact('user', 'permissions'));
    }

    /**
     * Journal d'audit des comptes (table tr_user_audit_trail) — réservé au menu Administration.
     */
    public function auditJournal(Request $request)
    {
        $query = UserAuditTrail::query()
            ->with(['user.personne'])
            ->orderByDesc('created_at');

        if ($request->filled('code_user')) {
            $q = trim($request->code_user);
            $query->where('code_user', 'like', '%'.$q.'%');
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $rows = $query->paginate(40)->appends($request->except('page'));
        $actionLabels = UserAuditTrail::getAvailableActions();

        return view('authentification::utilisateur.audit_journal', compact('rows', 'actionLabels'));
    }

    /**
     * Page dédiée : mise à jour coordonnées, affectation et compte (depuis le profil).
     */
    public function editProfileData($id)
    {
        $user = User::find($id);
        if ($user === null) {
            flash()->error("Impossible d'effectuer cette opération", [], 'Gestion des utilisateurs');

            return back();
        }

        $nationalites = Nationalite::all();
        $niveauInstructions = Sifec::niveauInstructions();
        $typeDocuments = TypeDocument::all();
        $fonctions = Fonction::all();
        $institutions = Institution::all();

        $auditHistory = UserAuditTrail::byUser($user->code_user)
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        $affActive = $user->affectationActive();
        $pieceNominationChemin = $affActive ? $affActive->piece_nomination_chemin : null;
        $codeFonctionActuel = $affActive?->code_fonction;
        $codeInstitutionActuel = $affActive?->code_institution;

        return view('authentification::utilisateur.profile_mise_a_jour', compact(
            'user',
            'nationalites',
            'niveauInstructions',
            'typeDocuments',
            'fonctions',
            'institutions',
            'auditHistory',
            'pieceNominationChemin',
            'codeFonctionActuel',
            'codeInstitutionActuel'
        ));
    }

    /**
     * Enregistrement depuis la page « Mise à jour des données » + journalisation tr_user_audit_trail.
     */
    public function updateProfileData(Request $request, $id)
    {
        $user = User::find($id);
        if ($user === null) {
            flash()->error("Impossible d'effectuer cette opération", [], 'Gestion des utilisateurs');

            return back();
        }

        $personne = Personne::find($user->code_personne);
        if ($personne === null) {
            flash()->error('Personne introuvable pour cet utilisateur.');

            return redirect()->back();
        }

        $aff = $user->affectationActive();
        if ($aff === null) {
            flash()->error('Aucune affectation active : impossible de mettre à jour le poste ou le centre.');

            return redirect()->back();
        }

        $insUser = InstitutionUser::find($aff->cui);
        if ($insUser === null) {
            flash()->error("L'enregistrement d'affectation (institution) est introuvable.");

            return redirect()->back();
        }

        $affectationModifiee = $request->code_fonction !== $insUser->code_fonction
            || $request->code_institution !== $insUser->code_institution;

        $pieceNominationRules = $affectationModifiee
            ? ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120']
            : ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];

        $request->validate([
            'nom' => ['required', 'string'],
            'sexe' => ['required', 'string'],
            'date_naissance' => ['required'],
            'code_nationalite' => ['required'],
            'adresse' => ['required', 'string'],
            'telephone' => ['nullable', 'string', 'max:32'],
            'code_fonction' => ['required'],
            'code_institution' => ['required'],
            'email' => ['required', 'email', 'max:255', Rule::unique('tr_user', [], 'email')->ignore($user->code_user, 'code_user')],
            'email_professionnel' => ['nullable', 'email', 'max:255', Rule::unique('tr_user', 'email_professionnel')->ignore($user->code_user, 'code_user')],
            'active' => ['required', 'in:0,1'],
            'niveau_instruction' => ['nullable', 'string'],
            'code_type_document' => ['nullable', 'required_with:numero_document'],
            'numero_document' => ['nullable', 'required_with:code_type_document', 'string'],
            'piece_nomination' => $pieceNominationRules,
        ], [
            'piece_nomination.required' => 'Une note de service ou un acte de nomination (PDF ou image) est obligatoire lorsque la fonction ou le centre est modifié.',
            'piece_nomination.mimes' => 'Le justificatif doit être un fichier PDF, JPG ou PNG.',
            'piece_nomination.max' => 'Le fichier ne doit pas dépasser 5 Mo.',
        ]);

        $oldNominationPath = $insUser->piece_nomination_chemin;

        $oldSnapshot = [
            'email' => $user->email,
            'email_professionnel' => $user->email_professionnel,
            'status' => (int) $user->status,
            'code_institution' => $insUser->code_institution,
            'code_fonction' => $insUser->code_fonction,
            'adresse' => $personne->adresse,
            'telephone' => $personne->telephone,
            'code_nationalite' => $personne->code_nationalite,
            'niveau_instruction' => $personne->niveau_instruction,
            'piece_nomination_chemin' => $oldNominationPath,
        ];

        DB::beginTransaction();
        try {
            if ($request->filled('code_type_document') && $request->filled('numero_document')) {
                $document = new Document;
                $document->code_document = Sifec::genererCodeUniqueReferentiel($document, 'code_document', 8, 'DOC_');
                $document->numero_document = $request->numero_document;
                $document->code_type_document = $request->code_type_document;
                $document->code_personne = $personne->code_personne;
                $document->save();
            }

            $insUser->code_institution = $request->code_institution;
            $insUser->code_fonction = $request->code_fonction;

            if ($request->hasFile('piece_nomination')) {
                $file = $request->file('piece_nomination');
                if (! $file->isValid()) {
                    throw new Exception('Le fichier de note de service / nomination est corrompu ou inaccessible.');
                }
                $destDir = public_path('app/nominations');
                if (! is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                if (! empty($insUser->piece_nomination_chemin)) {
                    $ancienAbsolu = public_path('app/'.$insUser->piece_nomination_chemin);
                    if (file_exists($ancienAbsolu)) {
                        @unlink($ancienAbsolu);
                    }
                }
                $filename = $file->hashName();
                $file->move($destDir, $filename);
                $insUser->piece_nomination_chemin = 'nominations/'.$filename;
            }

            $insUser->save();

            $personne->adresse = $request->adresse;
            $personne->telephone = $request->telephone;
            $personne->code_nationalite = $request->code_nationalite;
            if ($request->filled('niveau_instruction')) {
                $personne->niveau_instruction = $request->niveau_instruction;
            }
            $personne->save();

            $user->email = $request->email;
            $user->email_professionnel = $request->filled('email_professionnel') ? $request->email_professionnel : null;
            $user->status = (bool) (int) $request->active;
            $user->save();

            $newSnapshot = [
                'email' => $user->email,
                'email_professionnel' => $user->email_professionnel,
                'status' => (int) $user->status,
                'code_institution' => $insUser->code_institution,
                'code_fonction' => $insUser->code_fonction,
                'adresse' => $personne->adresse,
                'telephone' => $personne->telephone,
                'code_nationalite' => $personne->code_nationalite,
                'niveau_instruction' => $personne->niveau_instruction,
                'nouveau_document' => $request->filled('code_type_document') && $request->filled('numero_document'),
                'piece_nomination_chemin' => $insUser->piece_nomination_chemin,
            ];

            UserAuditTrail::log(
                $user->code_user,
                'profile_update',
                'Mise à jour des données (page profil / affectation & compte)',
                $oldSnapshot,
                $newSnapshot
            );

            DB::commit();
            flash()->success('Les informations ont été mises à jour.');

            return redirect()->route('utilisateur.profile', $user->code_user);
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error($e->getMessage());

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function signature(Request $request, $id)
    {

        $request->validate([
            'signature' => ['required', 'image', 'mimes:png,jpg'],
        ]);

        $user = User::find($id);
        if ($user == null) {
            return back()->with('error', "Impossible d'effectuer cette opération.");
        }

        // Dossier cible : public/app/signature (cohérent avec les fichiers existants)
        $destDir = public_path('app/signature');
        if (! is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        DB::beginTransaction();
        try {

            // Supprimer l'ancienne signature si elle existe
            if ($user->personne && ! empty($user->personne->signature)) {
                $signatureExistant = $user->personne->signature;
                $cheminSignature = public_path('app/'.$signatureExistant);
                if (file_exists($cheminSignature)) {
                    unlink($cheminSignature);
                }
            }

            if ($request->hasFile('signature')) {
                $file = $request->file('signature');
                if ($file->isValid()) {
                    $filename = $file->hashName();
                    $file->move($destDir, $filename);
                    $signature = 'signature/'.$filename;

                    $personne = Personne::find($user->code_personne);
                    $personne->signature = $signature;
                    $personne->save();
                } else {
                    return redirect()
                        ->route('utilisateur.profile', $user->code_user)
                        ->with('error', 'Le fichier de signature est corrompu ou inaccessible.')
                        ->withInput();
                }
            } else {
                return redirect()
                    ->route('utilisateur.profile', $user->code_user)
                    ->with('error', "Aucun fichier de signature n'a été envoyé.")
                    ->withInput();
            }

            DB::commit();

            return redirect()
                ->route('utilisateur.profile', $user->code_user)
                ->with('success', 'Signature enregistrée avec succès.');

        } catch (Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('utilisateur.profile', $id)
                ->with('error', $e->getMessage());
        }

    }

    public function searchUser()
    {
        $personnes = Personne::select('*')
            ->where('supprimer', 0)
            ->where('nom', 'LIKE', '%'.request('nom'))
            ->where('date_naissance', 'LIKE', '%'.request('date_naissance'))
            ->where('sexe', 'LIKE', '%'.request('sexe'))
            ->where('telephone', 'LIKE', '%'.request('telephone'))
            ->orWhere('prenom', 'LIKE', '%'.request('prenom'))
            ->get();

        return response()->json([
            'personnes' => $personnes,
        ]);

    }

    public function SearDistrict()
    {
        $codedeprtement = request('id');
        $districts = District::where('code_departement', $codedeprtement)->get();

        return $districts;
    }

    public function SearCommune()
    {
        $codedeprtement = request('id');
        $communes = Commune::where('code_departement', $codedeprtement)->get();

        return $communes;
    }

    public function SearInstitution()
    {
        $codetypeinstitution = request('id');
        $institutions = Institution::where('code_type_institution', $codetypeinstitution)->get();

        return $institutions;
    }

    // Ajouter une fonctionnalite a un user
    public function assignerFonctionnalite($id)
    {
        $user = User::with(['personne', 'affectations.institution', 'affectations.fonction', 'fonctionnalites'])->find($id);

        if ($user == null) {
            flash()->error('Impossible de charger cette page');

            return back();
        }

        // Charger les modules avec leurs fonctionnalités
        $modules = Module::with('fonctionnalites')->get();

        $userPermissionCodes = $user->toutesfonctionnalites()
            ->pluck('code_fonctionnalite')
            ->unique()
            ->values()
            ->all();

        $totalFonctionnalites = (int) $modules->sum(static function ($m) {
            return $m->fonctionnalites->count();
        });

        return view('authentification::utilisateur.assignation', compact(
            'user',
            'modules',
            'userPermissionCodes', [], 'totalFonctionnalites'));

    }

    // enregistrer l'assignation pour une fonction
    public function storeAssigner(Request $request, $id)
    {
        $fonction = Fonction::find($id);

        if ($fonction == null) {
            flash()->error('Impossible de charger cette page');

            return back();
        }

        DB::beginTransaction();
        try {

            if ($request->fonctionnalites != null) {
                $fonction->fonctionnalites()->sync($request->fonctionnalites);
            }

            DB::commit();

            flash()->success('Fonctionnalité assignée avec succès', [], 'Gestion des fonctions');

            return redirect()->route('fonction.index');

        } catch (Exception $e) {
            DB::rollBack();
            flash()->error($e->getMessage());

            return back()->withInput();

        }
    }

    // enregistrer l'assignation des permissions pour un utilisateur
    public function storeAssignerPermission(Request $request, $id)
    {
        $user = User::find($id);

        if ($user == null) {
            flash()->error('Impossible de charger cette page');

            return back();
        }

        DB::beginTransaction();
        try {

            if ($request->fonctionnalites != null) {
                $user->fonctionnalites()->sync($request->fonctionnalites);
            } else {
                // Si aucune fonctionnalité n'est sélectionnée, on supprime toutes les permissions de l'utilisateur
                $user->fonctionnalites()->sync([]);
            }

            DB::commit();

            flash()->success('Permissions assignées avec succès', [], 'Gestion des utilisateurs');

            return redirect()->route('utilisateur.profile', $user->code_user);

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error("Erreur lors de l'assignation des permissions: ".$e->getMessage());
            flash()->error($e->getMessage());

            return back()->withInput();

        }
    }

    /**
     * Afficher le formulaire de modification du mot de passe
     */
    public function showChangePasswordForm($id)
    {
        $user = User::find($id);

        if ($user == null) {
            flash()->error('Utilisateur introuvable');

            return back();
        }

        return view('authentification::utilisateur.change-password', compact('user'));
    }

    /**
     * Activer / désactiver le compte d'un utilisateur.
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            flash()->error('Utilisateur introuvable.', [], 'Erreur');

            return back();
        }

        if ($user->code_user === auth()->user()?->code_user) {
            flash()->warning('Vous ne pouvez pas modifier votre propre statut.', [], 'Action refusée');

            return back();
        }

        $user->status = $user->status ? 0 : 1;
        $user->save();

        $label = $user->status ? 'activé' : 'désactivé';
        $nom = trim(($user->personne->nom ?? '').' '.($user->personne->prenom ?? ''));

        Log::channel('sifec')->info("Compte {$label} : #{$user->code_user} ({$nom}) par ".auth()->user()?->email);

        flash()->success("Compte de {$nom} {$label} avec succès.", [], 'Statut utilisateur');

        return back();
    }

    /**
     * Activer / désactiver la 2FA en masse pour une sélection d'utilisateurs.
     * Chaque utilisateur activé reçoit son QR code + codes de récupération par email.
     */
    public function bulkToggle2FA(Request $request)
    {
        Log::channel('sifec')->info('[bulk2FA] Requête reçue', [
            'action' => $request->input('action'),
            'nb_users' => count($request->input('user_ids', [])),
            'user_ids' => $request->input('user_ids', []),
            'ip' => $request->ip(),
            'admin' => auth()->user()?->email,
        ]);

        $validated = validator($request->all(), [
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['required', 'string'],
            'action' => ['required', 'in:enable,disable'],
        ]);

        if ($validated->fails()) {
            $errMsg = $validated->errors()->first();
            Log::channel('sifec')->warning('[bulk2FA] Validation échouée : '.$errMsg);
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $errMsg], 422);
            }
            flash()->error($errMsg, [], 'Gestion 2FA');

            return back();
        }

        $action = $request->input('action');
        $userIds = $request->input('user_ids');
        $users = User::whereIn('code_user', $userIds)->get();

        Log::channel('sifec')->info('[bulk2FA] Utilisateurs trouvés : '.$users->count());

        if ($users->isEmpty()) {
            Log::channel('sifec')->warning('[bulk2FA] Aucun utilisateur trouvé pour les IDs : '.implode(',', $userIds));
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Aucun utilisateur trouvé dans la sélection.'], 404);
            }
            flash()->error('Aucun utilisateur trouvé dans la sélection.', [], 'Gestion 2FA');

            return back();
        }

        $google2fa = new Google2FA;
        $successCount = 0;
        $mailErrors = [];
        $appName = config('app.name', 'SIFEC');

        DB::beginTransaction();
        try {
            foreach ($users as $user) {
                Log::channel('sifec')->info("[bulk2FA] Traitement user #{$user->code_user} ({$user->email}) — action={$action}");

                if ($action === 'enable') {
                    if ($user->hasTwoFactorEnabled()) {
                        // Déjà actif : régénérer uniquement les codes de récupération
                        $codes = $user->generateRecoveryCodes();
                        $rawSecret = $user->getTwoFactorSecret();
                        Log::channel('sifec')->info("[bulk2FA] 2FA déjà active pour #{$user->code_user}, codes régénérés.");
                    } else {
                        $rawSecret = $google2fa->generateSecretKey();
                        $user->enableTwoFactor($rawSecret);
                        $codes = $user->getRecoveryCodes();
                        Log::channel('sifec')->info("[bulk2FA] 2FA activée pour #{$user->code_user}.");
                    }

                    // Construction de l'URL otpauth pour le QR code Google Authenticator
                    $accountLabel = $user->twoFactorAccountLabel();
                    $label = urlencode($appName.':'.$accountLabel);
                    $otpUrl = "otpauth://totp/{$label}?secret={$rawSecret}&issuer=".urlencode($appName);
                    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=10&data='.urlencode($otpUrl);

                    $mail2fa = $user->emailForTwoFactorMail();
                    if ($mail2fa !== null) {
                        try {
                            Mail::to($mail2fa)
                                ->send(new TwoFactorBulkMailable($user, $codes, 'enabled', $rawSecret, $qrUrl));
                            Log::channel('sifec')->info("[bulk2FA] Email 2FA envoyé à {$mail2fa} (user #{$user->code_user}).");
                        } catch (\Throwable $mailEx) {
                            Log::channel('sifec')->warning("[bulk2FA] Email non envoyé à {$mail2fa} : ".$mailEx->getMessage());
                            $mailErrors[] = ($user->personne->nom ?? '').' <'.$mail2fa.'>';
                        }
                    } else {
                        Log::channel('sifec')->warning("[bulk2FA] Aucune adresse e-mail (compte ou professionnelle) pour #{$user->code_user} — e-mail 2FA non envoyé.");
                    }

                } else {
                    // Désactivation
                    if ($user->hasTwoFactorEnabled()) {
                        $user->disableTwoFactor();
                        Log::channel('sifec')->info("[bulk2FA] 2FA désactivée pour #{$user->code_user}.");

                        $mail2fa = $user->emailForTwoFactorMail();
                        if ($mail2fa !== null) {
                            try {
                                Mail::to($mail2fa)
                                    ->send(new TwoFactorBulkMailable($user, [], 'disabled', null, null));
                                Log::channel('sifec')->info("[bulk2FA] Email désactivation envoyé à {$mail2fa}.");
                            } catch (\Throwable $mailEx) {
                                Log::channel('sifec')->warning("[bulk2FA] Email désactivation non envoyé à {$mail2fa} : ".$mailEx->getMessage());
                                $mailErrors[] = ($user->personne->nom ?? '').' <'.$mail2fa.'>';
                            }
                        }
                    } else {
                        Log::channel('sifec')->info("[bulk2FA] 2FA déjà inactive pour #{$user->code_user} — ignoré.");
                    }
                }

                $successCount++;
            }

            DB::commit();
            Log::channel('sifec')->info("[bulk2FA] Opération terminée. Succès: {$successCount}, Emails en erreur: ".count($mailErrors));

            $label = $action === 'enable' ? 'activée' : 'désactivée';
            $msg = "2FA <strong>{$label}</strong> pour <strong>{$successCount}</strong> utilisateur(s).";

            if (! empty($mailErrors)) {
                $msg .= '<br><small style="color:#856404;">⚠️ Emails non envoyés : '.implode(', ', $mailErrors).'</small>';
            }

            // Réponse JSON pour les appels AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $msg,
                    'count' => $successCount,
                    'mailErrors' => $mailErrors,
                ]);
            }

            // Fallback non-AJAX
            if (! empty($mailErrors)) {
                flash()->warning(strip_tags($msg), [], 'Gestion 2FA');
            } else {
                flash()->success(strip_tags($msg), [], 'Gestion 2FA');
            }

            return redirect()->route('utilisateur.index');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('sifec')->error('[bulk2FA] ERREUR : '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            $errMsg = 'Erreur : '.$e->getMessage();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $errMsg], 500);
            }

            flash()->error($errMsg, [], 'Gestion 2FA');

            return back();
        }
    }

    /**
     * Traiter la modification du mot de passe
     */
    public function changePassword(Request $request, $id)
    {
        $user = User::find($id);

        if ($user == null) {
            flash()->error('Utilisateur introuvable');

            return back();
        }

        // Validation
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ], [
            'current_password.required' => 'Le mot de passe actuel est requis',
            'new_password.required' => 'Le nouveau mot de passe est requis',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 8 caractères',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
            'new_password_confirmation.required' => 'La confirmation du mot de passe est requise',
        ]);

        // Vérifier le mot de passe actuel
        if (! Hash::check($request->current_password, $user->password)) {
            flash()->error('Le mot de passe actuel est incorrect');

            return back()->withInput();
        }

        // Vérifier que le nouveau mot de passe est différent de l'ancien
        if (Hash::check($request->new_password, $user->password)) {
            flash()->error("Le nouveau mot de passe doit être différent de l'actuel");

            return back()->withInput();
        }

        try {
            // Mettre à jour le mot de passe
            $user->password = Hash::make($request->new_password);
            $user->must_change_password = false;
            $user->save();

            // Log de l'audit trail
            if (method_exists($user, [], 'logActivity')) {
                $user->logActivity('password_change', 'Mot de passe modifié', [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            flash()->success('Mot de passe modifié avec succès');

            return redirect()->route('utilisateur.profile', $user->code_user);

        } catch (Exception $e) {
            flash()->error('Erreur lors de la modification du mot de passe : '.$e->getMessage());

            return back()->withInput();
        }
    }
}
