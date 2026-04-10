<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistreValideParTribunalMailable extends Mailable
{
    use Queueable, SerializesModels;

    public string $tribunalLib;

    public string $typeRegistre;

    public string $numeroOrdreRegistre;

    public string $cecLib;

    public function __construct(string $tribunalLib, string $typeRegistre, string $numeroOrdreRegistre, string $cecLib)
    {
        $this->tribunalLib = $tribunalLib;
        $this->typeRegistre = $typeRegistre;
        $this->numeroOrdreRegistre = $numeroOrdreRegistre;
        $this->cecLib = $cecLib;
    }

    public function build()
    {
        return $this
            ->subject('SIFEC — Registre validé par le tribunal')
            ->from(env('MAIL_FROM_ADDRESS', 'noreply@example.com'), env('SUBJECT', 'SIFEC'))
            ->markdown('mail.registre_valide_par_tribunal');
    }
}
