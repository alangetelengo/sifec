<?php

declare(strict_types=1);

namespace PkiSdk;

use RuntimeException;

/**
 * TrustException
 *
 * Base exception for all PKI SDK errors (HTTP errors, connection failures, invalid responses).
 */
class TrustException extends RuntimeException
{
    public function __construct(
        string          $message,
        private int     $httpStatus = 0,
        ?\Throwable     $previous   = null,
    ) {
        parent::__construct($message, $httpStatus, $previous);
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }
}
