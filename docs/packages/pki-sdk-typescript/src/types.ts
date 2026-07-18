export type JsonPrimitive = string | number | boolean | null;
export type JsonValue = JsonPrimitive | JsonObject | JsonValue[] | undefined;

export interface JsonObject {
  [key: string]: JsonValue;
}

export type FetchLike = (
  input: string | URL | Request,
  init?: RequestInit,
) => Promise<Response>;

export interface SignumClientOptions {
  baseUrl: string;
  apiKey?: string;
  bearerToken?: string;
  fetch?: FetchLike;
  headers?: Record<string, string>;
  timeoutMs?: number;
}

export interface RequestOptions {
  headers?: Record<string, string>;
  timeoutMs?: number;
}

export interface SignumApiEnvelope {
  api_version?: string;
}

export type SignerStatus =
  | "active"
  | "pending_approval"
  | "pending_enrollment"
  | "revoked"
  | "expired"
  | "suspended"
  | "unknown";

export type CertificateStatus =
  | "valid"
  | "revoked"
  | "expired"
  | "superseded"
  | "unknown";

export type ApprovalMode = "tenant_default" | "immediate" | "manual";

export type RevocationReason =
  | "unspecified"
  | "key_compromise"
  | "affiliation_changed"
  | "superseded"
  | "cessation_of_operation";

export interface CertificateSummary {
  serial_number?: string;
  not_before?: string;
  not_after?: string;
  status?: CertificateStatus;
  subject?: string;
  issuer?: string;
  fingerprint_sha256?: string;
  [key: string]: JsonValue | undefined;
}

export interface Signer {
  actor_id: string;
  external_user_id?: string;
  nom?: string;
  display_name?: string;
  titre?: string;
  job_title?: string;
  email?: string;
  service?: string;
  status: SignerStatus;
  certificate?: CertificateSummary;
  metadata?: JsonObject;
  created_at?: string;
  updated_at?: string;
  [key: string]: JsonValue | CertificateSummary | undefined;
}

export interface CreateSignerRequest {
  external_user_id?: string;
  nom?: string;
  display_name?: string;
  titre?: string;
  job_title?: string;
  email?: string;
  service?: string;
  approval_mode?: ApprovalMode;
  metadata?: JsonObject;
  idempotency_key?: string;
  [key: string]: JsonValue | undefined;
}

export interface CreateSignerResponse extends SignumApiEnvelope {
  signer?: Signer;
  enrollment_request?: {
    id: string;
    status: string;
    [key: string]: JsonValue;
  };
}

export interface SignerListFilters {
  status?: SignerStatus;
  service?: string;
  external_user_id?: string;
  expires_before?: string;
  page?: number;
  limit?: number;
}

export interface Pagination {
  page: number;
  limit: number;
  has_more: boolean;
  total?: number;
}

export interface ListSignersResponse extends SignumApiEnvelope {
  signers: Signer[];
  pagination?: Pagination;
}

export interface GetSignerResponse extends SignumApiEnvelope {
  signer: Signer;
}

export interface SignerStatusResponse extends SignumApiEnvelope {
  actor_id: string;
  status: SignerStatus;
  certificate_status?: CertificateStatus;
  not_after?: string;
  freshness?: {
    checked_at: string;
    max_age_seconds: number;
  };
  [key: string]: JsonValue | undefined;
}

export interface RevokeSignerRequest {
  reason?: RevocationReason;
  comment?: string;
  mode?: ApprovalMode;
  [key: string]: JsonValue | undefined;
}

export interface RevokeSignerResponse extends SignumApiEnvelope {
  signer?: Signer;
  revocation_request?: {
    id: string;
    status: string;
    [key: string]: JsonValue;
  };
  status?: string;
}

export interface RenewSignerRequest {
  mode?: ApprovalMode;
  ttl?: string;
  metadata?: JsonObject;
  [key: string]: JsonValue | undefined;
}

export interface RenewSignerResponse extends SignumApiEnvelope {
  signer: Signer & {
    previous_certificate?: CertificateSummary;
  };
}

export interface SignerBatchItem extends CreateSignerRequest {
  client_ref?: string;
}

