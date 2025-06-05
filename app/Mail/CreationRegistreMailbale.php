<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreationRegistreMailbale extends Mailable
{
    use Queueable, SerializesModels;
    private $tribunal;
    private $type_registre;
    private $code_registre;
    private $cec;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tribunal,$type_registre,$code_registre,$cec)
    {
        $this->tribunal = $tribunal;
        $this->type_registre = $type_registre;
        $this->code_registre = $code_registre;
        $this->cec = $cec;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Création Registre")->from(env("MAIL_FROM_ADDRESS"),env("SUBJECT"))->markdown('mail.creation_registre')->with([
            "tribunal"=>$this->tribunal,
            "type_registre"=>$this->type_registre,
            "code_registre"=>$this->code_registre,
            "cec"=>$this->cec
        ]);
    }
}
