<?php

namespace Modules\Referentiel\Http\Controllers;

use App\Models\User;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Modules\Deces\Entities\ActeDeces;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Notification\Jobs\CreationRegistreJob;
use Modules\Notification\Jobs\RegistreValideParTribunalJob;
use Modules\Notification\Jobs\ValidationRegistreJob;
use Modules\Notification\Notifications\CreationRegistreParCecNotification;
use Modules\Notification\Notifications\FeuilletRegistreAjouteNotification;
use Modules\Notification\Notifications\RegistreValideParTribunalNotification;
use Modules\Notification\Services\NotificationService;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Registre;
use Modules\Referentiel\Entities\TypeRegistre;

class RegistreController extends Controller
{
    /** Catégorie d'institution « centre d'état civil » (tr_type_institution.code_type_categorie_ins). */
    private const CODE_TYPE_CATEGORIE_INS_CEC = 'TCINS_0001';

    private const OTP_VALID_MINUTES = 1;

    private const OTP_MAX_ATTEMPTS = 3;

    private const OTP_LOCKOUT_MINUTES = 3;

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        // dispatch(new ValidationRegistreJob("OBELA","152ffgfg","alfed520","obela.sifec@gmail.com"));

        $modules = Auth::user()->modules();
        $registres = collect([]);
        $typeRegistres = collect([]);
        $typeRegistres_vide = collect([]);

        if ($modules->count() > 0) {
            $inst = Auth::user()->affectationActive();

            foreach ($modules as $m) {
                switch ($m->code_module) {
                    case 'MOD_0002':
                    case 'MOD_0004':
                    case 'MOD_0005':

                        $typeRegistres = TypeRegistre::whereIn('code_type_registre', ['TPRG_0001', 'TPRG_0002', 'TPRG_0003'])->get();
                        // $registres = $typeRegistres->map->registres->flatten()->where("cui",$inst->cui);
                        $registres = $inst->institution->registres();

                        if ($inst->institution->lib_institution == 'MAIRIE CENTRALE') {
                            $typeRegistres = TypeRegistre::all();
                            $registres = $typeRegistres->map->registres->flatten()->where('cui', $inst->cui);
                        }
                        // le cas de mairie qui n'est pas relié à une pompe funebre
                        if ($inst->institution->lieu->localiteParent->pompes_funebres == 0) {
                            $typeRegistres = TypeRegistre::all();
                            // $registres = $typeRegistres->map->registres->flatten()->where("cui",$inst->cui);
                            $registres = $inst->institution->registres();
                        }
                        // le cas des ambassades
                        if ($inst->institution->lieu->typeLocalite->code_type_localite == 'TPLOC_0009') {
                            $typeRegistres = TypeRegistre::all();
                            // $registres = $typeRegistres->map->registres->flatten()->where("cui",$inst->cui);
                            $registres = $inst->institution->registres();
                        }

                        $typeRegistres_vide = $typeRegistres;
                        $registre_naissance_last = Registre::where('code_type_registre', 'TPRG_0001')
                            ->where('date_fermeture', '>', date('Y-m-d'))
                            ->orWhere('nombre_acte_prevu', '<', 'nombre_acte_transcrit')
                            ->count();

                        if ($registre_naissance_last > 0) {
                            $typeRegistres_vide = TypeRegistre::whereIn('code_type_registre', ['TPRG_0002', 'TPRG_0003'])->get();
                        }

                        break;

                    case 'MOD_0003':

                        $typeRegistres = TypeRegistre::whereIn('code_type_registre', ['TPRG_0004'])->get();
                        // $registres = $typeRegistres->map->registres->flatten()->where("cui",$inst->cui);
                        $registres = $inst->institution->registres();

                        $typeRegistres_vide = $typeRegistres;
                        $registre_deces_last = Registre::where('code_type_registre', 'TPRG_0004')
                            ->where('date_fermeture', '>', date('Y-m-d'))
                            ->orWhere('nombre_acte_prevu', '<', 'nombre_acte_transcrit')
                            ->count();

                        if ($registre_deces_last > 0) {
                            $typeRegistres_vide = TypeRegistre::whereNotIn('code_type_registre', ['TPRG_0004', 'TPRG_0001', 'TPRG_0002', 'TPRG_0003'])->get();
                        }
                        break;
                }

            }
        }

