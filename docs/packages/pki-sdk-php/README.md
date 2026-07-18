# pki-sdk-php

## Rôle
SDK PHP générique (sans dépendance framework) pour communiquer avec le service `trust-api`.

## Compatibilité PHP

| PHP | Supporté |
|-----|----------|
| 8.1 | ✅ minimum requis |
| 8.2 | ✅ |
| 8.3+ | ✅ |
| < 8.1 | ❌ (`readonly` properties requises) |

> **Sans dépendance framework.** Utilisable dans n'importe quelle application PHP 8.1+ :
> Laravel, Symfony, Slim, vanilla PHP, scripts CLI.
> Pour les projets Laravel, utiliser `laravel-pki` qui requiert PHP 8.2+ (exigence de Laravel 11).

## Responsabilité dans l'architecture
Couche cliente réutilisable. Peut être intégrée dans n'importe quelle application PHP 8.1+ (Laravel, Symfony, ou vanilla).

## Classes

| Classe               | Rôle                                               |
|---------------------|----------------------------------------------------|
| `TrustClient`        | Client HTTP bas niveau (cURL natif, pas de Guzzle) |
| `SignatureClient`    | Toutes les opérations de signature PKI             |
| `SignerClient`       | Cycle de vie SaaS des signataires (`/v1/signers`)  |
| `VerificationClient` | `verifyByProofId(id)` · `verifyByPayload(p, sig)`  |
| `ProofClient`        | `get(id)`                                          |
| `OffboardingClient`  | Endpoints JSON `/v1/offboarding`                   |
| `TrustException`     | Exception de base du SDK                           |

## Méthodes SignatureClient

### Signature transactionnelle (Layer 1)
```php
sign(string $transactionId, array $payload, string $actorId, string $purpose): array
```

### Signature documentaire (Layer 2 + 3)
```php
signDocument(string $documentHash, string $actorId, string $purpose, string $documentRef): array
sealDocument(string $documentHash, string $institutionId, string $purpose, string $documentRef, ?string $docSigId): array
verifyDocument(string $documentHash, ?string $docSigId, ?string $docSealId): array
getDocumentSignatureBundle(string $docSigId): array
getDocumentSealBundle(string $docSealId): array
```

### Cycle de vie des signataires (SDK-first)
```php
$signers->create(array $input, ?string $idempotencyKey = null): array
$signers->list(array $filters = []): array
$signers->get(string $actorId): array
$signers->status(string $actorId): array
$signers->createBatch(array $items, array $options = []): array
$signers->getBatch(string $batchId): array
$signers->revoke(string $actorId, string $reason = 'unspecified', array $options = []): array
$signers->suspend(string $actorId, array $options = []): array
$signers->renew(string $actorId, array $options = []): array
$signers->createTokenEnrollmentSession(array $input): array
```

### Offboarding et endpoints publics
```php
$offboarding->suspendSigner(string $actorId, array $payload = []): array
$offboarding->revokeSigner(string $actorId, array $payload = []): array
$offboarding->reinstateSigner(string $actorId, array $payload = []): array
$offboarding->archiveSigner(string $actorId, array $payload = []): array
$offboarding->requestTenantTermination(string $tenantId, array $payload = []): array
$offboarding->listRequests(array $filters = []): array
$offboarding->getRequest(string $requestId): array
$offboarding->approveRequest(string $requestId, array $payload = []): array
$offboarding->rejectRequest(string $requestId, array $payload = []): array
$offboarding->createExportToken(string $reportId): array

$verifier->publicVerifyByProofId(string $proofId): array
$verifier->publicVerifyByPayload(array $payload, string $signature, ?string $algorithm = null): array
$verifier->publicVerificationContext(array $query = []): array
$proofs->getPublicBundle(string $proofId): array
```

Le telechargement ZIP `/v1/offboarding/export/{token}` existe cote `trust-api`,
mais n'est pas expose par ce SDK tant que `TrustClientInterface` retourne des
reponses JSON decodees.

Les routes `/v1/public/*` sont publiques cote `trust-api`. Le SDK bas niveau
peut donc utiliser un `TrustClient` sans `apiKey` pour ces appels, tandis que
les endpoints offboarding restent des endpoints authentifies.

