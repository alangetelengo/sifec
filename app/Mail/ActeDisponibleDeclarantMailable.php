<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActeDisponibleDeclarantMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $corpsMessage
    ) {}

    public function build()
    {
        $fromName = env('SUBJECT', config('app.name'));

        return $this->subject($this->subjectLine)
            ->from(env('MAIL_USERNAME'), $fromName)
            ->markdown('mail.acte_disponible_declarant', [
                'corps' => $this->corpsMessage,
            ]);
    }
}
