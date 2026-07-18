<?php

declare(strict_types=1);

namespace LaravelPki\Tests;

use LaravelPki\SignatureManager;
use PHPUnit\Framework\TestCase;
use PkiSdk\OffboardingClient;
use PkiSdk\ProofClient;
use PkiSdk\SignatureClient;
use PkiSdk\SignerClient;
use PkiSdk\VerificationClient;

final class SignatureManagerTest extends TestCase
{
    public function testDelegatesSignatureProofAndVerificationMethods(): void
    {
        $signature = $this->createMock(SignatureClient::class);
        $signers = $this->createMock(SignerClient::class);
        $verification = $this->createMock(VerificationClient::class);
        $proofs = $this->createMock(ProofClient::class);

        $signature->expects(self::once())
            ->method('sign')
            ->with('tx-1', ['amount' => 100], 'actor-1', 'approval')
            ->willReturn(['proof_id' => 'proof-1']);
        $verification->expects(self::once())
            ->method('verifyByProofId')
            ->with('proof-1')
            ->willReturn(['valid' => true]);
        $verification->expects(self::once())
            ->method('publicVerifyByProofId')
            ->with('proof-public')
            ->willReturn(['valid' => true, 'public' => true]);
        $verification->expects(self::once())
            ->method('publicVerifyByPayload')
            ->with(['amount' => 10], 'sig', 'ECDSA-P256')
            ->willReturn(['valid' => true, 'payload' => true]);
        $verification->expects(self::once())
            ->method('publicVerificationContext')
            ->with(['tenant_slug' => 'city'])
            ->willReturn(['tenant_slug' => 'city']);
        $proofs->expects(self::once())
            ->method('get')
            ->with('proof-1')
            ->willReturn(['proof_id' => 'proof-1']);
        $proofs->expects(self::once())
            ->method('getPublicBundle')
            ->with('proof-public')
            ->willReturn(['proof_id' => 'proof-public', 'layers' => []]);

        $manager = new SignatureManager($signature, $signers, $verification, $proofs);

        self::assertSame(['proof_id' => 'proof-1'], $manager->sign('tx-1', ['amount' => 100], 'actor-1', 'approval'));
        self::assertSame(['valid' => true], $manager->verify('proof-1'));
        self::assertSame(['valid' => true, 'public' => true], $manager->publicVerify('proof-public'));
        self::assertSame(['valid' => true, 'payload' => true], $manager->publicVerifyPayload(['amount' => 10], 'sig', 'ECDSA-P256'));
        self::assertSame(['tenant_slug' => 'city'], $manager->publicVerificationContext(['tenant_slug' => 'city']));
        self::assertSame(['proof_id' => 'proof-1'], $manager->getProof('proof-1'));
        self::assertSame(['proof_id' => 'proof-public', 'layers' => []], $manager->getPublicProofBundle('proof-public'));
    }

    public function testDelegatesDocumentMethodsWithExplicitInstitutionForSealDocument(): void
    {
        $signature = $this->createMock(SignatureClient::class);
        $signers = $this->createMock(SignerClient::class);
        $verification = $this->createMock(VerificationClient::class);
        $proofs = $this->createMock(ProofClient::class);

        $signature->expects(self::once())
            ->method('signDocument')
            ->with('hash', 'actor-1', 'purpose', 'doc.pdf')
            ->willReturn(['doc_sig_id' => 'sig-1']);
        $signature->expects(self::once())
            ->method('sealDocument')
            ->with('hash', 'institution-1', 'seal', 'doc.pdf', 'sig-1')
            ->willReturn(['doc_seal_id' => 'seal-1']);
        $signature->expects(self::once())
            ->method('verifyDocument')
            ->with('hash', 'sig-1', 'seal-1')
            ->willReturn(['valid' => true]);
        $signature->expects(self::once())
            ->method('getDocumentSignatureBundle')
            ->with('sig-1')
            ->willReturn(['bundle' => 'sig']);
        $signature->expects(self::once())
            ->method('getDocumentSealBundle')
            ->with('seal-1')
            ->willReturn(['bundle' => 'seal']);

        $manager = new SignatureManager($signature, $signers, $verification, $proofs);

        self::assertSame(['doc_sig_id' => 'sig-1'], $manager->signDocument('hash', 'actor-1', 'purpose', 'doc.pdf'));
        self::assertSame(
            ['doc_seal_id' => 'seal-1'],
            $manager->sealDocument('hash', 'institution-1', 'seal', 'doc.pdf', 'sig-1')
        );
        self::assertSame(['valid' => true], $manager->verifyDocument('hash', 'sig-1', 'seal-1'));
        self::assertSame(['bundle' => 'sig'], $manager->getDocumentSignatureBundle('sig-1'));
        self::assertSame(['bundle' => 'seal'], $manager->getDocumentSealBundle('seal-1'));
    }

