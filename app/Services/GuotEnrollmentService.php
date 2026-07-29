<?php

namespace App\Services;

use App\Models\InstitutionUser;
use App\Models\User;
use App\Support\GuotSignataires;
use Illuminate\Support\Facades\Log;
use PkiSdk\SignerClient;
use PkiSdk\TrustException;
use RuntimeException;
use Illuminate\Support\Facades\Schema;

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

        $idempotencyKey = 'sifec:cui:'.$affectation->cui;

        try {
            $response = $this->signerClient->create([
                'nom' => $nom,
                'email' => $user->email,
                'external_user_id' => (string) $user->code_user,
                'enrollment_type' => 'p12',
                'role' => $affectation->fonction?->lib_fonction ?? $affectation->code_fonction,
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
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        $signer = $response['signer'] ?? $response;
        $cert = $signer['certificate'] ?? null;

        $affectation->guot_user_id = $signer['actor_id'] ?? null;
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

        $response = $this->signerClient->escrowP12($affectation->guot_user_id);
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
            Log::channel('sifec')->error('Révocation GUOT échouée', [
                'cui' => $affectation->cui,
                'actor_id' => $actorId,
                'reason' => $reason,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        if (Schema::hasColumn('tr_ins_user', 'code_raison_revocation')) {
            $affectation->code_raison_revocation = $meta['code_raison_revocation'] ?? null;
        }
        if (Schema::hasColumn('tr_ins_user', 'guot_revoke_justificatif') && array_key_exists('justificatif_chemin', $meta)) {
            $affectation->guot_revoke_justificatif = $meta['justificatif_chemin'];
        }
        if (Schema::hasColumn('tr_ins_user', 'guot_revoked_at')) {
            $affectation->guot_revoked_at = now();
        }
        if (Schema::hasColumn('tr_ins_user', 'guot_revoked_actor_id')) {
            $affectation->guot_revoked_actor_id = $actorId;
        }

        $affectation->guot_user_id = null;
        $affectation->guot_user_cert_serial = null;
        $affectation->guot_user_cert_not_before = null;
        $affectation->guot_user_cert_not_after = null;
        $affectation->guot_user_verifier_url = null;
        $affectation->save();

        Log::channel('sifec')->info('Révocation GUOT terminée', [
            'cui' => $affectation->cui,
            'actor_id' => $actorId,
            'reason' => $reason,
            'code_raison_revocation' => $meta['code_raison_revocation'] ?? null,
        ]);

        return $affectation->fresh();
    }
}
