<?php

namespace Modules\Naissance\Services;

use App\Models\User;
use App\Services\GuotDocumentSignatureService;
use App\Support\GuotSignatureAffichage;
use App\Support\GuotSignataires;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Naissance\Entities\ActeNaissance;
use PkiSdk\TrustException;

/**
 * Validation / signature électronique d'un acte de naissance — flux .p12 local + cachet institutionnel.
 */
class ActeNaissanceGuotValidationService
{
    public function __construct(
        private GuotDocumentSignatureService $guot,
        private ActeNaissanceSignatureFinalizer $finalizer,
        private ActeNaissancePdfRenderer $pdfRenderer,
        private MouvementService $mouvementService,
        private DeclarantActeNaissanceNotificationService $declarantNotification,
    ) {}

    /**
     * Prépare les empreintes PDF à signer localement (étape 1).
     *
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

        $user->loadMissing('personne');
        $cui = (string) $ctx['cui'];
        $actorId = (string) $ctx['actor_id'];
        $certSerial = (string) ($ctx['cert_serial'] ?? '');

        $cacheItems = [];
        $publicItems = [];

        foreach ($codes as $code) {
            try {
                $prepared = $this->prepareUn($user, $code, $cui, $actorId, $certSerial);
            } catch (Exception $e) {
                return ['ok' => false, 'message' => $code.': '.$e->getMessage(), 'items' => []];
            }

            $cacheItems[$code] = $prepared['cache'];
            $publicItems[] = $prepared['public'];
        }

        $token = Str::random(40);
        Cache::put($this->cacheKey($user, $token), [
            'items' => $cacheItems,
            'actor_id' => $actorId,
            'institution_id' => $ctx['institution_id'],
            'cui' => $cui,
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
     * Finalise après signature locale .p12 (étape 3).
     *
     * @param  list<array{code_declaration?: string, code_registre?: string, signature_hex: string}>  $signatures
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
            $code = (string) ($row['code_declaration'] ?? $row['code_registre'] ?? '');
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
                Log::channel('sifec')->error('Signature acte naissance (trust-api)', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            } catch (Exception $e) {
                Log::channel('sifec')->error('Signature acte naissance', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            }
        }

        if ($signed > 0) {
            Cache::forget($this->cacheKey($user, $token));
        }

        foreach ($signedActes as $acte) {
            try {
                $this->notifyDeclarant($acte);
            } catch (\Throwable $e) {
                Log::channel('sifec')->warning('Notification déclarant après signature (non bloquant)', [
                    'code' => $acte->code_declaration_naissance,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($signed === 0) {
            return [
                'ok' => false,
                'message' => $errors !== [] ? implode(' | ', $errors) : 'Aucun acte signé.',
                'signed' => 0,
            ];
        }

        $message = $signed.' acte(s) signé(s) électroniquement.';
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
        return 'acte_naissance_p12:'.$user->code_user.':'.$token;
    }

    /**
     * @return array{cache: array{pdf: string, hash: string, payload: array<string, mixed>, signature_mairie: ?string}, public: array{code_declaration: string, document_hash: string, libelle: string}}
     */
    private function prepareUn(User $user, string $codeDeclaration, string $cui, string $actorId, string $certSerial): array
    {
        /** @var ActeNaissance|null $acte */
        $acte = ActeNaissance::query()
            ->where('code_declaration_naissance', $codeDeclaration)
            ->first();

        if ($acte === null) {
            throw new Exception('Acte introuvable.');
        }

        if (filled($acte->approbation_mairie) && filled($acte->proof_id)) {
            throw new Exception('Acte déjà signé électroniquement.');
        }

        // NIUPP prévisionnel pour le rendu du PDF à signer uniquement. L'allocation réelle
        // (incrément du registre + création du feuillet) est différée à la finalisation, une fois
        // la signature .p12 vérifiée, afin de ne pas consommer de numéro en cas d'abandon.
        if (! filled($acte->niupp)) {
            $acte->niupp = $this->finalizer->previewNiupp($acte);
        }

        $user->loadMissing('personne');

        // Attributs en mémoire uniquement (pour le rendu PDF) — persistés à la finalisation.
        $signatureMairie = filled($acte->signature_mairie)
            ? (string) $acte->signature_mairie
            : (filled($user->personne?->signature) ? (string) $user->personne->signature : null);
        $acte->approbation_mairie = $cui;
        $acte->signature_mairie = $signatureMairie;
        $acte->date_heure_approbation_mairie = now();
        GuotSignatureAffichage::applySignerPreview($acte, $user);

        $pdfBinary = $this->pdfRenderer->renderBinary($acte);
        $hash = hash('sha256', $pdfBinary);
        $payload = $this->buildPayload($acte, $actorId, $certSerial);

        $enfant = trim(($acte->declaration?->enfant?->nom ?? '').' '.($acte->declaration?->enfant?->prenom ?? ''));

        return [
            'cache' => [
                'pdf' => base64_encode($pdfBinary),
                'hash' => $hash,
                'payload' => $payload,
                'signature_mairie' => $signatureMairie,
            ],
            'public' => [
                'code_declaration' => $codeDeclaration,
                'document_hash' => $hash,
                'libelle' => $enfant !== '' ? $enfant : $codeDeclaration,
            ],
        ];
    }

