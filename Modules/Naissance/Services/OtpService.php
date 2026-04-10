<?php

namespace Modules\Naissance\Services;

use App\Sifec\SifecFacade;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Exceptions\ActeNaissanceOtpLockedException;
use Modules\Notification\Jobs\DeclarantActeDisponibleInformationJob;
use Modules\Notification\Jobs\ValidationacteNaissanceJob;

class OtpService
{
    private const OTP_VALID_MINUTES = 1;

    /** Renvois de code tant que l’OTP précédent est encore valide (3ᵉ renvoi → verrouillage). */
    private const OTP_MAX_RESEND = 3;

    /** Saisies incorrectes avant verrouillage. */
    private const OTP_MAX_WRONG = 3;

    private const OTP_LOCKOUT_MINUTES = 3;

    /**
     * Prépare ou envoie l’OTP pour validation d’acte(s) à l’officier connecté : SMS + e-mail(s) valides
     * (professionnel et/ou personnel, sans doublon, sur toutes les fiches contact actives via Personne::adressesEmailPourNotificationAgregees).
     *
     * @param  bool  $demandeRenvoi  true uniquement si l’utilisateur a cliqué « Renvoyer le code »
     * @return array{kind: 'reused'|'sent', valid_for_seconds: int, row_count: int}
     *
     * @throws \Exception Si contact, téléphone ou au moins un e-mail valide manque pour l’envoi complet
     */
    public function envoyerOtpValidationActes($user, $actes, bool $demandeRenvoi = false): array
    {
        $codes = collect($actes)->pluck('code_declaration_naissance')->unique()->values();

        $out = DB::transaction(function () use ($codes, $demandeRenvoi) {
            $rows = ActeNaissance::whereIn('code_declaration_naissance', $codes)
                ->lockForUpdate()
                ->get();

            if ($rows->count() !== $codes->count()) {
                throw new \Exception('Un ou plusieurs actes sont introuvables.');
            }

            foreach ($rows as $an) {
                if (! is_null($an->approbation_mairie)) {
                    throw new \Exception('Un ou plusieurs actes sont déjà validés.');
                }
            }

            $ref = $rows->first();
            $this->assertNotLockedOrThrow($ref);

            $hasActiveOtp = $ref->otp_approbation_mairie
                && $ref->otp_expire_at
                && now()->lessThan(Carbon::parse($ref->otp_expire_at));

            // Réouverture du modal : même code encore valide → pas de SMS, pas d’incrément de renvoi
            if ($hasActiveOtp && ! $demandeRenvoi) {
                $expire = Carbon::parse($ref->otp_expire_at);
                $secondsRemaining = max(0, $expire->getTimestamp() - now()->getTimestamp());

                return [
                    'status' => 'reused',
                    'seconds_remaining' => $secondsRemaining,
                    'row_count' => $rows->count(),
                ];
            }

            if ($hasActiveOtp && $demandeRenvoi) {
                $resend = (int) $ref->otp_mairie_resend_attempts + 1;

                if ($resend >= self::OTP_MAX_RESEND) {
                    $this->applyLockoutToActes($rows);

                    return [
                        'status' => 'locked_resend',
                        'retry_after' => self::OTP_LOCKOUT_MINUTES * 60,
                    ];
                }

                $this->assignOtpSecurityToAll($rows, [
                    'otp_mairie_resend_attempts' => $resend,
                ]);
            } else {
                $this->assignOtpSecurityToAll($rows, [
                    'otp_mairie_resend_attempts' => 0,
                    'otp_mairie_wrong_attempts' => 0,
                    'otp_mairie_locked_until' => null,
                ]);
            }

            $otp = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            $expireAt = now()->addMinutes(self::OTP_VALID_MINUTES);

            foreach ($rows as $acte) {
                $acte->otp_approbation_mairie = $otp;
                $acte->otp_expire_at = $expireAt;
                $acte->save();
            }

            return [
                'status' => 'sent',
                'otp' => $otp,
                'expire_at' => $expireAt,
                'row_count' => $rows->count(),
            ];
        });

        if ($out['status'] === 'locked_resend') {
            throw new ActeNaissanceOtpLockedException(
                'Nombre maximal de demandes de code atteint (renvois). Vous pourrez recommencer dans '.self::OTP_LOCKOUT_MINUTES.' minute(s).',
                (int) $out['retry_after']
            );
        }

        if ($out['status'] === 'reused') {
            return [
                'kind' => 'reused',
                'valid_for_seconds' => max(1, (int) $out['seconds_remaining']),
                'row_count' => (int) $out['row_count'],
            ];
        }

        $otp = $out['otp'];
        $rowCount = (int) $out['row_count'];
        $validForSeconds = max(1, (int) ceil(Carbon::parse($out['expire_at'])->getTimestamp() - now()->getTimestamp()));

        if ($rowCount > 1) {
            $temp = config('sifec.sms.templates.actions.validation_multiples_acte_naissances');
        } else {
            $temp = config('sifec.sms.templates.actions.validation_acte_naissance');
        }
        $temp = str_replace(':maire', $user->personne->nom, $temp);
        $temp = str_replace(':nombre', (string) $rowCount, $temp);
        $temp = str_replace(':code_otp', $otp, $temp);

        $personne = $user->personne;
        $contacts = $personne->contacts()->orderBy('id')->get();
        if ($contacts->isEmpty()) {
            throw new \Exception('Aucune fiche contact pour l’officier : impossible d’envoyer le code OTP.');
        }

        // Téléphone : premier contact actif par id (cohérent avec une fiche « principale »).
        $contact = $contacts->first();
        $telephone = trim((string) ($contact->indicatif ?? '').(string) ($contact->telephone ?? ''));
        if ($telephone === '') {
            throw new \Exception('Numéro de téléphone de l’officier manquant : impossible d’envoyer le code OTP par SMS.');
        }

        // E-mails : toutes les fiches contact (l’OTP ne doit pas dépendre d’un first() aléatoire si plusieurs lignes existent).
        $emails = $personne->adressesEmailPourNotificationAgregees($contacts);
        if ($emails === []) {
            throw new \Exception('E-mail professionnel ou personnel de l’officier manquant ou invalide : impossible d’envoyer le code OTP par e-mail.');
        }

        SifecFacade::sendSms($telephone, $temp);
        // Exécution synchrone : si la file (QUEUE_CONNECTION ≠ sync) n’a pas de worker,
        // l’e-mail OTP ne partait jamais alors que le SMS était envoyé.
        foreach ($emails as $email) {
            ValidationacteNaissanceJob::dispatchSync(
                $personne->nomComplet(),
                $rowCount,
                $otp,
                $email
            );
        }

        return [
            'kind' => 'sent',
            'valid_for_seconds' => $validForSeconds,
            'row_count' => $rowCount,
        ];
    }