export interface CreateSignerBatchRequest {
  dry_run?: boolean;
  approval_mode?: ApprovalMode;
  items: SignerBatchItem[];
  [key: string]: JsonValue | SignerBatchItem[] | undefined;
}

export interface SignerBatchSummary {
  accepted?: number;
  created?: number;
  pending_approval?: number;
  failed?: number;
}

export interface SignerBatchResultItem {
  client_ref?: string;
  status: "queued" | "created" | "pending_approval" | "failed";
  actor_id?: string;
  error_code?: string;
  message?: string;
}

export interface SignerBatch {
  id: string;
  status: "accepted" | "processing" | "completed" | "completed_with_errors" | "failed";
  total?: number;
  dry_run?: boolean;
  summary?: SignerBatchSummary;
  items?: SignerBatchResultItem[];
}

export interface CreateSignerBatchResponse extends SignumApiEnvelope {
  batch: SignerBatch;
}

export interface GetSignerBatchResponse extends SignumApiEnvelope {
  batch: SignerBatch;
}

export type TokenEnrollmentSessionStatus = "pending" | "completed" | "expired" | "cancelled" | string;

export interface TokenEnrollmentSessionCreateRequest {
  external_user_id: string;
  nom: string;
  allowed_origin: string;
  titre?: string;
  email?: string;
  service?: string;
  expires_in_seconds?: number;
  metadata?: JsonObject;
  [key: string]: JsonValue | undefined;
}

export interface TokenEnrollmentSession {
  id: string;
  token: string;
  status: TokenEnrollmentSessionStatus;
  expires_at: string;
  [key: string]: JsonValue | undefined;
}

export interface TokenEnrollmentSessionResponse extends SignumApiEnvelope {
  session: TokenEnrollmentSession;
}

export interface TokenEnrollmentCompleteRequest {
  session_token: string;
  csr: string;
  device_attestation?: JsonObject;
  [key: string]: JsonValue | undefined;
}

export interface TokenEnrollment {
  id: string;
  status: TokenEnrollmentSessionStatus;
  actor_id?: string;
  certificate_status?: CertificateStatus;
  [key: string]: JsonValue | undefined;
}

export interface TokenEnrollmentResponse extends SignumApiEnvelope {
  token_enrollment: TokenEnrollment;
}

export interface VerificationContext extends SignumApiEnvelope {
  tenant?: {
    id?: string;
    name?: string;
    verification_domain?: string;
    branding?: JsonObject;
    [key: string]: JsonValue | JsonObject | undefined;
  };
  trust?: {
    ca_certificates?: CertificateSummary[];
    crl_url?: string;
    ocsp_url?: string;
    checked_at?: string;
    [key: string]: JsonValue | CertificateSummary[] | undefined;
  };
  [key: string]: JsonValue | JsonObject | undefined;
}

export interface VerificationLayer {
  name: "L1" | "L2" | "L3" | "TSA" | string;
  valid?: boolean;
  status?: string;
  reason?: string;
  signature?: string;
  certificate?: CertificateSummary;
  signed_at?: string;
  tsa?: JsonObject;
  [key: string]: JsonValue | CertificateSummary | undefined;
}

export interface ProofBundle extends SignumApiEnvelope {
  proof_id: string;
  status?: string;
  valid?: boolean;
  document?: {
    id?: string;
    hash_sha256?: string;
    ref?: string;
    title?: string;
    [key: string]: JsonValue | undefined;
  };
  institution?: {
    id?: string;
    name?: string;
    [key: string]: JsonValue | undefined;
  };
  layers?: VerificationLayer[];
  verification_log?: JsonObject[];
  [key: string]: JsonValue | JsonObject[] | VerificationLayer[] | undefined;
}

export interface PublicVerifyRequest {
  proof_id?: string;
  document_hash?: string;
  signature?: string;
  proof_bundle?: ProofBundle;
  [key: string]: JsonValue | ProofBundle | undefined;
}

export interface PublicVerifyResponse extends SignumApiEnvelope {
  valid: boolean;
  status?: string;
  reason?: string;
  proof_id?: string;
  bundle?: ProofBundle;
  layers?: VerificationLayer[];
  [key: string]: JsonValue | ProofBundle | VerificationLayer[] | undefined;
}

