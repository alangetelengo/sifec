<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuDemandeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Les entrées « Demandes de documents » sont définies dans
     * database/seeders/Data/menu_items_definitions.php (MENU_MC_DEM_DOC, MENU_CEC_DEM_DOC)
     * et chargées par MenuItemSeeder. Ne pas réinsérer ici : risque de doublons et
     * toute ligne ajoutée manuellement est effacée au prochain MenuItemSeeder (truncate).
     */
    public function run(): void
    {
        $this->command?->info(
            'Menu « Demandes de documents » : source = menu_items_definitions.php + php artisan db:seed --class=MenuItemSeeder'
        );
    }
}