        return view('referentiel::registre.index', compact('registres', 'typeRegistres', 'typeRegistres_vide'));
    }

    public function store(Request $request, NotificationService $notificationService)
    {
        $annee = date('Y');
        $code_cec = Auth::user()->affectationActive()->cui;

        // $prefix = $request->prefix.$code_cec;
        // $coderegistre =   Sifec::genererCodeUfniqueReferentiel(new Registre(),"code_registre",3,$request->prefix);

        $request->validate([
            'lib_registre' => ['string'],
            'code_type_registre' => ['required', 'string'],
            'nbre_acte_prevu' => ['required'],
            'statut' => ['required'],
        ]);

        $registreActif = Registre::where(['statut' => 1, 'code_type_registre' => $request->code_type_registre, 'cui' => $code_cec])->first();

        if ($registreActif != null) {
            flash()->warning('Un registre valide est encore en cours');

            return back();
        }

        DB::beginTransaction();

        try {

            $registre = new Registre;
            $registre->code_registre = Sifec::genererCodeUniqueReferentiel($registre, 'code_registre', 2, [], 'REG_'); // $code_registre;
            $registre->lib_registre = $request->lib_registre;
            $registre->code_type_registre = $request->code_type_registre;
            $registre->nombre_acte_prevu = $request->nbre_acte_prevu;
            $registre->date_ouverture = date('Y-m-d');
            $registre->date_fermeture = date('Y-12-31');
            $registre->statut = $request->statut;
            $registre->cui = Auth::user()->affectationActive()->cui;
            $registre->identifiant_registre = $request->prefix.Auth::user()->institution()->code_institution.date('dmYHis');
            $registre->save();

            // Envoi de notification au tribunal de ressort (parent du CEC)
            $institution = optional($registre->institutionUser)->institution;
            $tribunal = $institution ? optional($institution)->institutionParent : null;
            $validateur = $tribunal ? $tribunal->validateur() : null;

            if ($tribunal && $validateur) {
                $otp = substr(time(), 2);

                $temp = config('sifec.sms.templates.actions.creation_registre');
                $temp = str_replace(':tribunal', $validateur->nom, $temp);
                $temp = str_replace(':code_registre', $registre->numeroOrdreRegistre(), $temp);
                $temp = str_replace(':cec', Auth::user()->affectationActive()->institution->lib_institution, $temp);
                $temp = str_replace(':type_registre', $registre->typeRegistre->lib_type_registre, $temp);
                $temp = str_replace(':code_otp', $otp, $temp);

                $contactValidateur = optional($validateur->contacts)->first();
                $telephone = $contactValidateur ? $contactValidateur->indicatif.$contactValidateur->telephone : null;

                if ($telephone) {
                    SifecFacade::sendSms($telephone, $temp);
                }

                $emailTribunal = $contactValidateur ? ($contactValidateur->email_professionnelle ?? null) : null;
                if ($emailTribunal) {
                    dispatch(new CreationRegistreJob(
                        $validateur->nom,
                        $registre->typeRegistre->lib_type_registre,
                        $registre->numeroOrdreRegistre(),
                        Auth::user()->affectationActive()->institution->lib_institution,
                        $emailTribunal
                    ));
                }
            }

            // Notification in-app (même principe que Naissance : notifierAgentsInstitution sur le tribunal).
            // Si aucun tr_ins_user actif ne matche, repli : compte User lié à la personne du validateur (celle du SMS).
            if ($tribunal) {
                try {
                    $cecLib = Auth::user()->affectationActive()->institution->lib_institution;
                    $notif = new CreationRegistreParCecNotification($registre, $cecLib);
                    $nb = $notificationService->notifierAgentsInstitution($tribunal->code_institution, $notif);
                    $fallbackPresident = false;
                    if ($nb === 0 && $validateur && ! empty($validateur->code_personne)) {
                        $presidentUser = User::query()->where('code_personne', $validateur->code_personne)->first();
                        if ($presidentUser) {
                            try {
                                $presidentUser->notify($notif);
                                $fallbackPresident = true;
                            } catch (\Throwable $e) {
                                Log::channel('sifec')->error('[Registre][store] Échec notify() repli président (code_personne)', [
                                    'code_registre' => $registre->code_registre,
                                    'code_personne' => $validateur->code_personne,
                                    'code_user' => $presidentUser->code_user,
                                    'message' => $e->getMessage(),
                                    'exception' => $e::class,
                                    'trace' => $e->getTraceAsString(),
                                ]);
                                throw $e;
                            }
                        } else {
                            Log::channel('sifec')->warning('[Registre][store] Aucun User pour code_personne du validateur (repli SMS)', [
                                'code_registre' => $registre->code_registre,
                                'code_personne' => $validateur->code_personne,
                                'code_institution_tribunal' => $tribunal->code_institution,
                            ]);
                        }
                    }
                    if ($nb === 0 && ! $fallbackPresident) {
                        Log::channel('sifec')->warning('[Registre][store] Aucune notification in-app enregistrée', [
                            'code_registre' => $registre->code_registre,
                            'code_institution_tribunal' => $tribunal->code_institution,
                            'agents_tribunal_notifies' => $nb,
                            'validateur_code_personne' => $validateur->code_personne ?? null,
                        ]);
                    } else {
                        Log::channel('sifec')->info('[Registre][store] Notification création registre', [
                            'code_registre' => $registre->code_registre,
                            'code_institution_tribunal' => $tribunal->code_institution,
                            'agents_tribunal_notifies' => $nb,
                            'repli_president' => $fallbackPresident,
                        ]);
                    }
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::channel('sifec')->error('[Registre][store] Échec notification création registre', [
                        'message' => $e->getMessage(),
                        'code_registre' => $registre->code_registre ?? null,
                        'code_institution_tribunal' => $tribunal->code_institution ?? null,
                        'exception' => $e::class,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    flash()->error('Erreur lors de la notification aux agents du tribunal : '.$e->getMessage());

                    return redirect()->back()->withInput();
                }
            }

            DB::commit();

            flash()->success('Registre enregistré avec succès');

            return redirect()->route('registre.index');

        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('[Registre][store] Erreur transaction enregistrement registre', [
                'message' => $e->getMessage(),
                'exception' => $e::class,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
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
        $registre = Registre::find($id);

        if ($registre == null) {
            flash()->error('Impossible de charger cette page');
        }

        $registre->delete();
        flash()->success('Registre supprimé avec succès');

        return redirect()->route('registre.index');

    }

    public function sendOtp(Request $request, $id)
    {
        $reg = Registre::where('code_registre', $id)->first();
        if ($reg === null) {
            return response()->json([
                'code' => '182',
                'message' => 'Aucun registre trouvé pour ce code.',
            ]);
        }

        if ($auth = $this->ensureParapherAuthorizedForRegistre($reg)) {
            return $auth;
        }

        if ((int) $reg->statut === 1) {
            return response()->json([
                'code' => '186',
                'message' => 'Ce registre est déjà validé (paraphé).',
            ]);
        }

        if ($locked = $this->jsonIfOtpLocked($reg)) {
            return $locked;
        }

        try {
            $hasActiveOtp = $reg->otp_paraphage
                && $reg->otp_expire_at
                && now()->lessThan($reg->otp_expire_at);

            // Tout nouvel envoi alors qu'un code est encore valide = renvoi (même sans ?resend=1) : incrémente le même quota que les codes incorrects.
            if ($hasActiveOtp) {
                $attempts = (int) $reg->otp_paraphage_attempts + 1;
                $reg->otp_paraphage_attempts = $attempts;

                if ($attempts >= self::OTP_MAX_ATTEMPTS) {
                    return $this->applyParapheOtpLockout($reg);
                }
            } else {
                $reg->otp_paraphage_attempts = 0;
                $reg->otp_locked_until = null;
            }

            $otp = $this->generateNumericOtp();

            $temp = config('sifec.sms.templates.actions.paraphage_registre');
            $temp = str_replace(':tribunal', Auth::user()->personne->nomcomplet(), $temp);
            $temp = str_replace(':code_registre', $reg->numeroOrdreRegistre(), $temp);
            $temp = str_replace(':code_otp', $otp, $temp);

            $reg->otp_paraphage = $otp;
            $reg->otp_expire_at = now()->addMinutes(self::OTP_VALID_MINUTES);
            $reg->save();

            $contact = Auth::user()->personne->contacts->first();

            if ($contact !== null) {
                SifecFacade::sendSms($contact->indicatif.$contact->telephone, $temp);

                if (! empty($contact->email_professionnelle)) {
                    dispatch(new ValidationRegistreJob(
                        Auth::user()->personne->nomcomplet(),
                        $otp,
                        $reg->getcode(),
                        $contact->email_professionnelle
                    ));
                }
            }

            Log::channel('sifec')->info('OTP paraphe registre envoyé', [
                'code_registre' => $reg->code_registre,
                'cui' => Auth::user()->affectationActive()->cui,
                'renvoi_client' => $request->boolean('resend'),
                'otp_encore_valide_avant_envoi' => $hasActiveOtp,
            ]);

            return response()->json([
                'code' => '200',
                'message' => 'Code envoyé par SMS (et e-mail si configuré). Il est valable '.self::OTP_VALID_MINUTES.' minute.',
                'valid_for_seconds' => self::OTP_VALID_MINUTES * 60,
                'otp_max_attempts' => self::OTP_MAX_ATTEMPTS,
                'attempts_used' => (int) $reg->otp_paraphage_attempts,
                'otp_lockout_minutes' => self::OTP_LOCKOUT_MINUTES,
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('sendOtp registre: '.$e->getMessage(), [
                'code_registre' => $reg->code_registre,
            ]);

            return response()->json([
                'code' => '181',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function validateOtp(Request $request)
    {
        $rules = [
            'otp_paraphage' => ['required', 'digits:6'],
            'code_registre' => ['required', 'string'],
        ];

        $validator = Validator::make($request->all(), $rules, [
            'otp_paraphage.required' => 'Le code OTP est obligatoire.',
            'otp_paraphage.digits' => 'Le code OTP doit contenir exactement 6 chiffres (0 à 9), sans lettre.',
            'code_registre.required' => 'L’identifiant du registre est manquant.',
        ]);

        if ($validator->fails()) {
            $msg = $validator->errors()->first('otp_paraphage')
                ?: $validator->errors()->first('code_registre')
                ?: 'Vérifiez le code OTP (6 chiffres) et l’identifiant du registre.';

            return response()->json([
                'code' => '180',
                'message' => $msg,
            ]);
        }

        $codeReg = $request->code_registre;
        $otpSaisi = (string) $request->otp_paraphage;

        $reg = Registre::where('code_registre', $codeReg)->first();
        if ($reg === null) {
            return response()->json([
                'code' => '182',
                'message' => "Aucun registre trouvé pour le code {$codeReg}.",
            ]);
        }

        if ($auth = $this->ensureParapherAuthorizedForRegistre($reg)) {
            return $auth;
        }

        if ((int) $reg->statut === 1) {
            return response()->json([
                'code' => '186',
                'message' => 'Ce registre est déjà validé (paraphé).',
            ]);
        }

        if ($locked = $this->jsonIfOtpLocked($reg)) {
            return $locked;
        }

        if ($reg->otp_paraphage === null || $reg->otp_paraphage === '') {
            return response()->json([
                'code' => '185',
                'message' => 'Aucun code actif. Demandez un nouveau code OTP.',
            ]);
        }

        if (! $reg->otp_expire_at || now()->greaterThan($reg->otp_expire_at)) {
            return response()->json([
                'code' => '185',
                'message' => 'Le code OTP a expiré. Demandez un nouveau code.',
            ]);
        }

        if ($otpSaisi !== (string) $reg->otp_paraphage) {
            return $this->registerFailedParapheOtpAttempt($reg);
        }

        try {
            $reg->loadMissing('institutionUser.institution.institutionParent');
            $reg->sceau = $reg->institutionUser->institution->institutionParent->sceau;
            // Conserver le OTP validé sur tr_registre.otp_paraphage (traçabilité, comme pour les actes).
            $reg->otp_paraphage = $otpSaisi;
            $reg->otp_expire_at = null;
            $reg->otp_paraphage_attempts = 0;
            $reg->otp_locked_until = null;
            $reg->signature_tribunal = Auth::user()->personne->signature;
            $reg->approbation_tribunal = Auth::user()->affectationActive()->cui;
            $reg->statut = 1;
            $reg->save();

            Log::channel('sifec')->info('Registre paraphe validé', [
                'code_registre' => $reg->code_registre,
                'cui' => Auth::user()->affectationActive()->cui,
                'otp_paraphage_conserve' => true,
            ]);

            $this->notifyCecApresValidationTribunal($reg);

            return response()->json([
                'code' => '200',
                'message' => 'Registre de '.$reg->typeRegistre->lib_type_registre.' est validé avec succès',
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('validateOtp registre: '.$e->getMessage(), [
                'code_registre' => $reg->code_registre,
            ]);

            return response()->json([
                'code' => '183',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envoi d’un OTP unique pour parapher plusieurs registres (tribunal).
     */
    public function sendOtpBulk(Request $request): JsonResponse
    {
        if (! Gate::allows('module.fonctionnalites.parapher')) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à parapher des registres.",
            ]);
        }

        $codes = $request->input('codes', []);
        if (! is_array($codes)) {
            $codes = [];
        }
        $codes = array_values(array_unique(array_filter(array_map('strval', $codes))));
        if ($codes === []) {
            return response()->json([
                'code' => '180',
                'message' => 'Sélectionnez au moins un registre.',
            ]);
        }
        if (count($codes) > 40) {
            return response()->json([
                'code' => '180',
                'message' => 'Maximum 40 registres par validation groupée.',
            ]);
        }

        $regs = Registre::whereIn('code_registre', $codes)->get();
        if ($regs->count() !== count($codes)) {
            return response()->json([
                'code' => '180',
                'message' => 'Un ou plusieurs registres sont introuvables.',
            ]);
        }

        foreach ($regs as $reg) {
            if ($auth = $this->ensureParapherAuthorizedForRegistre($reg)) {
                return $auth;
            }
            if ((int) $reg->statut === 1) {
                return response()->json([
                    'code' => '186',
                    'message' => 'Un registre sélectionné est déjà validé : '.$reg->code_registre,
                ]);
            }
            if ($locked = $this->jsonIfOtpLocked($reg)) {
                return $locked;
            }
            if ($reg->sceau !== null && $reg->sceau !== '') {
                return response()->json([
                    'code' => '186',
                    'message' => 'Un registre sélectionné est déjà paraphé : '.$reg->code_registre,
                ]);
            }
            if ($reg->otp_paraphage && $reg->otp_expire_at && now()->lessThan($reg->otp_expire_at)) {
                return response()->json([
                    'code' => '180',
                    'message' => 'Un code OTP individuel est encore actif pour « '.$reg->lib_registre.' ». Finalisez le paraphe unitaire ou attendez l’expiration.',
                ]);
            }
        }

        $cacheKey = $this->registreParapheBulkCacheKey();
        $cached = Cache::get($cacheKey);
        if (is_array($cached) && ! empty($cached['locked_until'])) {
            $lu = $cached['locked_until'] instanceof Carbon
                ? $cached['locked_until']
                : Carbon::parse($cached['locked_until']);
            if (now()->lessThan($lu)) {
                $retryAfter = max(1, $lu->getTimestamp() - now()->getTimestamp());

                return response()->json([
                    'code' => '184',
                    'message' => 'Suite à des tentatives infructueuses, veuillez attendre avant de demander un nouveau code.',
                    'retry_after_seconds' => $retryAfter,
                    'remaining_attempts' => 0,
                    'otp_max_attempts' => self::OTP_MAX_ATTEMPTS,
                    'attempts_used' => self::OTP_MAX_ATTEMPTS,
                ]);
            }
        }

        $codesSorted = $codes;
        sort($codesSorted);
        $sameActive = false;
        if (is_array($cached) && ! empty($cached['otp']) && ! empty($cached['otp_expires_at'])) {
            $exp = $cached['otp_expires_at'] instanceof Carbon
                ? $cached['otp_expires_at']
                : Carbon::parse($cached['otp_expires_at']);
            if (now()->lessThan($exp)) {
                $c0 = $cached['codes'];
                sort($c0);
                $sameActive = ($c0 === $codesSorted);
            }
        }

        $strikes = 0;
        if ($sameActive) {
            $strikes = (int) ($cached['strikes'] ?? 0) + 1;
            if ($strikes >= self::OTP_MAX_ATTEMPTS) {
                return $this->applyBulkParapheLockoutCache($codes);
            }
        }

        try {
            $otp = $this->generateNumericOtp();
            $personne = Auth::user()->personne;
            $tribunalNom = $personne ? $personne->nomcomplet() : 'Magistrat';
            $n = count($codes);
            $temp = (string) config('sifec.sms.templates.actions.paraphage_registre_bulk');
            $temp = str_replace(':tribunal', $tribunalNom, $temp);
            $temp = str_replace(':code_otp', $otp, $temp);
            $temp = str_replace(':nombre', (string) $n, $temp);
            $temp = str_replace(':minutes', (string) self::OTP_VALID_MINUTES, $temp);

            Cache::put($cacheKey, [
                'otp' => $otp,
                'codes' => $codes,
                'otp_expires_at' => now()->addMinutes(self::OTP_VALID_MINUTES),
                'strikes' => $strikes,
                'locked_until' => null,
            ], now()->addMinutes(15));

            $contact = $personne ? $personne->contacts->first() : null;
            if ($contact !== null) {
                SifecFacade::sendSms($contact->indicatif.$contact->telephone, $temp);
                if (! empty($contact->email_professionnelle)) {
                    $summary = $regs->map(fn (Registre $r) => $r->getcode())->implode(', ');
                    dispatch(new ValidationRegistreJob(
                        $tribunalNom,
                        $otp,
                        $summary.' ('.$n.' registre(s))',
                        $contact->email_professionnelle
                    ));
                }
            }

            Log::channel('sifec')->info('OTP paraphe registre (bulk) envoyé', [
                'codes' => $codes,
                'count' => $n,
                'cui' => Auth::user()->affectationActive()->cui,
                'renvoi' => $request->boolean('resend'),
            ]);

            return response()->json([
                'code' => '200',
                'message' => 'Code envoyé par SMS (et e-mail si configuré). Il est valable '.self::OTP_VALID_MINUTES.' minute.',
                'valid_for_seconds' => self::OTP_VALID_MINUTES * 60,
                'otp_max_attempts' => self::OTP_MAX_ATTEMPTS,
                'attempts_used' => $strikes,
                'otp_lockout_minutes' => self::OTP_LOCKOUT_MINUTES,
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('sendOtpBulk registre: '.$e->getMessage(), ['codes' => $codes]);

            return response()->json([
                'code' => '181',
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Validation OTP unique pour parapher plusieurs registres.
     */
    public function validateOtpBulk(Request $request): JsonResponse
    {
        if (! Gate::allows('module.fonctionnalites.parapher')) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à parapher des registres.",
            ]);
        }

        $codes = $request->input('codes', []);
        if (! is_array($codes)) {
            $codes = [];
        }
        $codes = array_values(array_unique(array_filter(array_map('strval', $codes))));
        $otpSaisi = (string) $request->input('otp_paraphage', '');

        if ($codes === [] || $otpSaisi === '' || strlen($otpSaisi) !== 6 || ! ctype_digit($otpSaisi)) {
            return response()->json([
                'code' => '180',
                'message' => 'Sélectionnez des registres et saisissez le code à 6 chiffres.',
            ]);
        }

        $cacheKey = $this->registreParapheBulkCacheKey();
        $cached = Cache::get($cacheKey);
        if (! is_array($cached) || empty($cached['otp'])) {
            return response()->json([
                'code' => '185',
                'message' => 'Aucun code actif. Demandez un nouveau code OTP.',
            ]);
        }

        if (! empty($cached['locked_until'])) {
            $lu = $cached['locked_until'] instanceof Carbon
                ? $cached['locked_until']
                : Carbon::parse($cached['locked_until']);
            if (now()->lessThan($lu)) {
                $retryAfter = max(1, $lu->getTimestamp() - now()->getTimestamp());

                return response()->json([
                    'code' => '184',
                    'message' => 'Temporisation active. Patientez avant de réessayer.',
                    'retry_after_seconds' => $retryAfter,
                    'otp_max_attempts' => self::OTP_MAX_ATTEMPTS,
                    'attempts_used' => self::OTP_MAX_ATTEMPTS,
                ]);
            }
        }

        $exp = $cached['otp_expires_at'] instanceof Carbon
            ? $cached['otp_expires_at']
            : Carbon::parse($cached['otp_expires_at']);
        if (now()->greaterThan($exp)) {
            return response()->json([
                'code' => '185',
                'message' => 'Le code OTP a expiré. Demandez un nouveau code.',
            ]);
        }

        sort($codes);
        $cachedCodes = $cached['codes'];
        sort($cachedCodes);
        if ($codes !== $cachedCodes) {
            return response()->json([
                'code' => '180',
                'message' => 'La sélection ne correspond plus au code envoyé. Fermez le modal et recommencez.',
            ]);
        }

        if (! hash_equals((string) $cached['otp'], $otpSaisi)) {
            $strikes = (int) ($cached['strikes'] ?? 0) + 1;
            $cached['strikes'] = $strikes;
            if ($strikes >= self::OTP_MAX_ATTEMPTS) {
                return $this->applyBulkParapheLockoutCache($cached['codes']);
            }
            Cache::put($cacheKey, $cached, now()->addMinutes(15));

            return response()->json([
                'code' => '183',
                'message' => 'Code OTP incorrect.',
                'remaining_attempts' => (int) (self::OTP_MAX_ATTEMPTS - $strikes),
                'otp_max_attempts' => (int) self::OTP_MAX_ATTEMPTS,
                'attempts_used' => $strikes,
            ]);
        }

        $regs = Registre::whereIn('code_registre', $cached['codes'])->get();
        if ($regs->count() !== count($cached['codes'])) {
            return response()->json([
                'code' => '182',
                'message' => 'Registres introuvables lors de la validation.',
            ]);
        }

        try {
            DB::beginTransaction();
            foreach ($regs as $reg) {
                $reg = Registre::where('code_registre', $reg->code_registre)->lockForUpdate()->first();
                if ($reg === null) {
                    throw new \RuntimeException('Registre introuvable.');
                }
                if ((int) $reg->statut === 1) {
                    throw new \RuntimeException('Registre déjà validé : '.$reg->code_registre);
                }
                if ($auth = $this->ensureParapherAuthorizedForRegistre($reg)) {
                    DB::rollBack();

                    return $auth;
                }
                $reg->loadMissing('institutionUser.institution.institutionParent');
                $reg->sceau = $reg->institutionUser->institution->institutionParent->sceau;
                $reg->otp_paraphage = $otpSaisi;
                $reg->otp_expire_at = null;
                $reg->otp_paraphage_attempts = 0;
                $reg->otp_locked_until = null;
                $reg->signature_tribunal = Auth::user()->personne->signature;
                $reg->approbation_tribunal = Auth::user()->affectationActive()->cui;
                $reg->statut = 1;
                $reg->save();
            }
            Cache::forget($cacheKey);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('sifec')->error('validateOtpBulk registre: '.$e->getMessage(), [
                'codes' => $cached['codes'] ?? [],
            ]);

            return response()->json([
                'code' => '183',
                'message' => $e->getMessage(),
            ]);
        }

        foreach ($regs as $reg) {
            $this->notifyCecApresValidationTribunal(Registre::query()->find($reg->code_registre));
        }

        Log::channel('sifec')->info('Registres paraphe validés (bulk)', [
            'codes' => $cached['codes'],
            'count' => count($cached['codes']),
            'cui' => Auth::user()->affectationActive()->cui,
        ]);

        $n = count($cached['codes']);

        return response()->json([
            'code' => '200',
            'message' => $n > 1
                ? $n.' registres validés (paraphe) avec succès.'
                : 'Registre validé (paraphe) avec succès.',
        ]);
    }

    private function registreParapheBulkCacheKey(): string
    {
        return 'registre_paraphe_bulk:'.Auth::id();
    }

    private function applyBulkParapheLockoutCache(array $codes): JsonResponse
    {
        $cacheKey = $this->registreParapheBulkCacheKey();
        Cache::put($cacheKey, [
            'otp' => null,
            'codes' => $codes,
            'otp_expires_at' => null,
            'strikes' => self::OTP_MAX_ATTEMPTS,
            'locked_until' => now()->addMinutes(self::OTP_LOCKOUT_MINUTES),
        ], now()->addMinutes(15));

        Log::channel('sifec')->warning('Registre paraphe bulk: verrouillage après quota OTP', [
            'codes' => $codes,
        ]);

        return response()->json([
            'code' => '184',
            'message' => 'Nombre maximal de tentatives atteint. Nouveau code possible dans '.self::OTP_LOCKOUT_MINUTES.' minute(s).',
            'retry_after_seconds' => self::OTP_LOCKOUT_MINUTES * 60,
            'remaining_attempts' => 0,
            'otp_max_attempts' => self::OTP_MAX_ATTEMPTS,
            'attempts_used' => self::OTP_MAX_ATTEMPTS,
        ]);
    }

    /**
     * Après paraphe tribunal : notification in-app + e-mails aux agents actifs du CEC (ne bloque pas la validation).
     */
    private function notifyCecApresValidationTribunal(Registre $reg): void
    {
        try {
            $reg->loadMissing(['typeRegistre', 'institutionUser.institution.institutionParent']);

            $cecInst = $reg->institutionUser->institution ?? null;
            if (! $cecInst) {
                Log::channel('sifec')->warning('[Registre][validateOtp] Notification CEC: institution absente', [
                    'code_registre' => $reg->code_registre,
                ]);

                return;
            }

            $tribunalInst = $cecInst->institutionParent;
            $tribunalLib = $tribunalInst->lib_institution ?? 'Tribunal';
            $cecLib = $cecInst->lib_institution ?? '';

            $notification = new RegistreValideParTribunalNotification($reg, $tribunalLib);
            $count = NotificationService::notifierAgentsInstitution($cecInst->code_institution, $notification);

            Log::channel('sifec')->info('[Registre][validateOtp] Notifications CEC (registre validé par tribunal)', [
                'code_registre' => $reg->code_registre,
                'code_institution_cec' => $cecInst->code_institution,
                'agents_notifies' => $count,
            ]);

            $typeLib = $reg->typeRegistre->lib_type_registre ?? 'Registre';
            $numero = $reg->numeroOrdreRegistre();

            $emails = User::whereHas('affectations', function ($q) use ($cecInst) {
                $q->where('code_institution', $cecInst->code_institution)
                    ->where(function ($q2) {
                        $q2->where('active', 1)->orWhere('active', true);
                    });
            })->whereNotNull('email')->where('email', '!=', [], '')
                ->pluck('email')
                ->unique()
                ->filter()
                ->values();

            foreach ($emails as $email) {
                RegistreValideParTribunalJob::dispatch($tribunalLib, $typeLib, $numero, $cecLib, $email);
            }

            if ($emails->isEmpty()) {
                Log::channel('sifec')->warning('[Registre][validateOtp] Aucun e-mail agent CEC pour envoi (registre validé)', [
                    'code_registre' => $reg->code_registre,
                    'code_institution_cec' => $cecInst->code_institution,
                ]);
            }
        } catch (\Throwable $e) {
            Log::channel('sifec')->error('[Registre][validateOtp] Échec notification CEC après validation tribunal', [
                'code_registre' => $reg->code_registre,
                'message' => $e->getMessage(),
                'exception' => $e::class,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    private function generateNumericOtp(): string
    {
        return (string) random_int(100000, 999999);
    }

    /**
     * @return JsonResponse|null
     */
    private function ensureParapherAuthorizedForRegistre(Registre $reg)
    {
        if (! Gate::allows('module.fonctionnalites.parapher')) {
            return response()->json([
                'code' => '181',
                'message' => "Vous n'êtes pas autorisé à parapher un registre",
            ]);
        }

        $reg->loadMissing('institutionUser.institution');
        $cec = $reg->institutionUser->institution ?? null;
        if ($cec === null) {
            Log::channel('sifec')->warning('Paraphe registre: institution CEC introuvable', [
                'code_registre' => $reg->code_registre,
            ]);

            return response()->json([
                'code' => '181',
                'message' => 'Impossible de vérifier le centre d\'état civil pour ce registre.',
            ]);
        }

        $parentCode = $cec->code_institution_parent;
        $userInst = Auth::user()->affectationActive()->institution ?? null;
        if (! $parentCode || ! $userInst || $userInst->code_institution !== $parentCode) {
            Log::channel('sifec')->info('Paraphe registre: refus (tribunal de ressort)', [
                'code_registre' => $reg->code_registre,
                'user_institution' => $userInst->code_institution ?? null,
                'attendu_parent_cec' => $parentCode,
            ]);

            return response()->json([
                'code' => '181',
                'message' => 'Vous ne pouvez parapher que les registres des centres relevant de votre tribunal de ressort.',
            ]);
        }

        return null;
    }

    /**
     * @return JsonResponse|null
     */
    private function jsonIfOtpLocked(Registre $reg)
    {
        if (! $reg->otp_locked_until) {
            return null;
        }

        $lockedUntil = $reg->otp_locked_until instanceof Carbon
            ? $reg->otp_locked_until
            : Carbon::parse($reg->otp_locked_until);

        if (now()->lessThan($lockedUntil)) {
            $retryAfter = max(1, $lockedUntil->getTimestamp() - now()->getTimestamp());

            return response()->json([
                'code' => '184',
                'message' => 'Suite à des tentatives infructueuses, veuillez attendre avant de demander un nouveau code ou de réessayer.',
                'retry_after_seconds' => $retryAfter,
                'remaining_attempts' => 0,
                'otp_max_attempts' => self::OTP_MAX_ATTEMPTS,
                'attempts_used' => self::OTP_MAX_ATTEMPTS,
            ]);
        }

        return null;
    }

    /**
     * Verrouille le paraphe OTP après 3 actions (code incorrect et/ou renvoi avec code encore valide).
     */
    private function applyParapheOtpLockout(Registre $reg): JsonResponse
    {
        $reg->otp_paraphage = null;
        $reg->otp_expire_at = null;
        $reg->otp_paraphage_attempts = 0;
        $reg->otp_locked_until = now()->addMinutes(self::OTP_LOCKOUT_MINUTES);
        $reg->save();

        Log::channel('sifec')->warning('Registre paraphe: verrouillage après quota OTP', [
            'code_registre' => $reg->code_registre,
        ]);

        return response()->json([
            'code' => '184',
            'message' => 'Nombre maximal de tentatives atteint (code incorrect et/ou renvois). Vous pourrez demander un nouveau code dans '.self::OTP_LOCKOUT_MINUTES.' minute(s).',
            'retry_after_seconds' => self::OTP_LOCKOUT_MINUTES * 60,
            'remaining_attempts' => 0,
            'otp_max_attempts' => self::OTP_MAX_ATTEMPTS,
            'attempts_used' => self::OTP_MAX_ATTEMPTS,
        ]);
    }

    private function registerFailedParapheOtpAttempt(Registre $reg): JsonResponse
    {
        $attempts = (int) $reg->otp_paraphage_attempts + 1;
        $reg->otp_paraphage_attempts = $attempts;

        if ($attempts >= self::OTP_MAX_ATTEMPTS) {
            return $this->applyParapheOtpLockout($reg);
        }

        $reg->save();

        return response()->json([
            'code' => '183',
            'message' => 'Code OTP incorrect.',
            'remaining_attempts' => (int) (self::OTP_MAX_ATTEMPTS - $attempts),
            'otp_max_attempts' => (int) self::OTP_MAX_ATTEMPTS,
            'attempts_used' => (int) $attempts,
        ]);
    }

    public function cloturerRegistre(Request $request)
    {
        $rules = [
            'date_cloture' => ['required', 'date'],
            'code_registre' => ['required', 'string'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'code' => '180',
                'message' => 'Aucun registre trouvé pour ce code',
            ]);
        }

        // if(!Gate::allows("module.fonctionnalites.registre.cloture")){
        //     return response()->json([
        //         "code"=>"181",
        //         "message"=>"Vous n'êtes pas autorisé à clôturer un registre"
        //     ]);
        // }

        $code_reg = $request->code_registre;
        $datecloture = $request->date_cloture;

        $reg = Registre::where('code_registre', $code_reg)->first();

        if ($reg == null) {
            return response()->json([
                'code' => '182',
                'message' => "Aucun registre trouvé pour ce code $code_reg",
            ]);
        }

        try {

            $reg->signature_cloture_cec = Auth::user()->personne->signature;
            $reg->cloture_cec = Auth::user()->affectationActive()->cui;
            $reg->date_fermeture = $datecloture;
            $reg->statut = 0;
            $reg->save();

            return response()->json([
                'code' => '200',
                'message' => 'Registre de '.$reg->typeRegistre->lib_type_registre.' est clôturé avec succès',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'code' => '183',
                'message' => $e->getMessage(),
            ]);
        }

    }

    public function registreNaissance($id)
    {
        $registre = Registre::find($id);
        $dummy = 'XXXXXXXXXXXXXXXX';

        // Récupérer les actes triés par position dans le registre (4 derniers caractères du code_acte dans t_feuillet_registre)
        // Les 4 derniers caractères du code_acte représentent la position dans le registre
        $actesRegistre = ActeNaissance::where('code_registre', $id)
            ->join('t_feuillet_registre', 't_acte_naissance.niupp', '=', 't_feuillet_registre.code_acte')
            ->select('t_acte_naissance.*')
            ->orderByRaw('CAST(RIGHT(t_feuillet_registre.code_acte, 4) AS UNSIGNED) ASC')
            ->get();

        return view('referentiel::registre.registre_acte_naissance', compact('registre', 'actesRegistre', 'dummy'));
    }

    public function feuilletRN($id)
    {
        $acte = ActeNaissance::findByIdentifier($id);
        $dummy = 'XXXXXXXXXXXXXXXX';

        return view('referentiel::registre.feuillet_acte_naissance', compact('acte', 'dummy'));
    }

    public function registreMariage($id)
    {
        $registre = Registre::find($id);

        if ($registre == null) {
            flash()->error('Impossible de charger cette page');

            return back();
        }

        $actes = ActeMariage::where('code_registre', $id)->get();

        return view('referentiel::registre.registre_acte_mariage', compact('actes', [], 'registre'));
    }

    public function feuilletRM($id)
    {
        $acte = ActeMariage::find($id);

        if ($acte == null) {
            flash()->error('Impossible de charger cette page');

            return back();
        }

        return view('referentiel::registre.feuillet_acte_mariage', compact('acte'));
    }

    public function registreDeces($id)
    {
        $registre = Registre::find($id);

        // Récupérer les actes triés par position dans le registre (8 derniers caractères du code_acte_deces dans t_feuillet_registre)
        // Les 8 derniers caractères du code_acte_deces représentent la position dans le registre (ex: AD_00000001 → position 1)
        $actesRegistre = ActeDeces::where('code_registre', $id)
            ->join('t_feuillet_registre', 't_acte_deces.code_acte_deces', '=', [], 't_feuillet_registre.code_acte')
            ->select('t_acte_deces.*')
            ->orderByRaw('CAST(RIGHT(t_feuillet_registre.code_acte, 8) AS UNSIGNED) ASC')
            ->get();

        return view('referentiel::registre.registre_acte_deces', compact('registre', 'actesRegistre'));
    }

    public function feuilletRD($id)
    {
        $acteReg = ActeDeces::find($id);

        return view('referentiel::registre.feuillet_acte_deces', compact('acteReg'));
    }

    public function registresTribunal(Request $request)
    {
        $inst = Auth::user()->affectationActive()->institution;
        $inst->loadMissing('typeInstitution');

        $descendants = $inst->descendants();

        $centresEtatCivilJuridiction = $descendants
            ->filter(fn (Institution $d) => $d->typeInstitution?->code_type_categorie_ins === self::CODE_TYPE_CATEGORIE_INS_CEC)
            ->unique('code_institution')
            ->sortBy('lib_institution', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        $codesRegistres = $descendants->map->registres()->flatten()->pluck('code_registre')->unique()->filter()->values();

        if ($codesRegistres->isEmpty()) {
            $registres = collect();
        } else {
            $query = Registre::query()
                ->whereIn('code_registre', $codesRegistres)
                ->with(['typeRegistre', 'institutionUser.institution']);

            if ($request->filled('code_type_registre')) {
                $query->where('code_type_registre', $request->string('code_type_registre')->toString());
            }

            if ($request->filled('code_institution')) {
                $codeInst = $request->string('code_institution')->toString();
                $allowed = $centresEtatCivilJuridiction->pluck('code_institution')->all();
                if (! in_array($codeInst, $allowed, true)) {
                    abort(403, 'Centre d’état civil hors juridiction.');
                }
                $query->whereHas('institutionUser', fn ($q) => $q->where('code_institution', $codeInst));
            }

            if ($request->filled('annee')) {
                $year = (int) $request->input('annee');
                $currentY = (int) date('Y');
                if ($year >= 1900 && $year <= $currentY + 1) {
                    $query->where(function ($q) use ($year) {
                        $q->whereYear('date_ouverture', $year)
                            ->orWhere(function ($q2) use ($year) {
                                $q2->whereNull('date_ouverture')->whereYear('created_at', $year);
                            });
                    });
                }
            }

            if ($request->filled('statut_registre')) {
                switch ($request->input('statut_registre')) {
                    case 'en_attente_validation':
                        $query->where('statut', 0)->whereNull('approbation_tribunal');
                        break;
                    case 'actif':
                        $query->where('statut', 1)->whereNotNull('approbation_tribunal');
                        break;
                    case 'cloture':
                        $query->whereNotNull('signature_cloture_cec')->where('signature_cloture_cec', '!=', '');
                        break;
                }
            }

            if ($request->filled('recherche')) {
                $term = trim($request->string('recherche')->toString());
                if ($term !== '') {
                    $like = '%'.addcslashes($term, '%_\\').'%';
                    $query->where('lib_registre', 'like', $like);
                }
            }

            $registres = $query
                ->orderByDesc('date_ouverture')
                ->orderBy('lib_registre')
                ->get();
        }

        $typeRegistres = TypeRegistre::orderBy('lib_type_registre')->get();
        $typeRegistres_vide = [];
        $vueTribunalRegistres = true;
        $anneesFiltre = collect(range((int) date('Y'), (int) date('Y') - 25));

        return view('referentiel::registre.index', compact(
            'registres',
            'typeRegistres',
            'typeRegistres_vide',
            'centresEtatCivilJuridiction',
            'vueTribunalRegistres',
            'anneesFiltre'
        ));
    }

    // ajout de feuilles du registre au cours de la même année
    public function AddFeuilletRegistre(Request $request)
    {
        $rules = [
            'nbrefeuillets' => ['required'],
            'code_registre' => ['required', 'string'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'code' => '180',
                'message' => 'Aucun registre trouvé pour ce code',
            ]);
        }

        // if(!Gate::allows("module.fonctionnalites.registre.cloture")){
        //     return response()->json([
        //         "code"=>"181",
        //         "message"=>"Vous n'êtes pas autorisé à clôturer un registre"
        //     ]);
        // }

        $code_reg = $request->code_registre;
        $nbrefeuillets = $request->nbrefeuillets;

        $reg = Registre::where('code_registre', $code_reg)->first();
        if ($reg == null) {
            return response()->json([
                'code' => '182',
                'message' => "Aucun registre trouvé pour ce code $code_reg",
            ]);
        }

        try {

            $reg->nombre_acte_prevu = $nbrefeuillets + $reg->nombre_acte_prevu;
            $reg->statut = 1;
            $reg->save();

            // type de registre
            $typeRegistre = $reg->typeRegistre->lib_type_registre;

            // notifier le président du tribunal pour des feuillets ajoutés au registre (notification à titre d'information)
            if ($reg->institutionUser && $reg->institutionUser->institution && $reg->institutionUser->institution->institutionParent) {
                NotificationService::notifierFeuilletRegistreAjoute(
                    $reg->institutionUser->institution->institutionParent,
                    new FeuilletRegistreAjouteNotification($reg, $nbrefeuillets)
                );
            }

            return response()->json([
                'code' => '200',
                'message' => "REGISTRE DE $typeRegistre $nbrefeuillets feuillets ajouté avec succès",
            ]);

        } catch (Exception $e) {
            Log::channel('sifec')->error('[Registre][AddFeuilletRegistre] Erreur', [
                'code_registre' => $code_reg ?? null,
                'message' => $e->getMessage(),
                'exception' => $e::class,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'code' => '183',
                'message' => $e->getMessage(),
            ]);
        }

    }
}
