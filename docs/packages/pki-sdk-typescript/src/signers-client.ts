import { HttpClient } from "./http.js";
import type {
  CreateSignerBatchRequest,
  CreateSignerBatchResponse,
  CreateSignerRequest,
  CreateSignerResponse,
  GetSignerBatchResponse,
  GetSignerResponse,
  ListSignersResponse,
  RenewSignerRequest,
  RenewSignerResponse,
  RequestOptions,
  RevokeSignerRequest,
  RevokeSignerResponse,
  SignerListFilters,
  SignerStatusResponse,
  SignumClientOptions,
} from "./types.js";

export interface CreateSignerOptions extends RequestOptions {
  idempotencyKey?: string;
}

export class SignumSignerClient {
  private readonly http: HttpClient;

  constructor(options: SignumClientOptions) {
    if (!options.apiKey) {
      throw new TypeError("SignumSignerClient requires an apiKey.");
    }
    this.http = new HttpClient(options);
  }

  create(input: CreateSignerRequest, options: CreateSignerOptions = {}): Promise<CreateSignerResponse> {
    const headers = {
      ...options.headers,
      ...(options.idempotencyKey ? { "Idempotency-Key": options.idempotencyKey } : {}),
    };

    const body =
      options.idempotencyKey && input.idempotency_key === undefined
        ? { ...input, idempotency_key: options.idempotencyKey }
        : input;

    return this.http.post<CreateSignerResponse>("/v1/signers", body, { ...options, headers });
  }

  list(filters: SignerListFilters = {}, options?: RequestOptions): Promise<ListSignersResponse> {
    return this.http.get<ListSignersResponse>(withQuery("/v1/signers", filters), options);
  }

  get(actorId: string, options?: RequestOptions): Promise<GetSignerResponse> {
    return this.http.get<GetSignerResponse>(`/v1/signers/${encodeURIComponent(actorId)}`, options);
  }

  status(actorId: string, options?: RequestOptions): Promise<SignerStatusResponse> {
    return this.http.get<SignerStatusResponse>(`/v1/signers/${encodeURIComponent(actorId)}/status`, options);
  }

  revoke(
    actorId: string,
    input: RevokeSignerRequest = { reason: "unspecified" },
    options?: RequestOptions,
  ): Promise<RevokeSignerResponse> {
    return this.http.post<RevokeSignerResponse>(
      `/v1/signers/${encodeURIComponent(actorId)}/revoke`,
      { reason: "unspecified", ...input },
      options,
    );
  }

  renew(actorId: string, input: RenewSignerRequest = {}, options?: RequestOptions): Promise<RenewSignerResponse> {
    return this.http.post<RenewSignerResponse>(`/v1/signers/${encodeURIComponent(actorId)}/renew`, input, options);
  }

  createBatch(input: CreateSignerBatchRequest, options?: RequestOptions): Promise<CreateSignerBatchResponse> {
    return this.http.post<CreateSignerBatchResponse>("/v1/signers/batch", input, options);
  }

  getBatch(batchId: string, options?: RequestOptions): Promise<GetSignerBatchResponse> {
    return this.http.get<GetSignerBatchResponse>(`/v1/signers/batch/${encodeURIComponent(batchId)}`, options);
  }
}

export function createSignumSignerClient(options: SignumClientOptions): SignumSignerClient {
  return new SignumSignerClient(options);
}

function withQuery(path: string, filters: SignerListFilters): string {
  const params = new URLSearchParams();
  for (const [key, value] of Object.entries(filters)) {
    if (value !== undefined && value !== null && value !== "") {
      params.set(key, String(value));
    }
  }

  const query = params.toString();
  return query ? `${path}?${query}` : path;
}
