<?php

declare(strict_types=1);

namespace LaravelPki;

use PkiSdk\SignatureClient;
use PkiSdk\SignerClient;
use PkiSdk\VerificationClient;
use PkiSdk\ProofClient;
use PkiSdk\OffboardingClient;
use PkiSdk\WebhookClient;
use PkiSdk\TrustException;

/**
 * SignatureManager
 *
 * Laravel-aware façade over the pki-sdk-php clients.
 * Provides a single entry point for application code.
 */
class SignatureManager
{
    public function __construct(
        private readonly SignatureClient    $signer,
        private readonly SignerClient       $signers,
        private readonly VerificationClient $verifier,
        private readonly ProofClient        $proofs,
        private readonly ?OffboardingClient $offboarding = null,
        private readonly ?WebhookClient     $webhooks = null,
    ) {}

    /**
     * Sign a transaction.
     *
     * @param  array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function sign(
        string $transactionId,
        array  $payload,
        string $actorId = '',
        string $purpose = '',
    ): array {
        return $this->signer->sign($transactionId, $payload, $actorId, $purpose);
    }

    /**
     * Step 1 of the external/hardware-signed flow: get the hash to sign
     * locally (browser holding a .p12 today, a smart card tomorrow).
     *
     * @param  array<string, mixed> $payload
     * @return array<string, mixed> { payload_hash, payload }
     * @throws TrustException
     */
    public function prepareExternalSignature(
        string $transactionId,
        array  $payload,
        string $actorId = '',
        string $purpose = '',
    ): array {
        return $this->signer->prepareExternalSignature($transactionId, $payload, $actorId, $purpose);
    }

    /**
     * Step 2 of the external/hardware-signed flow: submit a signature produced
     * entirely outside trust-api for verification and proof recording.
     *
     * @param  array<string, mixed> $payload the exact augmented payload from prepareExternalSignature()
     * @return array<string, mixed> same shape as sign()
     * @throws TrustException
     */
    public function submitExternalSignature(
        string $transactionId,
        array  $payload,
        string $signatureBase64,
        string $actorId = '',
        string $purpose = '',
    ): array {
        return $this->signer->submitExternalSignature($transactionId, $payload, $signatureBase64, $actorId, $purpose);
    }

    /**
     * Verify a signature by proof ID.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function verify(string $proofId): array
    {
        return $this->verifier->verifyByProofId($proofId);
    }

    /**
     * Verify a proof through the public verification endpoint.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function publicVerify(string $proofId): array
    {
        return $this->verifier->publicVerifyByProofId($proofId);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function publicVerifyPayload(array $payload, string $signature, ?string $algorithm = null): array
    {
        return $this->verifier->publicVerifyByPayload($payload, $signature, $algorithm);
    }

    /**
     * @param array<string, scalar|null> $query
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function publicVerificationContext(array $query = []): array
    {
        return $this->verifier->publicVerificationContext($query);
    }

    /**
     * Retrieve a proof by ID.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getProof(string $proofId): array
    {
        return $this->proofs->get($proofId);
    }

    /**
     * Retrieve a public verification bundle for a proof.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getPublicProofBundle(string $proofId): array
    {
        return $this->proofs->getPublicBundle($proofId);
    }

    // ─── Document signature (Layer 2) ─────────────────────────────────────────

    /**
     * Sign the SHA-256 hash of a document (PDF binary) on behalf of a human actor.
     *
     * @param  string $documentHash Hex-encoded SHA-256 of the PDF bytes
     * @param  string $actorId      Enrolled actor (e.g. 'agent-marie-dupont')
     * @param  string $purpose      Purpose label (e.g. 'signature_acte_naissance')
     * @param  string $documentRef  Human-readable reference (e.g. filename)
     * @return array<string, mixed> { doc_sig_id, signature_der_b64, certificate_ref, cert_pem, ... }
     * @throws TrustException
     */
    public function signDocument(
        string $documentHash,
        string $actorId,
        string $purpose     = '',
        string $documentRef = '',
    ): array {
        return $this->signer->signDocument($documentHash, $actorId, $purpose, $documentRef);
    }

