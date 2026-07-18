<?php

declare(strict_types=1);

namespace PkiSdk;

/**
 * TrustClient
 *
 * Low-level HTTP client for the Trust API.
 * Uses PHP's native cURL — no framework dependency.
 *
 * Features:
 * - Implements TrustClientInterface — inject a mock in tests instead of real HTTP calls
 * - Typed exceptions (RateLimitException, ServiceUnavailableException,
 *   UnauthorizedException, NotFoundException, TrustException)
 * - Automatic retry with exponential backoff on transient errors (5xx, cURL failures)
 *   up to $maxRetries attempts with $retryDelaysMs delays between attempts
 * - Per-call timeout override via optional $timeout parameter (0 = use instance default)
 */
class TrustClient implements TrustClientInterface
{
    /** Delays in milliseconds between retry attempts (index 0 = before 2nd attempt, etc.) */
    private const DEFAULT_RETRY_DELAYS_MS = [100, 500, 2000];

    public function __construct(
        private readonly string $baseUrl,
        private readonly string $apiKey       = '',
        private readonly int    $timeout      = 10,
        private readonly int    $maxRetries   = 3,
        /** @var int[] */
        private readonly array  $retryDelaysMs = self::DEFAULT_RETRY_DELAYS_MS,
        private readonly ?string $caBundle    = null,
    ) {
        if ($this->caBundle !== null) {
            $caBundle = trim($this->caBundle);
            if ($caBundle === '') {
                throw new \InvalidArgumentException('TLS CA bundle path must not be empty.');
            }
            if (! is_file($caBundle) || ! is_readable($caBundle)) {
                throw new \InvalidArgumentException("TLS CA bundle is not readable: {$caBundle}");
            }
        }
    }

    /**
     * @param  array<string, mixed> $body
     * @param  int                  $timeout Override timeout in seconds (0 = instance default)
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function post(string $path, array $body, int $timeout = 0): array
    {
        return $this->requestWithRetry('POST', $path, $body, $timeout ?: $this->timeout);
    }

    /**
     * @param  int $timeout Override timeout in seconds (0 = instance default)
     * @return array<string, mixed>
     * @throws TrustException
     */
    public function get(string $path, int $timeout = 0): array
    {
        return $this->requestWithRetry('GET', $path, null, $timeout ?: $this->timeout);
    }

    // ─── Private ──────────────────────────────────────────────────────────────

    /**
     * Execute the request with automatic retry on transient failures.
     *
     * Retryable conditions:
     *  - HTTP 503 / 504 (service temporarily unavailable or gateway timeout)
     *  - cURL connection error (network issue, DNS, timeout)
     *
     * Non-retryable (thrown immediately):
     *  - HTTP 400, 401, 403, 404, 409, 422, 429 and other client errors
     *
     * @param  array<string, mixed>|null $body
     * @return array<string, mixed>
     * @throws TrustException
     */
    private function requestWithRetry(string $method, string $path, ?array $body = null, int $timeout = 0): array
    {
        $lastException = null;
        $effectiveTimeout = $timeout > 0 ? $timeout : $this->timeout;

        for ($attempt = 0; $attempt < $this->maxRetries; $attempt++) {
            if ($attempt > 0) {
                $delayMs = $this->retryDelaysMs[$attempt - 1] ?? end($this->retryDelaysMs);
                usleep($delayMs * 1_000);
            }

            try {
                return $this->request($method, $path, $body, $effectiveTimeout);
            } catch (ServiceUnavailableException $e) {
                // 503/504 or cURL failure — retryable
                $lastException = $e;
            }
            // All other typed exceptions (RateLimitException, UnauthorizedException,
            // NotFoundException, generic TrustException) are NOT retried — rethrown immediately.
        }

        throw $lastException;
    }

    /**
     * Execute a single HTTP request and return the decoded JSON body.
     *
     * @param  array<string, mixed>|null $body
     * @return array<string, mixed>
     * @throws TrustException
     */
    private function request(string $method, string $path, ?array $body = null, int $timeout = 0): array
    {
        $url = rtrim($this->baseUrl, '/') . $path;

        $headers = ['Content-Type: application/json', 'Accept: application/json'];
        if ($this->apiKey !== '') {
            $headers[] = "X-Api-Key: {$this->apiKey}";
        }

        $effectiveTimeout = $timeout > 0 ? $timeout : $this->timeout;

        $ch = curl_init($url);
        $curlOptions = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $effectiveTimeout,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_CUSTOMREQUEST  => $method,
        ];

        if ($this->caBundle !== null) {
            $curlOptions[CURLOPT_CAINFO] = trim($this->caBundle);
        }

        $this->configureCurl($ch, $curlOptions);

        if ($body !== null) {
            $this->setCurlOption($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $raw    = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error  = curl_error($ch);
        curl_close($ch);

        // cURL-level failure (network error, DNS, timeout) — treated as transient
        if ($error) {
            throw new ServiceUnavailableException(
                "cURL error (will retry if attempts remain): {$error}",
                0,
            );
        }

        $decoded = json_decode((string) $raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new TrustException("Invalid JSON response from Trust API (HTTP {$status}): {$raw}");
        }

        if ($status < 400) {
            return $decoded;
        }

        // ── Typed error dispatch ──────────────────────────────────────────────
        $apiMessage = $decoded['error'] ?? $decoded['message'] ?? "HTTP {$status}";
        $fullMessage = "Trust API error ({$status}): {$apiMessage}";

        return match (true) {
            $status === 429              => throw new RateLimitException($fullMessage),
            $status === 401,
            $status === 403              => throw new UnauthorizedException($fullMessage, $status),
            $status === 404              => throw new NotFoundException($fullMessage),
            $status === 503,
            $status === 504              => throw new ServiceUnavailableException($fullMessage, $status),
            default                      => throw new TrustException($fullMessage, $status),
        };
    }

    /**
     * @param array<int, mixed> $options
     */
    protected function configureCurl(mixed $ch, array $options): void
    {
        curl_setopt_array($ch, $options);
    }

    protected function setCurlOption(mixed $ch, int $option, mixed $value): void
    {
        curl_setopt($ch, $option, $value);
    }
}
