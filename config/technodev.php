<?php

return [
    "image_link"=>"http://localhost/peel/upload/thumbs/",
    "after_payment_link"=>"http://localhost/peel/",
    "devise"=>"XAF",
    "payment_provider"=>[
        "momo"=>[
            "endpoints"=>[
                "token_uri"=>"https://proxy.momoapi.mtn.com/collection/token/",
                "pay_uri"=>"https://proxy.momoapi.mtn.com/collection/v1_0/requesttopay",
                "pay_status"=>"https://proxy.momoapi.mtn.com/collection/v1_0/requesttopay/"
            ],
            "headers"=>[
                "token"=>[
                    "ocp_apim_subscription_key"=>"b29460fba77c4f45b6cc4c26a18f314c",
                    "x_target_environment"=>"mtncongo"
                ],
                "api_keys"=>[
                    "user_id"=>"187c4d4d-b034-442a-8f04-412107e6b335",
                    "api_key"=>"375f4c72666640a688f5241d6b021b45"
                ]
            ]
        ],
        "airtel"=>[
            "endpoints"=>[
                "pay_uri"=>"https://www.tstcgb.com/postswitch/epay000.php",
                "pay_status"=>"http://www.tstcgb.com/switch/tst-paye-ci-status.php",
                "callback_url"=>"https://payment.noki-drive.cg/am/response"
            ],
            "static_params"=>[
                "action"=>"getID",
            ],
            "login_data"=>[
                "merchant_id"=>"NOKINOKI_SERVICE",
                "merchant_pass"=>"nnS!@@_190722@"
            ],
        ],
        "flexpay" => [
            "scope" => [
                "243"
            ],
            "endpoints" => [
                "pay_uri" => "https://backend.flexpay.cd/api/rest/v1/paymentService",
                "callback_url" => "https://techno-dev.com/momo/status"
            ],
            "headers" => [
                "authorization" => "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJcL2xvZ2luIiwicm9sZXMiOlsiTUVSQ0hBTlQiXSwiZXhwIjoxNzc2MzM1NDk0LCJzdWIiOiJhZDE5MzVlNjIzNmE2Mzc5MWQzNWFjNjY2ZDBiNzQ5MCJ9.v6NW-dK69FECYuCAGGmHfxlltbVnqQXGYWZ6kaiipnI"
            ],
            // PAYOUT Coords
            "actions" => [
                "login" => [
                    "endpoint" => "https://beta-payout.flexpaie.com/api/v1/auth/authenticate",
                    "username" => "pau.662fa739aedc1",
                    "password" => "Xe2U(*(]HM34ccC;<&o[8Wd"
                ],
                "payout" => [
                    "endpoint" => "https://beta-payout.flexpaie.com/api/v1/merchant/pay",
                    "method" => "POST",
                    "body" => [
                        "merchant" => "NOKIPAY",
                        "type" => "1",
                        "reference" => "reference",
                        "amount" => "amount",
                        "currency" => "USD",
                        "customer" => "", //téléphone 243814809740
                        "description" => "",
                        "callback_url" => ""
                    ]
                ],
                "check_balance" => [
                    "endpoint" => "https://beta-payout.flexpaie.com/api/v1/merchant/balance/NOKIPAY"
                ],
                "check_transaction" => [
                    "endpoint" => "https://beta-payout.flexpaie.com/api/v1/merchant/check/:order_number"
                ]

            ]
        ]

    ],

    "notifications"=>[
        "templates"=>[
            "order"=>"Cher :nom, votre commande referencee :reference a ete enregistree avec succes. Le total à payer est :total_payer XAF.",
            "delivery"=>""
        ]
        ],
    "sms_global"=>[
        "infobip"=>[
            "api_key"=> "App 59bef508c0696345e7529a63d9b255aa-a047a4b5-ede8-4d94-ae49-caea9276beed",
            "actions"=>[
                "send_sms"=>[
                    "send_url" => "https://mpn66j.api.infobip.com/sms/2/text/advanced"
                ]
            ]
        ]
    ]
];
