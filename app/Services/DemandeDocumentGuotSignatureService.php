<?php

namespace App\Services;

use App\Models\DemandeDocumentConfig;
use App\Models\User;
use App\Support\GuotSignatureAffichage;
use App\Support\GuotSignataires;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Mobile\Entities\DemandeDocument;
use PkiSdk\TrustException;

/**
 * Signature électronique des copies / extraits (demande document) — flux .p12 local + cachet institutionnel.
 * Remplace l’ancien parcours OTP.
 */
class DemandeDocumentGuotSignatureService
{
    public function __construct(
        private GuotDocumentSignatureService $guot,
        private DocumentPdfService $documentPdfService,
        private OtpDemandeDocumentService $otpNotifier,
    ) {}

    /**
     * @param  list<string>  $codesDemandes
     * @return array{ok: bool, message: string, token?: string, expected_serial?: string|null, items?: list<array{code_demande: string, document_hash: string, libelle: string}>}
     */
    public function prepare(User $user, array $codesDemandes): array
    {
        $ctx = $this->assertSignerContext($user);
        if (! ($ctx['ok'] ?? false)) {
            return ['ok' => false, 'message' => $ctx['message'], 'items' => []];
        }

        $codes = array_values(array_unique(array_filter(array_map('strval', $codesDemandes))));
        if ($codes === []) {
            return ['ok' => false, 'message' => 'Aucune demande à signer.', 'items' => []];
        }

        $user->loadMissing('personne');
        $cui = (string) $ctx['cui'];
        $actorId = (string) $ctx['actor_id'];
        $certSerial = (string) ($ctx['cert_serial'] ?? '');
        $affectation = $user->affectationActive();
        $affectation?->loadMissing(['user.personne', 'institution']);

        $cacheItems = [];
        $publicItems = [];

        foreach ($codes as $code) {
            try {
                $this->assertPermission($code);
                $prepared = $this->prepareUn($user, $code, $cui, $actorId, $certSerial, $affectation);
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
     * @param  list<array{code_demande?: string, signature_hex: string}>  $signatures
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
            $code = (string) ($row['code_demande'] ?? '');
            $hex = (string) ($row['signature_hex'] ?? '');
            if ($code !== '' && $hex !== '') {
                $byCode[$code] = $hex;
            }
        }

        $signed = 0;
        $errors = [];
        $signedDemandes = [];

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
                $this->assertPermission($code);
                $demande = $this->finalizeUn(
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
                $signedDemandes[] = $demande;
            } catch (TrustException $e) {
                Log::channel('sifec')->error('Signature demande document (trust-api)', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            } catch (Exception $e) {
                Log::channel('sifec')->error('Signature demande document', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            }
        }

        if ($signed > 0) {
            Cache::forget($this->cacheKey($user, $token));
        }

        foreach ($signedDemandes as $demande) {
            try {
                $this->otpNotifier->notifierDemandeurApresSignature($demande);
            } catch (\Throwable $e) {
                Log::channel('sifec')->warning('Notification demandeur après signature (non bloquant)', [
                    'code' => $demande->code_demande_document,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($signed === 0) {
            return [
                'ok' => false,
                'message' => $errors !== [] ? implode(' | ', $errors) : 'Aucun document signé.',
                'signed' => 0,
            ];
        }

        $message = $signed.' document(s) signé(s) électroniquement.';
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

    private function assertPermission(string $code): void
    {
        $demande = DemandeDocument::where('code_demande_document', $code)->first();
        if ($demande === null) {
            throw new Exception('Demande introuvable.');
        }
        if (! Gate::allows($demande->getPermissionSignature())) {
            throw new Exception('Droits insuffisants pour signer ce type de document.');
        }
    }

    private function cacheKey(User $user, string $token): string
    {
        return 'demande_document_p12:'.$user->code_user.':'.$token;
    }

    /**
     * @return array{cache: array{pdf: string, hash: string}, public: array{code_demande: string, document_hash: string, libelle: string}}
     */
    private function prepareUn(
        User $user,
        string $code,
        string $cui,
        string $actorId,
        string $certSerial,
        $affectation,
    ): array {
        /** @var DemandeDocument|null $demande */
        $demande = DemandeDocument::query()
            ->with(['typeActe', 'typeDocumentDemande', 'institution'])
            ->where('code_demande_document', $code)
            ->first();

        if ($demande === null) {
            throw new Exception('Demande introuvable.');
        }

        if (! $demande->estEnAttenteSignature()) {
            throw new Exception('La demande doit être en attente de signature.');
        }

        if ($demande->estSignee() && filled($demande->proof_id)) {
            throw new Exception('Document déjà signé électroniquement.');
        }

        $user->loadMissing('personne');
        $signatureImg = filled($user->personne?->signature) ? (string) $user->personne->signature : null;

        // Attributs en mémoire pour le rendu PDF de délivrance (persistés à la finalisation)
        $demande->code_signataire = $cui;
        $demande->date_signature = now();
        $demande->signature_officier = $signatureImg;
        if ($affectation !== null) {
            $demande->setRelation('signataire', $affectation);
        }
        GuotSignatureAffichage::applySignerPreview($demande, $user);

        if ($demande->estCopie()) {
            $chemin = $this->documentPdfService->genererCopie($demande);
        } else {
            $chemin = $this->documentPdfService->genererExtrait($demande);
        }

        $pdfBinary = @file_get_contents($chemin);
        if ($pdfBinary === false || $pdfBinary === '') {
            throw new Exception('Échec lecture du PDF préparé.');
        }

        $hash = hash('sha256', $pdfBinary);
        $libelle = trim($demande->getLibelleTypeDocument().' — '.$demande->getLibelleTypeActe().' '.$demande->numero_acte);

        return [
            'cache' => [
                'pdf' => base64_encode($pdfBinary),
                'hash' => $hash,
                'chemin_tmp' => $chemin,
                'signature_officier' => $signatureImg,
            ],
            'public' => [
                'code_demande' => $code,
                'document_hash' => $hash,
                'libelle' => $libelle !== '' ? $libelle : $code,
            ],
        ];
    }

    /**
     * @param  array{pdf: string, hash: string, signature_officier?: ?string}  $prepared
     */
    private function finalizeUn(
        User $user,
        string $code,
        array $prepared,
        string $signatureHex,
        string $cui,
        string $actorId,
        string $institutionId,
        ?string $ip,
        ?string $userAgent,
    ): DemandeDocument {
        return DB::transaction(function () use ($user, $code, $prepared, $signatureHex, $cui, $actorId, $institutionId, $ip, $userAgent) {
            /** @var DemandeDocument|null $demande */
            $demande = DemandeDocument::query()
                ->where('code_demande_document', $code)
                ->lockForUpdate()
                ->first();

            if ($demande === null) {
                throw new Exception('Demande introuvable.');
            }

            if (! $demande->estEnAttenteSignature()) {
                throw new Exception('La demande n’est plus en attente de signature.');
            }

            if ($demande->estSignee() && filled($demande->proof_id)) {
                throw new Exception('Document déjà signé électroniquement.');
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
            $actorFonction = GuotSignatureAffichage::fonctionUtilisateur($user);

            $l2 = $this->guot->verifyClientDocumentSignature(
                $hash,
                $signatureHex,
                $actorId,
                'signature_demande_document',
                'demande_document_'.$code,
            );

            $l3 = $this->guot->sealDocument(
                $hash,
                $institutionId,
                'signature_demande_document',
                'demande_document_'.$code,
                is_string($l2['doc_sig_id'] ?? null) ? $l2['doc_sig_id'] : null,
            );

            $moisValidite = DemandeDocumentConfig::validiteEnMois();
            $now = now();

            $demande->signature_officier = $prepared['signature_officier'] ?? (filled($user->personne?->signature) ? (string) $user->personne->signature : null);
            $demande->code_signataire = $cui;
            $demande->date_signature = $now;
            $demande->document_valide_de = $now->copy()->startOfDay();
            $demande->document_valide_jusquau = $now->copy()->addMonths($moisValidite)->endOfDay();
            $demande->statut = 'Traitée';
            $demande->otp_code = null;
            $demande->otp_expire_at = null;
            $demande->ip_signature = $ip;
            $demande->user_agent_signature = $this->simplifyUa($userAgent);

            $demande->proof_id = $l2['proof_id'] ?? null;
            $demande->payload_hash = $hash;
            $demande->actor_id = $actorId;
            $demande->actor_nom = $actorNom;
            $demande->actor_fonction = $actorFonction;
            $demande->certificate_ref = $l2['certificate_ref'] ?? null;
            $demande->signed_at = $l2['signed_at'] ?? $now;
            $demande->rfc3161_l1_serial = $l2['rfc3161_serial'] ?? ($l2['timestamp_serial'] ?? null);
            $demande->pdf_content_hash = $hash;
            $demande->doc_sig_id = $l2['doc_sig_id'] ?? null;
            $demande->doc_sig_signed_at = $l2['signed_at'] ?? $now;
            $demande->rfc3161_l2_serial = $l2['rfc3161_serial'] ?? ($l2['timestamp_serial'] ?? null);
            $demande->doc_seal_id = $l3['doc_seal_id'] ?? null;
            $demande->doc_seal_sealed_at = $l3['sealed_at'] ?? $now;
            $demande->rfc3161_l3_serial = $l3['rfc3161_serial'] ?? ($l3['timestamp_serial'] ?? null);

            $dossier = 'demandes_documents/'.$now->format('Y/m');
            $nom = ($demande->estCopie() ? 'copie' : 'extrait').'_'.$code.'.pdf';
            $relPath = $dossier.'/'.$nom;
            \Illuminate\Support\Facades\Storage::disk('local')->makeDirectory($dossier);
            // Binaire scellé (empreinte GUOT) — inchangé après signature.
            \Illuminate\Support\Facades\Storage::disk('local')->put($relPath, $pdfBinary);
            $absoluScelle = storage_path('app/'.$relPath);
            $demande->pdf_path = $relPath;
            $demande->chemin_document = $absoluScelle;
            $demande->save();

            // PDF de consultation : régénéré avec certificat + empreinte (métadonnées post-L2).
            $regenOk = false;
            $lastRegenError = null;
            for ($attempt = 1; $attempt <= 2; $attempt++) {
                try {
                    app(DemandeDocumentService::class)->regenererPdfApresSignature($demande);
                    $regenOk = true;
                    break;
                } catch (\Throwable $e) {
                    $lastRegenError = $e;
                    Log::channel('sifec')->warning('Régénération PDF consultation après signature échouée', [
                        'code' => $code,
                        'attempt' => $attempt,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
            if (! $regenOk) {
                Log::channel('sifec')->error('PDF consultation indisponible après signature — binaire scellé conservé (réparation au 1er téléchargement)', [
                    'code' => $code,
                    'error' => $lastRegenError?->getMessage(),
                ]);
            }

            Log::channel('sifec')->info('Demande document .p12 OK', [
                'code' => $code,
                'doc_sig_id' => $demande->doc_sig_id,
                'doc_seal_id' => $demande->doc_seal_id,
                'pdf_consultation' => $regenOk,
                'ip' => $ip,
            ]);

            return $demande->fresh(['institution', 'typeActe', 'typeDocumentDemande', 'signataire.user.personne']);
        });
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
