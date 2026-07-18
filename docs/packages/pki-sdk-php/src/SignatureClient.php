<?php

declare(strict_types=1);

namespace PkiSdk;

/**
 * SignatureClient
 *
 * Requests transaction signatures and document signatures from the Trust API.
 *
 * Per-operation timeout guidance (passed to TrustClientInterface):
 *   - verify / status  : 3 s  (read-only, fast)
 *   - sign / seal      : 5 s  (crypto operation, moderate)
 *   - enroll / revoke  : 15 s (PKI issuance, slow)
 */
class SignatureClient
{
    private const TIMEOUT_VERIFY = 3;
    private const TIMEOUT_SIGN   = 30;
    private const TIMEOUT_ENROLL = 30;
    public function __construct(
        private readonly TrustClientInterface $client,
    ) {}

    /**
     * Sign a transaction payload (Layer 1 — canonical JSON payload).
     *
     * @param  string               $transactionId Unique identifier for the transaction
     * @param  array<string, mixed> $payload       Document/data to sign
     * @param  string               $actorId       Identity of the signing actor
     * @param  string               $purpose       Purpose of the signature
     * @return array<string, mixed> { proof_id, signature, certificate_ref, signed_at, status }
     * @throws TrustException
     */
    public function sign(
        string $transactionId,
        array  $payload,
        string $actorId  = '',
        string $purpose  = '',
    ): array {
        return $this->client->post('/sign', [
            'transaction_id' => $transactionId,
            'payload'        => $payload,
            'actor_id'       => $actorId,
            'purpose'        => $purpose,
        ], self::TIMEOUT_SIGN);
    }

    /**
     * Step 1 of the external/hardware-signed flow (Layer 1): trust-api injects
     * signed_at/tenant context into the payload and returns the augmented
     * payload + its hash, for the caller to sign OUTSIDE trust-api (browser
     * holding a p12 today, a smart card/hardware token tomorrow). No proof is
     * created here — this is stateless.
     *
     * @param  string               $transactionId Unique identifier for the transaction
     * @param  array<string, mixed> $payload       Document/data to sign
     * @param  string               $actorId       Identity of the signing actor
     * @param  string               $purpose       Purpose of the signature
     * @return array<string, mixed> { payload_hash, payload }
     * @throws TrustException
     */
    public function prepareExternalSignature(
        string $transactionId,
        array  $payload,
        string $actorId  = '',
        string $purpose  = '',
    ): array {
        return $this->client->post('/sign/prepare', [
            'transaction_id' => $transactionId,
            'payload'        => $payload,
            'actor_id'       => $actorId,
            'purpose'        => $purpose,
        ], self::TIMEOUT_SIGN);
    }

    /**
     * Step 2 of the external/hardware-signed flow: submit a signature produced
     * entirely outside trust-api for verification against the signer's own
     * certificate and proof recording. $payload must be the exact augmented
     * payload returned by prepareExternalSignature() — echoed back unchanged.
     *
     * @param  string               $transactionId
     * @param  array<string, mixed> $payload          The augmented payload from prepareExternalSignature()
     * @param  string               $signatureBase64  Base64-encoded DER ECDSA signature
     * @param  string               $actorId
     * @param  string               $purpose
     * @return array<string, mixed> same shape as sign()
     * @throws TrustException
     */
    public function submitExternalSignature(
        string $transactionId,
        array  $payload,
        string $signatureBase64,
        string $actorId  = '',
        string $purpose  = '',
    ): array {
        return $this->client->post('/sign/external', [
            'transaction_id' => $transactionId,
            'payload'        => $payload,
            'signature'      => $signatureBase64,
            'actor_id'       => $actorId,
            'purpose'        => $purpose,
        ], self::TIMEOUT_SIGN);
    }

    /**
     * Sign a document hash (Layer 2 — SHA-256 of PDF binary content).
     *
     * @param  string $documentHash  Hex-encoded SHA-256 of the document bytes
     * @param  string $actorId       Identity of the signing actor (enrolled signer)
     * @param  string $purpose       Purpose descriptor (e.g. 'signature_acte_naissance')
     * @param  string $documentRef   Human-readable document reference (e.g. filename)
     * @return array<string, mixed> {
     *     doc_sig_id, signature_der_b64, certificate_ref, cert_pem,
     *     signing_key_ref, algorithm, actor_id, purpose, signed_at
     * }
     * @throws TrustException
     */
    public function signDocument(
        string $documentHash,
        string $actorId,
        string $purpose     = '',
        string $documentRef = '',
    ): array {
        return $this->client->post('/sign-document', [
            'document_hash' => $documentHash,
            'actor_id'      => $actorId,
            'purpose'       => $purpose,
            'document_ref'  => $documentRef,
        ], self::TIMEOUT_SIGN);
    }

