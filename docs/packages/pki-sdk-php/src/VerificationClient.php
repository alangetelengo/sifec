<?php

declare(strict_types=1);

namespace PkiSdk;

/**
 * VerificationClient
 *
 * Verifies signatures via the Trust API.
 */
class VerificationClient
{
    public function __construct(
        private readonly TrustClientInterface $client,
    ) {}

    /**
     * Verify a signature by proof ID.
     *
     * @return array<string, mixed> { valid, reason, certificate_ref, signed_at }
     * @throws TrustException
     */
    public function verifyByProofId(string $proofId): array
    {
        return $this->client->post('/verify', ['proof_id' => $proofId], 3);
    }

    /**
     * Verify a signature by providing the original payload and signature.
     *
     * @param  array<string, mixed> $payload
     * @return array<string, mixed> { valid, reason, certificate_ref, signed_at }
     * @throws TrustException
     */
    public function verifyByPayload(array $payload, string $signature): array
    {
        return $this->client->post('/verify', [
            'payload'   => $payload,
            'signature' => $signature,
        ], 3);
    }

    /**
     * Verify a proof through the public verification endpoint.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function publicVerifyByProofId(string $proofId): array
    {
        return $this->client->post('/v1/public/verify', ['proof_id' => $proofId], 3);
    }

    /**
     * Verify a payload/signature pair through the public verification endpoint.
     *
     * @param  array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function publicVerifyByPayload(array $payload, string $signature, ?string $algorithm = null): array
    {
        $body = [
            'payload'   => $payload,
            'signature' => $signature,
        ];
        if ($algorithm !== null && $algorithm !== '') {
            $body['algorithm'] = $algorithm;
        }

        return $this->client->post('/v1/public/verify', $body, 3);
    }

    /**
     * Retrieve public white-label verification context.
     *
     * @param array<string, scalar|null> $query
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function publicVerificationContext(array $query = []): array
    {
        $params = array_filter(
            $query,
            static fn (mixed $value): bool => $value !== null && $value !== ''
        );
        $path = '/v1/public/verification-context';
        if ($params !== []) {
            $path .= '?' . http_build_query($params);
        }

        return $this->client->get($path, 3);
    }
}
