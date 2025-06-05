<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ValidationActeDeceMailable extends Mailable
{
    use Queueable, SerializesModels;
    private $directeur_pompe_funebre;
    private $code_declaration_deces;
    private $nombre;
    private $code_otp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($directeur_pompe_funebre,$nombre,$code_otp)
    {
        $this->directeur_pompe_funebre = $directeur_pompe_funebre;
        $this->nombre = $nombre;
        $this->code_otp = $code_otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Validation acte deces")->from(env("MAIL_USERNAME"), env("SUBJECT"))->markdown('mail.validation_acte_deces')->with([
           "directeur_pompe_funebre" => $this->directeur_pompe_funebre,
           "nombre" => $this->nombre,
           "code_otp" => $this->code_otp
        ]);

    }
}
