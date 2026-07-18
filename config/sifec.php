<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Signature électronique GUOT (SIGNELEC)
    |--------------------------------------------------------------------------
    | Seuls ces postes (tr_fonction) sont éligibles à l’enrôlement PKI.
    | Les agents de saisie ne portent pas le certificat Signum.
    */
    'guot' => [
        'signataire_fonctions' => [
            'FONC_0002', // Officier d'état civil (responsable CEC)
            'FONC_0021', // Consule
            'FONC_0009', // Président du tribunal (paraphe)
        ],
        'signataire_description' => 'Officier d’état civil, Consule, Président du tribunal — pas les agents de saisie.',
    ],

    'demande_document' => [
        /** Utilisé uniquement à la création de la ligne en base si absente (l'admin modifie via l'écran dédié). */
        'validite_mois_par_defaut' => 3,
    ],
    'sms' => [
        /** wirepick | infobip (Infobip : voir aussi config/technodev.php) */
        'provider' => env('SIFEC_SMS_PROVIDER', 'wirepick'),
        'sender_id' => env('SIFEC_SMS_SENDER_ID', 'ETAT-CIVIL'),
        'wirepick' => [
            'client' => env('SIFEC_SMS_WIREPICK_CLIENT', 'mukinayiseth'),
            'password' => env('SIFEC_SMS_WIREPICK_PASSWORD', '123456789@123456789'),
            'endpoint' => env('SIFEC_SMS_WIREPICK_ENDPOINT', 'https://api.wirepick.com/httpsms/send'),
        ],
        'templates' => [
            'actions' => [
                'creation_registre' => 'M.(Mme) :tribunal, un registre de :type_registre numero :code_registre provenance :cec est en attente de validation',
                'paraphage_registre' => 'M (Mme) :tribunal, votre code pour parapher le registre numero :code_registre est :code_otp',
                'paraphage_registre_bulk' => 'M (Mme) :tribunal, votre code :code_otp paraphe :nombre registre(s) d\'état civil (validité :minutes min).',

                'declaration_naissance' => 'M.(Mme) :declarant, une declaration de naissance de :enfant, dont vous etes declarant a ete emis avec succes, le numero de la declaration est :code_declaration',
                'acte_naissance' => "M.(Mme) :declarant, l'acte de la declaration de naissance :code_acte_naissance dont vous etes declarant est disponible,priere de vous rapprocher du centre d'etat civil :libCec",
                'validation_acte_naissance' => 'M (Mme) :maire, votre code pour valider :nombre acte de naissance est :code_otp',

                'declaration_deces' => 'M.(Mme) :declarant, une declaration de deces de :defunt, dont vous etes declarant a ete emis avec succes, le numero de la declaration est :code_declaration',
                'acte_deces' => "M.(Mme) :declarant, l'acte de la declaration de deces :code_acte_deces de :defunt,  dont vous etes declarant est disponible,priere de vous rapprocher du centre d'etat civil :libCec",
                'validation_acte_deces' => "M (Mme) :pompe_funebre, votre code pour valider l'acte de deces :code_acte_deces est :code_otp",
                'validation_acte_mariages' => "M (Mme) :maire, votre code pour valider l'acte de mariage :code_declaration_mariages est :code_otp",
                'acte_mariage' => "M.(Mme) :declarant, l'acte de la declaration de mariage :code_acte_mariage dont vous etes declarant est disponible",

                'validation_multiples_acte_naissances' => 'M (Mme) :maire, votre code pour valider :nombre actes de naissance  est :code_otp',
                'validation_multiples_acte_deces' => 'M (Mme) :pompe_funebre, votre code pour valider :nombre actes de deces  est :code_otp',
                'validation_multiples_acte_mariages' => 'M (Mme) :maire, votre code pour valider :nombre actes de mariage  est :code_otp',
                'demande_document' => "M (Mme) :nom_demandeur, votre demande de :type_document d'acte de :type_acte a ete prise en compte. le code pour valider le telechargement est :code_otp.",
                'document_signe' => 'M.(Mme) :demandeur, votre demande de :type_document de :type_acte (N° :numero_acte) est signée. Le document est prêt pour le retrait au :libCec. Code demande: :code_demande',
            ],
        ],
    ],
    'acsi_paiement' => [
        'application_unique_key' => '2294e83d-a5d8-4bd2-8f74-b854dbbdc623',
        'actions' => [
            'login' => [
                'endpoint' => 'https://app.acsipayement.com/api/v1/login',
                'body' => [
                    'email' => 'manzanza.florendo@gmail.com',
                    'application_unique_key' => '2294e83d-a5d8-4bd2-8f74-b854dbbdc623',
                ],
            ],
            'debit' => [
                'endpoint' => 'https://app.acsipayement.com/api/v1/paiement',
                'body' => [
                    'phone' => '',
                    'amount' => '',
                    'application_unique_key' => '2294e83d-a5d8-4bd2-8f74-b854dbbdc623',
                ],
                'header' => [
                    'Authorization' => 'Bearer :token',
                    'Accept' => 'application/json',
                ],
            ],
            'status' => [
                'endpoint' => 'https://app.acsipayement.com/api/v1/statut-paiement',
                'body' => [
                    'transid' => '',
                ],
                'hearder' => [
                    'Authorization' => 'Bearer :token',
                    'Accept' => 'application/json',
                ],
            ],
        ],
    ],

];