    /**
     * Step 1 of the external/hardware-signed flow (Layer 2 — document hash):
     * verifies the signer is eligible and p12-enrolled, and echoes back the
     * document_hash to sign OUTSIDE trust-api. No augmentation is needed here
     * (unlike prepareExternalSignature()) — the hash to sign is already fully
     * determined by the caller. Stateless — no proof is created.
     *
     * @param  string $documentHash Hex-encoded SHA-256 of the document bytes
     * @param  string $actorId      Identity of the signing actor (must be enrollment_type=p12)
     * @param  string $purpose      Purpose descriptor
     * @param  string $documentRef  Human-readable document reference
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
        return $this->client->post('/sign-document/prepare', [
            'document_hash' => $documentHash,
            'actor_id'      => $actorId,
            'purpose'       => $purpose,
            'document_ref'  => $documentRef,
            'proof_id'      => $proofId,
        ], self::TIMEOUT_SIGN);
    }

    /**
     * Step 2 of the external/hardware-signed flow (Layer 2): submit a
     * signature computed outside trust-api over the exact document_hash
     * bytes (SubtleCrypto.sign({hash:'SHA-256'}, key, hashBytes) client-side)
     * for verification against the signer's certificate and proof recording.
     *
     * @param  string $documentHash    Hex-encoded SHA-256 of the document bytes — must match prepare()
     * @param  string $signatureBase64 Base64-encoded DER ECDSA signature
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
        return $this->client->post('/sign-document/external', [
            'document_hash' => $documentHash,
            'signature'     => $signatureBase64,
            'actor_id'      => $actorId,
            'purpose'       => $purpose,
            'document_ref'  => $documentRef,
            'proof_id'      => $proofId,
        ], self::TIMEOUT_SIGN);
    }

    /**
     * Apply an institutional seal to a document hash (Layer 3).
     *
     * @param  string      $documentHash  Hex-encoded SHA-256 of the document bytes
     * @param  string      $institutionId Institution identifier (e.g. 'mairie-poc')
     * @param  string      $purpose       Purpose descriptor
     * @param  string      $documentRef   Human-readable document reference
     * @param  string|null $docSigId      Optional: link to an existing document signature
     * @return array<string, mixed> {
     *     doc_seal_id, seal_der_b64, certificate_ref, cert_pem,
     *     algorithm, institution_id, institution_nom, purpose, sealed_at
     * }
     * @throws TrustException
     */
    public function sealDocument(
        string  $documentHash,
        string  $institutionId,
        string  $purpose      = '',
        string  $documentRef  = '',
        ?string $docSigId     = null,
    ): array {
        return $this->client->post('/seal-document', [
            'document_hash'  => $documentHash,
            'institution_id' => $institutionId,
            'purpose'        => $purpose,
            'document_ref'   => $documentRef,
            'doc_sig_id'     => $docSigId,
        ], self::TIMEOUT_SIGN);
    }

    /**
     * Verify a document signature or institutional seal (online, via Transit).
     *
     * @param  string      $documentHash  Hex-encoded SHA-256 of the document bytes
     * @param  string|null $docSigId      ID of the document signature to verify
     * @param  string|null $docSealId     ID of the institutional seal to verify
     * @return array<string, mixed> { valid, type, reason, ... }
     * @throws TrustException
     */
    public function verifyDocument(
        string  $documentHash,
        ?string $docSigId  = null,
        ?string $docSealId = null,
    ): array {
        $body = ['document_hash' => $documentHash];
        if ($docSigId  !== null) $body['doc_sig_id']  = $docSigId;
        if ($docSealId !== null) $body['doc_seal_id'] = $docSealId;

        return $this->client->post('/verify-document', $body, self::TIMEOUT_VERIFY);
    }

