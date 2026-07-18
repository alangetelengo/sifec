<?php

declare(strict_types=1);

namespace LaravelPki\Tests;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use LaravelPki\PkiServiceProvider;
use LaravelPki\SignatureManager;
use Orchestra\Testbench\TestCase;
use PkiSdk\ProofClient;
use PkiSdk\OffboardingClient;
use PkiSdk\SignatureClient;
use PkiSdk\SignerClient;
use PkiSdk\TrustClient;
use PkiSdk\VerificationClient;

final class PkiServiceProviderTest extends TestCase
{
    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [PkiServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('pki.url', 'https://trust-api.test');
        $app['config']->set('pki.api_key', 'test-api-key');
        $app['config']->set('pki.timeout', 12);
    }

    public function testRegistersSdkClientsAndManager(): void
    {
        self::assertInstanceOf(TrustClient::class, $this->app->make(TrustClient::class));
        self::assertInstanceOf(SignatureClient::class, $this->app->make(SignatureClient::class));
        self::assertInstanceOf(SignerClient::class, $this->app->make(SignerClient::class));
        self::assertInstanceOf(VerificationClient::class, $this->app->make(VerificationClient::class));
        self::assertInstanceOf(ProofClient::class, $this->app->make(ProofClient::class));
        self::assertInstanceOf(OffboardingClient::class, $this->app->make(OffboardingClient::class));
        self::assertInstanceOf(SignatureManager::class, $this->app->make(SignatureManager::class));
    }

    public function testAllowsEmptyApiKeyInTestingEnvironment(): void
    {
        $this->app['config']->set('pki.api_key', '');

        self::assertInstanceOf(SignatureManager::class, $this->app->make(SignatureManager::class));
    }

    public function testAllowsMissingCaBundleInTestingEnvironment(): void
    {
        $this->app['config']->set('pki.ca_bundle', null);

        $client = $this->app->make(TrustClient::class);
        $property = new \ReflectionProperty(TrustClient::class, 'caBundle');
        $property->setAccessible(true);

        self::assertNull($property->getValue($client));
    }

    public function testPassesConfiguredCaBundleToTrustClient(): void
    {
        $caBundle = tempnam(sys_get_temp_dir(), 'laravel-pki-ca-');
        self::assertIsString($caBundle);
        file_put_contents($caBundle, "-----BEGIN CERTIFICATE-----\nMIIB\n-----END CERTIFICATE-----\n");

        try {
            $this->app['config']->set('pki.ca_bundle', $caBundle);

            $client = $this->app->make(TrustClient::class);
            $property = new \ReflectionProperty(TrustClient::class, 'caBundle');
            $property->setAccessible(true);

            self::assertSame($caBundle, $property->getValue($client));
        } finally {
            @unlink($caBundle);
        }
    }

    public function testFailsFastWithEmptyApiKeyOutsideTestingEnvironment(): void
    {
        $app = new Application(sys_get_temp_dir());
        $app->detectEnvironment(static fn (): string => 'production');
        $app->instance('config', new Repository([
            'pki' => [
                'url' => 'https://trust-api.test',
                'api_key' => '',
                'timeout' => 10,
                'ca_bundle' => null,
            ],
        ]));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('PKI_API_KEY is empty or not set');

        (new PkiServiceProvider($app))->register();
    }

    public function testPublishesConfigPath(): void
    {
        $paths = PkiServiceProvider::pathsToPublish(PkiServiceProvider::class, 'pki-config');

        self::assertNotEmpty($paths);
        self::assertContains('pki.php', array_map('basename', $paths));
    }
}
