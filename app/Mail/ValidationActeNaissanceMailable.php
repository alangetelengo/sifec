<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ValidationActeNaissanceMailable extends Mailable
{
    use Queueable, SerializesModels;
    private $maire;
    private $nombre;
    private $code_otp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($maire,$nombre,$code_otp)
    {
        $this->maire = $maire;
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
        return $this->subject("Validation acte naissance")->from(env("MAIL_USERNAME"), env("SUBJECT"))->markdown('mail.validation_acte_naissance')->with([
           "maire" => $this->maire,
           "nombre" => $this->nombre,
           "code_otp" => $this->code_otp
        ]);

    }
}
