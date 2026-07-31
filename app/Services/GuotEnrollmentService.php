<?php

namespace App\Services;

use App\Models\InstitutionUser;
use App\Models\User;
use App\Support\GuotSignataires;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use PkiSdk\SignerClient;
use PkiSdk\TrustException;
use RuntimeException;

/**
 * Enrôlement PKI GUOT pour les responsables SIFEC (affectation tr_ins_user).
 */
class GuotEnrollmentService
{
    public function __construct(private SignerClient $signerClient) {}

    public function isConfigured(): bool
    {
        return filled(config('pki.url')) && filled(config('pki.api_key'));
    }

    /**
     * @param  array{organization?: string, organizational_unit?: string, country?: string, profile?: string}  $params
     *
     * @throws TrustException
     * @throws RuntimeException
     */
    public function enrollInstitutionUser(InstitutionUser $affectation, User $user, array $params = [], ?User $declaredBy = null): InstitutionUser
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('trust-api non configuré (PKI_TRUST_API_URL / PKI_API_KEY).');
        }

        if (! GuotSignataires::isSignataire($affectation->code_fonction)) {
            throw new RuntimeException('Cette fonction n’est pas éligible à l’enrôlement PKI.');
        }

        if (filled($affectation->guot_user_id)) {
            throw new RuntimeException('Cette affectation possède déjà un certificat GUOT.');
        }

        $user->loadMissing('personne');
        $nom = trim(($user->personne?->nom ?? '').' '.($user->personne?->prenom ?? ''));
        if ($nom === '') {
            $nom = $user->email ?? $user->code_user;
        }

        $declaredBy = $declaredBy ?? $user;
        $declaredBy->loadMissing('personne');
        $declaredName = trim(($declaredBy->personne?->nom ?? '').' '.($declaredBy->personne?->prenom ?? ''));
        if ($declaredName === '') {
            $declaredName = $declaredBy->email ?? $declaredBy->code_user;
        }

        $organization = $params['organization'] ?? config('app.name', 'SIFEC');
        $country = strtoupper((string) ($params['country'] ?? 'CG'));
        $profile = $params['profile'] ?? 'user_auth_enc';
        $ou = $params['organizational_unit'] ?? null;
        $role = $affectation->fonction?->lib_fonction ?? $affectation->code_fonction;

        // Après révocation, un nouvel external_user_id évite que Trust API renvoie l’ancien actor révoqué.
        $generation = (string) ($affectation->guot_revoked_at
            ?? $affectation->guot_revoked_actor_id
            ?? '0');
        $externalUserId = (string) $user->code_user;
        if ($generation !== '0') {
            $externalUserId .= '#'.substr(hash('sha256', $generation), 0, 8);
        }

        // Clé stable pour un même corps ; change si payload / génération change (évite 409 body mismatch).
        $idempotencyFingerprint = hash('sha256', json_encode([
            'cui' => $affectation->cui,
            'code_user' => (string) $user->code_user,
            'external_user_id' => $externalUserId,
            'email' => (string) $user->email,
            'nom' => $nom,
            'role' => $role,
            'organization' => $organization,
            'organizational_unit' => $ou,
            'country' => $country,
            'profile' => $profile,
            'code_institution' => $affectation->code_institution,
            'code_fonction' => $affectation->code_fonction,
            'generation' => $generation,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $idempotencyKey = 'sifec:enroll:'.$affectation->cui.':'.substr($idempotencyFingerprint, 0, 40);

        try {
            $response = $this->signerClient->create([
                'nom' => $nom,
                'email' => $user->email,
                'external_user_id' => $externalUserId,
                'enrollment_type' => 'p12',
                'role' => $role,
                'declared_by' => $declaredName,
                'institution_unit' => $ou,
                'assurance_level' => 'institution_delegated',
                'onboarding_mode' => 'client_delegated',
                'common_name' => $nom,
                'organization' => $organization,
                'metadata' => [
                    'country' => $country,
                    'profile' => $profile,
                    'cui' => $affectation->cui,
                    'code_institution' => $affectation->code_institution,
                    'code_fonction' => $affectation->code_fonction,
                ],
            ], $idempotencyKey);
        } catch (TrustException $e) {
            Log::channel('sifec')->error('Enrôlement GUOT échoué', [
                'cui' => $affectation->cui,
                'code_user' => $user->code_user,
                'idempotency_key' => $idempotencyKey,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        $signer = $response['signer'] ?? $response;
        $cert = $signer['certificate'] ?? null;
        $actorId = $signer['actor_id'] ?? null;

        if (! filled($actorId)) {
            throw new RuntimeException('Réponse trust-api invalide (actor_id manquant).');
        }

        if ($this->isSignerRevoked((string) $actorId, $signer)) {
            $this->clearLocalGuotCertificate($affectation, (string) $actorId);
            Log::channel('sifec')->warning('Enrôlement GUOT : actor renvoyé déjà révoqué', [
                'cui' => $affectation->cui,
                'actor_id' => $actorId,
            ]);
            throw new RuntimeException(
                'Trust API a renvoyé un certificat déjà révoqué. Relancez « Générer le certificat » pour créer un nouveau signataire.'
            );
        }

        $affectation->guot_user_id = $actorId;
        $affectation->guot_user_cert_serial = $cert['serial_number'] ?? null;
        $affectation->guot_user_cert_not_before = $cert['not_before'] ?? null;
        $affectation->guot_user_cert_not_after = $cert['not_after'] ?? null;
        $affectation->guot_user_verifier_url = $signer['verifier_url'] ?? ($response['verifier_url'] ?? null);
        $affectation->save();

        Log::channel('sifec')->info('Enrôlement GUOT terminé', [
            'cui' => $affectation->cui,
            'actor_id' => $affectation->guot_user_id,
            'serial' => $affectation->guot_user_cert_serial,
        ]);

        return $affectation->fresh();
    }

    /**
     * @return array{passphrase: string, p12_binary: string, serial_number?: string}
     */
    public function downloadP12(InstitutionUser $affectation): array
    {
        if (! filled($affectation->guot_user_id)) {
            throw new RuntimeException('Aucun actor_id GUOT sur cette affectation.');
        }

        $actorId = (string) $affectation->guot_user_id;

        try {
            $response = $this->signerClient->escrowP12($actorId);
        } catch (TrustException $e) {
            if ($this->exceptionIndicatesRevoked($e)) {
                $this->clearLocalGuotCertificate($affectation, $actorId);
                Log::channel('sifec')->warning('Téléchargement .p12 : certificat révoqué côté Trust API, état local synchronisé', [
                    'cui' => $affectation->cui,
                    'actor_id' => $actorId,
                ]);
                throw new RuntimeException(
                    'Ce certificat est révoqué côté Trust API. L’état SIFEC a été mis à jour : générez un nouveau certificat.'
                );
            }
            throw $e;
        }

        $p12Base64 = $response['p12_base64'] ?? null;
        if (! is_string($p12Base64) || $p12Base64 === '') {
            throw new RuntimeException('Réponse trust-api invalide (p12 manquant).');
        }

        return [
            'passphrase' => (string) ($response['passphrase'] ?? ''),
            'p12_binary' => base64_decode($p12Base64, true) ?: '',
            'serial_number' => $response['serial_number'] ?? null,
        ];
    }

    /**
     * Révocation PKI définitive (guide GUOT §11.3).
     * Les signatures passées restent valides ; un nouvel enrôlement crée un nouvel actor_id.
     *
     * @param  array{code_raison_revocation?: string|null, justificatif_chemin?: string|null}  $meta
     *
     * @throws TrustException
     * @throws RuntimeException
     */
    public function revokeInstitutionUser(
        InstitutionUser $affectation,
        string $reason = 'cessation_of_operation',
        array $meta = []
    ): InstitutionUser {
        if (! $this->isConfigured()) {
            throw new RuntimeException('trust-api non configuré (PKI_TRUST_API_URL / PKI_API_KEY).');
        }

        if (! filled($affectation->guot_user_id)) {
            throw new RuntimeException('Aucun certificat GUOT à révoquer sur cette affectation.');
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

        $actorId = (string) $affectation->guot_user_id;

        try {
            $this->signerClient->revoke($actorId, $reason);
        } catch (TrustException $e) {
            // Déjà révoqué côté Trust : on synchronise quand même l’état local.
            if (! $this->exceptionIndicatesRevoked($e)) {
                Log::channel('sifec')->error('Révocation GUOT échouée', [
                    'cui' => $affectation->cui,
                    'actor_id' => $actorId,
                    'reason' => $reason,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
            Log::channel('sifec')->info('Révocation GUOT : déjà révoqué côté Trust API', [
                'cui' => $affectation->cui,
                'actor_id' => $actorId,
            ]);
        }

        if (Schema::hasColumn('tr_ins_user', 'code_raison_revocation')) {
            $affectation->code_raison_revocation = $meta['code_raison_revocation'] ?? null;
        }
        if (Schema::hasColumn('tr_ins_user', 'guot_revoke_justificatif') && array_key_exists('justificatif_chemin', $meta)) {
            $affectation->guot_revoke_justificatif = $meta['justificatif_chemin'];
        }

        $this->clearLocalGuotCertificate($affectation, $actorId);

        Log::channel('sifec')->info('Révocation GUOT terminée', [
            'cui' => $affectation->cui,
            'actor_id' => $actorId,
            'reason' => $reason,
            'code_raison_revocation' => $meta['code_raison_revocation'] ?? null,
        ]);

        return $affectation->fresh();
    }

    /**
     * @param  array<string, mixed>  $signerPayload
     */
    private function isSignerRevoked(string $actorId, array $signerPayload = []): bool
    {
        $status = strtolower((string) ($signerPayload['status'] ?? ''));
        if ($status === 'revoked') {
            return true;
        }

        try {
            $remote = $this->signerClient->status($actorId);
            $remoteStatus = strtolower((string) (
                $remote['status']
                ?? $remote['signer']['status']
                ?? ''
            ));

            return $remoteStatus === 'revoked';
        } catch (TrustException $e) {
            Log::channel('sifec')->warning('Impossible de vérifier le statut GUOT du signataire', [
                'actor_id' => $actorId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function exceptionIndicatesRevoked(TrustException $e): bool
    {
        return str_contains(strtolower($e->getMessage()), 'revoked')
            || str_contains(strtolower($e->getMessage()), 'révoqué');
    }

    private function clearLocalGuotCertificate(InstitutionUser $affectation, ?string $actorId = null): void
    {
        $actorId = $actorId ?? (string) ($affectation->guot_user_id ?? '');

        if (Schema::hasColumn('tr_ins_user', 'guot_revoked_at')) {
            $affectation->guot_revoked_at = now();
        }
        if ($actorId !== '' && Schema::hasColumn('tr_ins_user', 'guot_revoked_actor_id')) {
            $affectation->guot_revoked_actor_id = $actorId;
        }

        $affectation->guot_user_id = null;
        $affectation->guot_user_cert_serial = null;
        $affectation->guot_user_cert_not_before = null;
        $affectation->guot_user_cert_not_after = null;
        $affectation->guot_user_verifier_url = null;
        $affectation->save();
    }
}
