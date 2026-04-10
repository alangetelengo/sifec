<?php

/**
 * Routage envoi certificat / dossiers décès (centre d'hygiène → CEC ou pompe funèbre).
 *
 * @see \Modules\Deces\Services\DecesDestinataireEnvoiService
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Communes avec pompe funèbre municipale unique (tous arrondissements → même PF)
    |--------------------------------------------------------------------------
    */
    'communes_pompe_funebre_centrale' => [
        [
            'code_localite_commune' => env('SIFEC_DECES_BZZ_COMMUNE_LOC', 'LOC_0026'),
            'code_institution_pompe_funebre' => env('SIFEC_DECES_BZZ_PF_INS', 'INS_0192'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Types d'institution exclus du « CEC » automatique (réception constatation)
    |--------------------------------------------------------------------------
    */
    'types_institution_exclus_reception_cec' => ['TPINS_0003', 'TPINS_0019'],

];
