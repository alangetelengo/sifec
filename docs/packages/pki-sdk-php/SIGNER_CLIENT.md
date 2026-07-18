# SignerClient SDK-first

`SignerClient` expose le cycle de vie SaaS des signataires via le contrat
`/v1/signers`.

Les methodes historiques `SignatureClient::enrollSigner` et
`SignatureClient::getSignerStatus` restent disponibles pour les endpoints POC
existants. `SignatureClient::revokeCert` est desactive depuis #90 car il
devinait signer ou institution a partir de prefixes POC. Les integrations SaaS
doivent utiliser `SignerClient::revoke()` pour les signataires et attendre une
methode explicite pour la revocation institutionnelle.

## Methodes

```php
create(array $input, ?string $idempotencyKey = null): array
createBatch(array $items, array $options = []): array
getBatch(string $batchId): array
createTokenEnrollmentSession(array $input): array
list(array $filters = []): array
get(string $actorId): array
status(string $actorId): array
revoke(string $actorId, string $reason = 'unspecified', array $options = []): array
suspend(string $actorId, array $options = []): array
renew(string $actorId, array $options = []): array
```

`external_user_id` est recommande pour rendre la creation idempotente par
utilisateur metier. Lorsque le transport SDK ne sait pas encore ajouter
l'en-tete `Idempotency-Key`, `SignerClient::create()` transmet aussi
`idempotency_key` dans le corps de requete; `trust-api` accepte ce repli.

`createBatch()` cree un lot asynchrone limite a 100 lignes. Les items invalides
sont retournes ligne par ligne avec `status=failed`; les items valides sont mis
en file `queued` sauf en `dry_run`.

`createTokenEnrollmentSession()` cree un jeton court pour le composant navigateur
token USB. Le navigateur recoit ce `session_token`, jamais la cle API serveur.

## Exemple

```php
use PkiSdk\SignerClient;
use PkiSdk\TrustClient;

$transport = new TrustClient(
    baseUrl: 'https://api.signum.cg',
    apiKey: 'sk_live_...'
);

$signers = new SignerClient($transport);

$created = $signers->create([
    'external_user_id' => 'users:42',
    'nom' => 'Dr. Marie Dupont',
    'titre' => 'Medecin chef',
    'email' => 'marie.dupont@chu.cg',
    'service' => 'Maternite',
], idempotencyKey: '018f9c5c-6ff0-7c6d-8e4a-demo');

$actorId = $created['signer']['actor_id'];
$status = $signers->status($actorId);

$renewed = $signers->renew($actorId);

$signers->revoke($actorId, 'affiliation_changed');
$signers->suspend($actorId, ['reason' => 'conge_longue_duree']);

$batch = $signers->createBatch([
    [
        'client_ref' => 'line-1',
        'external_user_id' => 'users:43',
        'nom' => 'Agent Batch',
        'email' => 'agent.batch@mairie.cg',
    ],
], ['dry_run' => true]);

$batchStatus = $signers->getBatch($batch['batch']['id']);

$tokenSession = $signers->createTokenEnrollmentSession([
    'external_user_id' => 'users:44',
    'nom' => 'Agent Token',
    'allowed_origin' => 'https://rh.institution.cg',
    'expires_in_seconds' => 300,
]);
```
