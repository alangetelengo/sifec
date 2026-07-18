<?php

declare(strict_types=1);

namespace PkiSdk\Tests;

use PHPUnit\Framework\TestCase;
use PkiSdk\SignerClient;
use PkiSdk\Tests\Support\FakeTrustClient;

final class SignerClientTest extends TestCase
{
    public function testSignerLifecycleEndpoints(): void
    {
        $transport = new FakeTrustClient();
        $client = new SignerClient($transport);

        $client->create(['actor_id' => 'actor-1'], 'idem-1');
        $client->list(['status' => 'active', 'empty' => '', 'null' => null]);
        $client->get('actor/1');
        $client->status('actor/1');
        $client->revoke('actor/1', 'key_compromise', ['comment' => 'test']);
        $client->suspend('actor/1', ['reason' => 'leave']);
        $client->renew('actor/1', ['reason' => 'expiry']);
        $client->escrowP12('actor/1', ['foo' => 'bar']);

        self::assertSame('/v1/signers', $transport->calls[0]['path']);
        self::assertSame('idem-1', $transport->calls[0]['body']['idempotency_key']);
        self::assertSame('/v1/signers?status=active', $transport->calls[1]['path']);
        self::assertSame('/v1/signers/actor%2F1', $transport->calls[2]['path']);
        self::assertSame('/v1/signers/actor%2F1/status', $transport->calls[3]['path']);
        self::assertSame('/v1/signers/actor%2F1/revoke', $transport->calls[4]['path']);
        self::assertSame('key_compromise', $transport->calls[4]['body']['reason']);
        self::assertSame('/v1/signers/actor%2F1/suspend', $transport->calls[5]['path']);
        self::assertSame(['reason' => 'leave'], $transport->calls[5]['body']);
        self::assertSame('/v1/signers/actor%2F1/renew', $transport->calls[6]['path']);
        self::assertSame('/v1/signers/actor%2F1/escrow-p12', $transport->calls[7]['path']);
        self::assertSame(['foo' => 'bar'], $transport->calls[7]['body']);
    }

    public function testBatchAndTokenEnrollmentUsesSessionTokenContract(): void
    {
        $transport = new FakeTrustClient();
        $client = new SignerClient($transport);

        $client->createBatch([['actor_id' => 'a1']], ['dry_run' => true]);
        $client->getBatch('batch/1');
        $client->createTokenEnrollmentSession(['actor_id' => 'a1']);
        $client->completeTokenEnrollment(['session_token' => 'token', 'csr' => 'csr']);

        self::assertSame('/v1/signers/batch', $transport->calls[0]['path']);
        self::assertSame([['actor_id' => 'a1']], $transport->calls[0]['body']['items']);
        self::assertSame('/v1/signers/batch/batch%2F1', $transport->calls[1]['path']);
        self::assertSame('/v1/token-enrollment-sessions', $transport->calls[2]['path']);
        self::assertSame('/v1/token-enrollments', $transport->calls[3]['path']);
        self::assertSame(['session_token' => 'token', 'csr' => 'csr'], $transport->calls[3]['body']);
    }

    public function testInstitutionEndpoints(): void
    {
        $transport = new FakeTrustClient();
        $client = new SignerClient($transport);

        $client->createInstitution(['institution_id' => 'inst-1']);
        $client->listInstitutions();
        $client->getInstitution('inst/1');

        self::assertSame('/v1/institutions', $transport->calls[0]['path']);
        self::assertSame('/v1/institutions', $transport->calls[1]['path']);
        self::assertSame('/v1/institutions/inst%2F1', $transport->calls[2]['path']);
    }
}
