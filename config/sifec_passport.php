<?php

use App\Http\Controllers\Api\UserController;

/**
 * Scopes OAuth2 Passport (tokens personnels émis via POST /api/v1/login).
 *
 * @see AppServiceProvider : Passport::tokensCan()
 * @see UserController::login
 */
return [

    'scopes' => [
        'sifec-api' => 'Accès général à l’API authentifiée (intégrations métier).',
        'mariage-ban' => 'Consulter le journal BAN (déclarations de mariage sans acte).',
        'signatures-mariage' => 'Enregistrer les signatures d’acte de mariage (périphériques / tablettes).',
    ],

    /**
     * Scopes attribués à chaque token personnel créé après login API réussi.
     * Ajustez pour restreindre certains comptes (ex. jeton dédié sans mariage-ban).
     */
    'default_personal_token_scopes' => [
        'sifec-api',
        'mariage-ban',
        'signatures-mariage',
    ],

];
