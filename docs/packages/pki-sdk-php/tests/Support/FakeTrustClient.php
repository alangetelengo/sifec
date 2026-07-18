<?php

declare(strict_types=1);

namespace PkiSdk\Tests\Support;

use PkiSdk\TrustClientInterface;

final class FakeTrustClient implements TrustClientInterface
{
    /** @var list<array{method: string, path: string, body?: array<string, mixed>, timeout: int}> */
    public array $calls = [];

    /** @var list<array<string, mixed>> */
    private array $responses;

    /**
     * @param list<array<string, mixed>> $responses
     */
    public function __construct(array $responses = [['ok' => true]])
    {
        $this->responses = $responses;
    }

    public function post(string $path, array $body, int $timeout = 0): array
    {
        $this->calls[] = [
            'method' => 'POST',
            'path' => $path,
            'body' => $body,
            'timeout' => $timeout,
        ];

        return array_shift($this->responses) ?? ['ok' => true];
    }

    public function get(string $path, int $timeout = 0): array
    {
        $this->calls[] = [
            'method' => 'GET',
            'path' => $path,
            'timeout' => $timeout,
        ];

        return array_shift($this->responses) ?? ['ok' => true];
    }
}