    public function testSealDocumentRequiresExplicitInstitutionId(): void
    {
        $signature = $this->createMock(SignatureClient::class);
        $signers = $this->createMock(SignerClient::class);
        $verification = $this->createMock(VerificationClient::class);
        $proofs = $this->createMock(ProofClient::class);

        $signature->expects(self::never())->method('sealDocument');

        $manager = new SignatureManager($signature, $signers, $verification, $proofs);

        $this->expectException(\ArgumentCountError::class);

        $manager->sealDocument('hash');
    }

    public function testDelegatesSignerLifecycleMethodsAndCompletesTokenEnrollmentWithPayload(): void
    {
        $signature = $this->createMock(SignatureClient::class);
        $signers = $this->createMock(SignerClient::class);
        $verification = $this->createMock(VerificationClient::class);
        $proofs = $this->createMock(ProofClient::class);

        $signers->expects(self::once())->method('create')->with(['actor_id' => 'a1'], 'idem')->willReturn(['actor_id' => 'a1']);
        $signers->expects(self::once())->method('createBatch')->with([['actor_id' => 'a1']], [])->willReturn(['batch_id' => 'b1']);
        $signers->expects(self::once())->method('getBatch')->with('b1')->willReturn(['status' => 'done']);
        $signers->expects(self::once())->method('createTokenEnrollmentSession')->with(['actor_id' => 'a1'])->willReturn(['session_id' => 's1']);
        $signers->expects(self::once())
            ->method('completeTokenEnrollment')
            ->with(['session_token' => 'token', 'csr' => 'csr'])
            ->willReturn(['completed' => true]);
        $signers->expects(self::once())->method('list')->with(['status' => 'active'])->willReturn(['items' => []]);
        $signers->expects(self::once())->method('get')->with('a1')->willReturn(['actor_id' => 'a1']);
        $signers->expects(self::once())->method('status')->with('a1')->willReturn(['status' => 'active']);
        $signers->expects(self::once())->method('revoke')->with('a1', 'key_compromise', [])->willReturn(['revoked' => true]);
        $signers->expects(self::once())->method('suspend')->with('a1', ['reason' => 'leave'])->willReturn(['suspended' => true]);
        $signers->expects(self::once())->method('renew')->with('a1', [])->willReturn(['renewed' => true]);
        $signers->expects(self::once())->method('createInstitution')->with(['institution_id' => 'i1'])->willReturn(['institution' => []]);

        $manager = new SignatureManager($signature, $signers, $verification, $proofs);

        self::assertSame(['actor_id' => 'a1'], $manager->createSigner(['actor_id' => 'a1'], 'idem'));
        self::assertSame(['batch_id' => 'b1'], $manager->createSignerBatch([['actor_id' => 'a1']]));
        self::assertSame(['status' => 'done'], $manager->getSignerBatch('b1'));
        self::assertSame(['session_id' => 's1'], $manager->createTokenEnrollmentSession(['actor_id' => 'a1']));
        self::assertSame(['completed' => true], $manager->completeTokenEnrollment(['session_token' => 'token', 'csr' => 'csr']));
        self::assertSame(['items' => []], $manager->listSigners(['status' => 'active']));
        self::assertSame(['actor_id' => 'a1'], $manager->getSigner('a1'));
        self::assertSame(['status' => 'active'], $manager->getSignerLifecycleStatus('a1'));
        self::assertSame(['revoked' => true], $manager->revokeSigner('a1', 'key_compromise'));
        self::assertSame(['suspended' => true], $manager->suspendSigner('a1', ['reason' => 'leave']));
        self::assertSame(['renewed' => true], $manager->renewSigner('a1'));
        self::assertSame(['institution' => []], $manager->createInstitution(['institution_id' => 'i1']));
    }

