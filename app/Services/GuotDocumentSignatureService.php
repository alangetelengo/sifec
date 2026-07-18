<?php

namespace App\Services;

use PkiSdk\SignatureClient;
use PkiSdk\TrustException;
use RuntimeException;

/**
 * Signatures cryptographiques — serveur (Transit) ou locale (.p12 / escrow).
 */
class GuotDocumentSignatureService
{
    public function __construct(private SignatureClient $signatures) {}

    public function isConfigured(): bool
    {
        return filled(config('pki.url')) && filled(config('pki.api_key'));
    }

    /**
     * @param  array<string, mixed>  $payload  Doit être déjà canonisé (ksort)
     * @return array<string, mixed>
     *
     * @throws TrustException
     */
    public function signPayload(string $transactionId, array $payload, string $actorId, string $purpose): array
    {
        return $this->signatures->sign($transactionId, $payload, $actorId, $purpose);
    }

    /**
     * Prépare une signature L1 externe (payload) pour un signataire .p12.
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     *
     * @throws TrustException
     */
    public function prepareExternalPayload(string $transactionId, array $payload, string $actorId, string $purpose): array
    {
        return $this->signatures->prepareExternalSignature($transactionId, $payload, $actorId, $purpose);
    }

    /**
     * Soumet une signature L1 produite côté client.
     *
     * @param  array<string, mixed>  $payload  Payload exact renvoyé par prepareExternalPayload
     * @return array<string, mixed>
     *
     * @throws TrustException
     */
    public function submitExternalPayload(
        string $transactionId,
        array $payload,
        string $signatureBase64,
        string $actorId,
        string $purpose,
    ): array {
        return $this->signatures->submitExternalSignature(
            $transactionId,
            $payload,
            $signatureBase64,
            $actorId,
            $purpose,
        );
    }

    /**
     * Vérifie une signature document (L2) produite localement avec le .p12
     * (prepare + submit external côté trust-api).
     *
     * @return array<string, mixed>
     *
     * @throws TrustException
     * @throws RuntimeException
     */
    public function verifyClientDocumentSignature(
        string $documentHash,
        string $signatureHex,
        string $actorId,
        string $purpose,
        string $documentRef,
        ?string $proofId = null,
    ): array {
        $signatureBytes = @hex2bin(preg_replace('/\s+/', '', $signatureHex) ?? '');
        if ($signatureBytes === false || $signatureBytes === '') {
            throw new RuntimeException('Signature hexadécimale invalide.');
        }

        $signatureBase64 = base64_encode($signatureBytes);

        $prepared = $this->signatures->prepareExternalDocumentSignature(
            $documentHash,
            $actorId,
            $purpose,
            $documentRef,
            $proofId,
        );

        return $this->signatures->submitExternalDocumentSignature(
            $documentHash,
            $signatureBase64,
            $actorId,
            $purpose,
            $documentRef,
            $prepared['proof_id'] ?? $proofId,
        );
    }

    /**
     * Cachet institutionnel L3 (serveur / Transit institution).
     *
     * @return array<string, mixed>
     *
     * @throws TrustException
     */
    public function sealDocument(
        string $documentHash,
        string $institutionId,
        string $purpose,
        string $documentRef,
        ?string $docSigId = null,
    ): array {
        return $this->signatures->sealDocument(
            $documentHash,
            $institutionId,
            $purpose,
            $documentRef,
            $docSigId,
        );
    }

    /**
     * @return array{hash: string, l2: array<string, mixed>, l3: array<string, mixed>}
     *
     * @throws TrustException
     * @throws RuntimeException
     */
    public function signAndSealPdf(
        string $pdfBinary,
        string $actorId,
        string $institutionId,
        string $purpose,
        string $documentRef,
        ?string $proofId = null,
    ): array {
        if ($pdfBinary === '') {
            throw new RuntimeException('PDF vide — impossible de calculer le hash L2/L3.');
        }

        $hash = hash('sha256', $pdfBinary);

        $l2 = $this->signatures->signDocument($hash, $actorId, $purpose, $documentRef);
        $docSigId = $l2['doc_sig_id'] ?? null;

        $l3 = $this->signatures->sealDocument(
            $hash,
            $institutionId,
            $purpose,
            $documentRef,
            is_string($docSigId) ? $docSigId : null,
        );

        return [
            'hash' => $hash,
            'l2' => $l2,
            'l3' => $l3,
            'proof_id' => $proofId,
        ];
    }
}
