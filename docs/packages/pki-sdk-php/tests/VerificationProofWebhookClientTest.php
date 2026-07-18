<?php

declare(strict_types=1);

namespace PkiSdk\Tests;

use PHPUnit\Framework\TestCase;
use PkiSdk\ProofClient;
use PkiSdk\Tests\Support\FakeTrustClient;
use PkiSdk\VerificationClient;
use PkiSdk\WebhookClient;

final class VerificationProofWebhookClientTest extends TestCase
{
    public function testVerificationClientBuildsPayloads(): void
    {
        $transport = new FakeTrustClient();
        $client = new VerificationClient($transport);

        $client->verifyByProofId('proof-1');
        $client->verifyByPayload(['id' => 1], 'sig');

        self::assertSame('/verify', $transport->calls[0]['path']);
        self::assertSame(['proof_id' => 'proof-1'], $transport->calls[0]['body']);
        self::assertSame(['payload' => ['id' => 1], 'signature' => 'sig'], $transport->calls[1]['body']);
    }

    public function testProofClientGetsProofById(): void
    {
        $transport = new FakeTrustClient();
        $client = new ProofClient($transport);

        $client->get('proof-1');

        self::assertSame('/proofs/proof-1', $transport->calls[0]['path']);
        self::assertSame(3, $transport->calls[0]['timeout']);
    }

    public function testWebhookClientCoversCurrentEndpoints(): void
    {
        $transport = new FakeTrustClient();
        $client = new WebhookClient($transport);

        $client->listEndpoints();
        $client->createEndpoint('https://example.test/hook', ['signature.created']);
        $client->listDeliveries(['event' => 'certificate.renewed', 'empty' => '', 'null' => null]);

        self::assertSame('/v1/webhooks/endpoints', $transport->calls[0]['path']);
        self::assertSame('/v1/webhooks/endpoints', $transport->calls[1]['path']);
        self::assertSame(['url' => 'https://example.test/hook', 'events' => ['signature.created']], $transport->calls[1]['body']);
        self::assertSame('/v1/webhooks/deliveries?event=certificate.renewed', $transport->calls[2]['path']);
        self::assertFalse(method_exists($client, 'updateEndpoint'), 'Update endpoint is not implemented in the current SDK surface.');
        self::assertFalse(method_exists($client, 'deleteEndpoint'), 'Delete endpoint is not implemented in the current SDK surface.');
    }
}
