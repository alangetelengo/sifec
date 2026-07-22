<?php

namespace Modules\Deces\Services;

use App\Models\User;
use App\Sifec\SifecFacade;
use App\Services\GuotDocumentSignatureService;
use App\Support\GuotSignataires;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Deces\Entities\ActeDeces;
use Modules\Notification\Jobs\DeclarantActeDisponibleInformationJob;
use Modules\Notification\Jobs\ValidationacteDecesJob;
use PkiSdk\TrustException;

/**
 * Validation / signature électronique d'un acte de décès — flux .p12 (L2 + L3),
 * en remplacement de l'ancien flux OTP.
 */
class ActeDecesGuotValidationService
{
    public function __construct(
        private GuotDocumentSignatureService $guot,
        private ActeDecesPdfRenderer $pdfRenderer,
        private MouvementService $mouvementService,
    ) {}

    /**
     * @param  list<string>  $codesDeclaration
     * @return array{ok: bool, message: string, token?: string, expected_serial?: string|null, items?: list<array{code_declaration: string, document_hash: string, libelle: string}>}
     */
    public function prepare(User $user, array $codesDeclaration): array
    {
        $ctx = $this->assertSignerContext($user);
        if (! ($ctx['ok'] ?? false)) {
            return ['ok' => false, 'message' => $ctx['message'], 'items' => []];
        }

        $codes = array_values(array_unique(array_filter(array_map('strval', $codesDeclaration))));
        if ($codes === []) {
            return ['ok' => false, 'message' => 'Aucun acte à signer.', 'items' => []];
        }

        $cacheItems = [];
        $publicItems = [];

        foreach ($codes as $code) {
            try {
                $prepared = $this->prepareUn($user, $code);
            } catch (Exception $e) {
                return ['ok' => false, 'message' => $code.': '.$e->getMessage(), 'items' => []];
            }

            $cacheItems[$code] = $prepared['cache'];
            $publicItems[] = $prepared['public'];
        }

        $token = Str::random(40);
        Cache::put($this->cacheKey($user, $token), [
            'items' => $cacheItems,
            'actor_id' => $ctx['actor_id'],
            'institution_id' => $ctx['institution_id'],
            'cui' => $ctx['cui'],
            'cert_serial' => $ctx['cert_serial'],
            'codes' => $codes,
        ], now()->addMinutes(15));

        return [
            'ok' => true,
            'message' => 'Prêt à signer. Sélectionnez votre certificat (.p12) et validez.',
            'token' => $token,
            'expected_serial' => $ctx['cert_serial'],
            'items' => $publicItems,
        ];
    }