### Enrôlement PKI historique POC
```php
enrollSigner(string $actorId, string $nom, ?string $titre, string $commonName, string $organization): array
enrollInstitution(string $institutionId, string $nom, string $commonName, string $organization, string $ou): array
```

### Gestion des certificats
```php
// revokeCert() est conserve uniquement comme garde-fou legacy et leve une exception.
// Utiliser SignerClient::revoke() pour les signataires.
getSignerStatus(string $actorId): array
```

### Horodatage RFC 3161
```php
getRfc3161Bundle(?int $serial): ?array
```

## Installation (locale, depuis workspace)

```bash
# Dans l'application consommatrice, ajouter dans composer.json :
{
    "repositories": [
        {
            "type": "path",
            "url": "../../packages/pki-sdk-php",
            "options": {
                "versions": { "poc-signature/pki-sdk-php": "0.1.0" }
            }
        }
    ],
    "require": { "poc-signature/pki-sdk-php": "^0.1" }
}
composer install
```

> Aucun package Packagist public n'est suppose dans ce lot. L'exemple ci-dessus decrit la
> consommation locale via repository Composer `path`.

## Usage

```php
use PkiSdk\TrustClient;
use PkiSdk\SignatureClient;
use PkiSdk\SignerClient;
use PkiSdk\VerificationClient;
use PkiSdk\ProofClient;
use PkiSdk\TrustException;

$client   = new TrustClient(baseUrl: 'http://trust-api:8080', apiKey: 'VOTRE_CLE_API');
// CA interne optionnelle :
// $client = new TrustClient(baseUrl: 'https://trust-api.internal', apiKey: 'VOTRE_CLE_API', caBundle: '/path/to/ca-bundle.pem');
$signer   = new SignatureClient($client);
$signers  = new SignerClient($client);
$verifier = new VerificationClient($client);
$proofs   = new ProofClient($client);

// Enrôler un signataire depuis l'application métier cliente
$created = $signers->create([
    'display_name' => 'Marie Dupont',
    'job_title'    => 'Officier d’état civil',
    'service'      => 'Etat civil',
]);
$actorId = $created['signer']['actor_id'];

// Signer une transaction (Layer 1)
$result = $signer->sign('tx-001', ['doc' => 'acte'], 'agent-marie-dupont', 'signature_declaration');
// ['proof_id' => '...', 'signature' => 'vault:v1:...', ...]

// Signer un document (Layer 2) — hash SHA-256 du PDF
$docSig = $signer->signDocument(hash('sha256', $pdfBytes), 'agent-marie-dupont', 'signature_doc', 'acte.pdf');
// ['doc_sig_id' => '...', 'signature_der_b64' => '...', 'cert_pem' => '...', ...]

// Cachet institutionnel (Layer 3)
$seal = $signer->sealDocument(hash('sha256', $pdfBytes), 'institution-brazzaville-central', 'cachet_institution', 'acte.pdf', $docSig['doc_sig_id']);

// Vérifier une signature documentaire
$check = $signer->verifyDocument(hash('sha256', $pdfBytes), $docSig['doc_sig_id']);
// ['valid' => true, 'reason' => '...']

// Statut certificat via le contrat SaaS cible
$status = $signers->status('agent-marie-dupont');
// ['status' => 'active', 'serial_number' => '...', 'not_after' => '...']

// Révoquer un certificat
$signers->revoke('agent-jean-martin', 'affiliation_changed');

// Bundle RFC 3161
$bundle = $signer->getRfc3161Bundle($rfc3161Serial);
// ['tst_resp_der_b64' => '...', 'gen_time' => '...', 'verify_commands' => [...]]
```

## Raisons de révocation supportées

| Valeur                   | RFC 5280 code | Usage                                |
|--------------------------|---------------|--------------------------------------|
| `unspecified`            | 0             | Raison non précisée                  |
| `key_compromise`         | 1             | Compromission de la clé privée       |
| `affiliation_changed`    | 3             | Changement d'affiliation             |
| `superseded`             | 4             | Remplacé par un nouveau certificat   |
| `cessation_of_operation` | 5             | Fin d'activité                       |

## Dépendances
- PHP 8.2+
- Extension `curl` (standard)
- **Aucune dépendance framework**
