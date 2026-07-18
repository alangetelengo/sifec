<?php

declare(strict_types=1);

namespace PkiSdk;

/**
 * Thrown when the Trust API returns HTTP 429 Too Many Requests.
 *
 * The caller should back off and retry after the indicated delay.
 */
class RateLimitException extends TrustException
{
    public function __construct(
        string     $message   = 'Rate limit exceeded (HTTP 429). Please retry after a delay.',
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 429, $previous);
    }
}
