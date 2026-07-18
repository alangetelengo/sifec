<?php

declare(strict_types=1);

namespace PkiSdk;

/**
 * Thrown when the Trust API returns HTTP 404 Not Found.
 *
 * Typical causes: unknown actor_id, proof_id, or institution_id.
 */
class NotFoundException extends TrustException
{
    public function __construct(
        string      $message  = 'Resource not found (HTTP 404).',
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, 404, $previous);
    }
}
