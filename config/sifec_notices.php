<?php

/**
 * Messages associés à ?sifec_notice= (quand le texte exact n’est pas passé en query string).
 */
return [
    'messages' => [
        'default' => 'Opération effectuée avec succès.',
        'saved' => 'Enregistrement effectué avec succès.',
        'created' => 'Enregistrement créé avec succès.',
        'updated' => 'Modification effectuée avec succès.',
        'deleted' => 'Suppression effectuée avec succès.',
        // Codes historiques page « fonctions »
        'perms' => 'Les fonctionnalités ont été enregistrées avec succès.',
    ],
    /** Longueur max du texte passé en ?sifec_inline= (caractères UTF-8) */
    'max_inline_length' => 220,

    /**
     * Si true : ne propage le flash en query/cookie que vers des redirections dont l’hôte correspond
     * à la requête / APP_URL / internal_redirect_hosts (plus sûr si vous redirigez vers des domaines tiers).
     * Si false (défaut, recommandé Laragon / http://sifec) : propage sur toute redirection HTTP(S) — évite
     * les faux « externes » (hôte vhost ≠ APP_URL, proxy, etc.).
     */
    'strict_flash_redirect_host_check' => filter_var(
        env('SIFEC_FLASH_REDIRECT_STRICT', false),
        FILTER_VALIDATE_BOOL
    ),

    /**
     * Hôtes additionnels pour le mode strict (voir strict_flash_redirect_host_check).
     * Peut aussi être rempli via SIFEC_INTERNAL_REDIRECT_HOSTS=sifec,192.168.0.10
     *
     * @var array<int, string>
     */
    'internal_redirect_hosts' => array_values(array_filter(array_map(
        static fn (string $h): string => strtolower(trim($h)),
        array_filter(explode(',', (string) env('SIFEC_INTERNAL_REDIRECT_HOSTS', '')))
    ))),
];
