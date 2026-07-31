<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\Institution;
use PkiSdk\SignerClient;
use PkiSdk\TrustClient;
use PkiSdk\TrustException;
use RuntimeException;

/**
 * Enrôlement / liaison du cachet institutionnel GUOT (Layer 3).
 */
class GuotInstitutionService
{
    public function __construct(
        private SignerClient $signers,
        private TrustClient $trust,
    ) {}

    public function isConfigured(): bool
    {
        return filled(config('pki.url')) && filled(config('pki.api_key'));
    }

    /**
     * Crée (ou récupère) l’institution côté trust-api et synchronise les colonnes GUOT.
     *
     * @throws TrustException
     * @throws RuntimeException
     */
    public function enroll(Institution $institution): Institution
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('trust-api non configuré (PKI_TRUST_API_URL / PKI_API_KEY).');
        }

        // Cachet déjà connu localement : synchroniser ; s’il est révoqué / non actif, en créer un nouveau.
        if (filled($institution->guot_institution_id) && filled($institution->guot_institution_cert_serial)) {
            $blockedId = (string) $institution->guot_institution_id;
            try {
                $response = $this->signers->getInstitution($blockedId);
                $payload = $response['institution'] ?? $response;
                $status = mb_strtolower((string) (
                    (is_array($payload) ? ($payload['status'] ?? $payload['state'] ?? null) : null)
                    ?? $response['status']
                    ?? ''
                ));

                if (
                    $this->isRevokedInstitutionPayload(is_array($payload) ? $payload : [])
                    || $this->isRevokedInstitutionPayload($response)
                    || in_array($status, ['revoked', 'révoqué', 'revoque', 'inactive', 'inactif'], true)
                ) {
                    Log::channel('sifec')->warning('Cachet institutionnel GUOT révoqué/inactif — recreation', [
                        'code_institution' => $institution->code_institution,
                        'blocked_id' => $blockedId,
                        'status' => $status,
                    ]);
                    $this->clearLocalGuotFields($institution);
                    $this->tryRevokeOrphanInstitution($blockedId);
                    $recreated = $this->createUnderAlternateId($institution, $blockedId);
                    $this->applyTrustPayload($institution, $recreated['institution'] ?? $recreated);

                    return $institution->fresh();
                }

                // Statut actif explicite, ou absent mais GET OK : conserver
                if ($status === '' || in_array($status, ['active', 'actif', 'enabled'], true)) {
                    // Si statut absent, sonder via create : Trust renvoie 409 revoked si mort
                    if ($status === '') {
                        try {
                            $this->signers->createInstitution($this->createPayload($institution, $blockedId));
                        } catch (TrustException $probe) {
                            if ($this->exceptionIndicatesRevoked($probe)) {
                                Log::channel('sifec')->warning('Sonde create: ID révoqué — nouvel ID', [
                                    'blocked_id' => $blockedId,
                                    'api_error' => $probe->getMessage(),
                                ]);
                                $this->clearLocalGuotFields($institution);
                                $this->tryRevokeOrphanInstitution($blockedId);
                                $recreated = $this->createUnderAlternateId($institution, $blockedId);
                                $this->applyTrustPayload($institution, $recreated['institution'] ?? $recreated);

                                return $institution->fresh();
                            }
                            // 409 « existe déjà » sans revoked → cachet encore utilisable
                        }
                    }

                    $this->applyTrustPayload($institution, is_array($payload) ? $payload : $response);

                    return $institution->fresh();
                }

                // Autre statut inattendu → recreation
                Log::channel('sifec')->warning('Statut institution GUOT inattendu — recreation', [
                    'blocked_id' => $blockedId,
                    'status' => $status,
                ]);
                $this->clearLocalGuotFields($institution);
                $this->tryRevokeOrphanInstitution($blockedId);
                $recreated = $this->createUnderAlternateId($institution, $blockedId);
                $this->applyTrustPayload($institution, $recreated['institution'] ?? $recreated);

                return $institution->fresh();
            } catch (TrustException $e) {
                if ($e->getHttpStatus() === 404 || $this->isAlreadyEnrolledError($e)) {
                    return $this->enrollAfterClear($institution);
                }
                if ($this->exceptionIndicatesRevoked($e)) {
                    $this->clearLocalGuotFields($institution);
                    $this->tryRevokeOrphanInstitution($blockedId);
                    $recreated = $this->createUnderAlternateId($institution, $blockedId);
                    $this->applyTrustPayload($institution, $recreated['institution'] ?? $recreated);

                    return $institution->fresh();
                }
                throw $e;
            }
        }

        $institutionId = filled($institution->guot_institution_id)
            ? $institution->guot_institution_id
            : 'sifec-'.$institution->code_institution;

        try {
            $response = $this->signers->createInstitution([
                'institution_id' => $institutionId,
                'nom' => $institution->lib_institution,
                'common_name' => $institution->lib_institution,
                'organization' => config('app.name', 'SIFEC'),
                'ou' => $institution->typeInstitution?->lib_type_institution,
            ]);
        } catch (TrustException $e) {
            // ID déjà présent mais RÉVOQUÉ : ne jamais réattacher cet ID — créer un nouvel ID actif.
            if ($this->exceptionIndicatesRevoked($e)) {
                Log::channel('sifec')->warning('Enrôlement institution : ID révoqué côté Trust — nouvel ID', [
                    'code_institution' => $institution->code_institution,
                    'blocked_id' => $institutionId,
                    'api_error' => $e->getMessage(),
                ]);
                $this->clearLocalGuotFields($institution);
                $this->tryRevokeOrphanInstitution((string) $institutionId);
                $response = $this->createUnderAlternateId($institution, (string) $institutionId);
                $this->applyTrustPayload($institution, $response['institution'] ?? $response);

                Log::channel('sifec')->info('Institution GUOT enrôlée (nouvel ID après révocation Trust)', [
                    'code_institution' => $institution->code_institution,
                    'guot_institution_id' => $institution->guot_institution_id,
                    'has_serial' => filled($institution->guot_institution_cert_serial),
                ]);

                return $institution->fresh();
            }

            if (! $this->isAlreadyEnrolledError($e)) {
                Log::channel('sifec')->error('Enrôlement institution GUOT échoué', [
                    'code_institution' => $institution->code_institution,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }

            Log::channel('sifec')->info('Institution GUOT déjà présente côté Signum — récupération', [
                'code_institution' => $institution->code_institution,
                'institution_id' => $institutionId,
                'api_error' => $e->getMessage(),
            ]);

            $response = $this->recoverExistingInstitution((string) $institutionId, $institution);
        }

        $payload = $response['institution'] ?? $response;
        if ($this->isRevokedInstitutionPayload(is_array($payload) ? $payload : [])
            || $this->isRevokedInstitutionPayload(is_array($response) ? $response : [])) {
            Log::channel('sifec')->warning('Payload institution encore révoqué après récupération — nouvel ID', [
                'code_institution' => $institution->code_institution,
                'blocked_id' => $institutionId,
            ]);
            $this->clearLocalGuotFields($institution);
            $this->tryRevokeOrphanInstitution((string) $institutionId);
            $response = $this->createUnderAlternateId($institution, (string) $institutionId);
            $payload = $response['institution'] ?? $response;
        }

        $this->applyTrustPayload($institution, is_array($payload) ? $payload : []);

        Log::channel('sifec')->info('Institution GUOT enrôlée', [
            'code_institution' => $institution->code_institution,
            'guot_institution_id' => $institution->guot_institution_id,
            'has_serial' => filled($institution->guot_institution_cert_serial),
        ]);

        return $institution->fresh();
    }

    /**
     * Relance un enroll après nettoyage local (évite la récursion via le branch « déjà enrôlé »).
     */
    private function enrollAfterClear(Institution $institution): Institution
    {
        $this->clearLocalGuotFields($institution);

        return $this->enroll($institution->fresh() ?? $institution);
    }

    /**
     * Resynchronise les métadonnées certificat depuis trust-api.
     *
     * @throws TrustException
     * @throws RuntimeException
     */
    public function syncFromTrustApi(Institution $institution): Institution
    {
        if (! filled($institution->guot_institution_id)) {
            throw new RuntimeException('Aucun guot_institution_id à synchroniser.');
        }

        try {
            $response = $this->signers->getInstitution($institution->guot_institution_id);
            $payload = $response['institution'] ?? $response;

            if (
                $this->isRevokedInstitutionPayload(is_array($payload) ? $payload : [])
                || $this->isRevokedInstitutionPayload($response)
            ) {
                // Actualiser ne doit pas « valider » un cachet révoqué : on recrée.
                return $this->enroll($institution->fresh() ?? $institution);
            }

            $this->applyTrustPayload($institution, is_array($payload) ? $payload : $response);

            return $institution->fresh();
        } catch (TrustException $e) {
            // Fiche absente mais clé Transit orpheline → récupération complète
            if ($e->getHttpStatus() === 404 || $this->isAlreadyEnrolledError($e) || $this->exceptionIndicatesRevoked($e)) {
                return $this->enroll($institution->fresh() ?? $institution);
            }
            throw $e;
        }
    }

    /**
     * Révoque le cachet institutionnel côté trust-api et nettoie les colonnes locales.
     *
     * @throws TrustException
     * @throws RuntimeException
     */
    public function revoke(Institution $institution, string $reason = 'cessation_of_operation'): Institution
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('trust-api non configuré (PKI_TRUST_API_URL / PKI_API_KEY).');
        }

        if (! filled($institution->guot_institution_id)) {
            throw new RuntimeException('Aucun cachet institutionnel à révoquer.');
        }

        $allowed = [
            'unspecified',
            'key_compromise',
            'affiliation_changed',
            'superseded',
            'cessation_of_operation',
        ];
        if (! in_array($reason, $allowed, true)) {
            throw new RuntimeException('Raison de révocation invalide.');
        }

        $institutionId = (string) $institution->guot_institution_id;

        if (! $this->tryRevokeOrphanInstitution($institutionId, $reason)) {
            throw new RuntimeException(
                "Échec de la révocation du cachet « {$institutionId} » côté GUOT/Signum."
            );
        }

        $institution->guot_institution_id = null;
        $institution->guot_institution_cert_serial = null;
        $institution->guot_institution_cert_not_before = null;
        $institution->guot_institution_cert_not_after = null;
        $institution->guot_institution_verifier_url = null;
        $institution->save();

        Log::channel('sifec')->info('Cachet institutionnel GUOT révoqué', [
            'code_institution' => $institution->code_institution,
            'guot_institution_id' => $institutionId,
            'reason' => $reason,
        ]);

        return $institution->fresh();
    }

    private function isAlreadyEnrolledError(TrustException $e): bool
    {
        $msg = mb_strtolower($e->getMessage());

        return $e->getHttpStatus() === 409
            || str_contains($msg, 'already')
            || str_contains($msg, 'existe déjà')
            || str_contains($msg, 'existe deja')
            || (str_contains($msg, 'transit') && (str_contains($msg, 'révoquer') || str_contains($msg, 'revoquer')));
    }

    /**
     * Cas orphelin : clé Transit présente, GET /v1/institutions/{id} → 404.
     *
     * @return array{institution?: array<string, mixed>}
     */
    private function recoverExistingInstitution(string $institutionId, Institution $institution): array
    {
        // 1) Lecture directe — uniquement si le cachet est ACTIF
        try {
            $response = $this->signers->getInstitution($institutionId);
            $payload = $response['institution'] ?? $response;
            if (
                ! $this->isRevokedInstitutionPayload(is_array($payload) ? $payload : [])
                && ! $this->isRevokedInstitutionPayload($response)
            ) {
                return $response;
            }
            Log::channel('sifec')->warning('getInstitution: cachet révoqué, recreation sous nouvel ID', [
                'institution_id' => $institutionId,
            ]);
        } catch (TrustException $e) {
            Log::channel('sifec')->warning('getInstitution après conflit', [
                'institution_id' => $institutionId,
                'error' => $e->getMessage(),
            ]);
        }

        // 2) Chercher dans la liste Signum un ID ACTIF pour cette institution
        $fromList = $this->findInInstitutionList($institutionId, $institution->code_institution);
        if ($fromList !== null && ! $this->isRevokedInstitutionPayload($fromList)) {
            return ['institution' => $fromList];
        }

        // 3) Best-effort : révoquer l’ancien ID (souvent déjà revoked), puis nouvel ID
        $this->tryRevokeOrphanInstitution($institutionId);

        return $this->createUnderAlternateId($institution, $institutionId);
    }

    /**
     * @return array{institution_id: string, nom: string, common_name: string, organization: string, ou: ?string}
     */
    private function createPayload(Institution $institution, string $institutionId): array
    {
        return [
            'institution_id' => $institutionId,
            'nom' => $institution->lib_institution,
            'common_name' => $institution->lib_institution,
            'organization' => config('app.name', 'SIFEC'),
            'ou' => $institution->typeInstitution?->lib_type_institution,
        ];
    }

    /**
     * Crée un cachet sous un nouvel ID unique si l’ID d’origine est orphelin.
     *
     * Une seule tentative : chaque échec partiel laisse une clé Transit orpheline.
     *
     * @return array{institution?: array<string, mixed>}
     */
    private function createUnderAlternateId(Institution $institution, string $blockedId): array
    {
        $candidate = 'sifec-'.$institution->code_institution.'-'.bin2hex(random_bytes(6));

        try {
            $response = $this->signers->createInstitution($this->createPayload($institution, $candidate));
            Log::channel('sifec')->info('Institution GUOT créée sous ID alternatif (orphelin contourné)', [
                'code_institution' => $institution->code_institution,
                'blocked_id' => $blockedId,
                'new_id' => $candidate,
            ]);

            return $response;
        } catch (TrustException $e) {
            // Création partielle / timeout + retry : la clé existe, la fiche peut arriver avec délai
            if ($this->isAlreadyEnrolledError($e)) {
                usleep(1_500_000);
                try {
                    $existing = $this->signers->getInstitution($candidate);
                    Log::channel('sifec')->info('ID alternatif récupéré via getInstitution après conflit', [
                        'candidate' => $candidate,
                    ]);

                    return $existing;
                } catch (TrustException $inner) {
                    Log::channel('sifec')->error('Création institution GUOT partielle (Transit orphelin)', [
                        'candidate' => $candidate,
                        'create_error' => $e->getMessage(),
                        'get_error' => $inner->getMessage(),
                    ]);
                }
            } else {
                throw $e;
            }
        }

        throw new RuntimeException(
            "Échec création cachet Signum (clé Transit orpheline côté OpenBao). "
            ."ID bloqué d’origine : « {$blockedId} », tentative : « {$candidate} ». "
            .'Le timeout d’écriture a été porté à 90s — réessayez une fois. '
            .'Si ça échoue encore, GUOT doit purger les clés Transit orphelines du tenant ten_sifecv1 '
            .'(préfixe institution-sifec-INS_0047*).'
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findInInstitutionList(string $institutionId, string $codeInstitution): ?array
    {
        try {
            $list = $this->signers->listInstitutions();
        } catch (TrustException $e) {
            Log::channel('sifec')->warning('listInstitutions échoué', ['error' => $e->getMessage()]);

            return null;
        }

        $items = $list['institutions'] ?? $list['data'] ?? (is_array($list) ? $list : []);
        if (! is_array($items)) {
            return null;
        }

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }
            $id = (string) ($item['institution_id'] ?? $item['id'] ?? '');
            if ($id === $institutionId || str_contains($id, $codeInstitution)) {
                Log::channel('sifec')->info('Institution trouvée via listInstitutions', [
                    'matched_id' => $id,
                ]);

                return $item;
            }
        }

        return null;
    }

    private function tryRevokeOrphanInstitution(string $institutionId, string $reason = 'superseded'): bool
    {
        $payloads = [
            ['institution_id' => $institutionId, 'reason' => $reason],
            ['actor_id' => $institutionId, 'reason' => $reason],
        ];

        foreach ($payloads as $payload) {
            try {
                $this->trust->post('/pki/revoke', $payload, 30);
                Log::channel('sifec')->info('Révocation institution OK', [
                    'institution_id' => $institutionId,
                    'payload_keys' => array_keys($payload),
                    'reason' => $reason,
                ]);

                return true;
            } catch (TrustException $e) {
                $msg = mb_strtolower($e->getMessage());
                // Déjà révoqué / introuvable : on considère la révocation comme aboutie côté PKI.
                if (
                    $e->getHttpStatus() === 404
                    || str_contains($msg, 'not found')
                    || str_contains($msg, 'already revoked')
                    || str_contains($msg, 'déjà révoqué')
                    || str_contains($msg, 'deja revoque')
                ) {
                    Log::channel('sifec')->info('Révocation institution : certificat déjà absent / révoqué', [
                        'institution_id' => $institutionId,
                        'error' => $e->getMessage(),
                    ]);

                    return true;
                }

                Log::channel('sifec')->warning('Tentative /pki/revoke', [
                    'payload_keys' => array_keys($payload),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function applyTrustPayload(Institution $institution, array $payload): void
    {
        $cert = $payload['certificate'] ?? $payload['cert'] ?? null;

        $institutionId = $payload['institution_id'] ?? $payload['id'] ?? null;
        if (filled($institutionId)) {
            $institution->guot_institution_id = $institutionId;
        }

        $serial = is_array($cert)
            ? ($cert['serial_number'] ?? $cert['serial'] ?? null)
            : ($payload['cert_serial'] ?? $payload['serial_number'] ?? null);
        if (filled($serial)) {
            $institution->guot_institution_cert_serial = $serial;
        }

        $notBefore = is_array($cert)
            ? ($cert['not_before'] ?? null)
            : ($payload['not_before'] ?? null);
        if (filled($notBefore)) {
            $institution->guot_institution_cert_not_before = $notBefore;
        }

        $notAfter = is_array($cert)
            ? ($cert['not_after'] ?? null)
            : ($payload['not_after'] ?? null);
        if (filled($notAfter)) {
            $institution->guot_institution_cert_not_after = $notAfter;
        }

        $verifierUrl = $payload['verifier_url'] ?? $payload['verification_url'] ?? null;
        if (filled($verifierUrl)) {
            $institution->guot_institution_verifier_url = $verifierUrl;
        }

        $institution->save();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function isRevokedInstitutionPayload(array $payload): bool
    {
        $candidates = [
            $payload['status'] ?? null,
            $payload['state'] ?? null,
            $payload['institution']['status'] ?? null,
            $payload['certificate']['status'] ?? null,
            $payload['cert']['status'] ?? null,
        ];

        foreach ($candidates as $status) {
            $normalized = mb_strtolower((string) $status);
            if (
                $normalized === 'revoked'
                || $normalized === 'révoqué'
                || $normalized === 'revoque'
                || str_contains($normalized, 'revok')
            ) {
                return true;
            }
        }

        return false;
    }

    private function exceptionIndicatesRevoked(TrustException $e): bool
    {
        $msg = mb_strtolower($e->getMessage());

        return str_contains($msg, 'revoked')
            || str_contains($msg, 'révoqué')
            || str_contains($msg, 'revoque')
            || (str_contains($msg, 'not active') && str_contains($msg, 'institution'));
    }

    private function clearLocalGuotFields(Institution $institution): void
    {
        $institution->guot_institution_id = null;
        $institution->guot_institution_cert_serial = null;
        $institution->guot_institution_cert_not_before = null;
        $institution->guot_institution_cert_not_after = null;
        $institution->guot_institution_verifier_url = null;
        $institution->save();
    }
}
