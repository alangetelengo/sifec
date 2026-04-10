<?php

namespace Modules\Notification\Jobs;

use App\Mail\ActeDisponibleDeclarantMailable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Envoie le même libellé que le SMS au déclarant sur ses e-mails (pro + perso valides).
 * Exécution synchrone (pas ShouldQueue) : avec QUEUE_CONNECTION=database, un job en file
 * ne part pas sans `php artisan queue:work` ; ici le mail doit partir tout de suite.
 */
class DeclarantActeDisponibleInformationJob
{
    use Dispatchable, SerializesModels;

    /**
     * @param  list<string>  $emails
     */
    public function __construct(
        public array $emails,
        public string $corpsMessage,
        public string $subjectLine
    ) {}

    public function handle(): void
    {
        foreach ($this->emails as $email) {
            $email = trim((string) $email);
            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            Mail::to($email)->send(new ActeDisponibleDeclarantMailable(
                $this->subjectLine,
                $this->corpsMessage
            ));
        }
    }
}