    /**
     * @param  list<array{code_declaration?: string, code_acte?: string, signature_hex: string}>  $signatures
     * @return array{ok: bool, message: string, signed: int}
     */
    public function finalize(
        User $user,
        string $token,
        array $signatures,
        ?string $ip = null,
        ?string $userAgent = null,
    ): array {
        $ctx = $this->assertSignerContext($user);
        if (! ($ctx['ok'] ?? false)) {
            return ['ok' => false, 'message' => $ctx['message'], 'signed' => 0];
        }

        $prep = Cache::get($this->cacheKey($user, $token));
        if (! is_array($prep) || empty($prep['items'])) {
            return ['ok' => false, 'message' => 'Session de signature expirée. Rouvrez la validation et recommencez.', 'signed' => 0];
        }

        $byCode = [];
        foreach ($signatures as $row) {
            if (! is_array($row)) {
                continue;
            }
            $code = (string) ($row['code_declaration'] ?? $row['code_acte'] ?? '');
            $hex = (string) ($row['signature_hex'] ?? '');
            if ($code !== '' && $hex !== '') {
                $byCode[$code] = $hex;
            }
        }

        $signed = 0;
        $errors = [];
        $signedActes = [];

        foreach ($prep['codes'] as $code) {
            $code = (string) $code;
            if (! isset($byCode[$code])) {
                $errors[] = $code.': signature manquante';
                continue;
            }
            if (! isset($prep['items'][$code])) {
                $errors[] = $code.': préparation introuvable';
                continue;
            }

            try {
                $acte = $this->finalizeUn(
                    $user,
                    $code,
                    $prep['items'][$code],
                    $byCode[$code],
                    (string) $prep['cui'],
                    (string) $prep['actor_id'],
                    (string) $prep['institution_id'],
                    $ip,
                    $userAgent,
                );
                $signed++;
                $signedActes[] = $acte;
            } catch (TrustException $e) {
                Log::channel('sifec')->error('Signature acte décès (trust-api)', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            } catch (Exception $e) {
                Log::channel('sifec')->error('Signature acte décès', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            }
        }

        if ($signed > 0) {
            Cache::forget($this->cacheKey($user, $token));
            $this->notifierDeclarants($user, $signedActes);
        }

        if ($signed === 0) {
            return [
                'ok' => false,
                'message' => $errors !== [] ? implode(' | ', $errors) : 'Aucun acte signé.',
                'signed' => 0,
            ];
        }

        $message = $signed.' acte(s) de décès signé(s) électroniquement.';
        if ($errors !== []) {
            $message .= ' Échecs : '.implode(' | ', $errors);
        }

        return ['ok' => true, 'message' => $message, 'signed' => $signed];
    }

    /**
     * @return array{ok: bool, message?: string, actor_id?: string, institution_id?: string, cui?: string, cert_serial?: string|null}
     */
    private function assertSignerContext(User $user): array
    {
        if (! $this->guot->isConfigured()) {
            return ['ok' => false, 'message' => 'Service de signature électronique non configuré. Contactez l’administrateur.'];
        }

        $affectation = $user->affectationActive();
        if ($affectation === null || ! filled($affectation->cui)) {
            return ['ok' => false, 'message' => 'Aucune affectation active (CUI) pour cet utilisateur.'];
        }

        if (! GuotSignataires::isSignataire($affectation->code_fonction)) {
            return ['ok' => false, 'message' => 'Votre fonction n’est pas autorisée à signer électroniquement.'];
        }

        if (! filled($affectation->guot_user_id)) {
            return ['ok' => false, 'message' => 'Vous n’avez pas de certificat numérique actif. Demandez l’activation depuis votre profil.'];
        }

        $affectation->loadMissing('institution');
        $institutionId = $affectation->institution?->guot_institution_id;
        if (! filled($institutionId)) {
            return [
                'ok' => false,
                'message' => 'Cachet institutionnel manquant : configurez-le sur la fiche de l’institution avant de signer.',
            ];
        }

        return [
            'ok' => true,
            'actor_id' => (string) $affectation->guot_user_id,
            'institution_id' => (string) $institutionId,
            'cui' => (string) $affectation->cui,
            'cert_serial' => $affectation->guot_user_cert_serial ? (string) $affectation->guot_user_cert_serial : null,
        ];
    }

    private function cacheKey(User $user, string $token): string
    {
        return 'acte_deces_p12:'.$user->code_user.':'.$token;
    }

    private function assertActeAppartientAInstitution(ActeDeces $acte, User $user): void
    {
        $instCode = (string) ($user->affectationActive()?->institution?->code_institution ?? '');
        $declaration = $acte->declaration;
        $portee = array_filter([
            (string) ($declaration?->code_institution ?? ''),
            (string) ($declaration?->code_institution_destinataire ?? ''),
        ]);

        if ($instCode === '' || ! in_array($instCode, $portee, true)) {
            throw new Exception('Cet acte de décès ne relève pas de votre institution.');
        }
    }

    /**
     * @return array{cache: array{pdf: string, hash: string, signature_pompe_funebre: ?string}, public: array{code_declaration: string, document_hash: string, libelle: string}}
     */
    private function prepareUn(User $user, string $codeDeclaration): array
    {
        /** @var ActeDeces|null $acte */
        $acte = ActeDeces::query()
            ->where('code_declaration_deces', $codeDeclaration)
            ->first();

        if ($acte === null) {
            throw new Exception('Acte de décès introuvable. Générez d’abord l’acte.');
        }

        $this->assertActeAppartientAInstitution($acte, $user);

        if (filled($acte->approbation_pompe_funebre) && filled($acte->proof_id)) {
            throw new Exception('Acte déjà signé électroniquement.');
        }

        $user->loadMissing('personne');

        $signaturePf = filled($acte->signature_pompe_funebre)
            ? (string) $acte->signature_pompe_funebre
            : (filled($user->personne?->signature) ? (string) $user->personne->signature : null);
        $acte->approbation_pompe_funebre = $acte->approbation_pompe_funebre ?: (string) $user->affectationActive()?->cui;
        $acte->signature_pompe_funebre = $signaturePf;
        $acte->date_heure_approbation_pompe_funebre = now();

        $pdfBinary = $this->pdfRenderer->renderBinary($acte);
        $hash = hash('sha256', $pdfBinary);

        $defunt = trim(($acte->declaration?->defunt?->nom ?? '').' '.($acte->declaration?->defunt?->prenom ?? ''));

        return [
            'cache' => [
                'pdf' => base64_encode($pdfBinary),
                'hash' => $hash,
                'signature_pompe_funebre' => $signaturePf,
            ],
            'public' => [
                'code_declaration' => $codeDeclaration,
                'document_hash' => $hash,
                'libelle' => $defunt !== '' ? $defunt : $codeDeclaration,
            ],
        ];
    }

    /**
     * @param  array{pdf: string, hash: string, signature_pompe_funebre: ?string}  $prepared
     */
    private function finalizeUn(
        User $user,
        string $codeDeclaration,
        array $prepared,
        string $signatureHex,
        string $cui,
        string $actorId,
        string $institutionId,
        ?string $ip,
        ?string $userAgent,
    ): ActeDeces {
        return DB::transaction(function () use ($user, $codeDeclaration, $prepared, $signatureHex, $cui, $actorId, $institutionId, $ip, $userAgent) {
            /** @var ActeDeces|null $acte */
            $acte = ActeDeces::query()
                ->where('code_declaration_deces', $codeDeclaration)
                ->lockForUpdate()
                ->first();

            if ($acte === null) {
                throw new Exception('Acte de décès introuvable.');
            }

            $this->assertActeAppartientAInstitution($acte, $user);

            if (filled($acte->approbation_pompe_funebre) && filled($acte->proof_id)) {
                throw new Exception('Acte déjà signé électroniquement.');
            }

            $hash = (string) $prepared['hash'];
            $pdfBinary = base64_decode((string) $prepared['pdf'], true);
            if ($pdfBinary === false || $pdfBinary === '') {
                throw new Exception('PDF préparé invalide.');
            }
            if (hash('sha256', $pdfBinary) !== $hash) {
                throw new Exception('Intégrité du PDF compromise.');
            }

            $user->loadMissing('personne');
            $actorNom = trim(($user->personne?->nom ?? '').' '.($user->personne?->prenom ?? '')) ?: (string) $user->email;
            $documentRef = 'acte_deces_'.$acte->code_acte_deces;

            $l2 = $this->guot->verifyClientDocumentSignature(
                $hash,
                $signatureHex,
                $actorId,
                'signature_acte_deces',
                $documentRef,
            );

            $l3 = $this->guot->sealDocument(
                $hash,
                $institutionId,
                'signature_acte_deces',
                $documentRef,
                is_string($l2['doc_sig_id'] ?? null) ? $l2['doc_sig_id'] : null,
            );

            $acte->approbation_pompe_funebre = $cui;
            if (! filled($acte->signature_pompe_funebre) && filled($prepared['signature_pompe_funebre'])) {
                $acte->signature_pompe_funebre = $prepared['signature_pompe_funebre'];
            } elseif (! filled($acte->signature_pompe_funebre) && filled($user->personne?->signature)) {
                $acte->signature_pompe_funebre = $user->personne->signature;
            }
            $acte->date_heure_approbation_pompe_funebre = now();
            $acte->adresse_mac_approbation = $ip;
            $acte->nom_appareil_approbation = $this->simplifyUa($userAgent);

            $acte->proof_id = $l2['proof_id'] ?? ('sifec:deces:'.$codeDeclaration);
            $acte->payload_hash = $hash;
            $acte->actor_id = $actorId;
            $acte->actor_nom = $actorNom;
            $acte->certificate_ref = $l2['certificate_ref'] ?? null;
            $acte->signed_at = $l2['signed_at'] ?? now();
            $acte->rfc3161_l1_serial = $l2['rfc3161_serial'] ?? ($l2['timestamp_serial'] ?? null);
            $acte->otp_expire_at = null;
            $acte->otp_approbation_pompe_funebre = null;

            $path = 'actes_deces/'.now()->format('Y/m').'/'.$acte->code_acte_deces.'.pdf';
            Storage::disk('local')->put($path, $pdfBinary);

            $acte->pdf_content_hash = $hash;
            $acte->doc_sig_id = $l2['doc_sig_id'] ?? null;
            $acte->doc_sig_signed_at = $l2['signed_at'] ?? now();
            $acte->rfc3161_l2_serial = $l2['rfc3161_serial'] ?? ($l2['timestamp_serial'] ?? null);
            $acte->doc_seal_id = $l3['doc_seal_id'] ?? null;
            $acte->doc_seal_sealed_at = $l3['sealed_at'] ?? now();
            $acte->rfc3161_l3_serial = $l3['rfc3161_serial'] ?? ($l3['timestamp_serial'] ?? null);
            $acte->pdf_path = $path;
            $acte->save();

            $this->mouvementService->ajouterEvenementActe($user, $acte->declaration, 'non_retiré', null, $acte);

            Log::channel('sifec')->info('Acte décès .p12 OK', [
                'code' => $acte->code_acte_deces,
                'doc_sig_id' => $acte->doc_sig_id,
                'doc_seal_id' => $acte->doc_seal_id,
                'ip' => $ip,
            ]);

            return $acte;
        });
    }

    /** @param  list<ActeDeces>  $actes */
    private function notifierDeclarants(User $user, array $actes): void
    {
        foreach ($actes as $ad) {
            $ad->loadMissing(['declaration.declarant.contacts', 'declaration.defunt', 'institution']);
            $contactDeclarant = SifecFacade::contactPourNotification($ad->declaration?->declarant);
            $msisdn = SifecFacade::msisdnFromContact($contactDeclarant);
            if ($msisdn === null) {
                continue;
            }

            $temp = config('sifec.sms.templates.actions.acte_deces');
            $temp = str_replace(':declarant', $ad->declaration->declarant->nomcomplet(), $temp);
            $temp = str_replace(':code_acte_deces', $ad->code_acte_deces, $temp);
            $temp = str_replace(':defunt', $ad->declaration->defunt->nomcomplet(), $temp);
            $temp = str_replace(':libCec', $ad->institution->lib_institution, $temp);
            SifecFacade::sendSms($msisdn, $temp);

            $emailsDecl = $contactDeclarant?->adressesEmailPourNotification() ?? [];
            if ($emailsDecl !== []) {
                dispatch(new DeclarantActeDisponibleInformationJob(
                    $emailsDecl,
                    $temp,
                    'SIFEC — Acte de décès disponible'
                ));
            }

            dispatch(new ValidationacteDecesJob(
                $user->personne->nomComplet(),
                1,
                '',
                $contactDeclarant?->email_professionnelle
            ));
        }
    }

    private function simplifyUa(?string $ua): ?string
    {
        if ($ua === null || $ua === '') {
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

        return mb_substr("{$browser} / {$os}", 0, 100);
    }
}
