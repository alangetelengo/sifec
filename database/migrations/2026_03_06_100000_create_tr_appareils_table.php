<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrAppareilsTable extends Migration
{
    public function up()
    {
        Schema::create('tr_appareils', function (Blueprint $table) {
            $table->string('code_appareil', 16)->primary();
            $table->string('adresse_mac', 50)->unique()->comment('Adresse MAC de l\'appareil');
            $table->string('nom_appareil', 100)->comment('Nom lisible de l\'appareil');
            $table->enum('type_appareil', ['ordinateur', 'tablette', 'smartphone', 'autre'])
                  ->default('ordinateur');
            $table->string('code_institution', 16)->nullable()->comment('Institution propriétaire');
            $table->string('enregistre_par', 16)->nullable()->comment('CUI de l\'administrateur enregistrant');
            $table->boolean('statut')->default(true)->comment('true = actif, false = désactivé');
            $table->timestamp('date_enregistrement')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('code_institution')
                  ->references('code_institution')
                  ->on('tr_institution')
                  ->onDelete('set null')
                  ->onUpdate('cascade');

            $table->foreign('enregistre_par')
                  ->references('cui')
                  ->on('tr_ins_user')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tr_appareils');
    }
}
