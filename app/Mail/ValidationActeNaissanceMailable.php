<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
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
        // Même en-tête From que `ActeDisponibleDeclarantMailable` : sur votre infra le déclarant
        // reçoit les mails avec ce From ; un expéditeur différent (ex. mail.from par défaut) peut
        // être filtré par Outlook/Hotmail alors que le SMTP a pourtant accepté le message.
        $fromName = env('SUBJECT', config('app.name'));

        return $this->subject('Validation acte naissance')
            ->from(env('MAIL_USERNAME'), $fromName)
            ->markdown('mail.validation_acte_naissance')
            ->with([
                'maire' => $this->maire,
                'nombre' => $this->nombre,
                'code_otp' => $this->code_otp,
            ]);
    }
}
