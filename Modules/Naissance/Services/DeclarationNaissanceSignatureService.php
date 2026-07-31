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
use Modules\Naissance\Entities\Declarationnaissance;
use PkiSdk\TrustException;

/**
 * Signature électronique GUOT (.p12 local + cachet institutionnel L3) :
 *  - phase « fs »  : certificat de naissance signé par le chef de service de la formation sanitaire ;
 *  - phase « cec » : déclaration de naissance signée par le responsable du centre d'état civil.
 *
 * Même patron que ActeNaissanceGuotValidationService : prepare (hash PDF + cache) → signature côté
 * client → finalize (vérification L2 + cachet L3 + persistance des colonnes de preuve sig_{phase}_*).
 */
class DeclarationNaissanceSignatureService
{
    public const PHASE_FS = 'fs';

    public const PHASE_CEC = 'cec';

    /**
     * @var array<string, array{prefix: string, contexte: string, purpose: string, ref: string, storage: string, label: string}>
     */
    private const PHASES = [
        self::PHASE_FS => [
            'prefix' => 'sig_fs_',
            'contexte' => 'formation_sanitaire',
            'purpose' => 'signature_certificat_naissance',
            'ref' => 'certificat_naissance_',
            'storage' => 'certificats_naissance',
            'label' => 'certificat',
        ],
        self::PHASE_CEC => [
            'prefix' => 'sig_cec_',
            'contexte' => 'centre_etat_civil',
            'purpose' => 'signature_declaration_naissance',
            'ref' => 'declaration_naissance_',
            'storage' => 'declarations_naissance',
            'label' => 'déclaration',
        ],
    ];

    public function __construct(
        private GuotDocumentSignatureService $guot,
        private DeclarationNaissancePdfRenderer $pdfRenderer,
    ) {}

    /**
     * Étape 1 : prépare les empreintes PDF à signer localement.
     *
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
     * Étape 3 : vérifie les signatures .p12, appose le cachet L3 et persiste les preuves.
     *
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
                    $phase,
                    $prep['items'][$code],
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
                Log::channel('sifec')->error('Signature '.$cfg['label'].' naissance (trust-api)', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$this->humanizeTrustException($e);
            } catch (Exception $e) {
                Log::channel('sifec')->error('Signature '.$cfg['label'].' naissance', [
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

    /**
     * Le document a-t-il déjà été signé pour la phase donnée ?
     */
    public function estSignee(Declarationnaissance $declaration, string $phase): bool
    {
        $prefix = $this->phaseConfig($phase)['prefix'];

        return filled($declaration->{$prefix.'proof_id'});
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
     * @return array{cache: array{pdf: string, hash: string}, public: array{code_declaration: string, document_hash: string, libelle: string}}
     */
    private function prepareUn(User $user, string $codeDeclaration, string $phase): array
    {
        $cfg = $this->phaseConfig($phase);

        /** @var Declarationnaissance|null $declaration */
        $declaration = Declarationnaissance::query()
            ->where('code_declaration_naissance', $codeDeclaration)
            ->first();

        if ($declaration === null) {
            throw new Exception('Document introuvable.');
        }

        if (filled($declaration->{$cfg['prefix'].'proof_id'})) {
            throw new Exception(ucfirst($cfg['label']).' déjà signé électroniquement.');
        }

        GuotSignatureAffichage::applySignerPreview($declaration, $user, $cfg['prefix']);

        $pdfBinary = $this->pdfRenderer->renderBinary($declaration, $cfg['contexte']);
        $hash = hash('sha256', $pdfBinary);

        $enfant = trim(($declaration->enfant?->nom ?? '').' '.($declaration->enfant?->prenom ?? ''));

        return [
            'cache' => [
                'pdf' => base64_encode($pdfBinary),
                'hash' => $hash,
            ],
            'public' => [
                'code_declaration' => $codeDeclaration,
                'document_hash' => $hash,
                'libelle' => $enfant !== '' ? $enfant : $codeDeclaration,
            ],
        ];
    }

    /**
     * @param  array{pdf: string, hash: string}  $prepared
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

        DB::transaction(function () use ($user, $codeDeclaration, $phase, $cfg, $prefix, $prepared, $signatureHex, $cui, $actorId, $institutionId, $ip, $userAgent) {
            /** @var Declarationnaissance|null $declaration */
            $declaration = Declarationnaissance::query()
                ->where('code_declaration_naissance', $codeDeclaration)
                ->lockForUpdate()
                ->first();

            if ($declaration === null) {
                throw new Exception('Document introuvable.');
            }

            if (filled($declaration->{$prefix.'proof_id'})) {
                throw new Exception(ucfirst($cfg['label']).' déjà signé électroniquement.');
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
                $cfg['purpose'],
                $documentRef,
            );

            $l3 = $this->guot->sealDocument(
                $hash,
                $institutionId,
                $cfg['purpose'],
                $documentRef,
                is_string($l2['doc_sig_id'] ?? null) ? $l2['doc_sig_id'] : null,
            );

            $path = $cfg['storage'].'/'.now()->format('Y/m').'/'.$codeDeclaration.'.pdf';
            Storage::disk('local')->put($path, $pdfBinary);

            $declaration->{$prefix.'proof_id'} = $l2['proof_id'] ?? ('sifec:naissance:'.$phase.':'.$codeDeclaration);
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

            Log::channel('sifec')->info('Signature '.$cfg['label'].' naissance .p12 OK', [
                'code' => $codeDeclaration,
                'phase' => $phase,
                'doc_sig_id' => $declaration->{$prefix.'doc_sig_id'},
                'doc_seal_id' => $declaration->{$prefix.'doc_seal_id'},
                'ip' => $ip,
            ]);
        });
    }

    /**
     * @return array{prefix: string, contexte: string, purpose: string, ref: string, storage: string, label: string}
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
        return 'declaration_naissance_'.$phase.'_p12:'.$user->code_user.':'.$token;
    }

    private function humanizeTrustException(TrustException $e): string
    {
        $msg = $e->getMessage();
        $lower = mb_strtolower($msg);

        if (
            str_contains($lower, 'institution')
            && (str_contains($lower, 'revoked') || str_contains($lower, 'not active'))
        ) {
            return 'Le cachet institutionnel GUOT est révoqué (Trust API). '
                .'Ouvrez la fiche de l’institution (ex. Hôpital Makelekele), '
                .'régénérez / réactivez le cachet, puis réessayez l’envoi.';
        }

        return $msg;
    }
}
