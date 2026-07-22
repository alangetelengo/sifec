<?php

namespace Modules\Deces\Services;

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
use Modules\Deces\Entities\DeclarationDeces;
use PkiSdk\TrustException;

/**
 * Signature électronique GUOT (.p12 + cachet L3) sur les documents de décès :
 *  - phase « fs »  : certificat de décès (formation sanitaire, envoi) ;
 *  - phase « ch »  : certificat de constatation (centre d'hygiène, envoi) ;
 *  - phase « cec » : validation CEC/PF (déclaration depuis certificat FS, ou validation constatation CH).
 */
class DeclarationDecesSignatureService
{
    public const PHASE_FS = 'fs';

    public const PHASE_CH = 'ch';

    public const PHASE_CEC = 'cec';

    /**
     * @var array<string, array{prefix: string, contexte: string|null, purpose: string, ref: string, storage: string, label: string}>
     */
    private const PHASES = [
        self::PHASE_FS => [
            'prefix' => 'sig_fs_',
            'contexte' => 'formation_sanitaire',
            'purpose' => 'signature_certificat_deces',
            'ref' => 'certificat_deces_',
            'storage' => 'certificats_deces',
            'label' => 'certificat de décès',
        ],
        self::PHASE_CH => [
            'prefix' => 'sig_ch_',
            'contexte' => 'centre_hygiene',
            'purpose' => 'signature_constatation_deces',
            'ref' => 'constatation_deces_',
            'storage' => 'constatations_deces',
            'label' => 'certificat de constatation',
        ],
        self::PHASE_CEC => [
            'prefix' => 'sig_cec_',
            'contexte' => null,
            'purpose' => 'signature_declaration_deces',
            'ref' => 'validation_deces_',
            'storage' => 'validations_deces',
            'label' => 'document de décès',
        ],
    ];

    public function __construct(
        private GuotDocumentSignatureService $guot,
        private DeclarationDecesPdfRenderer $pdfRenderer,
    ) {}

