<?php

declare(strict_types=1);

namespace PkiSdk;

/**
 * SDK-first offboarding client.
 *
 * Covers the existing JSON endpoints under /v1/offboarding. Binary export
 * downloads are intentionally not exposed here because TrustClientInterface
 * returns decoded JSON arrays.
 */
class OffboardingClient
{
    private const TIMEOUT_READ = 3;
    private const TIMEOUT_WRITE = 15;

    public function __construct(
        private readonly TrustClientInterface $client,
    ) {}

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function suspendSigner(string $actorId, array $payload = []): array
    {
        return $this->signerAction($actorId, 'suspend', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function revokeSigner(string $actorId, array $payload = []): array
    {
        return $this->signerAction($actorId, 'revoke', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function reinstateSigner(string $actorId, array $payload = []): array
    {
        return $this->signerAction($actorId, 'reinstate', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function archiveSigner(string $actorId, array $payload = []): array
    {
        return $this->signerAction($actorId, 'archive', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function requestTenantTermination(string $tenantId, array $payload = []): array
    {
        return $this->client->post(
            '/v1/offboarding/tenants/' . rawurlencode($tenantId) . '/terminate',
            $payload,
            self::TIMEOUT_WRITE
        );
    }

    /**
     * @param array<string, scalar|null> $filters
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function listRequests(array $filters = []): array
    {
        $query = array_filter(
            $filters,
            static fn (mixed $value): bool => $value !== null && $value !== ''
        );
        $path = '/v1/offboarding/requests';
        if ($query !== []) {
            $path .= '?' . http_build_query($query);
        }

        return $this->client->get($path, self::TIMEOUT_READ);
    }

    /**
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function getRequest(string $requestId): array
    {
        return $this->client->get(
            '/v1/offboarding/requests/' . rawurlencode($requestId),
            self::TIMEOUT_READ
        );
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function approveRequest(string $requestId, array $payload = []): array
    {
        return $this->requestDecision($requestId, 'approve', $payload);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function rejectRequest(string $requestId, array $payload = []): array
    {
        return $this->requestDecision($requestId, 'reject', $payload);
    }

    /**
     * Issue a short-lived token for the existing binary export endpoint.
     *
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function createExportToken(string $reportId): array
    {
        return $this->client->post(
            '/v1/offboarding/reports/' . rawurlencode($reportId) . '/export-token',
            [],
            self::TIMEOUT_WRITE
        );
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    private function signerAction(string $actorId, string $action, array $payload): array
    {
        return $this->client->post(
            '/v1/offboarding/signers/' . rawurlencode($actorId) . '/' . $action,
            $payload,
            self::TIMEOUT_WRITE
        );
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     * @throws TrustException
     */
    private function requestDecision(string $requestId, string $decision, array $payload): array
    {
        return $this->client->post(
            '/v1/offboarding/requests/' . rawurlencode($requestId) . '/' . $decision,
            $payload,
            self::TIMEOUT_WRITE
        );
    }
}
