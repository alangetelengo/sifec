<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mise à jour des icônes pour différencier les menus
        
        // SANTE - garde l'icône médicale
        DB::table('tr_menu_item')
            ->where('code_menu_item', 'MENU_FS')
            ->update(['lib_icone' => 'flaticon-381-hospital']);
            
        // CENTRE D'HYGIENE - icône laboratoire/microscope
        DB::table('tr_menu_item')
            ->where('code_menu_item', 'MENU_CH')
            ->update(['lib_icone' => 'flaticon-381-first-aid-kit']);
            
        // POMPES FUNEBRES - icône croix/monument
        DB::table('tr_menu_item')
            ->where('code_menu_item', 'MENU_PF')
            ->update(['lib_icone' => 'flaticon-381-cross']);
            
        // ETAT CIVIL - Mairie centrale ET CEC - même icône bâtiment/institution
        DB::table('tr_menu_item')
            ->whereIn('code_menu_item', ['MENU_MC', 'MENU_CEC'])
            ->update(['lib_icone' => 'flaticon-381-building']);
            
        // AMBASSADE - icône globe/avion
        DB::table('tr_menu_item')
            ->where('code_menu_item', 'MENU_AMB')
            ->update(['lib_icone' => 'flaticon-381-airplane']);
            
        // TRIBUNAL - icône marteau de juge
        DB::table('tr_menu_item')
            ->where('code_menu_item', 'MENU_TRI')
            ->update(['lib_icone' => 'flaticon-381-law']);
            
        // Supprimer l'icône du sous-menu "Dossiers reçus" dans Tribunal
        DB::table('tr_menu_item')
            ->where('code_menu_item', 'MENU_TRI_REC')
            ->update(['lib_icone' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer l'icône originale
        DB::table('tr_menu_item')
            ->whereIn('code_menu_item', [
                'MENU_FS',
                'MENU_CH', 
                'MENU_PF',
                'MENU_MC',
                'MENU_CEC',
                'MENU_AMB',
                'MENU_TRI'
            ])
            ->update(['lib_icone' => 'flaticon-381-layer-1']);
    }
};
