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
use Modules\Referentiel\Entities\Registre;
use Modules\Referentiel\Entities\TypeRegistre;

class RegistreController extends Controller
{
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

    public function registresTribunal()
    {
        $inst = Auth::user()->affectationActive()->institution;
        $registres = $inst->descendants()->map->registres()->flatten();
        $typeRegistres = TypeRegistre::all();
        $typeRegistres_vide = [];

        return view('referentiel::registre.index', compact('registres', 'typeRegistres', 'typeRegistres_vide'));
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
