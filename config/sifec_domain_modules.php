<?php

/**
 * Associe le namespace du contrôleur d’une route nommée au code_module (tr_module).
 * Préfixe le plus long gagnant. Utilisé par le menu latéral et le middleware
 * pour masquer / refuser l’accès quand etat_module ≠ Activé.
 *
 * route_to_module : routes dont le contrôleur n’est pas sous Modules\… (ex. API App\Http\Controllers\Api).
 */
return [
    'namespace_to_module' => [
        'Modules\\Naissance\\' => 'MOD_0002',
        'Modules\\Deces\\' => 'MOD_0003',
        'Modules\\Mariage\\' => 'MOD_0004',
    ],

    'route_to_module' => [
        'copieActeNaissance' => 'MOD_0002',
        'acteNaissance.displayExtraitActe' => 'MOD_0002',
        'duplicataActeNaissance' => 'MOD_0002',
        'copieActeNaissancePortail' => 'MOD_0002',
        'acteNaissance.displayExtraitActePortail' => 'MOD_0002',
        'acteNaissance.displayEtat' => 'MOD_0002',
        'etatRecuNaissance' => 'MOD_0002',
        'etatRecuNaissanceNA' => 'MOD_0002',

        'copieActeDeces' => 'MOD_0003',
        'acteDeces.displayExtrait' => 'MOD_0003',
        'duplicataActeDeces' => 'MOD_0003',
        'acteDeces.displayEtat' => 'MOD_0003',
        'etatRecuDeces' => 'MOD_0003',
        'etatRecuDecesNA' => 'MOD_0003',

        'acteMariage.displayEtat' => 'MOD_0004',
        'banMariage' => 'MOD_0004',
        'etatRecuMariage' => 'MOD_0004',
        'etatRecuMariageNA' => 'MOD_0004',
    ],
];