    /**
     * @param  array{pdf: string, hash: string, payload: array<string, mixed>, signature_mairie: ?string}  $prepared
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
    ): ActeNaissance {
        $acte = DB::transaction(function () use ($user, $codeDeclaration, $prepared, $signatureHex, $cui, $actorId, $institutionId, $ip, $userAgent) {
            /** @var ActeNaissance|null $acte */
            $acte = ActeNaissance::query()
                ->where('code_declaration_naissance', $codeDeclaration)
                ->lockForUpdate()
                ->first();

            if ($acte === null) {
                throw new Exception('Acte introuvable.');
            }

            if (filled($acte->approbation_mairie) && filled($acte->proof_id)) {
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

            if (! filled($acte->niupp)) {
                $this->finalizer->assignNiuppFeuilletRegistre($acte, $user);
                $acte->refresh();
            }

            // Le PDF signé (préparé) a été rendu avec un NIUPP prévisionnel : quelle que soit la
            // façon dont le NIUPP a finalement été attribué (préparation, OTP ou autre flux entre
            // la préparation et la finalisation), on s'assure qu'il correspond à celui du document
            // signé, sinon on annule (rollback) pour éviter d'enregistrer un acte dont le numéro
            // diffère de celui du PDF signé.
            $niuppAttendu = (string) ($prepared['payload']['niupp'] ?? '');
            if ($niuppAttendu !== '' && (string) $acte->niupp !== $niuppAttendu) {
                throw new Exception('Le NIUPP a changé depuis la préparation (registre modifié entre-temps). Veuillez relancer la signature.');
            }

            $user->loadMissing('personne');
            $actorNom = trim(($user->personne?->nom ?? '').' '.($user->personne?->prenom ?? '')) ?: (string) $user->email;
            $actorFonction = GuotSignatureAffichage::fonctionUtilisateur($user);

            $l2 = $this->guot->verifyClientDocumentSignature(
                $hash,
                $signatureHex,
                $actorId,
                'signature_acte_naissance',
                'acte_naissance_'.($acte->code_acte_naissance ?: $acte->code_declaration_naissance),
            );

            $l3 = $this->guot->sealDocument(
                $hash,
                $institutionId,
                'signature_acte_naissance',
                'acte_naissance_'.($acte->code_acte_naissance ?: $acte->code_declaration_naissance),
                is_string($l2['doc_sig_id'] ?? null) ? $l2['doc_sig_id'] : null,
            );

            $acte->approbation_mairie = $cui;
            if (! filled($acte->signature_mairie) && filled($prepared['signature_mairie'])) {
                $acte->signature_mairie = $prepared['signature_mairie'];
            } elseif (! filled($acte->signature_mairie) && filled($user->personne?->signature)) {
                $acte->signature_mairie = $user->personne->signature;
            }
            $acte->date_heure_approbation_mairie = now();
            $acte->adresse_mac_approbation = $ip;
            $acte->nom_appareil_approbation = $this->simplifyUa($userAgent);

            $acte->proof_id = $l2['proof_id'] ?? ($prepared['payload']['transaction_id'] ?? null);
            $acte->payload_hash = $hash;
            $acte->actor_id = $actorId;
            $acte->actor_nom = $actorNom;
            $acte->actor_fonction = $actorFonction;
            $acte->certificate_ref = $l2['certificate_ref'] ?? null;
            $acte->signed_at = $l2['signed_at'] ?? now();
            $acte->rfc3161_l1_serial = $l2['rfc3161_serial'] ?? ($l2['timestamp_serial'] ?? null);
            $acte->otp_expire_at = null;
            $acte->otp_approbation_mairie = null;

            $path = 'actes_naissance/'.now()->format('Y/m').'/'.($acte->code_acte_naissance ?: $acte->code_declaration_naissance).'.pdf';
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

            $this->mouvementService->ajouterEvenementActe($user, $acte->declaration, 'non_retiré');

            Log::channel('sifec')->info('Acte naissance .p12 OK', [
                'code' => $acte->code_declaration_naissance,
                'doc_sig_id' => $acte->doc_sig_id,
                'doc_seal_id' => $acte->doc_seal_id,
                'ip' => $ip,
            ]);

            return $acte->fresh(['declaration.declarant.contacts', 'institutionUser.institution']);
        });

        return $acte;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPayload(ActeNaissance $acte, string $actorId, string $certFingerprint): array
    {
        $acte->loadMissing(['declaration.enfant', 'registre', 'institutionUser.institution']);

        $payload = [
            'actor_id' => $actorId,
            'cert_fingerprint' => $certFingerprint,
            'document_id' => (string) ($acte->code_acte_naissance ?: $acte->code_declaration_naissance),
            'document_type' => 'acte_naissance',
            'transaction_id' => 'sifec:naissance:'.$acte->code_declaration_naissance,
            'code_declaration' => (string) $acte->code_declaration_naissance,
            'niupp' => (string) ($acte->niupp ?? ''),
            'date_document' => optional($acte->date_emission)->format('Y-m-d') ?? (string) $acte->date_emission,
            'lieu' => (string) ($acte->institutionUser?->institution?->lib_institution ?? ''),
            'numero_registre' => (string) ($acte->registre?->code_registre ?? $acte->code_registre ?? ''),
            'enfant_nom' => (string) ($acte->declaration?->enfant?->nom ?? ''),
            'enfant_prenom' => (string) ($acte->declaration?->enfant?->prenom ?? ''),
        ];
        ksort($payload);

        return $payload;
    }

    private function notifyDeclarant(ActeNaissance $acte): void
    {
        $this->declarantNotification->notify($acte);
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