    /**
     * Retrieve the full verification bundle for a document signature.
     * Contains DER signature, certificate PEM, and instructions for offline verify.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getDocumentSignatureBundle(string $docSigId): array
    {
        return $this->client->get('/document-signatures/' . urlencode($docSigId) . '/bundle', self::TIMEOUT_VERIFY);
    }

    /**
     * Retrieve the full verification bundle for an institutional seal.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getDocumentSealBundle(string $docSealId): array
    {
        return $this->client->get('/document-seals/' . urlencode($docSealId) . '/bundle', self::TIMEOUT_VERIFY);
    }

    /**
     * @deprecated Use SignerClient::revoke() for signers. Institution certificate
     *             revocation requires an explicit SDK/API contract and is tracked
     *             separately from this legacy POC helper.
     * @see \PkiSdk\SignerClient::revoke()
     *
     * @param  string $actorId  Former actor_id/institution_id argument
     * @param  string $reason   Former revocation reason
     * @return array<string, mixed>
     * @throws TrustException Always thrown; the ambiguous legacy helper is disabled.
     */
    public function revokeCert(string $actorId, string $reason = 'unspecified'): array
    {
        throw new TrustException(
            'SignatureClient::revokeCert() is disabled because it cannot safely distinguish signer and institution certificates. '
            . 'Use SignerClient::revoke() for signers; wait for an explicit institution revocation method before revoking institution certificates through the SDK.'
        );
    }

    /**
     * @deprecated Use SignerClient::create() which calls POST /v1/signers.
     *             That endpoint provides tenant scoping, idempotency, and richer responses.
     * @see \PkiSdk\SignerClient::create()
     *
     * @param  string      $actorId      Unique applicative identifier (e.g. 'medecin-dr-kofi')
     * @param  string      $nom          Display name
     * @param  string|null $titre        Optional title / function
     * @param  string      $commonName   X.509 CN (defaults to $nom)
     * @param  string      $organization X.509 O
     * @return array<string, mixed> { enrolled, entity_type, actor_id, subject_dn, serial_number,
     *                                fingerprint_sha256, not_after, status }
     * @throws TrustException
     */
    public function enrollSigner(
        string  $actorId,
        string  $nom,
        ?string $titre        = null,
        string  $commonName   = '',
        string  $organization = 'POC',
    ): array {
        // Route /enroll/signer has been removed. Delegate to the SDK-first /v1/signers endpoint.
        return $this->client->post('/v1/signers', [
            'actor_id'     => $actorId,
            'nom'          => $nom,
            'titre'        => $titre,
            'common_name'  => $commonName ?: $nom,
            'organization' => $organization,
        ], self::TIMEOUT_ENROLL);
    }

    /**
     * @deprecated Use SignerClient::createInstitution() which calls POST /v1/institutions.
     *             That endpoint provides tenant scoping and a richer response payload.
     * @see \PkiSdk\SignerClient::createInstitution()
     *
     * @param  string $institutionId Unique identifier (e.g. 'chu-pointe-noire')
     * @param  string $nom           Display name
     * @param  string $commonName    X.509 CN (defaults to $nom)
     * @param  string $organization  X.509 O
     * @param  string $ou            X.509 OU (optional)
     * @return array<string, mixed> { institution: { institution_id, subject_dn, ... } }
     * @throws TrustException
     */
    public function enrollInstitution(
        string $institutionId,
        string $nom,
        string $commonName   = '',
        string $organization = 'POC',
        string $ou           = '',
    ): array {
        // Route /enroll/institution has been removed. Delegate to the SDK-first /v1/institutions endpoint.
        return $this->client->post('/v1/institutions', [
            'institution_id' => $institutionId,
            'nom'            => $nom,
            'common_name'    => $commonName ?: $nom,
            'organization'   => $organization,
            'ou'             => $ou,
        ], self::TIMEOUT_ENROLL);
    }

    /**
     * Get the certificate status of a signer or institution.
     *
     * @param  string $actorId  actor_id (signer) or institution_id
     * @return array<string, mixed> { actor_id, nom, serial_number, status, revoked_at, ... }
     * @throws TrustException
     */
    public function getSignerStatus(string $actorId): array
    {
        return $this->client->get('/pki/signers/' . urlencode($actorId) . '/status', self::TIMEOUT_VERIFY);
    }

    /**
     * Retrieve the RFC 3161 timestamp bundle by serial number.
     * Contains: tst_resp_der_b64, serial_number, gen_time, tsa_cert_pem,
     *           ca_cert_pem, verify_commands (openssl ts -verify).
     *
     * @return array<string, mixed>|null  null if serial is null or token not found
     * @throws TrustException
     */
    public function getRfc3161Bundle(?int $serial): ?array
    {
        if ($serial === null) {
            return null;
        }
        try {
            return $this->client->get('/tsa/tokens/' . $serial . '/bundle', self::TIMEOUT_VERIFY);
        } catch (TrustException $e) {
            return null;
        }
    }
}