export type SignumPlan = "standard" | "institution" | "high_volume";
export type AccreditationStatus = "submitted" | "needs_more_info" | "approved" | "rejected";
export type SubscriptionStatus = "pending_guot_validation" | "active" | "suspended" | "terminated";
export type ApiKeyEnvironment = "test" | "live";
export type ApiKeyStatus = "active" | "revoked";
export type WebhookEndpointStatus = "active" | "revoked";
export type WebhookDeliveryStatus = "pending" | "delivered" | "retry" | "failed";

export type ApiKeyScope =
  | "signatures:read"
  | "signatures:write"
  | "signers:read"
  | "signers:write"
  | "signers:batch"
  | "signers:revoke"
  | "webhooks:read"
  | "webhooks:write"
  | "usage:read"
  | "api_keys:read"
  | "api_keys:write"
  | "platform:admin"
  | "platform:client"
  | "platform:provisioning"
  | "platform:auth"
  | "*";

export type WebhookEventType =
  | "signer.created"
  | "signer.status_changed"
  | "certificate.expiring_soon"
  | "certificate.expired"
  | "certificate.revoked"
  | "enrollment.approved"
  | "enrollment.rejected"
  | "batch.completed"
  | "batch.completed_with_errors"
  | string;

export interface AuditEvent {
  id?: number;
  actor?: string | null;
  action?: string;
  metadata?: JsonObject;
  created_at?: string;
  [key: string]: JsonValue | undefined;
}

export interface Tenant {
  id: string;
  slug?: string;
  status?: string;
  provisioning_status?: string;
  organization_name?: string;
  verification_domain?: {
    domain?: string;
    status?: string;
    [key: string]: JsonValue | undefined;
  } | null;
  branding?: {
    display_name?: string;
    primary_color?: string;
    logo_url?: string | null;
    [key: string]: JsonValue | undefined;
  };
  audit_events?: AuditEvent[];
  [key: string]: JsonValue | AuditEvent[] | undefined;
}

export interface Subscription {
  id: string;
  organization_name?: string;
  plan?: SignumPlan;
  status?: SubscriptionStatus;
  tenant_id?: string | null;
  tenant?: Tenant | null;
  [key: string]: JsonValue | Tenant | undefined;
}

export interface AccreditationRequest {
  id: string;
  organization_name?: string;
  contact_email?: string;
  requested_plan?: SignumPlan;
  status?: AccreditationStatus;
  reason?: string | null;
  kyc_payload?: JsonObject;
  subscription?: Subscription | null;
  instruction_events?: AuditEvent[];
  [key: string]: JsonValue | Subscription | AuditEvent[] | undefined;
}

export interface AccreditationSubmitRequest {
  organization_name: string;
  legal_identifier: string;
  registration_authority: string;
  contact_email: string;
  representative_name: string;
  representative_email: string;
  technical_contact_email: string;
  security_contact_email: string;
  requested_plan: SignumPlan;
  business_domain: string;
  verification_domain: string;
  organization_type?: string;
  supporting_documents?: Array<Blob | File>;
  supporting_document_types?: string[];
  [key: string]: string | string[] | Array<Blob | File> | undefined;
}

export interface AccreditationComplementRequest {
  contact_email: string;
  complement_message: string;
  supporting_documents?: Array<Blob | File>;
  supporting_document_types?: string[];
  [key: string]: string | string[] | Array<Blob | File> | undefined;
}

export interface AccreditationResponse extends SignumApiEnvelope {
  mode?: string;
  submitted?: boolean;
  accreditation_request: AccreditationRequest;
}

export interface AccreditationListResponse extends SignumApiEnvelope {
  mode?: string;
  accreditation_requests: AccreditationRequest[];
}

export interface SubscriptionListResponse extends SignumApiEnvelope {
  subscriptions: Subscription[];
}

export interface SubscriptionResponse extends SignumApiEnvelope {
  subscription: Subscription;
  tenant?: Tenant | null;
}

export interface TenantListResponse extends SignumApiEnvelope {
  tenants: Tenant[];
}

export interface TenantResponse extends SignumApiEnvelope {
  tenant: Tenant;
}

