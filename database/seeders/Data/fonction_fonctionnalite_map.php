<?php

/**
 * Attachements tr_ff : code_fonction (FONC_*) → codes fonctionnalité (FNC_*).
 * Aligné sur FonctionSeeder et database/seeders/Data/fonctionnalites_definitions.php.
 * Les FNC_0035–FNC_0037 (visa d’acte) peuvent rester absents : ils sont « Désactivé » et filtrés côté Gate.
 */
$cecEtatCivil = [
    'FNC_0009', 'FNC_0032', 'FNC_0033', 'FNC_0034',
    'FNC_0002', 'FNC_0003', 'FNC_0029',
    'FNC_0012', 'FNC_0013', 'FNC_0014', 'FNC_0015',
    'FNC_0016', 'FNC_0017', 'FNC_0019', 'FNC_0020',
    'FNC_0023', 'FNC_0024', 'FNC_0025', 'FNC_0026', 'FNC_0027',
    'FNC_0028', 'FNC_0030', 'FNC_0031', 'FNC_0046',
    'FNC_0040', 'FNC_0041', 'FNC_0044', 'FNC_0047',
    'FNC_0021', 'FNC_0022', 'FNC_0045',
];

$formationSanitaire = [
    'FNC_0006',
    'FNC_0012', 'FNC_0013',
    'FNC_0016', 'FNC_0019',
    'FNC_0040',
];

$centreHygiene = [
    'FNC_0007',
    'FNC_0003', 'FNC_0034',
    'FNC_0018',
    'FNC_0016', 'FNC_0019',
];

$pompesFunebres = [
    'FNC_0008',
    'FNC_0003', 'FNC_0034',
    'FNC_0016', 'FNC_0017', 'FNC_0019', 'FNC_0020',
    'FNC_0026', 'FNC_0027',
    'FNC_0022', 'FNC_0045',
    'FNC_0028', 'FNC_0021',
];

$tribunal = [
    'FNC_0010',
    'FNC_0042', 'FNC_0022', 'FNC_0045',
    'FNC_0047',
];

$tribunalPresident = [
    'FNC_0010',
    'FNC_0021', 'FNC_0028',
    'FNC_0042', 'FNC_0022',
    'FNC_0047',
];

$ambassade = [
    'FNC_0038',
    'FNC_0028',
    'FNC_0032', 'FNC_0002',
    'FNC_0012', 'FNC_0013', 'FNC_0014', 'FNC_0015',
    'FNC_0034', 'FNC_0003',
    'FNC_0016', 'FNC_0017', 'FNC_0019', 'FNC_0020',
];

$hautFonctionnaire = array_values(array_unique(array_merge(
    ['FNC_0005', 'FNC_0009', 'FNC_0032', 'FNC_0033', 'FNC_0034', 'FNC_0002', 'FNC_0003', 'FNC_0029'],
    ['FNC_0012', 'FNC_0013', 'FNC_0014', 'FNC_0015', 'FNC_0016', 'FNC_0017', 'FNC_0019', 'FNC_0020'],
    ['FNC_0023', 'FNC_0024', 'FNC_0025', 'FNC_0026', 'FNC_0027', 'FNC_0028', 'FNC_0030', 'FNC_0031', 'FNC_0046'],
    ['FNC_0042', 'FNC_0044', 'FNC_0022', 'FNC_0047']
)));

return [
    // 1 Sous-préfet
    'FONC_0001' => array_values(array_unique(array_merge(
        ['FNC_0005', 'FNC_0009', 'FNC_0032', 'FNC_0034', 'FNC_0002', 'FNC_0003', 'FNC_0012', 'FNC_0013', 'FNC_0016', 'FNC_0019']
    ))),
    // 2–4 Officier / délégué / agent mairie + 15 DEC + 16 Chef de service → même socle CEC
    'FONC_0002' => $cecEtatCivil,
    'FONC_0003' => $cecEtatCivil,
    'FONC_0004' => $cecEtatCivil,
    'FONC_0015' => $cecEtatCivil,
    'FONC_0016' => $cecEtatCivil,
    // 5 Agent pompes funèbres
    'FONC_0005' => $pompesFunebres,
    // 6 Agent formation sanitaire
    'FONC_0006' => $formationSanitaire,
    // 7 Agent centre d'hygiène
    'FONC_0007' => $centreHygiene,
    // 8 Agent tribunal
    'FONC_0008' => $tribunal,
    // 9 Président du tribunal
    'FONC_0009' => $tribunalPresident,
    // 10 Procureur général
    'FONC_0010' => $tribunal,
    // 11 Super administrateur : toutes les fonctionnalités présentes en base (traité dans le seeder)
    'FONC_0011' => null,
    // 12 Directeur pompes funèbres
    'FONC_0012' => array_values(array_unique(array_merge($pompesFunebres, ['FNC_0011']))),
    // 13 DGAT
    'FONC_0013' => ['FNC_0011', 'FNC_0004', 'FNC_0005', 'FNC_0009', 'FNC_0032', 'FNC_0033', 'FNC_0034'],
    // 14 Agent mairie centrale
    'FONC_0014' => [
        'FNC_0043', 'FNC_0032', 'FNC_0002',
        'FNC_0012', 'FNC_0024', 'FNC_0014',
        'FNC_0029', 'FNC_0030', 'FNC_0033', 'FNC_0031', 'FNC_0046',
        'FNC_0028',
    ],
    // 17 Agent bureau d'enregistrement de décès
    'FONC_0017' => [
        'FNC_0009', 'FNC_0034', 'FNC_0003',
        'FNC_0016', 'FNC_0017', 'FNC_0019',
    ],
    // 18 Procureur de la République
    'FONC_0018' => array_values(array_unique(array_merge(
        $tribunal,
        ['FNC_0034', 'FNC_0003', 'FNC_0016', 'FNC_0019']
    ))),
    // 19 Agent ambassade
    'FONC_0019' => $ambassade,
    // 20 Agent sanitaire naissance — même socle que formation sanitaire (FONC_0006)
    'FONC_0020' => $formationSanitaire,
    // 21 Consule
    'FONC_0021' => $ambassade,
    // 22–23 Gouverneur, Ministre
    'FONC_0022' => $hautFonctionnaire,
    'FONC_0023' => $hautFonctionnaire,
];
