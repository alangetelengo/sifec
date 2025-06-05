<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;

class ValidationRegistreMailable extends Mailable
{
    use Queueable;
    use SerializesModels;
    use InteractsWithQueue;
    private $tribunal;
    private $otp;
    private $code_registre;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tribunal, $otp, $code_registre)
    {
        $this->tribunal = $tribunal;
        $this->otp = $otp;
        $this->code_registre = $code_registre;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("sifec@techno-dev.com", "Système SIFEC")->subject("Paraphage Registre")->markdown('mail.validation_registre')->with(["tribunal"=>$this->tribunal,"otp"=>$this->otp,"code_registre"=>$this->code_registre]);
    }
}

