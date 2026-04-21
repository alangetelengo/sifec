<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE t_demande_document MODIFY COLUMN statut ENUM(
            'En attente de paiement',
            'En traitement',
            'En attente de signature',
            'Traitée',
            'Livrée',
            'Rejetée',
            'Expirée'
        ) NOT NULL DEFAULT 'En traitement'");

        Schema::table('t_demande_document', function (Blueprint $table) {
            $table->dateTime('document_valide_de')->nullable()->after('date_signature');
            $table->dateTime('document_valide_jusquau')->nullable()->after('document_valide_de');
            $table->unsignedSmallInteger('compteur_renouvellement')->default(0)->after('document_valide_jusquau');
        });
    }

    public function down(): void
    {
        Schema::table('t_demande_document', function (Blueprint $table) {
            $table->dropColumn(['document_valide_de', 'document_valide_jusquau', 'compteur_renouvellement']);
        });

        DB::statement("ALTER TABLE t_demande_document MODIFY COLUMN statut ENUM(
            'En attente de paiement',
            'En traitement',
            'En attente de signature',
            'Traitée',
            'Livrée',
            'Rejetée'
        ) NOT NULL DEFAULT 'En traitement'");
    }
};
