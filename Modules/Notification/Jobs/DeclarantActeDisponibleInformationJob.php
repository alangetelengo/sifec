<?php

namespace Modules\Notification\Jobs;

use App\Mail\ActeDisponibleDeclarantMailable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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
        public string $subjectLine,
        public ?string $attachmentPath = null,
        public ?string $attachmentName = null,
    ) {}

    public function handle(): void
    {
        foreach ($this->emails as $email) {
            $email = trim((string) $email);
            if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }
            try {
                Mail::to($email)->send(new ActeDisponibleDeclarantMailable(
                    $this->subjectLine,
                    $this->corpsMessage,
                    $this->attachmentPath,
                    $this->attachmentName
                ));
            } catch (\Throwable $e) {
                Log::channel('sifec')->warning('Echec envoi e-mail acte disponible (déclarant)', [
                    'email_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $email),
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
