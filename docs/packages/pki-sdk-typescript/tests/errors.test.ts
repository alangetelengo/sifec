import assert from "node:assert/strict";
import test from "node:test";
import {
  SignumNotFoundError,
  SignumRateLimitError,
  SignumServiceUnavailableError,
  SignumSignerClient,
  SignumUnauthorizedError,
} from "../src/index.js";
import type { FetchLike } from "../src/index.js";

test("normalizes API error classes", async () => {
  const cases: Array<[number, new (...args: never[]) => Error]> = [
    [401, SignumUnauthorizedError],
    [404, SignumNotFoundError],
    [429, SignumRateLimitError],
    [503, SignumServiceUnavailableError],
  ];

  for (const [status, ErrorClass] of cases) {
    const fetch: FetchLike = async () =>
      new Response(JSON.stringify({ error: "Nope", code: "nope" }), {
        status,
        headers: { "x-request-id": "req_1" },
      });
    const client = new SignumSignerClient({ baseUrl: "https://api.signum.test", apiKey: "sk", fetch });

    await assert.rejects(
      () => client.get("act_1"),
      (error: unknown) => {
        assert.ok(error instanceof ErrorClass);
        assert.equal((error as { status?: number }).status, status);
        assert.equal((error as { code?: string }).code, "nope");
        assert.equal((error as { requestId?: string }).requestId, "req_1");
        return true;
      },
    );
  }
});
