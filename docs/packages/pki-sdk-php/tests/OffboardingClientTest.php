<?php

declare(strict_types=1);

namespace PkiSdk\Tests;

use PHPUnit\Framework\TestCase;
use PkiSdk\OffboardingClient;
use PkiSdk\Tests\Support\FakeTrustClient;

final class OffboardingClientTest extends TestCase
{
    public function testSignerOffboardingActionsUseExplicitExistingEndpoints(): void
    {
        $transport = new FakeTrustClient();
        $client = new OffboardingClient($transport);

        $client->suspendSigner('actor/1', ['reason' => 'leave']);
        $client->revokeSigner('actor/1', ['reason' => 'key_compromise']);
        $client->reinstateSigner('actor/1', ['reason' => 'return']);
        $client->archiveSigner('actor/1', ['reason' => 'retention']);

        self::assertSame('/v1/offboarding/signers/actor%2F1/suspend', $transport->calls[0]['path']);
        self::assertSame(['reason' => 'leave'], $transport->calls[0]['body']);
        self::assertSame('/v1/offboarding/signers/actor%2F1/revoke', $transport->calls[1]['path']);
        self::assertSame('/v1/offboarding/signers/actor%2F1/reinstate', $transport->calls[2]['path']);
        self::assertSame('/v1/offboarding/signers/actor%2F1/archive', $transport->calls[3]['path']);
    }

    public function testTenantRequestAndRequestManagementEndpoints(): void
    {
        $transport = new FakeTrustClient();
        $client = new OffboardingClient($transport);

        $client->requestTenantTermination('tenant/1', ['reason' => 'closure']);
        $client->listRequests(['status' => 'pending', 'subject_type' => 'tenant', 'empty' => '', 'null' => null]);
        $client->getRequest('req/1');
        $client->approveRequest('req/1', ['reason' => 'ok']);
        $client->rejectRequest('req/2', ['reason' => 'no']);
        $client->createExportToken('report/1');

        self::assertSame('/v1/offboarding/tenants/tenant%2F1/terminate', $transport->calls[0]['path']);
        self::assertSame(['reason' => 'closure'], $transport->calls[0]['body']);
        self::assertSame('/v1/offboarding/requests?status=pending&subject_type=tenant', $transport->calls[1]['path']);
        self::assertSame('/v1/offboarding/requests/req%2F1', $transport->calls[2]['path']);
        self::assertSame('/v1/offboarding/requests/req%2F1/approve', $transport->calls[3]['path']);
        self::assertSame(['reason' => 'ok'], $transport->calls[3]['body']);
        self::assertSame('/v1/offboarding/requests/req%2F2/reject', $transport->calls[4]['path']);
        self::assertSame('/v1/offboarding/reports/report%2F1/export-token', $transport->calls[5]['path']);
        self::assertSame([], $transport->calls[5]['body']);
    }
}
