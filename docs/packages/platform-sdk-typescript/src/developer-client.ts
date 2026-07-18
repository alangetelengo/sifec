import { HttpClient } from "./http.js";
import type {
  DeveloperApiKeyCreateRequest,
  DeveloperApiKeyListResponse,
  DeveloperApiKeyResponse,
  DeveloperOverviewResponse,
  RequestOptions,
  SignumClientOptions,
} from "./types.js";

export class SignumDeveloperClient {
  private readonly http: HttpClient;

  constructor(options: SignumClientOptions) {
    requireBearer(options);
    this.http = new HttpClient(options);
  }

  overview(options?: RequestOptions): Promise<DeveloperOverviewResponse> {
    return this.http.get<DeveloperOverviewResponse>("/v1/developer/overview", options);
  }

  listApiKeys(options?: RequestOptions): Promise<DeveloperApiKeyListResponse> {
    return this.http.get<DeveloperApiKeyListResponse>("/v1/developer/api-keys", options);
  }

  createApiKey(input: DeveloperApiKeyCreateRequest, options?: RequestOptions): Promise<DeveloperApiKeyResponse> {
    return this.http.post<DeveloperApiKeyResponse>("/v1/developer/api-keys", input, options);
  }

  revokeApiKey(id: number | string, options?: RequestOptions): Promise<DeveloperApiKeyResponse> {
    return this.http.post<DeveloperApiKeyResponse>(`/v1/developer/api-keys/${encodeURIComponent(String(id))}/revoke`, {}, options);
  }
}

export function createSignumDeveloperClient(options: SignumClientOptions): SignumDeveloperClient {
  return new SignumDeveloperClient(options);
}

function requireBearer(options: SignumClientOptions): void {
  if (!options.bearerToken && !options.headers?.Authorization) {
    throw new TypeError("SignumDeveloperClient requires a bearerToken or Authorization header.");
  }
}
