# Signum TypeScript SDK

Premiere base officielle du SDK TypeScript Signum. Le package est autonome, sans dependance runtime, et utilise `fetch` natif.

## Installation locale

```bash
cd packages/pki-sdk-typescript
npm install
npm run build
npm test
```

Node 18+ est requis pour `fetch` natif et le runner de tests Node.

## Client public de verification

Le client public n'envoie pas de cle API. Il couvre les endpoints du portail public:

- `GET /v1/public/verification-context`
- `GET /v1/public/proofs/{proof_id}/bundle`
- `POST /v1/public/verify`

```ts
import { SignumPublicClient } from "@signum/pki-sdk-typescript";

const publicClient = new SignumPublicClient({
  baseUrl: "https://api.signum.cg",
});

const context = await publicClient.getVerificationContext();
const bundle = await publicClient.getProofBundle("prf_01HX...");
const result = await publicClient.verify({ proof_id: "prf_01HX..." });
```

## Client serveur signataires

Le client signataire requiert une cle API serveur `X-Api-Key`. Il expose le contrat SDK-first `/v1/signers`:

- `create(input, { idempotencyKey })`
- `list(filters)`
- `get(actorId)`
- `status(actorId)`
- `revoke(actorId, input)`
- `renew(actorId, input)`
- `createBatch(input)`
- `getBatch(batchId)`

```ts
import { SignumSignerClient } from "@signum/pki-sdk-typescript";

const signers = new SignumSignerClient({
  baseUrl: "https://api.signum.cg",
  apiKey: process.env.SIGNUM_API_KEY,
});

const created = await signers.create(
  {
    external_user_id: "users:42",
    nom: "Dr. Marie Dupont",
    titre: "Medecin chef",
    email: "marie.dupont@chu.cg",
    service: "Maternite",
    approval_mode: "tenant_default",
    metadata: { source: "rh" },
  },
  { idempotencyKey: "018f9c5c-6ff0-7c6d-8e4a-demo" },
);

const actorId = created.signer?.actor_id;
if (actorId) {
  const status = await signers.status(actorId);
  await signers.renew(actorId);
  await signers.revoke(actorId, {
    reason: "affiliation_changed",
    comment: "Depart de l'agent",
    mode: "tenant_default",
  });
}
```

## Client webhooks

Le client webhooks utilise une cle API serveur avec les scopes `webhooks:read` et/ou `webhooks:write`.

```ts
import { SignumWebhookClient } from "@signum/pki-sdk-typescript";

const webhooks = new SignumWebhookClient({
  baseUrl: "https://api.signum.cg",
  apiKey: process.env.SIGNUM_API_KEY,
});

const endpoint = await webhooks.createEndpoint({
  url: "https://institution.cg/signum/webhook",
  events: ["certificate.revoked", "certificate.expiring_soon"],
});

const deliveries = await webhooks.listDeliveries({ event_type: "certificate.revoked" });
```

## Enrolement token USB

Le flux token USB se fait en deux temps pour ne jamais exposer la cle API serveur dans le navigateur.

1. Le backend institution cree une session ephemere avec `X-Api-Key`.
2. Le composant navigateur finalise l'enrolement avec `session_token` et le CSR du token.

```ts
import { SignumTokenEnrollmentClient } from "@signum/pki-sdk-typescript";

const tokenEnrollment = new SignumTokenEnrollmentClient({
  baseUrl: "https://api.signum.cg",
  apiKey: process.env.SIGNUM_API_KEY,
});

const session = await tokenEnrollment.createSession({
  external_user_id: "users:42",
  nom: "Dr. Marie Dupont",
  email: "marie.dupont@chu.cg",
  allowed_origin: "https://rh.chu.cg",
  expires_in_seconds: 300,
});

// Cote navigateur ou composant embarque: aucune cle API n'est envoyee.
const result = await tokenEnrollment.complete({
  session_token: session.session.token,
  csr: "-----BEGIN CERTIFICATE REQUEST-----\n...\n-----END CERTIFICATE REQUEST-----",
});

console.log(result.token_enrollment.actor_id);
```

## Erreurs

Les erreurs HTTP et reseau sont normalisees:

- `SignumApiError`
- `SignumUnauthorizedError` pour 401/403
- `SignumNotFoundError` pour 404
- `SignumRateLimitError` pour 429
- `SignumServiceUnavailableError` pour 503/504, timeout et erreur reseau

Chaque erreur expose `status`, `code`, `requestId` et `details` quand l'API les fournit.

> **Note sur les 403 :** `SignumUnauthorizedError` couvre à la fois les erreurs d'authentification (401)
> et les erreurs d'autorisation (403). Un 403 sur une opération de signature peut avoir plusieurs causes :
> signataire `suspended`, `revoked` ou `archived` ; tenant suspendu ou résilié ; institution inactive.
> Inspecter `error.details` ou le message pour identifier la cause précise.

```ts
import { SignumNotFoundError, SignumUnauthorizedError, isSignumApiError } from "@signum/pki-sdk-typescript";

try {
  await signers.get("act_unknown");
} catch (error) {
  if (error instanceof SignumNotFoundError) {
    // signer absent dans le tenant
  }

  if (error instanceof SignumUnauthorizedError && error.status === 403) {
    // Signataire suspendu/révoqué, tenant suspendu, ou institution inactive
    // Inspecter error.details pour la cause exacte
  }

  if (isSignumApiError(error)) {
    console.error(error.status, error.code, error.requestId);
  }
}
```

## Injection de fetch

Un `fetch` custom peut etre injecte pour les tests, workers ou runtimes specifiques:

```ts
const client = new SignumPublicClient({
  baseUrl: "https://api.signum.cg",
  fetch: async (url, init) => fetch(url, init),
});
```

## Limites de cette premiere base

- Les types suivent les specs disponibles dans le repo, mais restent permissifs sur quelques champs d'extension tant que le contrat OpenAPI officiel TypeScript n'est pas consomme.
- Les endpoints de signature transactionnelle/documentaire historiques du SDK PHP ne sont pas encore portes.
- Le composant navigateur token USB packagable reste a produire autour du flux `SignumTokenEnrollmentClient`.
- Les operations de portails Signum et de back-office GUOT sont exclues de ce package public. Elles vivent dans le package prive `@signum/platform-sdk-typescript`.
