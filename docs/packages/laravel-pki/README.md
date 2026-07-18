# laravel-pki

## 🎯 Rôle

Package Laravel encapsulant `pki-sdk-php` dans l'écosystème Laravel via ServiceProvider et SignatureManager.

**Couche 2** de l'architecture : Intégration framework

## Compatibilité PHP

| PHP | Laravel | Supporté |
|-----|---------|----------|
| 8.2 | 11 / 12 / 13 | ✅ minimum requis |
| 8.3+ | 11 / 12 / 13 | ✅ |
| 8.1 | — | ❌ Laravel 11 requiert PHP 8.2 |
| < 8.1 | — | ❌ |

> Pour une intégration **sans Laravel** (Symfony, vanilla PHP), utiliser directement `pki-sdk-php`
> qui supporte PHP 8.1+.

## 📦 Responsabilité dans l'Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Application Laravel                       │
│                      (civil-demo)                            │
└────────────────────────┬────────────────────────────────────┘
                         │ Injection de dépendances
                         ▼
┌─────────────────────────────────────────────────────────────┐
│                      laravel-pki                             │
│                   (Package Laravel)                          │
│                                                              │
│  ┌────────────────────────────────────────────────────┐    │
│  │           SignatureManager                          │    │
│  │                                                     │    │
│  │  - sign(transactionId, payload, actorId, purpose)  │    │
│  │  - verify(proofId)                                 │    │
│  │  - getProof(proofId)                               │    │
│  │  - signDocument(hash, actorId, ...)                │    │
│  │  - sealDocument(hash, institutionId, ...)          │    │
│  │  - verifyDocument(hash, docSigId, docSealId)       │    │
│  └────────────────────────────────────────────────────┘    │
│                         │                                    │
│                         │ Utilise                            │
│                         ▼                                    │
│  ┌────────────────────────────────────────────────────┐    │
│  │              pki-sdk-php                            │    │
│  │  - TrustClient                                      │    │
│  │  - SignatureClient                                  │    │
│  │  - SignerClient                                     │    │
│  │  - VerificationClient                               │    │
│  │  - ProofClient                                      │    │
│  │  - OffboardingClient                                │    │
│  └────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
                         │
                         │ HTTP
                         ▼
                   ┌─────────────┐
                   │  trust-api  │
                   └─────────────┘
