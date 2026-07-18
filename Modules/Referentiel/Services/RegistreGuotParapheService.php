<?php

namespace Modules\Referentiel\Services;

use App\Models\User;
use App\Services\GuotDocumentSignatureService;
use App\Support\GuotSignataires;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Referentiel\Entities\Registre;
use Modules\Referentiel\Http\Controllers\RegistreController;
use PkiSdk\TrustException;

/**
 * Paraphe électronique d’un registre — signature locale .p12 (escrow) + cachet institutionnel.
 */
class RegistreGuotParapheService
{
    public function __construct(
        private GuotDocumentSignatureService $guot,
        private RegistreParaphePdfRenderer $pdfRenderer,
    ) {}

    /**
     * Prépare les hash à signer localement (étape 1).
     *
     * @param  list<string>  $codesRegistre
     * @return array{ok: bool, message: string, token?: string, expected_serial?: string|null, items?: list<array{code_registre: string, document_hash: string, libelle: string}>}
     */
    public function prepare(User $user, array $codesRegistre): array
    {
        $ctx = $this->assertSignerContext($user);
        if (! ($ctx['ok'] ?? false)) {
            return ['ok' => false, 'message' => $ctx['message'], 'items' => []];
        }

        $codes = array_values(array_unique(array_filter(array_map('strval', $codesRegistre))));
        if ($codes === []) {
            return ['ok' => false, 'message' => 'Aucun registre à parapher.', 'items' => []];
        }

        $user->loadMissing('personne');
        $actorNom = trim(($user->personne?->nom ?? '').' '.($user->personne?->prenom ?? '')) ?: (string) $user->email;

        $cacheItems = [];
        $publicItems = [];

        foreach ($codes as $code) {
            $reg = Registre::with(['typeRegistre', 'institutionUser.institution.institutionParent'])
                ->where('code_registre', $code)
                ->first();

            if ($reg === null) {
                return ['ok' => false, 'message' => "Registre introuvable : {$code}", 'items' => []];
            }
            if ((int) $reg->statut === 1) {
                return ['ok' => false, 'message' => "Registre déjà paraphé : {$code}", 'items' => []];
            }

            $pdfBinary = $this->pdfRenderer->renderBinary($reg, $actorNom);
            $hash = hash('sha256', $pdfBinary);
            $payload = $this->buildPayload($reg, $ctx['actor_id'], (string) ($ctx['cert_serial'] ?? ''), 'sifec:registre:'.$reg->code_registre);

            $cacheItems[$code] = [
                'pdf' => base64_encode($pdfBinary),
                'hash' => $hash,
                'payload' => $payload,
            ];
            $publicItems[] = [
                'code_registre' => $code,
                'document_hash' => $hash,
                'libelle' => (string) ($reg->lib_registre ?: $reg->typeRegistre?->lib_type_registre ?: $code),
            ];
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
     * Finalise après signature locale .p12 (étape 3).
     *
     * @param  list<array{code_registre: string, signature_hex: string}>  $signatures
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
            return ['ok' => false, 'message' => 'Session de signature expirée. Rouvrez le paraphe et recommencez.', 'signed' => 0];
        }

        $byCode = [];
        foreach ($signatures as $row) {
            if (! is_array($row)) {
                continue;
            }
            $code = (string) ($row['code_registre'] ?? '');
            $hex = (string) ($row['signature_hex'] ?? '');
            if ($code !== '' && $hex !== '') {
                $byCode[$code] = $hex;
            }
        }

        $signed = 0;
        $errors = [];
        $signedRegs = [];

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
                $reg = $this->finalizeUn(
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
                $signedRegs[] = $reg;
            } catch (TrustException $e) {
                Log::channel('sifec')->error('Paraphe registre (trust-api)', [
                    'code_registre' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            } catch (Exception $e) {
                Log::channel('sifec')->error('Paraphe registre', [
                    'code_registre' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            }
        }

        if ($signed > 0) {
            Cache::forget($this->cacheKey($user, $token));
        }

        foreach ($signedRegs as $reg) {
            $this->notifyCec($reg);
        }

        if ($signed === 0) {
            return [
                'ok' => false,
                'message' => $errors !== [] ? implode(' | ', $errors) : 'Aucun registre paraphé.',
                'signed' => 0,
            ];
        }

        $message = $signed.' registre(s) paraphé(s) électroniquement.';
        if ($errors !== []) {
            $message .= ' Échecs : '.implode(' | ', $errors);
        }

        return ['ok' => true, 'message' => $message, 'signed' => $signed];
    }

    /**
     * @deprecated Conservé pour compat ; préfère prepare + finalize (.p12).
     *
     * @param  list<string>  $codesRegistre
     * @return array{ok: bool, message: string, signed: int}
     */
    public function parapher(User $user, array $codesRegistre, ?string $ip = null, ?string $userAgent = null): array
    {
        return [
            'ok' => false,
            'message' => 'La signature serveur n’est pas disponible pour votre certificat. Utilisez votre fichier .p12 dans le formulaire de paraphe.',
            'signed' => 0,
        ];
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
                'message' => 'Cachet institutionnel manquant sur le tribunal : configurez-le sur la fiche de l’institution avant de parapher.',
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
        return 'registre_paraphe_p12:'.$user->code_user.':'.$token;
    }

    /**
     * @param  array{pdf: string, hash: string, payload: array<string, mixed>}  $prepared
     */
    private function finalizeUn(
        User $user,
        string $codeRegistre,
        array $prepared,
        string $signatureHex,
        string $cui,
        string $actorId,
        string $institutionId,
        ?string $ip,
        ?string $userAgent,
    ): Registre {
        return DB::transaction(function () use ($user, $codeRegistre, $prepared, $signatureHex, $cui, $actorId, $institutionId, $ip, $userAgent) {
            /** @var Registre|null $reg */
            $reg = Registre::query()
                ->where('code_registre', $codeRegistre)
                ->lockForUpdate()
                ->first();

            if ($reg === null) {
                throw new Exception('Registre introuvable.');
            }
            if ((int) $reg->statut === 1) {
                throw new Exception('Registre déjà validé (paraphé).');
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

            $l2 = $this->guot->verifyClientDocumentSignature(
                $hash,
                $signatureHex,
                $actorId,
                'paraphe_registre',
                'registre_'.$reg->code_registre,
            );

            $l3 = $this->guot->sealDocument(
                $hash,
                $institutionId,
                'paraphe_registre',
                'registre_'.$reg->code_registre,
                is_string($l2['doc_sig_id'] ?? null) ? $l2['doc_sig_id'] : null,
            );

            $reg->loadMissing('institutionUser.institution.institutionParent', 'typeRegistre');
            $parent = $reg->institutionUser?->institution?->institutionParent;
            if ($parent?->sceau) {
                $reg->sceau = $parent->sceau;
            }
            if (filled($user->personne?->signature)) {
                $reg->signature_tribunal = $user->personne->signature;
            }

            $reg->approbation_tribunal = $cui;
            $reg->statut = 1;
            $reg->otp_paraphage = null;
            $reg->otp_expire_at = null;
            $reg->otp_paraphage_attempts = 0;
            $reg->otp_locked_until = null;

            $reg->proof_id = $l2['proof_id'] ?? ($prepared['payload']['transaction_id'] ?? null);
            $reg->payload_hash = $hash;
            $reg->actor_id = $actorId;
            $reg->actor_nom = $actorNom;
            $reg->certificate_ref = $l2['certificate_ref'] ?? null;
            $reg->signed_at = $l2['signed_at'] ?? now();
            $reg->rfc3161_l1_serial = $l2['rfc3161_serial'] ?? ($l2['timestamp_serial'] ?? null);

            $path = 'registres_paraphe/'.now()->format('Y/m').'/'.$reg->code_registre.'.pdf';
            Storage::disk('local')->put($path, $pdfBinary);

            $reg->pdf_content_hash = $hash;
            $reg->doc_sig_id = $l2['doc_sig_id'] ?? null;
            $reg->doc_sig_signed_at = $l2['signed_at'] ?? now();
            $reg->rfc3161_l2_serial = $l2['rfc3161_serial'] ?? ($l2['timestamp_serial'] ?? null);
            $reg->doc_seal_id = $l3['doc_seal_id'] ?? null;
            $reg->doc_seal_sealed_at = $l3['sealed_at'] ?? now();
            $reg->rfc3161_l3_serial = $l3['rfc3161_serial'] ?? ($l3['timestamp_serial'] ?? null);
            $reg->pdf_path = $path;
            $reg->save();

            Log::channel('sifec')->info('Registre paraphé', [
                'code_registre' => $reg->code_registre,
                'doc_sig_id' => $reg->doc_sig_id,
                'doc_seal_id' => $reg->doc_seal_id,
            ]);

            return $reg->fresh(['typeRegistre']);
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPayload(Registre $reg, string $actorId, string $certFingerprint, string $transactionId): array
    {
        $reg->loadMissing(['typeRegistre', 'institutionUser.institution.institutionParent']);

        $payload = [
            'actor_id' => $actorId,
            'cert_fingerprint' => $certFingerprint,
            'document_id' => (string) $reg->code_registre,
            'document_type' => 'paraphe_registre',
            'transaction_id' => $transactionId,
            'code_registre' => (string) $reg->code_registre,
            'type_registre' => (string) ($reg->typeRegistre?->lib_type_registre ?? $reg->code_type_registre),
            'lib_registre' => (string) ($reg->lib_registre ?? ''),
            'cec' => (string) ($reg->institutionUser?->institution?->lib_institution ?? ''),
            'tribunal' => (string) ($reg->institutionUser?->institution?->institutionParent?->lib_institution ?? ''),
            'nombre_acte_prevu' => (string) ($reg->nombre_acte_prevu ?? ''),
            'date_document' => now()->format('Y-m-d'),
        ];
        ksort($payload);

        return $payload;
    }

    private function notifyCec(Registre $reg): void
    {
        try {
            app(RegistreController::class)->notifyCecApresValidationTribunal($reg);
        } catch (Exception $e) {
            Log::channel('sifec')->warning('Notification CEC après paraphe', [
                'code_registre' => $reg->code_registre,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
