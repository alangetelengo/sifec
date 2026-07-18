import assert from "node:assert/strict";
import test from "node:test";
import { SignumTokenEnrollmentClient } from "../src/index.js";
import type { FetchLike } from "../src/index.js";

test("token enrollment client creates server-side sessions with api key", async () => {
  const requests: Array<{ url: string; init?: RequestInit }> = [];
  const fetch: FetchLike = async (url, init) => {
    requests.push({ url: String(url), init });
    return jsonResponse({
      api_version: "1.0",
      session: {
        id: "tes_1",
        token: "tok_1",
        status: "pending",
        expires_at: "2026-05-03T12:00:00Z",
      },
    });
  };

  const client = new SignumTokenEnrollmentClient({
    baseUrl: "https://api.signum.test",
    apiKey: "sk_test_123",
    fetch,
  });

  await client.createSession({
    external_user_id: "users:42",
    nom: "Marie",
    allowed_origin: "https://rh.example.test",
    expires_in_seconds: 300,
  });

  const headers = requests[0]?.init?.headers as Record<string, string>;
  const body = JSON.parse(String(requests[0]?.init?.body)) as Record<string, unknown>;

  assert.equal(requests[0]?.url, "https://api.signum.test/v1/token-enrollment-sessions");
  assert.equal(headers["X-Api-Key"], "sk_test_123");
  assert.equal(body.external_user_id, "users:42");
  assert.equal(body.allowed_origin, "https://rh.example.test");
});

test("token enrollment client completes browser flow without api key", async () => {
  const requests: Array<{ url: string; init?: RequestInit }> = [];
  const fetch: FetchLike = async (url, init) => {
    requests.push({ url: String(url), init });
    return jsonResponse({
      api_version: "1.0",
      token_enrollment: {
        id: "ten_1",
        status: "completed",
        actor_id: "act_1",
        certificate_status: "valid",
      },
    });
  };

  const client = new SignumTokenEnrollmentClient({
    baseUrl: "https://api.signum.test",
    apiKey: "sk_test_123",
    bearerToken: "session_should_not_leak",
    headers: {
      Authorization: "Bearer custom_should_not_leak",
      "X-Api-Key": "header_should_not_leak",
    },
    fetch,
  });

  await client.complete({
    session_token: "tok_1",
    csr: "-----BEGIN CERTIFICATE REQUEST-----\n...\n-----END CERTIFICATE REQUEST-----",
  });

  const headers = requests[0]?.init?.headers as Record<string, string>;
  const body = JSON.parse(String(requests[0]?.init?.body)) as Record<string, unknown>;

  assert.equal(requests[0]?.url, "https://api.signum.test/v1/token-enrollments");
  assert.equal(headers["X-Api-Key"], undefined);
  assert.equal(headers.Authorization, undefined);
  assert.equal(body.session_token, "tok_1");
});

test("token enrollment client requires api key for session creation capability", () => {
  assert.throws(
    () => new SignumTokenEnrollmentClient({ baseUrl: "https://api.signum.test", fetch: async () => jsonResponse({}) }),
    /requires an apiKey/,
  );
});

function jsonResponse(body: unknown, status = 200): Response {
  return new Response(JSON.stringify(body), {
    status,
    headers: { "Content-Type": "application/json" },
  });
}
