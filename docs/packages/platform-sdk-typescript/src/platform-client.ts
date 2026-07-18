import { HttpClient } from "./http.js";
import type {
  AccreditationComplementRequest,
  AccreditationListResponse,
  AccreditationResponse,
  AccreditationSubmitRequest,
  ClientInvoicesResponse,
  ClientSubscriptionResponse,
  ClientUsageResponse,
  CreateSignerRequest,
  JsonValue,
  RequestOptions,
  SignumClientOptions,
  SubscriptionChangeRequest,
  SubscriptionListResponse,
  SubscriptionResponse,
  TenantListResponse,
  TenantResponse,
} from "./types.js";

export class SignumAccreditationClient {
  private readonly http: HttpClient;

  constructor(options: Omit<SignumClientOptions, "apiKey" | "bearerToken">) {
    this.http = new HttpClient(options);
  }

  submit(input: AccreditationSubmitRequest | FormData, options?: RequestOptions): Promise<AccreditationResponse> {
    return this.http.postForm<AccreditationResponse>("/v1/accreditation-requests", toFormData(input), options);
  }

  submitComplement(
    id: string,
    input: AccreditationComplementRequest | FormData,
    options?: RequestOptions,
  ): Promise<AccreditationResponse> {
    return this.http.postForm<AccreditationResponse>(
      `/v1/accreditation-requests/${encodeURIComponent(id)}/complements`,
      toFormData(input),
      options,
    );
  }
}

export class SignumAdminClient {
  private readonly http: HttpClient;

  constructor(options: SignumClientOptions) {
    requireBearer(options, "SignumAdminClient");
    this.http = new HttpClient(options);
  }

  listAccreditationRequests(options?: RequestOptions): Promise<AccreditationListResponse> {
    return this.http.get<AccreditationListResponse>("/v1/admin/accreditation-requests", options);
  }

  getAccreditationRequest(id: string, options?: RequestOptions): Promise<AccreditationResponse> {
    return this.http.get<AccreditationResponse>(`/v1/admin/accreditation-requests/${encodeURIComponent(id)}`, options);
  }

  approveAccreditationRequest(id: string, input: { approved_by?: string } = {}, options?: RequestOptions): Promise<AccreditationResponse> {
    return this.http.post<AccreditationResponse>(`/v1/admin/accreditation-requests/${encodeURIComponent(id)}/approve`, input, options);
  }

  requestAccreditationInfo(id: string, input: { reason: string; decided_by?: string }, options?: RequestOptions): Promise<AccreditationResponse> {
    return this.http.post<AccreditationResponse>(`/v1/admin/accreditation-requests/${encodeURIComponent(id)}/request-info`, input, options);
  }

  rejectAccreditationRequest(id: string, input: { reason: string; decided_by?: string }, options?: RequestOptions): Promise<AccreditationResponse> {
    return this.http.post<AccreditationResponse>(`/v1/admin/accreditation-requests/${encodeURIComponent(id)}/reject`, input, options);
  }

  listSubscriptions(options?: RequestOptions): Promise<SubscriptionListResponse> {
    return this.http.get<SubscriptionListResponse>("/v1/admin/subscriptions", options);
  }

  listPendingSubscriptions(options?: RequestOptions): Promise<SubscriptionListResponse> {
    return this.http.get<SubscriptionListResponse>("/v1/admin/subscriptions/pending", options);
  }

  getSubscription(id: string, options?: RequestOptions): Promise<SubscriptionResponse> {
    return this.http.get<SubscriptionResponse>(`/v1/admin/subscriptions/${encodeURIComponent(id)}`, options);
  }

  approveSubscription(id: string, input: { approved_by?: string } = {}, options?: RequestOptions): Promise<SubscriptionResponse> {
    return this.http.post<SubscriptionResponse>(`/v1/admin/subscriptions/${encodeURIComponent(id)}/approve`, input, options);
  }

  suspendSubscription(id: string, input: { reason?: string; decided_by?: string } = {}, options?: RequestOptions): Promise<SubscriptionResponse> {
    return this.http.post<SubscriptionResponse>(`/v1/admin/subscriptions/${encodeURIComponent(id)}/suspend`, input, options);
  }

  terminateSubscription(id: string, input: { reason?: string; decided_by?: string } = {}, options?: RequestOptions): Promise<SubscriptionResponse> {
    return this.http.post<SubscriptionResponse>(`/v1/admin/subscriptions/${encodeURIComponent(id)}/terminate`, input, options);
  }

  listTenants(options?: RequestOptions): Promise<TenantListResponse> {
    return this.http.get<TenantListResponse>("/v1/admin/tenants", options);
  }

  getTenant(id: string, options?: RequestOptions): Promise<TenantResponse> {
    return this.http.get<TenantResponse>(`/v1/admin/tenants/${encodeURIComponent(id)}`, options);
  }

