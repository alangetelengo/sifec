<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Paramètre admin : la signature électronique du certificat de naissance
 * est-elle obligatoire pour transmettre au centre d'état civil (formation sanitaire) ?
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('t_guot_signelec_config', function (Blueprint $table) {
            $table->boolean('certificat_signature_obligatoire')
                ->default(false)
                ->after('signataire_fonctions');
        });
    }

    public function down(): void
    {
        Schema::table('t_guot_signelec_config', function (Blueprint $table) {
            $table->dropColumn('certificat_signature_obligatoire');
        });
    }
};
