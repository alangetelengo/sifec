<?php

declare(strict_types=1);

namespace LaravelPki;

use Illuminate\Support\ServiceProvider;
use PkiSdk\TrustClient;
use PkiSdk\SignatureClient;
use PkiSdk\SignerClient;
use PkiSdk\VerificationClient;
use PkiSdk\ProofClient;
use PkiSdk\OffboardingClient;
use PkiSdk\WebhookClient;

class PkiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pki.php', 'pki');

        // ── Validation de configuration (hors environnement de test) ──────────
        // En production, une clé PKI vide permettrait des appels non authentifiés
        // à trust-api. On échoue tôt au démarrage plutôt qu'en cours d'exécution.
        if (! $this->app->environment('testing')) {
            $apiKey = $this->app['config']['pki']['api_key'] ?? '';
            if (! is_string($apiKey) || trim($apiKey) === '') {
                throw new \RuntimeException(
                    'PKI_API_KEY is empty or not set. '
                    . 'Set the PKI_API_KEY environment variable before starting the application.'
                );
            }
        }

        $this->app->singleton(TrustClient::class, function ($app) {
            $config = $app['config']['pki'];
            $caBundle = $config['ca_bundle'] ?? null;
            $caBundle = is_string($caBundle) && trim($caBundle) !== '' ? $caBundle : null;

            return new TrustClient(
                baseUrl: $config['url'],
                apiKey:  $config['api_key'],
                timeout: (int) $config['timeout'],
                // 1 seul essai : un retry après timeout laisse des clés Transit orphelines
                // (create OpenBao OK puis timeout HTTP → 2e POST → « clé existe déjà »).
                maxRetries: 1,
                caBundle: $caBundle,
            );
        });

        $this->app->singleton(SignatureClient::class, function ($app) {
            return new SignatureClient($app->make(TrustClient::class));
        });

        $this->app->singleton(SignerClient::class, function ($app) {
            return new SignerClient($app->make(TrustClient::class));
        });

        $this->app->singleton(VerificationClient::class, function ($app) {
            return new VerificationClient($app->make(TrustClient::class));
        });

        $this->app->singleton(ProofClient::class, function ($app) {
            return new ProofClient($app->make(TrustClient::class));
        });

        $this->app->singleton(OffboardingClient::class, function ($app) {
            return new OffboardingClient($app->make(TrustClient::class));
        });

        $this->app->singleton(WebhookClient::class, function ($app) {
            return new WebhookClient($app->make(TrustClient::class));
        });

        $this->app->singleton(SignatureManager::class, function ($app) {
            return new SignatureManager(
                $app->make(SignatureClient::class),
                $app->make(SignerClient::class),
                $app->make(VerificationClient::class),
                $app->make(ProofClient::class),
                $app->make(OffboardingClient::class),
                $app->make(WebhookClient::class),
            );
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/pki.php' => config_path('pki.php'),
        ], 'pki-config');
    }
}
