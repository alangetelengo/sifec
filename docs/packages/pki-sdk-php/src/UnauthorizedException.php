<?php

declare(strict_types=1);

namespace PkiSdk;

/**
 * Thrown when the Trust API returns HTTP 401 Unauthorized or 403 Forbidden.
 *
 * Typical causes: missing X-Api-Key, revoked key, insufficient permissions.
 */
class UnauthorizedException extends TrustException
{
    public function __construct(
        string      $message    = 'Authentication or authorization failed.',
        int         $httpStatus = 401,
        ?\Throwable $previous   = null,
    ) {
        parent::__construct($message, $httpStatus, $previous);
    }
}
