<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('t_tarification', function (Blueprint $table) {
            // Supprimer la foreign key existante
            $table->dropForeign(['cui']);

            // Rendre cui nullable pour permettre des tarifs généraux/nationaux
            $table->string('cui', 16)->nullable()->change();

            // Ajouter les champs pour la gestion des tarifs
            $table->date('date_debut_validite')->nullable()->after('prix');
            $table->date('date_fin_validite')->nullable()->after('date_debut_validite');
            $table->boolean('actif')->default(true)->after('date_fin_validite');
            $table->text('commentaire')->nullable()->after('actif');

            // Recréer la foreign key mais nullable
            $table->foreign('cui')
                ->references('cui')
                ->on('tr_ins_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_tarification', function (Blueprint $table) {
            // Supprimer la foreign key
            $table->dropForeign(['cui']);

            // Supprimer les colonnes ajoutées
            $table->dropColumn(['date_debut_validite', 'date_fin_validite', 'actif', 'commentaire']);

            // Remettre cui NOT NULL
            $table->string('cui', 16)->nullable(false)->change();

            // Recréer la foreign key
            $table->foreign('cui')
                ->references('cui')
                ->on('tr_ins_user')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }
};