    public function validerOtpActes($codes, $otp, ?string $ipAddress = null, ?string $userAgent = null)
    {
        $codes = array_values(array_unique(is_array($codes) ? $codes : [$codes]));

        $out = DB::transaction(function () use ($codes, $otp, $ipAddress, $userAgent) {
            $actes = ActeNaissance::whereIn('code_declaration_naissance', $codes)
                ->lockForUpdate()
                ->get();

            if ($actes->count() === 0) {
                return ['result' => 'fail', 'payload' => 'Aucun acte trouvé'];
            }

            if ($actes->count() !== count($codes)) {
                return ['result' => 'fail', 'payload' => 'Aucun acte trouvé'];
            }

            $premierActe = $actes->first();

            $this->assertNotLockedOrThrow($premierActe);

            if (! is_null($premierActe->approbation_mairie)) {
                return ['result' => 'fail', 'payload' => 'Cet acte a déjà été validé.'];
            }

            if (is_null($premierActe->otp_approbation_mairie)) {
                return ['result' => 'fail', 'payload' => 'Code OTP expiré. Veuillez en demander un nouveau.'];
            }

            if ($premierActe->otp_expire_at && now()->gt(Carbon::parse($premierActe->otp_expire_at))) {
                return ['result' => 'fail', 'payload' => 'Code OTP expiré (1 minute). Veuillez en demander un nouveau.'];
            }

            if ((string) $otp !== (string) $premierActe->otp_approbation_mairie) {
                $wrong = (int) $premierActe->otp_mairie_wrong_attempts + 1;
                $this->assignOtpSecurityToAll($actes, [
                    'otp_mairie_wrong_attempts' => $wrong,
                ]);

                if ($wrong >= self::OTP_MAX_WRONG) {
                    $this->applyLockoutToActes($actes);

                    return [
                        'result' => 'locked_validate',
                        'retry_after' => self::OTP_LOCKOUT_MINUTES * 60,
                    ];
                }

                $remaining = self::OTP_MAX_WRONG - $wrong;

                return [
                    'result' => 'fail',
                    'payload' => [
                        'error' => 'Code de validation incorrect. Il vous reste '.$remaining.' tentative(s) avant verrouillage temporaire.',
                        'remaining_validate_attempts' => $remaining,
                        'otp_max_validate' => self::OTP_MAX_WRONG,
                        'attempts_used_validate' => $wrong,
                    ],
                ];
            }

            $user = Auth::user();
            $affectation = $user->affectationActive();
            $cui = $affectation ? $affectation->cui : null;
            $signature = $user->personne->signature ?? null;

            if (is_null($cui)) {
                return ['result' => 'fail', 'payload' => 'Validation impossible : aucune affectation active trouvée pour cet officier.'];
            }
            if (is_null($signature)) {
                return ['result' => 'fail', 'payload' => 'Validation impossible : la signature numérique de l\'officier est absente. Veuillez contacter l\'administrateur.'];
            }

            $finalizer = app(ActeNaissanceSignatureFinalizer::class);
            $mouvementService = app(\Modules\Naissance\Services\MouvementService::class);

            try {
                foreach ($actes as $an) {
                    $finalizer->assignNiuppFeuilletRegistre($an, $user);
                    $an->refresh();

                    $an->approbation_mairie = $cui;
                    $an->signature_mairie = $signature;
                    $an->date_heure_approbation_mairie = now();
                    // Conserver otp_approbation_mairie : preuve d’authentification affichée via QR / page de vérification
                    $an->otp_expire_at = null;
                    $an->otp_mairie_resend_attempts = 0;
                    $an->otp_mairie_wrong_attempts = 0;
                    $an->otp_mairie_locked_until = null;
                    $an->adresse_mac_approbation = $ipAddress;
                    $an->nom_appareil_approbation = $this->simplifierUserAgent($userAgent);
                    $an->save();

                    $mouvementService->ajouterEvenementActe($user, $an->declaration, 'non_retiré');
                }
            } catch (\Throwable $e) {
                Log::channel('sifec')->error('Validation OTP acte naissance : '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);

                return ['result' => 'fail', 'payload' => $e->getMessage()];
            }

            return ['result' => 'ok', 'codes' => $codes];
        });

        if ($out['result'] === 'locked_validate') {
            throw new ActeNaissanceOtpLockedException(
                'Nombre maximal de tentatives de saisie du code atteint. Vous pourrez recommencer dans '.self::OTP_LOCKOUT_MINUTES.' minute(s).',
                (int) $out['retry_after']
            );
        }

        if ($out['result'] === 'fail') {
            return [false, $out['payload']];
        }

        $user = Auth::user();
        $actes = ActeNaissance::whereIn('code_declaration_naissance', $out['codes'])->get();

        foreach ($actes as $an) {
            $contactDeclarant = $an->declaration->declarant->contacts->first();
            if ($contactDeclarant !== null) {
                $temp = config('sifec.sms.templates.actions.acte_naissance');
                $temp = str_replace(':declarant', $an->declaration->declarant->nomcomplet(), $temp);
                $temp = str_replace(':code_acte_naissance', $an->niupp ?? '', $temp);
                $temp = str_replace(':libCec', $an->institutionUser->institution->lib_institution, $temp);
                SifecFacade::sendSms($contactDeclarant->indicatif.$contactDeclarant->telephone, $temp);
                $emailsDecl = $contactDeclarant->adressesEmailPourNotification();
                if ($emailsDecl !== []) {
                    dispatch(new DeclarantActeDisponibleInformationJob(
                        $emailsDecl,
                        $temp,
                        'SIFEC — Acte de naissance disponible'
                    ));
                }
            }
        }

        return [true, $actes];
    }