    /**
     * Step 1 of the external/hardware-signed flow (Layer 2 — document hash):
     * verifies the (p12-enrolled) signer's eligibility and echoes back the
     * document_hash to sign locally.
     *
     * @return array<string, mixed> { document_hash, document_ref, actor_id, purpose, proof_id }
     * @throws TrustException
     */
    public function prepareExternalDocumentSignature(
        string  $documentHash,
        string  $actorId,
        string  $purpose      = '',
        string  $documentRef  = '',
        ?string $proofId      = null,
    ): array {
        return $this->signer->prepareExternalDocumentSignature($documentHash, $actorId, $purpose, $documentRef, $proofId);
    }

    /**
     * Step 2 of the external/hardware-signed flow (Layer 2): submit a
     * signature computed outside trust-api for verification and recording.
     *
     * @return array<string, mixed> same shape as signDocument()
     * @throws TrustException
     */
    public function submitExternalDocumentSignature(
        string  $documentHash,
        string  $signatureBase64,
        string  $actorId,
        string  $purpose      = '',
        string  $documentRef  = '',
        ?string $proofId      = null,
    ): array {
        return $this->signer->submitExternalDocumentSignature($documentHash, $signatureBase64, $actorId, $purpose, $documentRef, $proofId);
    }

    /**
     * Apply an institutional electronic seal to a document hash (Layer 3).
     *
     * @param  string      $documentHash  Hex-encoded SHA-256 of the PDF bytes
     * @param  string      $institutionId Institution identifier provided by the integrator
     * @param  string      $purpose       Purpose label
     * @param  string      $documentRef   Human-readable reference
     * @param  string|null $docSigId      Optional link to the document signature (Layer 2)
     * @return array<string, mixed> { doc_seal_id, seal_der_b64, certificate_ref, cert_pem, ... }
     * @throws TrustException
     */
    public function sealDocument(
        string  $documentHash,
        string  $institutionId,
        string  $purpose       = '',
        string  $documentRef   = '',
        ?string $docSigId      = null,
    ): array {
        return $this->signer->sealDocument($documentHash, $institutionId, $purpose, $documentRef, $docSigId);
    }

    /**
     * Verify a document signature or institutional seal (online, via OpenBao Transit).
     *
     * @return array<string, mixed> { valid, reason, type, ... }
     * @throws TrustException
     */
    public function verifyDocument(
        string  $documentHash,
        ?string $docSigId  = null,
        ?string $docSealId = null,
    ): array {
        return $this->signer->verifyDocument($documentHash, $docSigId, $docSealId);
    }

    /**
     * Retrieve the full offline-verification bundle for a document signature.
     * Contains DER signature + certificate PEM + OpenSSL instructions.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getDocumentSignatureBundle(string $docSigId): array
    {
        return $this->signer->getDocumentSignatureBundle($docSigId);
    }

    /**
     * Retrieve the full offline-verification bundle for an institutional seal.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getDocumentSealBundle(string $docSealId): array
    {
        return $this->signer->getDocumentSealBundle($docSealId);
    }

    /**
     * Create a signer through the SDK-first SaaS contract (/v1/signers).
     *
     * The returned actor_id is stable and should be stored in the client
     * application's own users table.
     *
     * @param  array<string, mixed> $input
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function createSigner(array $input, ?string $idempotencyKey = null): array
    {
        return $this->signers->create($input, $idempotencyKey);
    }

    /**
     * Batch signer creation for initial imports.
     *
     * @param  array<int, array<string, mixed>> $items
     * @param  array<string, mixed>             $options
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function createSignerBatch(array $items, array $options = []): array
    {
        return $this->signers->createBatch($items, $options);
    }

    /**
     * Retrieve signer batch status.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getSignerBatch(string $batchId): array
    {
        return $this->signers->getBatch($batchId);
    }

    /**
     * Create a short-lived browser session for hardware-token enrollment.
     *
     * @param  array<string, mixed> $input
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function createTokenEnrollmentSession(array $input): array
    {
        return $this->signers->createTokenEnrollmentSession($input);
    }

    /**
     * Complete a hardware-token enrollment session by submitting the CSR.
     *
     * trust-api completes token enrollment with POST /v1/token-enrollments and
     * expects the browser payload to contain session_token and csr. The legacy
     * two-argument facade form is kept temporarily but emits a deprecation so
     * the unused session id is no longer ignored silently.
     *
     * @param  array<string, mixed>|string $inputOrSessionId
     * @param  array<string, mixed>|null   $input
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function completeTokenEnrollment(array|string $inputOrSessionId, ?array $input = null): array
    {
        if (is_array($inputOrSessionId)) {
            if ($input !== null) {
                throw new \InvalidArgumentException('Pass either the token enrollment payload or the deprecated session id + payload form, not both.');
            }

            return $this->signers->completeTokenEnrollment($inputOrSessionId);
        }

        trigger_error(
            'Passing $sessionId to SignatureManager::completeTokenEnrollment() is deprecated; pass a payload containing session_token and csr instead.',
            E_USER_DEPRECATED
        );

        if ($input === null) {
            throw new \InvalidArgumentException('Token enrollment completion payload is required.');
        }

        return $this->signers->completeTokenEnrollment($input);
    }

    /**
     * List signers known by Signum for the tenant.
     *
     * @param  array<string, scalar|null> $filters
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function listSigners(array $filters = []): array
    {
        return $this->signers->list($filters);
    }

    /**
     * Retrieve one Signum signer.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getSigner(string $actorId): array
    {
        return $this->signers->get($actorId);
    }

    /**
     * Retrieve lifecycle status through /v1/signers/{actor_id}/status.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getSignerLifecycleStatus(string $actorId): array
    {
        return $this->signers->status($actorId);
    }

    /**
     * Find a signer by certificate serial number (case-insensitive).
     *
     * @return array{signer: array<string, mixed>}
     * @throws TrustException
     */
    public function lookupSignerBySerial(string $serialHex): array
    {
        return $this->signers->lookupBySerial($serialHex);
    }

