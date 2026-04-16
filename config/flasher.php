<?php

declare(strict_types=1);

use Flasher\Prime\Configuration;

// Préfixe des URLs d’assets (scripts/CSS) : obligatoire si l’app est servie sous un sous-chemin
// (ex. APP_URL=http://host/sifec → préfixe /sifec). Sinon les navigateurs chargent /vendor/flasher/… à la racine du domaine (404).
$flasherPublicPath = env('FLASHER_PUBLIC_PATH');
if (! is_string($flasherPublicPath) || $flasherPublicPath === '') {
    $appUrl = env('APP_URL', '');
    $path = is_string($appUrl) && $appUrl !== '' ? parse_url($appUrl, PHP_URL_PATH) : null;
    $flasherPublicPath = (is_string($path) && $path !== '' && $path !== '/') ? rtrim($path, '/') : '';
} else {
    $flasherPublicPath = rtrim($flasherPublicPath, '/');
}

/*
 * Default PHPFlasher configuration for Laravel.
 *
 * This configuration file defines the default settings for PHPFlasher when
 * used within a Laravel application. It uses the Configuration class from
 * the core PHPFlasher library to establish type-safe configuration.
 *
 * @return array<string, mixed> PHPFlasher configuration
 */
return Configuration::from([
    // Default notification adapter (toastr = même rendu que le helper toastr())
    'default' => 'toastr',

    // Main script path
    'main_script' => '/vendor/flasher/flasher.min.js',

    // Prefix prepended to every flasher asset URL. Useful when the app is
    // served from a subdirectory (e.g. '/app') or a separate asset host
    // (e.g. 'https://cdn.example.com'). Leave empty when mounted at the root.
    // Dérivé de APP_URL par défaut ; surcharge possible via FLASHER_PUBLIC_PATH dans .env
    'public_path' => $flasherPublicPath,

    // Stylesheet files
    'styles' => [
        '/vendor/flasher/flasher.min.css',
    ],

    // Global notification options
    // 'options' => [
    //     'timeout' => 5000,
    //     'position' => 'top-right',
    // ],

    // Rendu explicite via @flasher_render dans le layout (évite double chargement avec les scripts toastr du thème)
    'inject_assets' => false,

    // Enable automatic message translation
    'translate' => true,

    // URL patterns to exclude from asset injection and flash bag conversion
    'excluded_paths' => [],

    // Désactivé : laisser les clés Laravel success/error à la session pour Blade / Toastr SIFEC.
    // Important : `[]` est normalisé par PHPFlasher en mapping par défaut (SessionMiddleware actif).
    // Seule la valeur `false` désactive le SessionMiddleware qui sinon retire success/error avant AppendSifecFlashQueryToRedirects.
    'flash_bag' => false,

    // Filter criteria for notifications
    // 'filter' => [
    //     'limit' => 5,
    // ],

    // Predefined notification configurations
    // 'presets' => [
    //     'entity_saved' => [
    //         'type' => 'success',
    //         'title' => 'Entity saved',
    //         'message' => 'Entity saved successfully',
    //     ],
    // ],
]);
