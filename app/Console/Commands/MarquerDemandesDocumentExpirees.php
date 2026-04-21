<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\Mobile\Entities\DemandeDocument;

class MarquerDemandesDocumentExpirees extends Command
{
    protected $signature = 'demande-document:marquer-expirees';

    protected $description = 'Passe en statut « Expirée » les demandes de documents (copie/extrait) dont la validité est dépassée.';

    public function handle(): int
    {
        $updated = DemandeDocument::query()
            ->whereIn('statut', ['Traitée', 'Livrée'])
            ->whereNotNull('document_valide_jusquau')
            ->where('document_valide_jusquau', '<', now())
            ->update(['statut' => 'Expirée']);

        if ($updated > 0) {
            Log::channel('sifec')->info('Demandes de documents marquées expirées', ['nombre' => $updated]);
            $this->info("{$updated} demande(s) passée(s) en statut Expirée.");
        } else {
            $this->comment('Aucune demande à expirer.');
        }

        return self::SUCCESS;
    }
}