    private function assertNotLockedOrThrow(ActeNaissance $acte): void
    {
        if (! $acte->otp_mairie_locked_until) {
            return;
        }

        $lockedUntil = Carbon::parse($acte->otp_mairie_locked_until);
        if (now()->greaterThanOrEqualTo($lockedUntil)) {
            return;
        }

        $retryAfter = max(1, $lockedUntil->getTimestamp() - now()->getTimestamp());

        throw new ActeNaissanceOtpLockedException(
            'Suite à des tentatives infructueuses, veuillez attendre avant de demander un nouveau code ou de réessayer.',
            $retryAfter
        );
    }

    /**
     * @param \Illuminate\Support\Collection|iterable $actes
     */
    private function applyLockoutToActes($actes): void
    {
        foreach ($actes as $acte) {
            $acte->otp_approbation_mairie = null;
            $acte->otp_expire_at = null;
            $acte->otp_mairie_resend_attempts = 0;
            $acte->otp_mairie_wrong_attempts = 0;
            $acte->otp_mairie_locked_until = now()->addMinutes(self::OTP_LOCKOUT_MINUTES);
            $acte->save();
        }

        Log::channel('sifec')->warning('[ActeNaissance][OTP] Verrouillage après quota', [
            'lockout_minutes' => self::OTP_LOCKOUT_MINUTES,
        ]);
    }

    /**
     * @param \Illuminate\Support\Collection|iterable $actes
     */
    private function assignOtpSecurityToAll($actes, array $fields): void
    {
        foreach ($actes as $acte) {
            foreach ($fields as $key => $value) {
                $acte->{$key} = $value;
            }
            $acte->save();
        }
    }

    private function simplifierUserAgent(?string $ua): ?string
    {
        if (! $ua) {
            return null;
        }

        if (preg_match('/Android/i', $ua)) {
            $os = 'Android';
        } elseif (preg_match('/iPhone|iPad/i', $ua)) {
            $os = 'iOS';
        } elseif (preg_match('/Windows/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/Macintosh|Mac OS/i', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/Linux/i', $ua)) {
            $os = 'Linux';
        } else {
            $os = 'Inconnu';
        }

        if (preg_match('/Edg\//i', $ua)) {
            $browser = 'Edge';
        } elseif (preg_match('/OPR\//i', $ua)) {
            $browser = 'Opera';
        } elseif (preg_match('/Chrome\//i', $ua) && ! preg_match('/Chromium/i', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Firefox\//i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Safari\//i', $ua) && ! preg_match('/Chrome/i', $ua)) {
            $browser = 'Safari';
        } else {
            $browser = 'Navigateur';
        }

        return "{$browser} / {$os}";
    }
}
