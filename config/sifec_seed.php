<?php

/**
 * Comportement des seeders SIFEC (données de référence).
 *
 * SIFEC_SEED_TRUNCATE_FONCTIONNALITES=true (défaut) : TRUNCATE tr_fonctionnalite (+ tr_ff) puis réinsertion complète.
 * false : updateOrInsert uniquement (idempotent, adapté aux réexécutions sans tout effacer).
 */
return [
    'truncate_fonctionnalites' => env('SIFEC_SEED_TRUNCATE_FONCTIONNALITES', true),
];
