import { HttpClient } from "./http.js";
import type {
  RequestOptions,
  SignumClientOptions,
  WebhookDeliveryListResponse,
  WebhookEndpointCreateRequest,
  WebhookEndpointListResponse,
  WebhookEndpointResponse,
} from "./types.js";

export interface WebhookDeliveryFilters {
  event_type?: string;
}

export class SignumWebhookClient {
  private readonly http: HttpClient;

  constructor(options: SignumClientOptions) {
    if (!options.apiKey) {
      throw new TypeError("SignumWebhookClient requires an apiKey.");
    }
    this.http = new HttpClient(options);
  }

  listEndpoints(options?: RequestOptions): Promise<WebhookEndpointListResponse> {
    return this.http.get<WebhookEndpointListResponse>("/v1/webhooks/endpoints", options);
  }

  createEndpoint(input: WebhookEndpointCreateRequest, options?: RequestOptions): Promise<WebhookEndpointResponse> {
    return this.http.post<WebhookEndpointResponse>("/v1/webhooks/endpoints", input, options);
  }

  listDeliveries(filters: WebhookDeliveryFilters = {}, options?: RequestOptions): Promise<WebhookDeliveryListResponse> {
    const params = new URLSearchParams();
    if (filters.event_type) {
      params.set("event_type", filters.event_type);
    }
    const query = params.toString();
    return this.http.get<WebhookDeliveryListResponse>(`/v1/webhooks/deliveries${query ? `?${query}` : ""}`, options);
  }
}

export function createSignumWebhookClient(options: SignumClientOptions): SignumWebhookClient {
  return new SignumWebhookClient(options);
}