    /**
     * Aggregate signer counts by status, tenant-scoped.
     *
     * @return array{stats: array<string, int|string>}
     * @throws TrustException
     */
    public function getSignerStats(): array
    {
        return $this->signers->stats();
    }

    /**
     * Revoke a signer through the SDK-first SaaS contract.
     *
     * @param  array<string, mixed> $options
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function revokeSigner(string $actorId, string $reason = 'unspecified', array $options = []): array
    {
        return $this->signers->revoke($actorId, $reason, $options);
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function suspendSigner(string $actorId, array $options = []): array
    {
        return $this->signers->suspend($actorId, $options);
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function reinstateSigner(string $actorId, array $options = []): array
    {
        return $this->offboarding()->reinstateSigner($actorId, $options);
    }

    /**
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function archiveSigner(string $actorId, array $options = []): array
    {
        return $this->offboarding()->archiveSigner($actorId, $options);
    }

    /**
     * Renew a signer certificate while preserving actor_id.
     *
     * @param  array<string, mixed> $options
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function renewSigner(string $actorId, array $options = []): array
    {
        return $this->signers->renew($actorId, $options);
    }

    /**
     * Decrypt the escrowed private key of a signer enrolled with
     * enrollment_type=p12 and return a brand-new PKCS#12 bundle + passphrase.
     * Repeatable: a forgotten PIN is resolved by calling this again, not by
     * revoking the signer.
     *
     * @param  array<string, mixed> $options
     * @return array{actor_id: string, passphrase: string, p12_base64: string, serial_number: string|null}
     * @throws TrustException
     */
    public function escrowSignerP12(string $actorId, array $options = []): array
    {
        return $this->signers->escrowP12($actorId, $options);
    }

    /**
     * Enroll a new institution via the SDK-first /v1/institutions endpoint.
     *
     * Returns the full institution payload including signing_key_name, cert_pem,
     * issuer_dn, not_before, not_after, and certificate details.
     *
     * @param  array<string, mixed> $input  Must include institution_id and nom.
     *                                      Accepts common_name, organization, ou.
     * @return array{institution: array<string, mixed>}
     * @throws TrustException
     */
    public function createInstitution(array $input): array
    {
        return $this->signers->createInstitution($input);
    }

    /**
     * Retrieve a single institution by institution_id.
     *
     * @return array{institution: array<string, mixed>}
     * @throws TrustException
     */
    public function getInstitution(string $institutionId): array
    {
        return $this->signers->getInstitution($institutionId);
    }

