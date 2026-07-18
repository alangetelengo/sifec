import {
  SignumApiError,
  SignumNotFoundError,
  SignumRateLimitError,
  SignumServiceUnavailableError,
  SignumUnauthorizedError,
} from "./errors.js";
import type { FetchLike, JsonObject, JsonValue, RequestOptions, SignumClientOptions } from "./types.js";

const DEFAULT_TIMEOUT_MS = 10_000;

interface RequestBody {
  body?: unknown;
  form?: BodyInit;
}

export class HttpClient {
  private readonly baseUrl: string;
  private readonly apiKey?: string;
  private readonly bearerToken?: string;
  private readonly fetchImpl: FetchLike;
  private readonly headers: Record<string, string>;
  private readonly timeoutMs: number;

  constructor(options: SignumClientOptions) {
    if (!options.baseUrl) {
      throw new TypeError("Signum baseUrl is required.");
    }

    const fetchImpl = options.fetch ?? globalThis.fetch;
    if (typeof fetchImpl !== "function") {
      throw new TypeError("A fetch implementation is required in this runtime.");
    }

    this.baseUrl = options.baseUrl.replace(/\/+$/, "");
    this.apiKey = options.apiKey;
    this.bearerToken = options.bearerToken;
    this.fetchImpl = fetchImpl.bind(globalThis) as FetchLike;
    this.headers = options.headers ?? {};
    this.timeoutMs = options.timeoutMs ?? DEFAULT_TIMEOUT_MS;
  }

  get<T>(path: string, options: RequestOptions = {}): Promise<T> {
    return this.request<T>("GET", path, {}, options);
  }

  post<T>(path: string, body: unknown, options: RequestOptions = {}): Promise<T> {
    return this.request<T>("POST", path, { body }, options);
  }

  put<T>(path: string, body: unknown, options: RequestOptions = {}): Promise<T> {
    return this.request<T>("PUT", path, { body }, options);
  }

  postForm<T>(path: string, form: BodyInit, options: RequestOptions = {}): Promise<T> {
    return this.request<T>("POST", path, { form }, options);
  }

  private async request<T>(
    method: string,
    path: string,
    requestBody: RequestBody,
    options: RequestOptions,
  ): Promise<T> {
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), options.timeoutMs ?? this.timeoutMs);

    const headers: Record<string, string> = {
      Accept: "application/json",
      ...this.headers,
      ...options.headers,
    };

    const init: RequestInit = {
      method,
      headers,
      signal: controller.signal,
    };

    if (requestBody.body !== undefined) {
      headers["Content-Type"] = "application/json";
      init.body = JSON.stringify(requestBody.body);
    }

    if (requestBody.form !== undefined) {
      init.body = requestBody.form;
    }

    if (this.apiKey) {
      headers["X-Api-Key"] = this.apiKey;
    }

    if (this.bearerToken && headers.Authorization === undefined) {
      headers.Authorization = `Bearer ${this.bearerToken}`;
    }

    try {
      const response = await this.fetchImpl(this.url(path), init);
      return await this.parseResponse<T>(response);
    } catch (error) {
      if (error instanceof SignumApiError) {
        throw error;
      }

      const aborted = error instanceof Error && error.name === "AbortError";
      throw new SignumServiceUnavailableError(
        aborted ? "Signum API request timed out." : "Signum API request failed before receiving a response.",
        { cause: error },
      );
    } finally {
      clearTimeout(timeout);
    }
  }

  private url(path: string): string {
    if (/^https?:\/\//i.test(path)) {
      return path;
    }
    return `${this.baseUrl}/${path.replace(/^\/+/, "")}`;
  }

  private async parseResponse<T>(response: Response): Promise<T> {
    const text = await response.text();
    const parsed = text === "" ? undefined : parseJson(text, response.status);

    if (response.ok) {
      return parsed as T;
    }

    throw createApiError(response, parsed);
  }
}

function parseJson(text: string, status: number): JsonValue {
  try {
    return JSON.parse(text) as JsonValue;
  } catch (error) {
    throw new SignumApiError(`Invalid JSON response from Signum API (HTTP ${status}).`, {
      status,
      cause: error,
      details: text,
    });
  }
}

function createApiError(response: Response, payload: JsonValue | undefined): SignumApiError {
  const objectPayload = isJsonObject(payload) ? payload : undefined;
  const message =
    stringField(objectPayload, "error") ??
    stringField(objectPayload, "message") ??
    `Signum API error (HTTP ${response.status}).`;

  const options = {
    status: response.status,
    code: stringField(objectPayload, "code"),
    requestId: response.headers.get("x-request-id") ?? stringField(objectPayload, "request_id"),
    details: payload,
  };

  if (response.status === 401 || response.status === 403) {
    return new SignumUnauthorizedError(message, options);
  }
  if (response.status === 404) {
    return new SignumNotFoundError(message, options);
  }
  if (response.status === 429) {
    return new SignumRateLimitError(message, options);
  }
  if (response.status === 503 || response.status === 504) {
    return new SignumServiceUnavailableError(message, options);
  }
  return new SignumApiError(message, options);
}

function isJsonObject(value: JsonValue | undefined): value is JsonObject {
  return typeof value === "object" && value !== null && !Array.isArray(value);
}

function stringField(object: JsonObject | undefined, field: string): string | undefined {
  const value = object?.[field];
  return typeof value === "string" ? value : undefined;
}
