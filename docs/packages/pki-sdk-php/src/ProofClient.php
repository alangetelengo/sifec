<?php

declare(strict_types=1);

namespace PkiSdk;

/**
 * ProofClient
 *
 * Retrieves archival proofs from the Trust API.
 */
class ProofClient
{
    public function __construct(
        private readonly TrustClientInterface $client,
    ) {}

    /**
     * Retrieve a proof by its identifier.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function get(string $id): array
    {
        return $this->client->get('/proofs/' . rawurlencode($id), 3);
    }

    /**
     * Retrieve the public verification bundle for a proof.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getPublicBundle(string $proofId): array
    {
        return $this->client->get('/v1/public/proofs/' . rawurlencode($proofId) . '/bundle', 3);
    }
}
