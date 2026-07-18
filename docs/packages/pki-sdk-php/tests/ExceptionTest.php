<?php

declare(strict_types=1);

namespace PkiSdk\Tests;

use PHPUnit\Framework\TestCase;
use PkiSdk\NotFoundException;
use PkiSdk\RateLimitException;
use PkiSdk\ServiceUnavailableException;
use PkiSdk\TrustException;
use PkiSdk\UnauthorizedException;

final class ExceptionTest extends TestCase
{
    public function testTypedExceptionsExtendTrustExceptionAndExposeStatus(): void
    {
        $exceptions = [
            new UnauthorizedException('nope', 403),
            new NotFoundException('missing'),
            new RateLimitException('slow down'),
            new ServiceUnavailableException('offline', 504),
        ];

        foreach ($exceptions as $exception) {
            self::assertInstanceOf(TrustException::class, $exception);
            self::assertSame($exception->getCode(), $exception->getHttpStatus());
            self::assertNotSame('', $exception->getMessage());
        }
    }

    public function testBaseExceptionCarriesHttpStatus(): void
    {
        $exception = new TrustException('validation failed', 422);

        self::assertSame('validation failed', $exception->getMessage());
        self::assertSame(422, $exception->getHttpStatus());
    }
}
