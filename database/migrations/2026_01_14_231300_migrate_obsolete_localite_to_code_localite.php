<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateObsoleteLocaliteToCodeLocalite extends Migration
{
    /**
     * Run the migrations.
     * Migre les données des champs obsolètes vers code_localite
     *
     * @return void
     */
    public function up()
    {
        Log::channel('sifec')->info('=== DÉBUT MIGRATION DONNÉES LOCALITÉ INSTITUTION ===');

        // Compter les institutions à migrer
        $toMigrate = DB::table('tr_institution')
            ->whereNull('code_localite')
            ->where(function($query) {
                $query->whereNotNull('code_commune')
                    ->orWhereNotNull('code_district')
                    ->orWhereNotNull('code_arrondissement')
                    ->orWhereNotNull('code_communaute_urbaine');
            })
            ->count();

        Log::channel('sifec')->info("Institutions à migrer : {$toMigrate}");

        // Migrer code_commune vers code_localite
        $migratedCommune = DB::table('tr_institution')
            ->whereNotNull('code_commune')
            ->whereNull('code_localite')
            ->update(['code_localite' => DB::raw('code_commune')]);
        
        Log::channel('sifec')->info("Migré depuis code_commune : {$migratedCommune}");

        // Migrer code_district vers code_localite
        $migratedDistrict = DB::table('tr_institution')
            ->whereNotNull('code_district')
            ->whereNull('code_localite')
            ->update(['code_localite' => DB::raw('code_district')]);
        
        Log::channel('sifec')->info("Migré depuis code_district : {$migratedDistrict}");

        // Migrer code_arrondissement vers code_localite
        $migratedArrondissement = DB::table('tr_institution')
            ->whereNotNull('code_arrondissement')
            ->whereNull('code_localite')
            ->update(['code_localite' => DB::raw('code_arrondissement')]);
        
        Log::channel('sifec')->info("Migré depuis code_arrondissement : {$migratedArrondissement}");

        // Migrer code_communaute_urbaine vers code_localite
        $migratedCommunauteUrbaine = DB::table('tr_institution')
            ->whereNotNull('code_communaute_urbaine')
            ->whereNull('code_localite')
            ->update(['code_localite' => DB::raw('code_communaute_urbaine')]);
        
        Log::channel('sifec')->info("Migré depuis code_communaute_urbaine : {$migratedCommunauteUrbaine}");

        // Vérifier les institutions qui ont toujours les deux systèmes
        $stillHaveBoth = DB::table('tr_institution')
            ->whereNotNull('code_localite')
            ->where(function($query) {
                $query->whereNotNull('code_commune')
                    ->orWhereNotNull('code_district')
                    ->orWhereNotNull('code_arrondissement')
                    ->orWhereNotNull('code_communaute_urbaine');
            })
            ->count();

        Log::channel('sifec')->info("Institutions avec les deux systèmes (après migration) : {$stillHaveBoth}");
        Log::channel('sifec')->info('=== FIN MIGRATION DONNÉES LOCALITÉ INSTITUTION ===');
    }

    /**
     * Reverse the migrations.
     * Note: Cette migration ne peut pas être complètement inversée car on ne sait pas
     * quel champ obsolète était utilisé à l'origine
     *
     * @return void
     */
    public function down()
    {
        // Ne peut pas être complètement inversé
        // Les données migrées vers code_localite resteront dans code_localite
        Log::channel('sifec')->warning('Rollback de la migration de données localité - Les données restent dans code_localite');
    }
}

