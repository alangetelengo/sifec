<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedTypeDocumentDemande extends Command
{
    protected $signature = 'demande-document:seed-types';

    protected $description = 'Insérer les types de documents demandés';

    public function handle()
    {
        $this->info("=== Insertion des types de documents ===\n");

        // Vérifier si déjà présents
        $existing = DB::table('tr_type_document_demande')->count();

        if ($existing > 0) {
            $this->info("✓ Types de documents déjà présents ({$existing} enregistrements)");

            DB::table('tr_type_document_demande')->get()->each(function ($type) {
                $this->line("  - {$type->code_type_document_demande}: {$type->lib_type_document_demande}");
            });

            return 0;
        }

        // Insérer les types de documents
        $types = [
            [
                'code_type_document_demande' => 'TDD_0001',
                'lib_type_document_demande' => 'Copie',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code_type_document_demande' => 'TDD_0002',
                'lib_type_document_demande' => 'Extrait',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tr_type_document_demande')->insert($types);

        $this->info('✅ Types de documents insérés:');
        foreach ($types as $type) {
            $this->line("  - {$type['code_type_document_demande']}: {$type['lib_type_document_demande']}");
        }

        return 0;
    }
}
