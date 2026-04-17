<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mime\Address;

/**
 * Trace centralisée des e-mails dans storage/logs/sifec.log (canal « sifec »).
 */
class LogMailToSifec
{
    public function handleMessageSending(MessageSending $event): void
    {
        $this->writeLog('envoi_en_cours', $event->message, $event->data);
    }

    public function handleMessageSent(MessageSent $event): void
    {
        $this->writeLog('envoye', $event->message, $event->data);
    }

    /**
     * @param  object  $message  Symfony Email (Laravel 9+), plus Swift_Message historique
     */
    private function writeLog(string $phase, $message, array $data): void
    {
        if (! is_object($message) || ! method_exists($message, 'getSubject')) {
            return;
        }

        Log::channel('sifec')->info('Mail ('.$phase.')', [
            'subject' => $message->getSubject(),
            'from' => $this->formatAddresses($message->getFrom() ?: []),
            'to' => $this->formatAddresses($message->getTo() ?: []),
            'cc' => $this->formatAddresses($message->getCc() ?: []),
            'mailable' => $this->guessMailableClass($data),
        ]);
    }

    private function guessMailableClass(array $data): ?string
    {
        foreach ($data as $value) {
            if ($value instanceof Mailable) {
                return get_class($value);
            }
        }

        return null;
    }

    /**
     * @param  array<int|string, mixed>  $addresses
     * @return list<array{email_masque: string, name: string|null}>
     */
    private function formatAddresses(array $addresses): array
    {
        $out = [];
        foreach ($addresses as $email => $name) {
            if ($name instanceof Address) {
                $out[] = [
                    'email_masque' => $this->maskEmail($name->getAddress()),
                    'name' => $this->optionalDisplayName($name->getName()),
                ];

                continue;
            }
            if (is_int($email)) {
                $email = $name;
                $name = null;
            }
            if ($email instanceof Address) {
                $out[] = [
                    'email_masque' => $this->maskEmail($email->getAddress()),
                    'name' => $this->optionalDisplayName($email->getName()),
                ];

                continue;
            }
            $out[] = [
                'email_masque' => $this->maskEmail((string) $email),
                'name' => is_string($name) && $name !== '' ? $name : null,
            ];
        }

        return $out;
    }

    private function optionalDisplayName(string $name): ?string
    {
        return $name !== '' ? $name : null;
    }

    private function maskEmail(string $email): string
    {
        $email = trim($email);
        if ($email === '' || strpos($email, '@') === false) {
            return '(n/d)';
        }

        return (string) preg_replace('/(^.).*(@.*$)/u', '$1…$2', $email);
    }
}
