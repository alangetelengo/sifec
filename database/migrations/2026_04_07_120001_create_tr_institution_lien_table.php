<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrInstitutionLienTable extends Migration
{
    public function up(): void
    {
        Schema::create('tr_institution_lien', function (Blueprint $table) {
            $table->id();
            $table->string('code_institution_source', 16);
            $table->string('code_institution_cible', 16);
            $table->string('code_type_lien', 16);
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->unique(
                ['code_institution_source', 'code_institution_cible', 'code_type_lien'],
                'uniq_tr_institution_lien_source_cible_type'
            );

            $table->foreign('code_institution_source', 'fk_tr_institution_lien_source')
                ->references('code_institution')->on('tr_institution')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('code_institution_cible', 'fk_tr_institution_lien_cible')
                ->references('code_institution')->on('tr_institution')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('code_type_lien', 'fk_tr_institution_lien_type')
                ->references('code_type_lien')->on('tr_type_lien_institution')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_institution_lien');
    }
}
