<?php

namespace Modules\Mariage\Services;

use App\Models\User;
use App\Services\GuotDocumentSignatureService;
use App\Support\GuotSignataires;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Mariage\Entities\ActeMariage;
use PkiSdk\TrustException;

/**
 * Validation / signature électronique d'un acte de mariage — flux .p12 local (L2 personnelle)
 * + cachet institutionnel (L3), en remplacement de l'ancien flux OTP.
 *
 * Même patron que ActeNaissanceGuotValidationService, mais sans NIUPP prévisionnel : l'acte de
 * mariage (et son feuillet de registre) est déjà généré avant la signature.
 */
class ActeMariageGuotValidationService
{
    public function __construct(
        private GuotDocumentSignatureService $guot,
        private ActeMariagePdfRenderer $pdfRenderer,
        private MouvementMariageService $mouvementService,
    ) {}

    /**
     * Étape 1 : prépare les empreintes PDF à signer localement.
     *
     * @param  list<string>  $codesDeclaration  Codes de déclaration de mariage
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
     * Étape 3 : vérifie les signatures .p12 (L2), appose le cachet L3 puis persiste les preuves.
     *
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
                $this->finalizeUn(
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
            } catch (TrustException $e) {
                Log::channel('sifec')->error('Signature acte mariage (trust-api)', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            } catch (Exception $e) {
                Log::channel('sifec')->error('Signature acte mariage', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            }
        }

        if ($signed > 0) {
            Cache::forget($this->cacheKey($user, $token));
        }

        if ($signed === 0) {
            return [
                'ok' => false,
                'message' => $errors !== [] ? implode(' | ', $errors) : 'Aucun acte signé.',
                'signed' => 0,
            ];
        }

        $message = $signed.' acte(s) de mariage signé(s) électroniquement.';
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
        return 'acte_mariage_p12:'.$user->code_user.':'.$token;
    }

    /**
     * Contrôle d'appartenance (anti-IDOR) : l'acte ne peut être signé que par un agent dont
     * l'institution active correspond à l'origine ou au centre destinataire de la déclaration,
     * conformément au périmètre de visibilité de l'index.
     */
    private function assertActeAppartientAInstitution(ActeMariage $acte, User $user): void
    {
        $instCode = (string) ($user->affectationActive()?->institution?->code_institution ?? '');
        $declaration = $acte->declaration;
        $portee = array_filter([
            (string) ($declaration?->code_institution ?? ''),
            (string) ($declaration?->code_institution_destinataire ?? ''),
        ]);

        if ($instCode === '' || ! in_array($instCode, $portee, true)) {
            throw new Exception('Cet acte de mariage ne relève pas de votre centre d’état civil.');
        }
    }

    /**
     * @return array{cache: array{pdf: string, hash: string, signature_maire: ?string}, public: array{code_declaration: string, document_hash: string, libelle: string}}
     */
    private function prepareUn(User $user, string $codeDeclaration): array
    {
        /** @var ActeMariage|null $acte */
        $acte = ActeMariage::query()
            ->where('code_declaration_mariage', $codeDeclaration)
            ->first();

        if ($acte === null) {
            throw new Exception('Acte de mariage introuvable. Générez d’abord l’acte.');
        }

        $this->assertActeAppartientAInstitution($acte, $user);

        if (filled($acte->approbation_mairie) && filled($acte->proof_id)) {
            throw new Exception('Acte déjà signé électroniquement.');
        }

        $user->loadMissing('personne');

        // Attributs en mémoire uniquement (pour le rendu PDF) — persistés à la finalisation.
        $signatureMaire = filled($acte->signature_maire)
            ? (string) $acte->signature_maire
            : (filled($user->personne?->signature) ? (string) $user->personne->signature : null);
        $acte->approbation_mairie = $acte->approbation_mairie ?: (string) $user->affectationActive()?->cui;
        $acte->signature_maire = $signatureMaire;
        $acte->date_heure_approbation_mairie = now();

        $pdfBinary = $this->pdfRenderer->renderBinary($acte);
        $hash = hash('sha256', $pdfBinary);

        $epoux = trim(($acte->declaration?->epoux?->nom ?? '').' '.($acte->declaration?->epoux?->prenom ?? ''));
        $epouse = trim(($acte->declaration?->epouse?->nom ?? '').' '.($acte->declaration?->epouse?->prenom ?? ''));
        $libelle = trim($epoux.' & '.$epouse, ' &');

        return [
            'cache' => [
                'pdf' => base64_encode($pdfBinary),
                'hash' => $hash,
                'signature_maire' => $signatureMaire,
            ],
            'public' => [
                'code_declaration' => $codeDeclaration,
                'document_hash' => $hash,
                'libelle' => $libelle !== '' ? $libelle : $codeDeclaration,
            ],
        ];
    }

    /**
     * @param  array{pdf: string, hash: string, signature_maire: ?string}  $prepared
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
    ): void {
        DB::transaction(function () use ($user, $codeDeclaration, $prepared, $signatureHex, $cui, $actorId, $institutionId, $ip, $userAgent) {
            /** @var ActeMariage|null $acte */
            $acte = ActeMariage::query()
                ->where('code_declaration_mariage', $codeDeclaration)
                ->lockForUpdate()
                ->first();

            if ($acte === null) {
                throw new Exception('Acte de mariage introuvable.');
            }

            $this->assertActeAppartientAInstitution($acte, $user);

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

            $user->loadMissing('personne');
            $actorNom = trim(($user->personne?->nom ?? '').' '.($user->personne?->prenom ?? '')) ?: (string) $user->email;
            $documentRef = 'acte_mariage_'.$acte->code_acte_mariage;

            $l2 = $this->guot->verifyClientDocumentSignature(
                $hash,
                $signatureHex,
                $actorId,
                'signature_acte_mariage',
                $documentRef,
            );

            $l3 = $this->guot->sealDocument(
                $hash,
                $institutionId,
                'signature_acte_mariage',
                $documentRef,
                is_string($l2['doc_sig_id'] ?? null) ? $l2['doc_sig_id'] : null,
            );

            $acte->approbation_mairie = $cui;
            if (! filled($acte->signature_maire) && filled($prepared['signature_maire'])) {
                $acte->signature_maire = $prepared['signature_maire'];
            } elseif (! filled($acte->signature_maire) && filled($user->personne?->signature)) {
                $acte->signature_maire = $user->personne->signature;
            }
            $acte->date_heure_approbation_mairie = now();
            $acte->adresse_mac_approbation = $ip;
            $acte->nom_appareil_approbation = $this->simplifyUa($userAgent);

            $acte->proof_id = $l2['proof_id'] ?? ('sifec:mariage:'.$codeDeclaration);
            $acte->payload_hash = $hash;
            $acte->actor_id = $actorId;
            $acte->actor_nom = $actorNom;
            $acte->certificate_ref = $l2['certificate_ref'] ?? null;
            $acte->signed_at = $l2['signed_at'] ?? now();
            $acte->rfc3161_l1_serial = $l2['rfc3161_serial'] ?? ($l2['timestamp_serial'] ?? null);
            $acte->otp_expire_at = null;
            $acte->otp_approbation_mairie = null;

            $path = 'actes_mariage/'.now()->format('Y/m').'/'.$acte->code_acte_mariage.'.pdf';
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

            Log::channel('sifec')->info('Acte mariage .p12 OK', [
                'code' => $acte->code_acte_mariage,
                'doc_sig_id' => $acte->doc_sig_id,
                'doc_seal_id' => $acte->doc_seal_id,
                'ip' => $ip,
            ]);
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