export interface ClientSubscriptionResponse extends SignumApiEnvelope {
  subscription?: Subscription | null;
  tenant?: Tenant | null;
}

export interface ClientUsageSummary {
  total_calls_30d: number;
  successful_calls_30d?: number;
  success_rate_30d?: number;
  avg_response_time_ms_30d?: number;
  quota_monthly?: number;
  quota_used_percent?: number;
  [key: string]: JsonValue | undefined;
}

export interface ClientUsageEndpoint {
  endpoint: string;
  method?: string;
  calls: number;
  errors?: number;
  [key: string]: JsonValue | undefined;
}

export interface ClientUsageEvent {
  endpoint: string;
  method?: string;
  status_code?: number;
  response_time_ms?: number;
  created_at?: string;
  [key: string]: JsonValue | undefined;
}

export interface ClientUsage {
  period: string;
  summary: ClientUsageSummary;
  by_endpoint?: ClientUsageEndpoint[];
  recent_usage?: ClientUsageEvent[];
  [key: string]: JsonValue | ClientUsageSummary | ClientUsageEndpoint[] | ClientUsageEvent[] | undefined;
}

export interface ClientUsageResponse extends SignumApiEnvelope {
  usage: ClientUsage;
}

export interface ClientInvoice {
  id: string;
  period?: string;
  label?: string;
  plan?: SignumPlan | string;
  amount?: number;
  amount_formatted?: string;
  status?: string;
  issued_at?: string;
  subscription_id?: string;
  [key: string]: JsonValue | undefined;
}

export interface ClientInvoicesResponse extends SignumApiEnvelope {
  invoices: ClientInvoice[];
}

export interface SubscriptionChangeRequest {
  requested_plan: SignumPlan;
  subscription_id?: string;
  reason?: string;
  [key: string]: JsonValue;
}

export interface DeveloperApiKey {
  id: number;
  key_prefix?: string;
  masked_key?: string;
  label?: string;
  environment?: ApiKeyEnvironment;
  status?: ApiKeyStatus;
  scopes?: ApiKeyScope[];
  tenant_id?: string | null;
  organization_id?: string | null;
  tenant_slug?: string | null;
  last_used_at?: string | null;
  created_at?: string;
  revoked_at?: string | null;
  [key: string]: JsonValue | ApiKeyScope[] | undefined;
}

export interface DeveloperApiKeyCreateRequest {
  label: string;
  environment: ApiKeyEnvironment;
  scopes?: ApiKeyScope[];
}

export interface DeveloperOverviewResponse extends SignumApiEnvelope {
  scope?: JsonObject;
  summary?: JsonObject;
  api_keys?: DeveloperApiKey[];
  usage_by_endpoint?: JsonObject[];
  recent_usage?: JsonObject[];
}

export interface DeveloperApiKeyListResponse extends SignumApiEnvelope {
  api_keys: DeveloperApiKey[];
}

export interface DeveloperApiKeyResponse extends SignumApiEnvelope {
  api_key: DeveloperApiKey;
  secret?: string;
}

export interface WebhookEndpoint {
  id: string;
  url?: string;
  events?: WebhookEventType[];
  status?: WebhookEndpointStatus;
  secret?: string;
  secret_preview?: string;
  tenant_id?: string | null;
  created_at?: string | null;
  [key: string]: JsonValue | WebhookEventType[] | undefined;
}

export interface WebhookEndpointCreateRequest {
  url: string;
  events: WebhookEventType[];
}

export interface WebhookDelivery {
  id: string;
  event_id?: string;
  endpoint_id?: string;
  event_type?: WebhookEventType;
  status?: WebhookDeliveryStatus;
  attempts?: number;
  headers?: JsonObject;
  payload?: JsonObject;
  next_attempt_at?: string | null;
  last_error?: string | null;
  [key: string]: JsonValue | undefined;
}

export interface WebhookEndpointListResponse extends SignumApiEnvelope {
  webhook_endpoints: WebhookEndpoint[];
}

export interface WebhookEndpointResponse extends SignumApiEnvelope {
  webhook_endpoint: WebhookEndpoint;
}

export interface WebhookDeliveryListResponse extends SignumApiEnvelope {
  webhook_deliveries: WebhookDelivery[];
}
