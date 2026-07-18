import { HttpClient } from "./http.js";
import type {
  ProofBundle,
  PublicVerifyRequest,
  PublicVerifyResponse,
  RequestOptions,
  SignumClientOptions,
  VerificationContext,
} from "./types.js";

export interface VerificationContextFilters {
  institution?: string;
  domain?: string;
}

export class SignumPublicClient {
  private readonly http: HttpClient;

  constructor(options: Omit<SignumClientOptions, "apiKey">) {
    this.http = new HttpClient(options);
  }

  getVerificationContext(filters: VerificationContextFilters = {}, options?: RequestOptions): Promise<VerificationContext> {
    return this.http.get<VerificationContext>(withQuery("/v1/public/verification-context", filters), options);
  }

  getProofBundle(proofId: string, options?: RequestOptions): Promise<ProofBundle> {
    return this.http.get<ProofBundle>(`/v1/public/proofs/${encodeURIComponent(proofId)}/bundle`, options);
  }

  verify(input: PublicVerifyRequest, options?: RequestOptions): Promise<PublicVerifyResponse> {
    return this.http.post<PublicVerifyResponse>("/v1/public/verify", input, options);
  }
}

export function createSignumPublicClient(options: Omit<SignumClientOptions, "apiKey">): SignumPublicClient {
  return new SignumPublicClient(options);
}

function withQuery(path: string, filters: VerificationContextFilters): string {
  const params = new URLSearchParams();
  for (const [key, value] of Object.entries(filters)) {
    if (value !== undefined && value !== null && value !== "") {
      params.set(key, String(value));
    }
  }

  const query = params.toString();
  return query ? `${path}?${query}` : path;
}
