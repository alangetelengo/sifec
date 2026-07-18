# WebhookClient

`WebhookClient` expose la configuration des webhooks tenant et la lecture des
livraisons signees preparees par Signum.

## Methodes

```php
listEndpoints(): array
createEndpoint(string $url, array $events): array
listDeliveries(array $filters = []): array
```

`createEndpoint()` retourne le secret `whsec_...` une seule fois. Les
livraisons portent les headers:

```http
Signum-Event-Id: evt_...
Signum-Event-Type: certificate.revoked
Signum-Signature: t=...,v1=<hmac_sha256>
```

La signature est calculee sur `timestamp.payload_json` avec HMAC-SHA256.

## Exemple

```php
use PkiSdk\TrustClient;
use PkiSdk\WebhookClient;

$transport = new TrustClient(
    baseUrl: 'https://api.signum.cg',
    apiKey: 'sk_live_...'
);

$webhooks = new WebhookClient($transport);

$endpoint = $webhooks->createEndpoint(
    'https://institution.cg/signum/webhook',
    ['signer.created', 'certificate.revoked']
);

$secret = $endpoint['webhook_endpoint']['secret'];
$deliveries = $webhooks->listDeliveries(['event_type' => 'certificate.revoked']);
```
