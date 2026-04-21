<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MoveDemandeDocumentMenu extends Command
{
    protected $signature = 'demande-document:move-menu';

    protected $description = 'Déplacer le menu Demandes de documents vers le menu État Civil';

    public function handle()
    {
        // Trouver le menu Demandes de documents
        $menu = DB::table('tr_menu_item')
            ->where('route_name', 'demandeDocument.index')
            ->first();

        if (! $menu) {
            $this->error('Menu non trouvé!');

            return 1;
        }

        $this->info('Menu actuel:');
        $this->line("Code: {$menu->code_menu_item}");
        $this->line('Parent actuel: '.($menu->code_parent ?? 'Aucun'));

        // Chercher le menu ETAT CIVIL ou un menu groupe approprié
        $menuEtatCivil = DB::table('tr_menu_item')
            ->where('is_group', 1)
            ->where(function ($q) {
                $q->where('libelle', 'like', '%ETAT CIVIL%')
                    ->orWhere('libelle', 'like', '%État Civil%')
                    ->orWhere('code_menu_item', 'MENU_CEC');
            })
            ->whereNull('code_parent')
            ->first();

        if (! $menuEtatCivil) {
            // Essayer de trouver n'importe quel menu groupe principal
            $menuEtatCivil = DB::table('tr_menu_item')
                ->where('is_group', 1)
                ->whereNull('code_parent')
                ->first();
        }

        if ($menuEtatCivil) {
            $this->info("\nNouveau parent trouvé: {$menuEtatCivil->code_menu_item} - {$menuEtatCivil->libelle}");

            // Déterminer le nouvel ordre
            $maxOrder = DB::table('tr_menu_item')
                ->where('code_parent', $menuEtatCivil->code_menu_item)
                ->max('sort_order') ?? 0;

            // Mettre à jour le menu
            DB::table('tr_menu_item')
                ->where('code_menu_item', $menu->code_menu_item)
                ->update([
                    'code_parent' => $menuEtatCivil->code_menu_item,
                    'sort_order' => $maxOrder + 1,
                    'updated_at' => now(),
                ]);

            $this->info("\n✅ Menu déplacé avec succès!");
            $this->info("Nouveau parent: {$menuEtatCivil->code_menu_item}");
            $this->info('Nouvel ordre: '.($maxOrder + 1));
        } else {
            // Mettre le menu au niveau racine
            $this->warn('Aucun menu parent trouvé, mise au niveau racine');

            DB::table('tr_menu_item')
                ->where('code_menu_item', $menu->code_menu_item)
                ->update([
                    'code_parent' => null,
                    'sort_order' => 999,
                    'updated_at' => now(),
                ]);

            $this->info("\n✅ Menu placé au niveau racine!");
        }

        $this->info("\nVeuillez rafraîchir votre page pour voir le changement.");

        return 0;
    }
}
