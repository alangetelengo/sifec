<?php

declare(strict_types=1);

namespace PkiSdk;

/**
 * Thrown when the Trust API is temporarily unavailable (HTTP 503 / 504)
 * or when the cURL connection fails after all retry attempts are exhausted.
 */
class ServiceUnavailableException extends TrustException
{
    public function __construct(
        string      $message  = 'Trust API service unavailable. Retries exhausted.',
        int         $httpStatus = 503,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $httpStatus, $previous);
    }
}