    /**
     * @param  list<string>  $codesDeclaration
     * @return array{ok: bool, message: string, token?: string, expected_serial?: string|null, items?: list<array{code_declaration: string, document_hash: string, libelle: string}>}
     */
    public function prepare(User $user, array $codesDeclaration, string $phase): array
    {
        $cfg = $this->phaseConfig($phase);

        $ctx = $this->assertSignerContext($user);
        if (! ($ctx['ok'] ?? false)) {
            return ['ok' => false, 'message' => $ctx['message'], 'items' => []];
        }

        $codes = array_values(array_unique(array_filter(array_map('strval', $codesDeclaration))));
        if ($codes === []) {
            return ['ok' => false, 'message' => 'Aucun document à signer.', 'items' => []];
        }

        $cacheItems = [];
        $publicItems = [];

        foreach ($codes as $code) {
            try {
                $prepared = $this->prepareUn($user, $code, $phase);
            } catch (Exception $e) {
                return ['ok' => false, 'message' => $code.': '.$e->getMessage(), 'items' => []];
            }

            $cacheItems[$code] = $prepared['cache'];
            $publicItems[] = $prepared['public'];
        }

        $token = Str::random(40);
        Cache::put($this->cacheKey($user, $phase, $token), [
            'phase' => $phase,
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
     * @param  list<array{code_declaration?: string, signature_hex: string}>  $signatures
     * @return array{ok: bool, message: string, signed: int, codes: list<string>}
     */
    public function finalize(
        User $user,
        string $token,
        array $signatures,
        string $phase,
        ?string $ip = null,
        ?string $userAgent = null,
    ): array {
        $cfg = $this->phaseConfig($phase);

        $ctx = $this->assertSignerContext($user);
        if (! ($ctx['ok'] ?? false)) {
            return ['ok' => false, 'message' => $ctx['message'], 'signed' => 0, 'codes' => []];
        }

        $prep = Cache::get($this->cacheKey($user, $phase, $token));
        if (! is_array($prep) || empty($prep['items'])) {
            return ['ok' => false, 'message' => 'Session de signature expirée. Rouvrez la validation et recommencez.', 'signed' => 0, 'codes' => []];
        }

        $byCode = [];
        foreach ($signatures as $row) {
            if (! is_array($row)) {
                continue;
            }
            $code = (string) ($row['code_declaration'] ?? '');
            $hex = (string) ($row['signature_hex'] ?? '');
            if ($code !== '' && $hex !== '') {
                $byCode[$code] = $hex;
            }
        }

        $signed = 0;
        $signedCodes = [];
        $errors = [];

        foreach ($prep['codes'] as $code) {
            $code = (string) $code;
            $item = $prep['items'][$code] ?? null;
            if (! is_array($item)) {
                $errors[] = $code.': préparation introuvable';
                continue;
            }

            if (! empty($item['already_signed'])) {
                $signed++;
                $signedCodes[] = $code;
                continue;
            }

            if (! isset($byCode[$code])) {
                $errors[] = $code.': signature manquante';
                continue;
            }

            try {
                $this->finalizeUn(
                    $user,
                    $code,
                    $phase,
                    $item,
                    $byCode[$code],
                    (string) $prep['cui'],
                    (string) $prep['actor_id'],
                    (string) $prep['institution_id'],
                    $ip,
                    $userAgent,
                );
                $signed++;
                $signedCodes[] = $code;
            } catch (TrustException $e) {
                Log::channel('sifec')->error('Signature '.$cfg['label'].' décès (trust-api)', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            } catch (Exception $e) {
                Log::channel('sifec')->error('Signature '.$cfg['label'].' décès', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            }
        }

        if ($signed > 0) {
            Cache::forget($this->cacheKey($user, $phase, $token));
        }

        if ($signed === 0) {
            return [
                'ok' => false,
                'message' => $errors !== [] ? implode(' | ', $errors) : 'Aucun document signé.',
                'signed' => 0,
                'codes' => [],
            ];
        }

        $message = $signed.' '.$cfg['label'].'(s) signé(s) électroniquement.';
        if ($errors !== []) {
            $message .= ' Échecs : '.implode(' | ', $errors);
        }

        return ['ok' => true, 'message' => $message, 'signed' => $signed, 'codes' => $signedCodes];
    }

    public function estSignee(DeclarationDeces $declaration, string $phase): bool
    {
        $prefix = $this->phaseConfig($phase)['prefix'];

        return filled($declaration->{$prefix.'proof_id'});
    }

    /**
     * Indique si l'action métier post-signature (envoi ou confirmation) reste à faire.
     */
    public function workflowIncomplet(DeclarationDeces $declaration, string $phase): bool
    {
        if ($phase === self::PHASE_CEC) {
            return ($declaration->cec_approuver ?? '') !== 'OUI';
        }

        $codesEnvoi = $declaration->estConstatation()
            ? ['MOUV_2006']
            : ['MOUV_0002'];

        return ! $declaration->mouvements()
            ->whereIn('code_mouvement', $codesEnvoi)
            ->exists();
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

    /**
     * @return array{cache: array{pdf: string, hash: string, purpose: string}, public: array{code_declaration: string, document_hash: string, libelle: string}}
     */
    private function prepareUn(User $user, string $codeDeclaration, string $phase): array
    {
        $cfg = $this->phaseConfig($phase);

        /** @var DeclarationDeces|null $declaration */
        $declaration = DeclarationDeces::query()
            ->where('code_declaration_deces', $codeDeclaration)
            ->first();

        if ($declaration === null) {
            throw new Exception('Document introuvable.');
        }

        if ($phase === self::PHASE_CEC) {
            $this->assertDeclarationAppartientAInstitution($declaration, $user);
        }

        $this->assertPhaseDocumentValide($declaration, $phase);

        if (filled($declaration->{$cfg['prefix'].'proof_id'})) {
            if ($this->workflowIncomplet($declaration, $phase)) {
                $defunt = trim(($declaration->defunt?->nom ?? '').' '.($declaration->defunt?->prenom ?? ''));

                return [
                    'cache' => [
                        'pdf' => '',
                        'hash' => '',
                        'purpose' => $cfg['purpose'],
                        'already_signed' => true,
                    ],
                    'public' => [
                        'code_declaration' => $codeDeclaration,
                        'document_hash' => '',
                        'libelle' => $defunt !== '' ? $defunt : $codeDeclaration,
                        'already_signed' => true,
                    ],
                ];
            }

            throw new Exception(ucfirst($cfg['label']).' déjà signé électroniquement.');
        }

        $contexte = $this->resolveContexte($declaration, $phase);
        $purpose = $this->resolvePurpose($declaration, $phase);

        GuotSignatureAffichage::applySignerPreview($declaration, $user, $cfg['prefix']);

        $pdfBinary = $this->pdfRenderer->renderBinary($declaration, $contexte, $phase === self::PHASE_CEC);
        $hash = hash('sha256', $pdfBinary);

        $defunt = trim(($declaration->defunt?->nom ?? '').' '.($declaration->defunt?->prenom ?? ''));

        return [
            'cache' => [
                'pdf' => base64_encode($pdfBinary),
                'hash' => $hash,
                'purpose' => $purpose,
            ],
            'public' => [
                'code_declaration' => $codeDeclaration,
                'document_hash' => $hash,
                'libelle' => $defunt !== '' ? $defunt : $codeDeclaration,
            ],
        ];
    }

    /**
     * @param  array{pdf: string, hash: string, purpose: string}  $prepared
     */
    private function finalizeUn(
        User $user,
        string $codeDeclaration,
        string $phase,
        array $prepared,
        string $signatureHex,
        string $cui,
        string $actorId,
        string $institutionId,
        ?string $ip,
        ?string $userAgent,
    ): void {
        $cfg = $this->phaseConfig($phase);
        $prefix = $cfg['prefix'];
        $purpose = (string) ($prepared['purpose'] ?? $cfg['purpose']);

        DB::transaction(function () use ($user, $codeDeclaration, $phase, $cfg, $prefix, $prepared, $signatureHex, $cui, $actorId, $institutionId, $ip, $purpose) {
            /** @var DeclarationDeces|null $declaration */
            $declaration = DeclarationDeces::query()
                ->where('code_declaration_deces', $codeDeclaration)
                ->lockForUpdate()
                ->first();

            if ($declaration === null) {
                throw new Exception('Document introuvable.');
            }

            if ($phase === self::PHASE_CEC) {
                $this->assertDeclarationAppartientAInstitution($declaration, $user);
            }

            $this->assertPhaseDocumentValide($declaration, $phase);

            if (filled($declaration->{$prefix.'proof_id'})) {
                // Idempotent : déjà signé, l'action métier sera reprise par le contrôleur.
                return;
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
            $documentRef = $cfg['ref'].$codeDeclaration;

            $l2 = $this->guot->verifyClientDocumentSignature(
                $hash,
                $signatureHex,
                $actorId,
                $purpose,
                $documentRef,
            );

            $l3 = $this->guot->sealDocument(
                $hash,
                $institutionId,
                $purpose,
                $documentRef,
                is_string($l2['doc_sig_id'] ?? null) ? $l2['doc_sig_id'] : null,
            );

            $path = $cfg['storage'].'/'.now()->format('Y/m').'/'.$codeDeclaration.'.pdf';
            Storage::disk('local')->put($path, $pdfBinary);

            $declaration->{$prefix.'proof_id'} = $l2['proof_id'] ?? ('sifec:deces:'.$phase.':'.$codeDeclaration);
            $declaration->{$prefix.'payload_hash'} = $hash;
            $declaration->{$prefix.'actor_id'} = $actorId;
            $declaration->{$prefix.'actor_nom'} = $actorNom;
            $declaration->{$prefix.'actor_fonction'} = $actorFonction;
            $declaration->{$prefix.'cui'} = $cui;
            $declaration->{$prefix.'certificate_ref'} = $l2['certificate_ref'] ?? null;
            $declaration->{$prefix.'signed_at'} = $l2['signed_at'] ?? now();
            $declaration->{$prefix.'rfc3161_l1_serial'} = $l2['rfc3161_serial'] ?? ($l2['timestamp_serial'] ?? null);
            $declaration->{$prefix.'pdf_content_hash'} = $hash;
            $declaration->{$prefix.'doc_sig_id'} = $l2['doc_sig_id'] ?? null;
            $declaration->{$prefix.'doc_sig_signed_at'} = $l2['signed_at'] ?? now();
            $declaration->{$prefix.'rfc3161_l2_serial'} = $l2['rfc3161_serial'] ?? ($l2['timestamp_serial'] ?? null);
            $declaration->{$prefix.'doc_seal_id'} = $l3['doc_seal_id'] ?? null;
            $declaration->{$prefix.'doc_seal_sealed_at'} = $l3['sealed_at'] ?? now();
            $declaration->{$prefix.'rfc3161_l3_serial'} = $l3['rfc3161_serial'] ?? ($l3['timestamp_serial'] ?? null);
            $declaration->{$prefix.'pdf_path'} = $path;
            $declaration->{$prefix.'institution_id'} = $institutionId;
            $declaration->save();

            Log::channel('sifec')->info('Signature '.$cfg['label'].' décès .p12 OK', [
                'code' => $codeDeclaration,
                'phase' => $phase,
                'doc_sig_id' => $declaration->{$prefix.'doc_sig_id'},
                'doc_seal_id' => $declaration->{$prefix.'doc_seal_id'},
                'ip' => $ip,
            ]);
        });
    }

    private function assertPhaseDocumentValide(DeclarationDeces $declaration, string $phase): void
    {
        if ($phase === self::PHASE_FS && ! $declaration->estCertificatFormationSanitaire()) {
            throw new Exception('Ce document n’est pas un certificat de décès de formation sanitaire.');
        }

        if ($phase === self::PHASE_CH && ! $declaration->estConstatation()) {
            throw new Exception('Ce document n’est pas un certificat de constatation de décès.');
        }

        if ($phase === self::PHASE_CEC && $declaration->cec_approuver === 'OUI') {
            throw new Exception('Ce dossier est déjà confirmé.');
        }
    }

    private function resolveContexte(DeclarationDeces $declaration, string $phase): string
    {
        if ($phase === self::PHASE_FS) {
            return 'formation_sanitaire';
        }

        if ($phase === self::PHASE_CH) {
            return 'centre_hygiene';
        }

        if ($declaration->estConstatation()) {
            return 'centre_hygiene';
        }

        return 'pompe_funebre';
    }

    private function resolvePurpose(DeclarationDeces $declaration, string $phase): string
    {
        if ($phase === self::PHASE_FS) {
            return 'signature_certificat_deces';
        }

        if ($phase === self::PHASE_CH) {
            return 'signature_constatation_deces';
        }

        if ($declaration->estConstatation()) {
            return 'signature_validation_constatation_deces';
        }

        return 'signature_declaration_deces';
    }

    private function assertDeclarationAppartientAInstitution(DeclarationDeces $declaration, User $user): void
    {
        $instCode = (string) ($user->affectationActive()?->institution?->code_institution ?? '');
        $portee = array_filter([
            (string) ($declaration->code_institution ?? ''),
            (string) ($declaration->code_institution_destinataire ?? ''),
        ]);

        if ($instCode === '' || ! in_array($instCode, $portee, true)) {
            throw new Exception('Ce document de décès ne relève pas de votre institution.');
        }
    }

    /**
     * @return array{prefix: string, contexte: string|null, purpose: string, ref: string, storage: string, label: string}
     */
    private function phaseConfig(string $phase): array
    {
        if (! isset(self::PHASES[$phase])) {
            throw new Exception('Phase de signature inconnue : '.$phase);
        }

        return self::PHASES[$phase];
    }

    private function cacheKey(User $user, string $phase, string $token): string
    {
        return 'declaration_deces_'.$phase.'_p12:'.$user->code_user.':'.$token;
    }
}
