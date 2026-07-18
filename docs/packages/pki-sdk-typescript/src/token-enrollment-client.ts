import { HttpClient } from "./http.js";
import type {
  RequestOptions,
  SignumClientOptions,
  TokenEnrollmentCompleteRequest,
  TokenEnrollmentResponse,
  TokenEnrollmentSessionCreateRequest,
  TokenEnrollmentSessionResponse,
} from "./types.js";

export class SignumTokenEnrollmentClient {
  private readonly serverHttp: HttpClient;
  private readonly publicHttp: HttpClient;

  constructor(options: SignumClientOptions) {
    if (!options.apiKey) {
      throw new TypeError("SignumTokenEnrollmentClient requires an apiKey for session creation.");
    }

    this.serverHttp = new HttpClient(options);
    this.publicHttp = new HttpClient(publicOptions(options));
  }

  createSession(
    input: TokenEnrollmentSessionCreateRequest,
    options?: RequestOptions,
  ): Promise<TokenEnrollmentSessionResponse> {
    return this.serverHttp.post<TokenEnrollmentSessionResponse>("/v1/token-enrollment-sessions", input, options);
  }

  complete(input: TokenEnrollmentCompleteRequest, options?: RequestOptions): Promise<TokenEnrollmentResponse> {
    return this.publicHttp.post<TokenEnrollmentResponse>("/v1/token-enrollments", input, options);
  }
}

export function createSignumTokenEnrollmentClient(options: SignumClientOptions): SignumTokenEnrollmentClient {
  return new SignumTokenEnrollmentClient(options);
}

function publicOptions(options: SignumClientOptions): SignumClientOptions {
  const headers = { ...(options.headers ?? {}) };
  delete headers.Authorization;
  delete headers.authorization;
  delete headers["X-Api-Key"];
  delete headers["x-api-key"];

  return {
    baseUrl: options.baseUrl,
    fetch: options.fetch,
    headers,
    timeoutMs: options.timeoutMs,
  };
}