    public function testDelegatesOffboardingClientMethods(): void
    {
        $signature = $this->createMock(SignatureClient::class);
        $signers = $this->createMock(SignerClient::class);
        $verification = $this->createMock(VerificationClient::class);
        $proofs = $this->createMock(ProofClient::class);
        $offboarding = $this->createMock(OffboardingClient::class);

        $offboarding->expects(self::once())
            ->method('reinstateSigner')
            ->with('actor-1', ['reason' => 'return'])
            ->willReturn(['reinstated' => true]);
        $offboarding->expects(self::once())
            ->method('archiveSigner')
            ->with('actor-1', ['reason' => 'retention'])
            ->willReturn(['archived' => true]);
        $offboarding->expects(self::once())
            ->method('requestTenantTermination')
            ->with('tenant-1', ['reason' => 'closure'])
            ->willReturn(['request' => ['id' => 'req-1']]);
        $offboarding->expects(self::once())
            ->method('listRequests')
            ->with(['status' => 'pending'])
            ->willReturn(['requests' => []]);
        $offboarding->expects(self::once())
            ->method('getRequest')
            ->with('req-1')
            ->willReturn(['request' => ['id' => 'req-1']]);
        $offboarding->expects(self::once())
            ->method('approveRequest')
            ->with('req-1', ['reason' => 'ok'])
            ->willReturn(['approved' => true]);
        $offboarding->expects(self::once())
            ->method('rejectRequest')
            ->with('req-2', ['reason' => 'no'])
            ->willReturn(['rejected' => true]);
        $offboarding->expects(self::once())
            ->method('createExportToken')
            ->with('report-1')
            ->willReturn(['token' => 'tok']);

        $manager = new SignatureManager($signature, $signers, $verification, $proofs, $offboarding);

        self::assertSame(['reinstated' => true], $manager->reinstateSigner('actor-1', ['reason' => 'return']));
        self::assertSame(['archived' => true], $manager->archiveSigner('actor-1', ['reason' => 'retention']));
        self::assertSame(['request' => ['id' => 'req-1']], $manager->requestTenantOffboarding('tenant-1', ['reason' => 'closure']));
        self::assertSame(['requests' => []], $manager->listOffboardingRequests(['status' => 'pending']));
        self::assertSame(['request' => ['id' => 'req-1']], $manager->getOffboardingRequest('req-1'));
        self::assertSame(['approved' => true], $manager->approveOffboardingRequest('req-1', ['reason' => 'ok']));
        self::assertSame(['rejected' => true], $manager->rejectOffboardingRequest('req-2', ['reason' => 'no']));
        self::assertSame(['token' => 'tok'], $manager->createOffboardingExportToken('report-1'));
    }

    public function testDeprecatedTokenEnrollmentSessionIdFormWarnsExplicitly(): void
    {
        $signature = $this->createMock(SignatureClient::class);
        $signers = $this->createMock(SignerClient::class);
        $verification = $this->createMock(VerificationClient::class);
        $proofs = $this->createMock(ProofClient::class);
        $deprecations = [];

        $signers->expects(self::once())
            ->method('completeTokenEnrollment')
            ->with(['session_token' => 'token', 'csr' => 'csr'])
            ->willReturn(['completed' => true]);

        $manager = new SignatureManager($signature, $signers, $verification, $proofs);

        set_error_handler(static function (int $severity, string $message) use (&$deprecations): bool {
            if ($severity === E_USER_DEPRECATED) {
                $deprecations[] = $message;
                return true;
            }

            return false;
        });

        try {
            self::assertSame(
                ['completed' => true],
                $manager->completeTokenEnrollment('legacy-session-id', ['session_token' => 'token', 'csr' => 'csr'])
            );
        } finally {
            restore_error_handler();
        }

        self::assertCount(1, $deprecations);
        self::assertStringContainsString('deprecated', $deprecations[0]);
        self::assertStringContainsString('session_token and csr', $deprecations[0]);
    }

    public function testDeprecatedRevokeCertFacadeIsDisabledInsteadOfDelegatingAmbiguousLegacyHelper(): void
    {
        $signature = $this->createMock(SignatureClient::class);
        $signers = $this->createMock(SignerClient::class);
        $verification = $this->createMock(VerificationClient::class);
        $proofs = $this->createMock(ProofClient::class);

        $signature->expects(self::never())->method('revokeCert');

        $manager = new SignatureManager($signature, $signers, $verification, $proofs);

        $this->expectException(\PkiSdk\TrustException::class);
        $this->expectExceptionMessage('revokeCert() is disabled');

        $manager->revokeCert('a1');
    }

    public function testDelegatesDeprecatedLegacyMethodsAfterIssue90(): void
    {
        $signature = $this->createMock(SignatureClient::class);
        $signers = $this->createMock(SignerClient::class);
        $verification = $this->createMock(VerificationClient::class);
        $proofs = $this->createMock(ProofClient::class);

        $signature->expects(self::once())->method('enrollSigner')->with('a1', 'Alice', null, '', 'POC')->willReturn(['enrolled' => true]);
        $signature->expects(self::once())->method('enrollInstitution')->with('i1', 'Inst', '', 'POC', '')->willReturn(['institution' => []]);
        $signature->expects(self::once())->method('getSignerStatus')->with('a1')->willReturn(['status' => 'active']);
        $signature->expects(self::once())->method('getRfc3161Bundle')->with(123)->willReturn(['serial' => 123]);

        $manager = new SignatureManager($signature, $signers, $verification, $proofs);

        self::assertSame(['enrolled' => true], $manager->enrollSigner('a1', 'Alice'));
        self::assertSame(['institution' => []], $manager->enrollInstitution('i1', 'Inst'));
        self::assertSame(['status' => 'active'], $manager->getSignerStatus('a1'));
        self::assertSame(['serial' => 123], $manager->getRfc3161Bundle(123));
    }
}
