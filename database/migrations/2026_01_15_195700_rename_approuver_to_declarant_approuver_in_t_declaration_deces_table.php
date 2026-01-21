<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RenameApprouverToDeclarantApprouverInTDeclarationDecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Si les deux colonnes existent, copier les données et supprimer approuver
        if (Schema::hasColumn('t_declaration_deces', 'approuver') && Schema::hasColumn('t_declaration_deces', 'declarant_approuver')) {
            // Copier les valeurs de approuver vers declarant_approuver où declarant_approuver est NULL ou NON
            // Priorité à approuver si elle a une valeur 'OUI'
            DB::statement("UPDATE `t_declaration_deces` SET `declarant_approuver` = `approuver` WHERE (`declarant_approuver` IS NULL OR `declarant_approuver` = 'NON') AND `approuver` IS NOT NULL");
            
            // Supprimer la colonne approuver
            Schema::table('t_declaration_deces', function (Blueprint $table) {
                $table->dropColumn('approuver');
            });
        } elseif (Schema::hasColumn('t_declaration_deces', 'approuver') && !Schema::hasColumn('t_declaration_deces', 'declarant_approuver')) {
            // Si seule approuver existe, la renommer
            DB::statement("ALTER TABLE `t_declaration_deces` CHANGE COLUMN `approuver` `declarant_approuver` ENUM('OUI','NON') NULL DEFAULT 'NON' COMMENT 'Permet de savoir si le docuement a été lu et approuvé par le déclarant'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_declaration_deces', function (Blueprint $table) {
            // Recréer la colonne approuver si elle n'existe pas
            if (!Schema::hasColumn('t_declaration_deces', 'approuver') && Schema::hasColumn('t_declaration_deces', 'declarant_approuver')) {
                $table->enum("approuver", ["OUI","NON"])->nullable()->default("NON")->comment("Permet de savoir si le docuement a été lu et approuvé par le déclarant");
                // Copier les valeurs
                DB::statement("UPDATE `t_declaration_deces` SET `approuver` = `declarant_approuver`");
            }
        });
    }
}

