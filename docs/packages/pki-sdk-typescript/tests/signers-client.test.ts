import assert from "node:assert/strict";
import test from "node:test";
import { SignumSignerClient } from "../src/index.js";
import type { FetchLike } from "../src/index.js";

test("signer client sends api key and idempotency key", async () => {
  const requests: Array<{ url: string; init?: RequestInit }> = [];
  const fetch: FetchLike = async (url, init) => {
    requests.push({ url: String(url), init });
    return jsonResponse({ api_version: "1.0", signer: { actor_id: "act_1", status: "active" } }, 201);
  };

  const client = new SignumSignerClient({
    baseUrl: "https://api.signum.test",
    apiKey: "sk_test_123",
    fetch,
  });

  await client.create({ external_user_id: "users:42", nom: "Marie" }, { idempotencyKey: "idem-1" });

  const headers = requests[0]?.init?.headers as Record<string, string>;
  const body = JSON.parse(String(requests[0]?.init?.body)) as Record<string, unknown>;

  assert.equal(requests[0]?.url, "https://api.signum.test/v1/signers");
  assert.equal(headers["X-Api-Key"], "sk_test_123");
  assert.equal(headers["Idempotency-Key"], "idem-1");
  assert.equal(body.idempotency_key, "idem-1");
});

test("signer client builds list filters and lifecycle paths", async () => {
  const urls: string[] = [];
  const fetch: FetchLike = async (url) => {
    urls.push(String(url));
    return jsonResponse({ api_version: "1.0", signers: [], signer: { actor_id: "act_1", status: "active" } });
  };

  const client = new SignumSignerClient({
    baseUrl: "https://api.signum.test",
    apiKey: "sk_test_123",
    fetch,
  });

  await client.list({ status: "active", page: 2, service: "" });
  await client.get("act/1");
  await client.status("act/1");
  await client.revoke("act/1", { reason: "affiliation_changed" });
  await client.renew("act/1");
  await client.createBatch({ dry_run: true, items: [{ client_ref: "line-1", nom: "Agent" }] });
  await client.getBatch("bat/1");

  assert.equal(urls[0], "https://api.signum.test/v1/signers?status=active&page=2");
  assert.equal(urls[1], "https://api.signum.test/v1/signers/act%2F1");
  assert.equal(urls[2], "https://api.signum.test/v1/signers/act%2F1/status");
  assert.equal(urls[3], "https://api.signum.test/v1/signers/act%2F1/revoke");
  assert.equal(urls[4], "https://api.signum.test/v1/signers/act%2F1/renew");
  assert.equal(urls[5], "https://api.signum.test/v1/signers/batch");
  assert.equal(urls[6], "https://api.signum.test/v1/signers/batch/bat%2F1");
});

function jsonResponse(body: unknown, status = 200): Response {
  return new Response(JSON.stringify(body), {
    status,
    headers: { "Content-Type": "application/json" },
  });
}
