<?php

/**
 * Test SMTP : php scripts/send_test_mail.php [email]
 * Ex. : php scripts/send_test_mail.php alangetelengo87@gmail.com
 */

$to = $argv[1] ?? 'alangetelengo87@gmail.com';

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

Illuminate\Support\Facades\Mail::raw(
    'Test SIFEC — si vous lisez ce message, la configuration SMTP (Gmail) fonctionne.',
    function ($m) use ($to) {
        $m->to($to)->subject('Test SIFEC — '.config('app.name'));
    }
);

echo "Message envoyé vers : {$to}\n";
