<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modifier d'abord l'enum statut pour inclure tous les nouveaux statuts
        DB::statement("ALTER TABLE t_demande_document MODIFY COLUMN statut ENUM(
            'En attente de paiement',
            'En traitement',
            'En attente de signature',
            'Traitée',
            'Livrée',
            'Rejetée'
        ) NOT NULL DEFAULT 'En traitement'");

        // 2. Ajouter les nouveaux champs
        Schema::table('t_demande_document', function (Blueprint $table) {
            // Origine de la demande
            $table->enum('origine_demande', ['portail', 'sur_site'])->default('sur_site')->after('code_demande_document');

            // Référence à l'acte concerné
            $table->string('numero_acte', 50)->nullable()->after('email_demandeur');
            $table->string('code_type_acte', 16)->nullable()->after('numero_acte');

            // Institution et agent traitant
            $table->string('code_institution', 16)->nullable()->after('code_type_acte');
            $table->string('cui', 16)->nullable()->after('code_institution');

            // Tarification
            $table->decimal('prix', 10, 2)->nullable()->after('cui');

            // Dates importantes
            $table->datetime('date_demande')->nullable()->after('prix');
            $table->datetime('date_traitement')->nullable()->after('date_demande');
            $table->datetime('date_livraison')->nullable()->after('date_traitement');

            // Signature électronique
            $table->string('signature_officier', 255)->nullable()->after('date_livraison');
            $table->datetime('date_signature')->nullable()->after('signature_officier');
            $table->string('code_signataire', 16)->nullable()->after('date_signature');

            // Workflow OTP pour signature
            $table->string('otp_code', 10)->nullable()->after('code_signataire');
            $table->datetime('otp_expire_at')->nullable()->after('otp_code');
            $table->string('ip_signature', 45)->nullable()->after('otp_expire_at');
            $table->string('user_agent_signature', 500)->nullable()->after('ip_signature');

            // Document généré
            $table->string('chemin_document', 500)->nullable()->after('user_agent_signature');

            // Notes et observations
            $table->text('observations')->nullable()->after('chemin_document');

            // Index pour performance
            $table->index('origine_demande');
            $table->index('statut');
            $table->index('date_demande');
            $table->index(['code_institution', 'statut']);
        });

        // 3. Ajouter les foreign keys
        Schema::table('t_demande_document', function (Blueprint $table) {
            $table->foreign('code_type_acte')->references('code_type_acte')->on('tr_type_acte')->nullOnDelete();
            $table->foreign('code_institution')->references('code_institution')->on('tr_institution')->nullOnDelete();
            $table->foreign('cui')->references('cui')->on('tr_ins_user')->nullOnDelete();
            $table->foreign('code_signataire')->references('cui')->on('tr_ins_user')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_demande_document', function (Blueprint $table) {
            // Supprimer les foreign keys
            $table->dropForeign(['code_type_acte']);
            $table->dropForeign(['code_institution']);
            $table->dropForeign(['cui']);
            $table->dropForeign(['code_signataire']);

            // Supprimer les index
            $table->dropIndex(['origine_demande']);
            $table->dropIndex(['statut']);
            $table->dropIndex(['date_demande']);
            $table->dropIndex(['code_institution', 'statut']);

            // Supprimer les colonnes
            $table->dropColumn([
                'origine_demande',
                'numero_acte',
                'code_type_acte',
                'code_institution',
                'cui',
                'prix',
                'date_demande',
                'date_traitement',
                'date_livraison',
                'signature_officier',
                'date_signature',
                'code_signataire',
                'otp_code',
                'otp_expire_at',
                'ip_signature',
                'user_agent_signature',
                'chemin_document',
                'observations',
            ]);
        });

        // Restaurer l'ancien enum statut
        DB::statement("ALTER TABLE t_demande_document MODIFY COLUMN statut ENUM(
            'En traitement',
            'Réjeté',
            'Traité',
            'Livré'
        ) NOT NULL DEFAULT 'En traitement'");
    }
};
