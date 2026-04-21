<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateTarifsMenuItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sifec:create-tarifs-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer l\'entrée de menu pour la gestion des tarifs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Vérifier si le menu existe déjà
        $exists = DB::table('tr_menu_item')
            ->where('route_name', 'admin.tarifs.index')
            ->exists();

        if ($exists) {
            $this->warn('Le menu "Gestion des Tarifs" existe déjà!');

            return 1;
        }

        // Trouver le parent (menu Administration)
        $parentAdmin = DB::table('tr_menu_item')
            ->where('code_menu_item', 'MENU_ADM')
            ->first();

        if (! $parentAdmin) {
            $this->error('Menu parent "MENU_ADM" (Administration) non trouvé!');
            $this->info('Menus disponibles:');
            DB::table('tr_menu_item')
                ->where('is_group', 1)
                ->select('code_menu_item', 'libelle')
                ->get()
                ->each(fn ($m) => $this->line("  - {$m->code_menu_item}: {$m->libelle}"));

            return 1;
        }

        // Trouver le prochain ordre disponible
        $maxOrder = DB::table('tr_menu_item')
            ->where('code_parent', 'MENU_ADM')
            ->max('sort_order') ?? 0;

        // Créer l'entrée de menu
        DB::table('tr_menu_item')->insert([
            'code_menu_item' => 'MENU_TARIFS',
            'libelle' => 'Gestion des Tarifs',
            'route_name' => 'admin.tarifs.index',
            'lib_icone' => 'fas fa-tags',
            'code_parent' => 'MENU_ADM',
            'sort_order' => $maxOrder + 1,
            'is_group' => 0,
            'permission_gate' => 'module.admin.tarifs',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info('✅ Menu "Gestion des Tarifs" créé avec succès!');
        $this->line('  → Emplacement: Menu Administration');
        $this->line('  → Route: admin.tarifs.index');
        $this->line('  → Permission: module.admin.tarifs');
        $this->line('  → Icône: fas fa-tags');

        $this->newLine();
        $this->info('🎉 Le menu est maintenant visible pour les utilisateurs avec la permission FNC_0059!');

        return 0;
    }
}
