<?php

declare(strict_types=1);

namespace PkiSdk;

class WebhookClient
{
    private const TIMEOUT_READ = 3;
    private const TIMEOUT_WRITE = 10;

    public function __construct(
        private readonly TrustClientInterface $client,
    ) {}

    /**
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function listEndpoints(): array
    {
        return $this->client->get('/v1/webhooks/endpoints', self::TIMEOUT_READ);
    }

    /**
     * @param string[] $events
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function createEndpoint(string $url, array $events): array
    {
        return $this->client->post('/v1/webhooks/endpoints', [
            'url' => $url,
            'events' => $events,
        ], self::TIMEOUT_WRITE);
    }

    /**
     * @param array<string, scalar|null> $filters
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function listDeliveries(array $filters = []): array
    {
        $query = array_filter(
            $filters,
            static fn (mixed $value): bool => $value !== null && $value !== ''
        );
        $path = '/v1/webhooks/deliveries';
        if ($query !== []) {
            $path .= '?' . http_build_query($query);
        }

        return $this->client->get($path, self::TIMEOUT_READ);
    }
}
