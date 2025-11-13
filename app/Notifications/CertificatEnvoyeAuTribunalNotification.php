<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CertificatEnvoyeAuTribunalNotification extends Notification
{
    use Queueable;

    protected $certificat;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($certificat)
    {
        $this->certificat = $certificat;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => "Un certificat de non-inscription a été envoyé au tribunal.",
            'url' => route('certificatNonInscription.show', $this->certificat->code_declaration_naissance),
            'type' => 'Certificat de non-inscription',
            'date' => now()->format('d/m/Y H:i'),
        ];
    }
}
