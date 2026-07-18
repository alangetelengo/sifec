import assert from "node:assert/strict";
import test from "node:test";
import { SignumPublicClient } from "../src/index.js";
import type { FetchLike } from "../src/index.js";

test("public client calls verification endpoints without api key", async () => {
  const requests: Array<{ url: string; init?: RequestInit }> = [];
  const fetch: FetchLike = async (url, init) => {
    requests.push({ url: String(url), init });
    return jsonResponse({ proof_id: "prf_123", valid: true });
  };

  const client = new SignumPublicClient({ baseUrl: "https://api.signum.test/", fetch });

  await client.getVerificationContext({ institution: "mairie-demo", domain: "verifier.mairie.cg" });
  await client.getProofBundle("prf 123");
  await client.verify({ proof_id: "prf_123" });

  assert.equal(requests[0]?.url, "https://api.signum.test/v1/public/verification-context?institution=mairie-demo&domain=verifier.mairie.cg");
  assert.equal(requests[1]?.url, "https://api.signum.test/v1/public/proofs/prf%20123/bundle");
  assert.equal(requests[2]?.url, "https://api.signum.test/v1/public/verify");
  assert.equal((requests[2]?.init?.headers as Record<string, string>)["X-Api-Key"], undefined);
});

function jsonResponse(body: unknown, status = 200): Response {
  return new Response(JSON.stringify(body), {
    status,
    headers: { "Content-Type": "application/json" },
  });
}
