<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDemandeDocumentValiditeMenuItem extends Command
{
    protected $signature = 'sifec:create-demande-document-validite-menu';

    protected $description = 'Créer l\'entrée de menu « Validité documents (demandes) » sous Administration';

    public function handle(): int
    {
        $exists = DB::table('tr_menu_item')
            ->where('route_name', 'admin.demande-document-config.edit')
            ->exists();

        if ($exists) {
            $this->warn('Le menu « Validité documents (demandes) » existe déjà.');

            return 1;
        }

        $parentAdmin = DB::table('tr_menu_item')
            ->where('code_menu_item', 'MENU_ADM')
            ->first();

        if (! $parentAdmin) {
            $this->error('Menu parent MENU_ADM (Administration) introuvable.');

            return 1;
        }

        $maxOrder = DB::table('tr_menu_item')
            ->where('code_parent', 'MENU_ADM')
            ->max('sort_order') ?? 0;

        DB::table('tr_menu_item')->insert([
            'code_menu_item' => 'MENU_DD_VALIDITE',
            'libelle' => 'Validité documents (demandes)',
            'route_name' => 'admin.demande-document-config.edit',
            'lib_icone' => 'fas fa-calendar-check',
            'code_parent' => 'MENU_ADM',
            'sort_order' => $maxOrder + 1,
            'is_group' => 0,
            'permission_gate' => 'module.admin.demande_document.parametres',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info('Menu créé : Administration → Validité documents (demandes)');
        $this->line('  Route : admin.demande-document-config.edit');
        $this->line('  Permission : module.admin.demande_document.parametres (FNC_0068)');

        return 0;
    }
}