```

## 🔧 Fonctionnalités

### API Unifiée

Fournit une API Laravel-friendly pour :
- ✅ Signature électronique (Layer 1 : transaction)
- ✅ Vérification de signature
- ✅ Récupération de preuve
- ✅ Signature documentaire (Layer 2 : document)
- ✅ Cachet institutionnel (Layer 3 : seal)
- ✅ Vérification documentaire
- ✅ Offboarding signer/tenant et vérification publique

Les endpoints `/v1/public/*` restent publics cote `trust-api`. Le package
Laravel conserve neanmoins une configuration globale `PKI_API_KEY` obligatoire
hors environnement de test, car la meme facade expose aussi des operations
authentifiees.

### Intégration Laravel

- ✅ ServiceProvider auto-découvert
- ✅ Configuration via `.env`
- ✅ Injection de dépendances
- ✅ Gestion des exceptions Laravel
- ✅ Logging intégré

### Avantages

**vs utilisation directe de pki-sdk-php** :
- Configuration centralisée (`.env`)
- Injection de dépendances (container Laravel)
- Gestion des erreurs Laravel
- Logging automatique
- Testabilité (mocking facile)

## Installation (locale, depuis workspace)

```bash
# Dans composer.json de l'app Laravel :
{
    "repositories": [
        {
            "type": "path",
            "url": "../../packages/pki-sdk-php",
            "options": {
                "versions": { "poc-signature/pki-sdk-php": "0.1.0" }
            }
        },
        {
            "type": "path",
            "url": "../../packages/laravel-pki",
            "options": {
                "versions": { "poc-signature/laravel-pki": "0.1.0" }
            }
        }
    ],
    "require": {
        "poc-signature/pki-sdk-php": "^0.1",
        "poc-signature/laravel-pki": "^0.1"
    }
}
composer install
```

> Les packages sont consommes localement via repositories Composer `path`. Aucune publication
> Packagist publique n'est supposee par ce guide.

Le `PkiServiceProvider` est auto-découvert via le package discovery Laravel.

## Configuration

```bash
# Publier la config
php artisan vendor:publish --tag=pki-config
```

```dotenv
# .env
PKI_TRUST_API_URL=http://trust-api:8080
PKI_API_KEY=sk_live_VotreCleConsoleSignum
PKI_TIMEOUT=10

# CA bundle PEM optionnel pour une AC interne
PKI_CA_BUNDLE=/path/to/ca-bundle.pem
```

> **Note :** Les clés API sont créées dans l'espace développeur de la console Signum, après validation du tenant par GUOT.
> Format : `sk_test_xxx` (développement) ou `sk_live_xxx` (production).  
> L'ancienne clé statique `poc-dev-key` n'est plus acceptée.

## 📚 API SignatureManager

### Méthodes Principales

#### sign() - Signature Transactionnelle (Layer 1)

```php
public function sign(
    string $transactionId,
    array $payload,
    string $actorId,
    string $purpose
): array
```

**Paramètres** :
- `$transactionId` : Identifiant unique de la transaction (ex: "acte-naissance-15-20260414")
- `$payload` : Données métier à signer (array associatif)
- `$actorId` : Identifiant du signataire (ex: "agent-marie-dupont")
- `$purpose` : Objectif de la signature (ex: "signature_declaration_naissance")

**Retour** :
```php
[
    'proof_id' => 'proof_abc123',
    'signature' => 'vault:v1:MEUCIQDEnCL...',
    'certificate_ref' => 'transit/signers/agent-marie-dupont:v1',
    'algorithm' => 'ECDSA-P256-TRANSIT',
    'payload_hash' => '3aeb46b0...',
    'signed_at' => '2026-04-14T10:41:21+00:00',
    'status' => 'signed'
]
```

**Exemple** :
```php
$result = $this->pki->sign(
    transactionId: 'acte-naissance-15-20260414',
    payload: [
        'demande_id' => 15,
        'nom_enfant' => 'Dupont',
        'prenom_enfant' => 'Marie',
        'date_naissance' => '2026-04-10',
    ],
    actorId: 'agent-marie-dupont',
    purpose: 'signature_declaration_naissance',
);
```

#### verify() - Vérification de Signature

```php
public function verify(string $proofId): array
```

**Paramètres** :
- `$proofId` : Identifiant de la preuve à vérifier

**Retour** :
```php
[
    'valid' => true,
    'reason' => 'Integrity and signature checks passed',
    'payload_hash_match' => true,
    'signature_valid' => true,
    'actor_id' => 'agent-marie-dupont',
    'signed_at' => '2026-04-14T10:41:21+00:00'
]
```

#### getProof() - Récupération de Preuve

```php
public function getProof(string $proofId): array
```

**Retour** : Preuve complète avec payload, signature, métadonnées

#### signDocument() - Signature Documentaire (Layer 2)

```php
public function signDocument(
    string $documentHash,
    string $actorId,
    string $purpose,
    string $documentRef
): array
```

**Paramètres** :
- `$documentHash` : Hash SHA-256 du document (ex: hash du PDF)
- `$actorId` : Identifiant du signataire
- `$purpose` : Objectif (ex: "signature_documentaire_declaration_naissance")
- `$documentRef` : Référence du document (ex: nom du fichier)

**Retour** :
```php
[
    'doc_sig_id' => 'doc_sig_xyz789',
    'signature' => 'vault:v1:...',
    'signed_at' => '2026-04-14T10:42:00+00:00',
    'rfc3161_serial' => '...'  // Si horodatage activé
]
```

#### sealDocument() - Cachet Institutionnel (Layer 3)

```php
public function sealDocument(
    string $documentHash,
    string $institutionId,
    string $purpose,
    string $documentRef,
    string $docSigId
): array
```

**Paramètres** :
- `$documentHash` : Hash SHA-256 du document
- `$institutionId` : Identifiant explicite de l'institution (ex: "institution-brazzaville-central")
- `$purpose` : Objectif (ex: "cachet_institutionnel_declaration_naissance")
- `$documentRef` : Référence du document
- `$docSigId` : Identifiant de la signature documentaire (Layer 2)

**Retour** :
```php
[
    'doc_seal_id' => 'doc_seal_mno456',
    'signature' => 'vault:v1:...',
    'sealed_at' => '2026-04-14T10:42:05+00:00',
    'rfc3161_serial' => '...'  // Si horodatage activé
]
```

#### verifyDocument() - Vérification Documentaire

```php
public function verifyDocument(
    string $documentHash,
    ?string $docSigId = null,
    ?string $docSealId = null
): array
```

**Retour** : Résultat de vérification (valid, reason, ...)

#### createSigner() - Enrôlement SDK-first

```php
public function createSigner(array $input, ?string $idempotencyKey = null): array
```

**Paramètres** :
- `$input` : `external_user_id`, `display_name`, `job_title`, `service`, `email` (voir `/v1/signers`)
- `$idempotencyKey` : clé d'idempotence optionnelle (prévient les doublons en cas de retry)

**Retour** :
```php
[
    'signer' => [
        'actor_id'   => 'act_01hzb7...',
        'status'     => 'active',
        'not_after'  => '2027-04-18T00:00:00Z',
    ]
]
```

#### revokeSigner() - Révocation d'un signataire

```php
public function revokeSigner(string $actorId, string $reason = 'unspecified'): array
```

**Paramètres** :
- `$actorId` : identifiant PKI du signataire
- `$reason` : motif RFC 5280 (`unspecified`, `key_compromise`, `affiliation_changed`, `superseded`, `cessation_of_operation`)

#### renewSigner() - Renouvellement de certificat

```php
public function renewSigner(string $actorId, array $options = []): array
```

Renouvelle le certificat X.509 du signataire sans changer son `actor_id`.

#### createTokenEnrollmentSession() - Session d'enrôlement token USB

```php
public function createTokenEnrollmentSession(array $input): array
```

Crée une session éphémère pour l'enrôlement d'un token USB hardware côté navigateur.
La clé API n'est jamais exposée au navigateur — seul le `session_token` de courte durée l'est.

## Services enregistrés

| Binding                      | Classe                  |
|-----------------------------|-------------------------|
| `SignatureManager::class`    | `LaravelPki\SignatureManager`    |
| `TrustClient::class`         | `PkiSdk\TrustClient`    |
| `SignatureClient::class`     | `PkiSdk\SignatureClient` |
| `SignerClient::class`        | `PkiSdk\SignerClient`   |
| `VerificationClient::class`  | `PkiSdk\VerificationClient` |
| `ProofClient::class`         | `PkiSdk\ProofClient`    |
| `OffboardingClient::class`   | `PkiSdk\OffboardingClient` |

#### getDocumentSignatureBundle() - Bundle de Vérification Offline (Layer 2)

```php
public function getDocumentSignatureBundle(string $docSigId): array
```

**Retour** : Bundle complet pour vérification offline (signature DER, certificat PEM, instructions OpenSSL)

#### getDocumentSealBundle() - Bundle de Vérification Offline (Layer 3)

```php
public function getDocumentSealBundle(string $docSealId): array
```

**Retour** : Bundle complet pour vérification offline du cachet institutionnel

#### getRfc3161Bundle() - Bundle d'Horodatage RFC 3161

```php
public function getRfc3161Bundle(?int $serial): ?array
```

**Retour** : Bundle d'horodatage (tst_resp_der_b64, gen_time, certificats TSA, commandes de vérification)

## Configuration Détaillée

### Fichier de Configuration

Après publication (`php artisan vendor:publish --tag=pki-config`), le fichier `config/pki.php` est créé :

```php
return [
    'trust_api' => [
        'url'     => env('PKI_TRUST_API_URL', 'http://trust-api:8080'),
        'api_key' => env('PKI_API_KEY', ''),  // Clé console Signum développeur (sk_live_xxx)
        'timeout' => (int) env('PKI_TIMEOUT', 10),
        'ca_bundle' => env('PKI_CA_BUNDLE') ?: null,
    ],
];
```

### Variables d'Environnement

```dotenv
# URL du service trust-api
PKI_TRUST_API_URL=http://trust-api:8080

# Clé d'API pour l'authentification (console Signum, espace développeur)
PKI_API_KEY=sk_live_VotreCleConsoleSignum

# Timeout des requêtes HTTP (secondes)
PKI_TIMEOUT=10
```

### Configuration en Production

```dotenv
# Production
PKI_TRUST_API_URL=https://trust-api.internal.company.com
PKI_API_KEY=prod-secure-key-xyz
PKI_TIMEOUT=30
PKI_CA_BUNDLE=/etc/ssl/signum/internal-ca-bundle.pem
```

## Usage — Injection de dépendances

```php
use LaravelPki\SignatureManager;
use PkiSdk\SignerClient;

class DocumentController extends Controller
{
    public function __construct(
        private readonly SignatureManager $pki,
        private readonly SignerClient $signers,
    ) {}

    public function enrolerAgent(Request $request): JsonResponse
    {
        $result = $this->pki->createSigner([
            'external_user_id' => 'user-' . $request->user()->id,
            'display_name'     => $request->user()->name,
            'job_title'        => $request->input('job_title'),
            'service'          => $request->input('service'),
            'email'            => $request->user()->email,
        ]);
        // Stocker $result['signer']['actor_id'] dans votre table utilisateurs
        return response()->json($result);
    }

    public function signerDocument(Document $doc, string $actorId): array
    {
        return $this->pki->sign(
            transactionId: 'doc-' . $doc->id . '-' . now()->format('Ymd'),
            payload: $doc->toSignablePayload(),
            actorId: $actorId,
            purpose: 'signature_document',
        );
    }
}
