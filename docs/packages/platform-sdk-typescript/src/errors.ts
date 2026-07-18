import type { JsonValue } from "./types.js";

export interface SignumApiErrorOptions {
  status?: number;
  code?: string;
  requestId?: string;
  details?: JsonValue;
  cause?: unknown;
}

export class SignumApiError extends Error {
  readonly status: number;
  readonly code?: string;
  readonly requestId?: string;
  readonly details?: JsonValue;

  constructor(message: string, options: SignumApiErrorOptions = {}) {
    super(message, { cause: options.cause });
    this.name = "SignumApiError";
    this.status = options.status ?? 0;
    this.code = options.code;
    this.requestId = options.requestId;
    this.details = options.details;
  }
}

export class SignumUnauthorizedError extends SignumApiError {
  constructor(message: string, options: SignumApiErrorOptions = {}) {
    super(message, options);
    this.name = "SignumUnauthorizedError";
  }
}

export class SignumNotFoundError extends SignumApiError {
  constructor(message: string, options: SignumApiErrorOptions = {}) {
    super(message, options);
    this.name = "SignumNotFoundError";
  }
}

export class SignumRateLimitError extends SignumApiError {
  constructor(message: string, options: SignumApiErrorOptions = {}) {
    super(message, options);
    this.name = "SignumRateLimitError";
  }
}

export class SignumServiceUnavailableError extends SignumApiError {
  constructor(message: string, options: SignumApiErrorOptions = {}) {
    super(message, options);
    this.name = "SignumServiceUnavailableError";
  }
}

export function isSignumApiError(error: unknown): error is SignumApiError {
  return error instanceof SignumApiError;
}
