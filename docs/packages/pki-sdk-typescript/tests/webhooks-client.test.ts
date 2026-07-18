import assert from "node:assert/strict";
import test from "node:test";
import { SignumWebhookClient } from "../src/index.js";
import type { FetchLike } from "../src/index.js";

test("webhook client uses api key and filters deliveries", async () => {
  const requests: Array<{ url: string; init?: RequestInit }> = [];
  const fetch: FetchLike = async (url, init) => {
    requests.push({ url: String(url), init });
    return jsonResponse({ webhook_endpoints: [], webhook_endpoint: { id: "wh_1" }, webhook_deliveries: [] });
  };

  const client = new SignumWebhookClient({
    baseUrl: "https://api.signum.test",
    apiKey: "sk_test_123",
    fetch,
  });

  await client.listEndpoints();
  await client.createEndpoint({ url: "https://institution.cg/webhook", events: ["certificate.revoked"] });
  await client.listDeliveries({ event_type: "certificate.revoked" });

  const headers = requests[0]?.init?.headers as Record<string, string>;
  assert.equal(headers["X-Api-Key"], "sk_test_123");
  assert.equal(requests[0]?.url, "https://api.signum.test/v1/webhooks/endpoints");
  assert.equal(requests[2]?.url, "https://api.signum.test/v1/webhooks/deliveries?event_type=certificate.revoked");
});

function jsonResponse(body: unknown, status = 200): Response {
  return new Response(JSON.stringify(body), {
    status,
    headers: { "Content-Type": "application/json" },
  });
}
