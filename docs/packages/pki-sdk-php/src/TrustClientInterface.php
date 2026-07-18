<?php

declare(strict_types=1);

namespace PkiSdk;

/**
 * TrustClientInterface
 *
 * Abstraction over the HTTP transport to the Trust API.
 * Implement this interface in tests to avoid real HTTP calls:
 *
 *     $mock = new class implements TrustClientInterface {
 *         public function post(string $path, array $body, int $timeout = 0): array { ... }
 *         public function get(string $path, int $timeout = 0): array { ... }
 *     };
 *
 *     $client = new SignatureClient($mock);
 *
 * The $timeout parameter (seconds) overrides the instance-level default for a
 * single call.  Pass 0 to use the instance default (recommended in most cases).
 *
 * Recommended per-operation timeouts (informational):
 *   - verify / proof lookup : 3 s
 *   - sign / seal           : 5 s
 *   - enroll / revoke       : 15 s
 */
interface TrustClientInterface
{
    /**
     * @param  array<string, mixed> $body
     * @param  int                  $timeout Override timeout in seconds (0 = instance default)
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function post(string $path, array $body, int $timeout = 0): array;

    /**
     * @param  int $timeout Override timeout in seconds (0 = instance default)
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function get(string $path, int $timeout = 0): array;
}
