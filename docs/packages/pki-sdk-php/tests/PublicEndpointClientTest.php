<?php

declare(strict_types=1);

namespace PkiSdk\Tests;

use PHPUnit\Framework\TestCase;
use PkiSdk\ProofClient;
use PkiSdk\Tests\Support\FakeTrustClient;
use PkiSdk\VerificationClient;

final class PublicEndpointClientTest extends TestCase
{
    public function testPublicVerificationEndpointsUseExistingRoutes(): void
    {
        $transport = new FakeTrustClient();
        $client = new VerificationClient($transport);

        $client->publicVerifyByProofId('proof/1');
        $client->publicVerifyByPayload(['amount' => 10], 'sig', 'ECDSA-P256');
        $client->publicVerificationContext(['tenant_slug' => 'city', 'empty' => '', 'null' => null]);

        self::assertSame('/v1/public/verify', $transport->calls[0]['path']);
        self::assertSame(['proof_id' => 'proof/1'], $transport->calls[0]['body']);
        self::assertSame('/v1/public/verify', $transport->calls[1]['path']);
        self::assertSame([
            'payload' => ['amount' => 10],
            'signature' => 'sig',
            'algorithm' => 'ECDSA-P256',
        ], $transport->calls[1]['body']);
        self::assertSame('/v1/public/verification-context?tenant_slug=city', $transport->calls[2]['path']);
    }

    public function testProofClientUsesEncodedProofPathsAndPublicBundleEndpoint(): void
    {
        $transport = new FakeTrustClient();
        $client = new ProofClient($transport);

        $client->get('proof/1');
        $client->getPublicBundle('proof/1');

        self::assertSame('/proofs/proof%2F1', $transport->calls[0]['path']);
        self::assertSame('/v1/public/proofs/proof%2F1/bundle', $transport->calls[1]['path']);
    }
}
