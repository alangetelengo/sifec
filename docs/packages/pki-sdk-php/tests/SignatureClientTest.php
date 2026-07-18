<?php

declare(strict_types=1);

namespace PkiSdk\Tests;

use PHPUnit\Framework\TestCase;
use PkiSdk\SignatureClient;
use PkiSdk\Tests\Support\FakeTrustClient;

final class SignatureClientTest extends TestCase
{
    public function testSignBuildsLayerOnePayload(): void
    {
        $transport = new FakeTrustClient();
        $client = new SignatureClient($transport);

        $client->sign('tx-1', ['amount' => 100], 'actor-1', 'approval');

        self::assertSame([
            'method' => 'POST',
            'path' => '/sign',
            'body' => [
                'transaction_id' => 'tx-1',
                'payload' => ['amount' => 100],
                'actor_id' => 'actor-1',
                'purpose' => 'approval',
            ],
            'timeout' => 5,
        ], $transport->calls[0]);
    }

    public function testPrepareAndSubmitExternalSignatureUseExpectedEndpoints(): void
    {
        $transport = new FakeTrustClient();
        $client = new SignatureClient($transport);

        $client->prepareExternalSignature('tx-1', ['a' => 1], 'actor-1', 'purpose-1');
        $client->submitExternalSignature('tx-1', ['a' => 1, 'signed_at' => 'x'], 'c2ln', 'actor-1', 'purpose-1');

        self::assertSame([
            'method' => 'POST',
            'path' => '/sign/prepare',
            'body' => [
                'transaction_id' => 'tx-1',
                'payload' => ['a' => 1],
                'actor_id' => 'actor-1',
                'purpose' => 'purpose-1',
            ],
            'timeout' => 5,
        ], $transport->calls[0]);

        self::assertSame('/sign/external', $transport->calls[1]['path']);
        self::assertSame([
            'transaction_id' => 'tx-1',
            'payload' => ['a' => 1, 'signed_at' => 'x'],
            'signature' => 'c2ln',
            'actor_id' => 'actor-1',
            'purpose' => 'purpose-1',
        ], $transport->calls[1]['body']);
    }

    public function testDocumentSignatureSealVerifyAndBundlesUseExpectedEndpoints(): void
    {
        $transport = new FakeTrustClient();
        $client = new SignatureClient($transport);

        $client->signDocument('hash', 'actor-1', 'purpose', 'doc.pdf');
        $client->sealDocument('hash', 'institution-1', 'seal', 'doc.pdf', 'sig-1');
        $client->verifyDocument('hash', 'sig-1', 'seal-1');
        $client->getDocumentSignatureBundle('sig/1');
        $client->getDocumentSealBundle('seal/1');

        self::assertSame('/sign-document', $transport->calls[0]['path']);
        self::assertSame([
            'document_hash' => 'hash',
            'actor_id' => 'actor-1',
            'purpose' => 'purpose',
            'document_ref' => 'doc.pdf',
        ], $transport->calls[0]['body']);
        self::assertSame('/seal-document', $transport->calls[1]['path']);
        self::assertSame('institution-1', $transport->calls[1]['body']['institution_id']);
        self::assertSame('/verify-document', $transport->calls[2]['path']);
        self::assertSame('/document-signatures/sig%2F1/bundle', $transport->calls[3]['path']);
        self::assertSame('/document-seals/seal%2F1/bundle', $transport->calls[4]['path']);
    }

    public function testRevokeCertLegacyMethodIsDisabledInsteadOfUsingPocHeuristics(): void
    {
        $transport = new FakeTrustClient();
        $client = new SignatureClient($transport);

        $this->expectException(\PkiSdk\TrustException::class);
        $this->expectExceptionMessage('revokeCert() is disabled');

        try {
            $client->revokeCert('mairie-demo', 'superseded');
        } finally {
            self::assertSame([], $transport->calls);
        }
    }

    public function testLegacyPkiMethodsDocumentCurrentEndpointsAfterIssue90(): void
    {
        $transport = new FakeTrustClient();
        $client = new SignatureClient($transport);

        $client->enrollSigner('actor-1', 'Alice');
        $client->enrollInstitution('inst-1', 'Institution');
        $client->getSignerStatus('actor-1');

        self::assertSame('/v1/signers', $transport->calls[0]['path']);
        self::assertSame('/v1/institutions', $transport->calls[1]['path']);
        self::assertSame('/pki/signers/actor-1/status', $transport->calls[2]['path']);
    }

    public function testRfc3161BundleReturnsNullForMissingSerial(): void
    {
        $transport = new FakeTrustClient();
        $client = new SignatureClient($transport);

        self::assertNull($client->getRfc3161Bundle(null));
        self::assertSame([], $transport->calls);
    }
}