  retryTenantProvisioning(id: string, options?: RequestOptions): Promise<TenantResponse> {
    return this.http.post<TenantResponse>(`/v1/admin/tenants/${encodeURIComponent(id)}/provisioning/retry`, {}, options);
  }

  updateTenantVerificationDomain(id: string, input: { domain: string }, options?: RequestOptions): Promise<TenantResponse> {
    return this.http.put<TenantResponse>(`/v1/admin/tenants/${encodeURIComponent(id)}/verification-domain`, input, options);
  }

  updateTenantBranding(
    id: string,
    input: { display_name?: string; primary_color?: string; logo_url?: string | null },
    options?: RequestOptions,
  ): Promise<TenantResponse> {
    return this.http.put<TenantResponse>(`/v1/admin/tenants/${encodeURIComponent(id)}/branding`, input, options);
  }
}

export class SignumClientConsoleClient {
  private readonly http: HttpClient;

  constructor(options: SignumClientOptions) {
    requireBearer(options, "SignumClientConsoleClient");
    this.http = new HttpClient(options);
  }

  getSubscription(options?: RequestOptions): Promise<ClientSubscriptionResponse> {
    return this.http.get<ClientSubscriptionResponse>("/v1/client/subscription", options);
  }

  requestSubscriptionChange(input: SubscriptionChangeRequest, options?: RequestOptions): Promise<SubscriptionResponse> {
    return this.http.post<SubscriptionResponse>("/v1/client/subscription/change-requests", input, options);
  }

  getUsage(options?: RequestOptions): Promise<ClientUsageResponse> {
    return this.http.get<ClientUsageResponse>("/v1/client/usage", options);
  }

  listInvoices(options?: RequestOptions): Promise<ClientInvoicesResponse> {
    return this.http.get<ClientInvoicesResponse>("/v1/client/invoices", options);
  }

  /** @deprecated Operational signer lifecycle belongs in the institution app through the SDK-first signer API. */
  listCertificates(options?: RequestOptions): Promise<{ certificates?: unknown[]; signers?: unknown[] }> {
    return this.http.get("/v1/client/certificates", options);
  }

  /** @deprecated Operational enrollment belongs in the institution app through the SDK-first signer API. */
  listEnrollmentRequests(options?: RequestOptions): Promise<{ enrollment_requests?: unknown[]; enrollments?: unknown[] }> {
    return this.http.get("/v1/client/enrollment-requests", options);
  }

  /** @deprecated Operational enrollment belongs in the institution app through the SDK-first signer API. */
  createEnrollmentRequest(input: CreateSignerRequest, options?: RequestOptions): Promise<{ enrollment_request?: unknown }> {
    return this.http.post("/v1/client/enrollment-requests", input, options);
  }

  /** @deprecated Operational revocation belongs in the institution app through the SDK-first signer API. */
  listRevocationRequests(options?: RequestOptions): Promise<{ revocation_requests?: unknown[]; revocations?: unknown[] }> {
    return this.http.get("/v1/client/revocation-requests", options);
  }

  /** @deprecated Operational revocation belongs in the institution app through the SDK-first signer API. */
  revokeCertificate(id: string, input: { reason?: string; comment?: string } = {}, options?: RequestOptions): Promise<{ revocation_request?: unknown }> {
    return this.http.post(`/v1/client/certificates/${encodeURIComponent(id)}/revoke`, input, options);
  }
}

export function createSignumAccreditationClient(options: Omit<SignumClientOptions, "apiKey" | "bearerToken">): SignumAccreditationClient {
  return new SignumAccreditationClient(options);
}

export function createSignumAdminClient(options: SignumClientOptions): SignumAdminClient {
  return new SignumAdminClient(options);
}

export function createSignumClientConsoleClient(options: SignumClientOptions): SignumClientConsoleClient {
  return new SignumClientConsoleClient(options);
}

function requireBearer(options: SignumClientOptions, clientName: string): void {
  if (!options.bearerToken && !options.headers?.Authorization) {
    throw new TypeError(`${clientName} requires a bearerToken or Authorization header.`);
  }
}

function toFormData(input: AccreditationSubmitRequest | AccreditationComplementRequest | FormData): FormData {
  if (typeof FormData === "undefined") {
    throw new TypeError("FormData is required for accreditation multipart requests.");
  }

  if (input instanceof FormData) {
    return input;
  }

  const form = new FormData();
  for (const [key, value] of Object.entries(input)) {
    if (value === undefined) {
      continue;
    }

    if (key === "supporting_documents" && Array.isArray(value)) {
      for (const file of value) {
        form.append(key, file);
      }
      continue;
    }

    if (Array.isArray(value)) {
      for (const item of value) {
        form.append(`${key}[]`, String(item));
      }
      continue;
    }

    form.append(key, String(value as JsonValue));
  }

  return form;
}
