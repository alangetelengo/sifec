<?php

namespace Modules\Mariage\Services;

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
use Modules\Mariage\Entities\DeclarationMariage;
use PkiSdk\TrustException;

/**
 * Signature électronique GUOT (.p12 local L2 + cachet institutionnel L3) de la déclaration de
 * mariage par le responsable du centre d'état civil, au moment de la confirmation du dossier.
 *
 * Prérequis à la génération de l'acte de mariage. Colonnes de preuve : sig_cec_* sur
 * t_declaration_mariage. Même patron que DeclarationNaissanceSignatureService (phase CEC).
 */
class DeclarationMariageSignatureService
{
    private const PREFIX = 'sig_cec_';

    private const PURPOSE = 'signature_declaration_mariage';

    private const STORAGE = 'declarations_mariage';

    public function __construct(
        private GuotDocumentSignatureService $guot,
        private DeclarationMariagePdfRenderer $pdfRenderer,
    ) {}

    /**
     * Étape 1 : prépare les empreintes PDF à signer localement.
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
            return ['ok' => false, 'message' => 'Aucun document à signer.', 'items' => []];
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
     * Étape 3 : vérifie les signatures .p12, appose le cachet L3 et persiste les preuves.
     *
     * @param  list<array{code_declaration?: string, signature_hex: string}>  $signatures
     * @return array{ok: bool, message: string, signed: int, codes: list<string>}
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
            return ['ok' => false, 'message' => $ctx['message'], 'signed' => 0, 'codes' => []];
        }

        $prep = Cache::get($this->cacheKey($user, $token));
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
                Log::channel('sifec')->error('Signature déclaration mariage (trust-api)', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            } catch (Exception $e) {
                Log::channel('sifec')->error('Signature déclaration mariage', [
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
                'message' => $errors !== [] ? implode(' | ', $errors) : 'Aucun document signé.',
                'signed' => 0,
                'codes' => [],
            ];
        }

        $message = $signed.' déclaration(s) de mariage signée(s) électroniquement.';
        if ($errors !== []) {
            $message .= ' Échecs : '.implode(' | ', $errors);
        }

        return ['ok' => true, 'message' => $message, 'signed' => $signed, 'codes' => $signedCodes];
    }

    /**
     * La déclaration a-t-elle déjà été signée électroniquement par le CEC ?
     */
    public function estSignee(DeclarationMariage $declaration): bool
    {
        return filled($declaration->{self::PREFIX.'proof_id'});
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
    private function prepareUn(User $user, string $codeDeclaration): array
    {
        /** @var DeclarationMariage|null $declaration */
        $declaration = DeclarationMariage::query()
            ->where('code_declaration_mariage', $codeDeclaration)
            ->first();

        if ($declaration === null) {
            throw new Exception('Document introuvable.');
        }

        $this->assertDeclarationAppartientAInstitution($declaration, $user);

        if (filled($declaration->{self::PREFIX.'proof_id'})) {
            throw new Exception('Déclaration déjà signée électroniquement.');
        }

        // Le document est rendu dans son état final signé (QR d'authentification affiché) afin
        // que les octets signés/hashés correspondent exactement au PDF publié après confirmation.
        GuotSignatureAffichage::applySignerPreview($declaration, $user, self::PREFIX);

        $pdfBinary = $this->pdfRenderer->renderBinary($declaration, true);
        $hash = hash('sha256', $pdfBinary);

        $epoux = trim(($declaration->epoux?->nom ?? '').' '.($declaration->epoux?->prenom ?? ''));
        $epouse = trim(($declaration->epouse?->nom ?? '').' '.($declaration->epouse?->prenom ?? ''));
        $libelle = trim($epoux.' & '.$epouse, ' &');

        return [
            'cache' => [
                'pdf' => base64_encode($pdfBinary),
                'hash' => $hash,
            ],
            'public' => [
                'code_declaration' => $codeDeclaration,
                'document_hash' => $hash,
                'libelle' => $libelle !== '' ? $libelle : $codeDeclaration,
            ],
        ];
    }

    /**
     * @param  array{pdf: string, hash: string}  $prepared
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
        $prefix = self::PREFIX;

        DB::transaction(function () use ($user, $codeDeclaration, $prefix, $prepared, $signatureHex, $cui, $actorId, $institutionId, $ip) {
            /** @var DeclarationMariage|null $declaration */
            $declaration = DeclarationMariage::query()
                ->where('code_declaration_mariage', $codeDeclaration)
                ->lockForUpdate()
                ->first();

            if ($declaration === null) {
                throw new Exception('Document introuvable.');
            }

            $this->assertDeclarationAppartientAInstitution($declaration, $user);

            if (filled($declaration->{$prefix.'proof_id'})) {
                throw new Exception('Déclaration déjà signée électroniquement.');
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
            $documentRef = 'declaration_mariage_'.$codeDeclaration;

            $l2 = $this->guot->verifyClientDocumentSignature(
                $hash,
                $signatureHex,
                $actorId,
                self::PURPOSE,
                $documentRef,
            );

            $l3 = $this->guot->sealDocument(
                $hash,
                $institutionId,
                self::PURPOSE,
                $documentRef,
                is_string($l2['doc_sig_id'] ?? null) ? $l2['doc_sig_id'] : null,
            );

            $path = self::STORAGE.'/'.now()->format('Y/m').'/'.$codeDeclaration.'.pdf';
            Storage::disk('local')->put($path, $pdfBinary);

            $declaration->{$prefix.'proof_id'} = $l2['proof_id'] ?? ('sifec:mariage:cec:'.$codeDeclaration);
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

            Log::channel('sifec')->info('Signature déclaration mariage .p12 OK', [
                'code' => $codeDeclaration,
                'doc_sig_id' => $declaration->{$prefix.'doc_sig_id'},
                'doc_seal_id' => $declaration->{$prefix.'doc_seal_id'},
                'ip' => $ip,
            ]);
        });
    }

    private function cacheKey(User $user, string $token): string
    {
        return 'declaration_mariage_cec_p12:'.$user->code_user.':'.$token;
    }

    /**
     * Contrôle d'appartenance (anti-IDOR) : seule une institution origine ou destinataire de la
     * déclaration peut la signer, conformément au périmètre de visibilité de l'index.
     */
    private function assertDeclarationAppartientAInstitution(DeclarationMariage $declaration, User $user): void
    {
        $instCode = (string) ($user->affectationActive()?->institution?->code_institution ?? '');
        $portee = array_filter([
            (string) ($declaration->code_institution ?? ''),
            (string) ($declaration->code_institution_destinataire ?? ''),
        ]);

        if ($instCode === '' || ! in_array($instCode, $portee, true)) {
            throw new Exception('Cette déclaration de mariage ne relève pas de votre centre d’état civil.');
        }
    }
}
