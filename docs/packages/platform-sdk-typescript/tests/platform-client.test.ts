import assert from "node:assert/strict";
import test from "node:test";
import {
  SignumAccreditationClient,
  SignumAdminClient,
  SignumClientConsoleClient,
  SignumDeveloperClient,
} from "../src/index.js";
import type { FetchLike } from "../src/index.js";

test("admin client sends bearer token and calls platform endpoints", async () => {
  const requests: Array<{ url: string; init?: RequestInit }> = [];
  const fetch: FetchLike = async (url, init) => {
    requests.push({ url: String(url), init });
    return jsonResponse({ tenant: { id: "ten_1" }, tenants: [] });
  };

  const client = new SignumAdminClient({
    baseUrl: "https://api.signum.test",
    bearerToken: "sess_admin",
    fetch,
  });

  await client.listTenants();
  await client.updateTenantVerificationDomain("ten/1", { domain: "verifier.mairie.cg" });
  await client.updateTenantBranding("ten/1", { display_name: "Mairie" });
  await client.approveSubscription("sub/1", { approved_by: "guot-admin" });

  const headers = requests[0]?.init?.headers as Record<string, string>;
  const approveBody = JSON.parse(String(requests[3]?.init?.body)) as Record<string, unknown>;
  assert.equal(headers.Authorization, "Bearer sess_admin");
  assert.equal(requests[0]?.url, "https://api.signum.test/v1/admin/tenants");
  assert.equal(requests[1]?.url, "https://api.signum.test/v1/admin/tenants/ten%2F1/verification-domain");
  assert.equal(requests[1]?.init?.method, "PUT");
  assert.equal(requests[2]?.url, "https://api.signum.test/v1/admin/tenants/ten%2F1/branding");
  assert.equal(requests[3]?.url, "https://api.signum.test/v1/admin/subscriptions/sub%2F1/approve");
  assert.equal(approveBody.approved_by, "guot-admin");
});

test("client console client covers tenant governance requests", async () => {
  const urls: string[] = [];
  const fetch: FetchLike = async (url) => {
    urls.push(String(url));
    return jsonResponse({ subscription: { id: "sub_1" }, usage: { period: "rolling_30d", summary: { total_calls_30d: 0 } }, invoices: [] });
  };

  const client = new SignumClientConsoleClient({
    baseUrl: "https://api.signum.test",
    bearerToken: "sess_client",
    fetch,
  });

  await client.getSubscription();
  await client.requestSubscriptionChange({ requested_plan: "institution", reason: "volume" });
  await client.getUsage();
  await client.listInvoices();

  assert.deepEqual(urls, [
    "https://api.signum.test/v1/client/subscription",
    "https://api.signum.test/v1/client/subscription/change-requests",
    "https://api.signum.test/v1/client/usage",
    "https://api.signum.test/v1/client/invoices",
  ]);
});

test("developer client manages api keys with bearer session", async () => {
  const requests: Array<{ url: string; init?: RequestInit }> = [];
  const fetch: FetchLike = async (url, init) => {
    requests.push({ url: String(url), init });
    return jsonResponse({ api_keys: [], api_key: { id: 7 }, secret: "sk_test_secret" });
  };

  const client = new SignumDeveloperClient({
    baseUrl: "https://api.signum.test",
    bearerToken: "sess_dev",
    fetch,
  });

  await client.overview();
  await client.listApiKeys();
  await client.createApiKey({ label: "SDK", environment: "test", scopes: ["signers:read"] });
  await client.revokeApiKey(7);

  const createBody = JSON.parse(String(requests[2]?.init?.body)) as Record<string, unknown>;
  assert.equal(requests[0]?.url, "https://api.signum.test/v1/developer/overview");
  assert.equal(requests[2]?.init?.method, "POST");
  assert.equal(createBody.label, "SDK");
  assert.equal(requests[3]?.url, "https://api.signum.test/v1/developer/api-keys/7/revoke");
});

test("accreditation client sends multipart form without credentials", async () => {
  const requests: Array<{ url: string; init?: RequestInit }> = [];
  const fetch: FetchLike = async (url, init) => {
    requests.push({ url: String(url), init });
    return jsonResponse({ submitted: true, accreditation_request: { id: "acr_1" } }, 202);
  };

  const client = new SignumAccreditationClient({
    baseUrl: "https://api.signum.test",
    fetch,
  });

  await client.submit({
    organization_name: "Mairie",
    legal_identifier: "NIU-123",
    registration_authority: "DGI",
    contact_email: "contact@mairie.cg",
    representative_name: "Maire",
    representative_email: "maire@mairie.cg",
    technical_contact_email: "tech@mairie.cg",
    security_contact_email: "rsssi@mairie.cg",
    requested_plan: "institution",
    business_domain: "etat civil",
    verification_domain: "verifier.mairie.cg",
    supporting_document_types: ["creation_document"],
  });

  const headers = requests[0]?.init?.headers as Record<string, string>;
  assert.equal(requests[0]?.url, "https://api.signum.test/v1/accreditation-requests");
  assert.equal(requests[0]?.init?.method, "POST");
  assert.ok(requests[0]?.init?.body instanceof FormData);
  assert.equal(headers.Authorization, undefined);
  assert.equal(headers["X-Api-Key"], undefined);
  assert.equal(headers["Content-Type"], undefined);
});

function jsonResponse(body: unknown, status = 200): Response {
  return new Response(JSON.stringify(body), {
    status,
    headers: { "Content-Type": "application/json" },
  });
}
