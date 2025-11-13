<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class DocumentImporteTribunalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $declaration;
    public $typeDocument;

    public function __construct($declaration, $typeDocument)
    {
        $this->declaration = $declaration;
        $this->typeDocument = $typeDocument;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        Log::channel('sifec')->info('[Notification] DocumentImporteTribunalNotification', [
            'declaration_id' => $this->declaration->getKey(),
            'type_document' => $this->typeDocument,
            'type_declaration' => $this->declaration->type_declaration,
            'numero_certificat' => $this->declaration->numero_certificat ?? null,
            'numero_req' => $this->declaration->numero_req ?? null,
        ]);
        return [
            'message' => 'Un document (' . ($this->typeDocument === 'requisition' ? 'Réquisition' : 'Jugement') . ') a été importé par le tribunal pour le '.$this->declaration->type_declaration . ($this->declaration->numero_certificat ?? $this->declaration->numero_req),
            'declaration_id' => $this->declaration->getKey(),
            'type_document' => $this->typeDocument,
            'module' => $this->declaration->module ?? null,
        ];
    }
}