    /**
     * List all institutions.
     *
     * @return array{institutions: list<array<string, mixed>>}
     * @throws TrustException
     */
    public function listInstitutions(): array
    {
        return $this->signers->listInstitutions();
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function requestTenantOffboarding(string $tenantId, array $payload = []): array
    {
        return $this->offboarding()->requestTenantTermination($tenantId, $payload);
    }

    /**
     * @param array<string, scalar|null> $filters
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function listOffboardingRequests(array $filters = []): array
    {
        return $this->offboarding()->listRequests($filters);
    }

    /**
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getOffboardingRequest(string $requestId): array
    {
        return $this->offboarding()->getRequest($requestId);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function approveOffboardingRequest(string $requestId, array $payload = []): array
    {
        return $this->offboarding()->approveRequest($requestId, $payload);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function rejectOffboardingRequest(string $requestId, array $payload = []): array
    {
        return $this->offboarding()->rejectRequest($requestId, $payload);
    }

    /**
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function createOffboardingExportToken(string $reportId): array
    {
        return $this->offboarding()->createExportToken($reportId);
    }

    /**
     * @deprecated Use createInstitution() which calls POST /v1/institutions.
     * @see self::createInstitution()
     *
     * @param  string      $actorId      Identifiant applicatif unique (ex: 'medecin-dr-kofi')
     * @param  string      $nom          Nom affiché
     * @param  string|null $titre        Titre / fonction (optionnel)
     * @param  string      $commonName   X.509 CN (= $nom si vide)
     * @param  string      $organization X.509 O
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function enrollSigner(
        string  $actorId,
        string  $nom,
        ?string $titre        = null,
        string  $commonName   = '',
        string  $organization = 'POC',
    ): array {
        return $this->signer->enrollSigner($actorId, $nom, $titre, $commonName, $organization);
    }

    /**
     * @deprecated Use createInstitution() which calls POST /v1/institutions.
     * @see self::createInstitution()
     *
     * @param  string $institutionId Identifiant unique (ex: 'chu-pointe-noire')
     * @param  string $nom           Nom affiché
     * @param  string $commonName    X.509 CN (= $nom si vide)
     * @param  string $organization  X.509 O
     * @param  string $ou            X.509 OU (optionnel)
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function enrollInstitution(
        string $institutionId,
        string $nom,
        string $commonName   = '',
        string $organization = 'POC',
        string $ou           = '',
    ): array {
        return $this->signer->enrollInstitution($institutionId, $nom, $commonName, $organization, $ou);
    }

    /**
     * @deprecated Use revokeSigner(), which calls /v1/signers/{actor_id}/revoke.
     *             Institution certificate revocation needs an explicit SDK/API
     *             contract and is not inferred from identifiers.
     *
     * @return array<string, mixed>
     * @throws TrustException Always thrown; the ambiguous legacy helper is disabled.
     */
    public function revokeCert(string $actorId, string $reason = 'unspecified'): array
    {
        throw new TrustException(
            'SignatureManager::revokeCert() is disabled because it cannot safely distinguish signer and institution certificates. '
            . 'Use revokeSigner() for signers; wait for an explicit institution revocation method before revoking institution certificates through the SDK.'
        );
    }

    /**
     * Legacy POC certificate status endpoint. New SaaS integrations should use
     * getSignerLifecycleStatus(), which calls /v1/signers/{actor_id}/status.
     *
     * @return array<string, mixed> { actor_id, nom, serial_number, status, revoked_at, ... }
     * @throws TrustException
     */
    public function getSignerStatus(string $actorId): array
    {
        return $this->signer->getSignerStatus($actorId);
    }

    /**
     * Retrieve the RFC 3161 timestamp bundle by serial number.
     * Contains tst_resp_der_b64, gen_time, tsa_cert_pem, ca_cert_pem,
     * and openssl ts -verify commands for offline verification.
     *
     * @return array<string, mixed>|null  null if serial is null or token not found
     */
    public function getRfc3161Bundle(?int $serial): ?array
    {
        return $this->signer->getRfc3161Bundle($serial);
    }

    /**
     * Enregistre un point de terminaison webhook auprès de trust-api.
     *
     * @param string[] $events
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function registerWebhookEndpoint(string $url, array $events): array
    {
        return $this->webhooks()->createEndpoint($url, $events);
    }

    /**
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function listWebhookEndpoints(): array
    {
        return $this->webhooks()->listEndpoints();
    }

    /**
     * @param array<string, scalar|null> $filters
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function listWebhookDeliveries(array $filters = []): array
    {
        return $this->webhooks()->listDeliveries($filters);
    }

    private function offboarding(): OffboardingClient
    {
        if ($this->offboarding === null) {
            throw new \LogicException('OffboardingClient is not configured on SignatureManager.');
        }

        return $this->offboarding;
    }

    private function webhooks(): WebhookClient
    {
        if ($this->webhooks === null) {
            throw new \LogicException('WebhookClient is not configured on SignatureManager.');
        }

        return $this->webhooks;
    }
}
