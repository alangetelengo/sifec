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

        if (filled($institution->guot_institution_id) && filled($institution->guot_institution_cert_serial)) {
            return $this->syncFromTrustApi($institution);
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

            $response = $this->recoverExistingInstitution($institutionId, $institution);
        }

        $this->applyTrustPayload($institution, $response['institution'] ?? $response);

        Log::channel('sifec')->info('Institution GUOT enrôlée', [
            'code_institution' => $institution->code_institution,
            'guot_institution_id' => $institution->guot_institution_id,
            'has_serial' => filled($institution->guot_institution_cert_serial),
        ]);

        return $institution->fresh();
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
            $this->applyTrustPayload($institution, $response['institution'] ?? $response);

            return $institution->fresh();
        } catch (TrustException $e) {
            // Fiche absente mais clé Transit orpheline → récupération complète
            if ($e->getHttpStatus() === 404 || $this->isAlreadyEnrolledError($e)) {
                return $this->enroll($institution->fresh() ?? $institution);
            }
            throw $e;
        }
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
        // 1) Lecture directe
        try {
            return $this->signers->getInstitution($institutionId);
        } catch (TrustException $e) {
            Log::channel('sifec')->warning('getInstitution après conflit', [
                'institution_id' => $institutionId,
                'error' => $e->getMessage(),
            ]);
        }

        // 2) Chercher dans la liste Signum (autre ID possible)
        $fromList = $this->findInInstitutionList($institutionId, $institution->code_institution);
        if ($fromList !== null) {
            return ['institution' => $fromList];
        }

        // 3) Tentative de révocation de l’orphelin, puis recréation du même ID
        if ($this->tryRevokeOrphanInstitution($institutionId)) {
            try {
                return $this->signers->createInstitution($this->createPayload($institution, $institutionId));
            } catch (TrustException $e) {
                Log::channel('sifec')->warning('Recréation après revoke échouée', [
                    'institution_id' => $institutionId,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // 4) Contournement : nouvel institution_id (clé Transit orpheline non révocable via API)
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

    private function tryRevokeOrphanInstitution(string $institutionId): bool
    {
        $payloads = [
            ['institution_id' => $institutionId, 'reason' => 'superseded'],
            ['actor_id' => $institutionId, 'reason' => 'superseded'],
        ];

        foreach ($payloads as $payload) {
            try {
                $this->trust->post('/pki/revoke', $payload, 30);
                Log::channel('sifec')->info('Révocation orphelin institution OK', [
                    'institution_id' => $institutionId,
                    'payload_keys' => array_keys($payload),
                ]);

                return true;
            } catch (TrustException $e) {
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
}
