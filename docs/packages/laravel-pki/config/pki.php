<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trust API base URL
    |--------------------------------------------------------------------------
    | The URL of the running trust-api service.
    */
    'url' => env('PKI_TRUST_API_URL', 'http://localhost:8080'),

    /*
    |--------------------------------------------------------------------------
    | OpenBao base URL
    |--------------------------------------------------------------------------
    | The URL of the OpenBao server (used for PKI admin links: CRL, OCSP, CA).
    */
    'openbao_url' => env('OPENBAO_URL', 'http://localhost:8081'),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    | Authentication key sent as a bearer token to trust-api.
    */
    'api_key' => env('PKI_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | HTTP timeout (seconds)
    |--------------------------------------------------------------------------
    */
    'timeout' => env('PKI_TIMEOUT', 10),

    /*
    |--------------------------------------------------------------------------
    | TLS CA bundle
    |--------------------------------------------------------------------------
    | Optional PEM bundle used by cURL to verify trust-api TLS certificates.
    | Keep TLS verification enabled; use this when trust-api is issued by an
    | internal or private CA.
    */
    'ca_bundle' => env('PKI_CA_BUNDLE') ?: null,

    /*
    |--------------------------------------------------------------------------
    | Webhook signing secret
    |--------------------------------------------------------------------------
    | Secret one-shot renvoyé par trust-api à l'enregistrement du point de
    | terminaison webhook (trust-api:register-webhook). Utilisé pour vérifier
    | l'en-tête Signum-Signature des requêtes entrantes.
    */
    'webhook_secret' => env('TRUST_API_WEBHOOK_SECRET', ''),

];
