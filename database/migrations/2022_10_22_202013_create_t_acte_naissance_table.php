<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTActeNaissanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_acte_naissance', function (Blueprint $table) {
            $table->string("niupp",50);
            $table->primary("niupp");
            $table->dateTime("date_emission")->nullable();
            $table->string("code_declaration_naissance",16);
            $table->string("code_registre",30)->nullable();
            $table->string("cui",16);
            $table->boolean("approbation_tribunal")->default(false);
            $table->string("approbation_mairie", 16)->nullable();

            $table->string("signature_mairie",175)->nullable();
            $table->string("sceau_tribunal",175)->nullable();
            $table->timestamp("date_heure_approbation_mairie")->nullable();
            $table->string("otp_approbation_mairie",8)->nullable();
            $table->boolean("retirer")->default(0);
            $table->boolean("statut")->default("0")->comment("permet de savoir si acte est annule ou pas");
            $table->string("motif_annulation",100)->nullable();
            $table->foreign("code_declaration_naissance")->references("code_declaration_naissance")->on("t_declaration_naissance")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_registre")->references("code_registre")->on("tr_registre")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");

            $table->foreign("approbation_mairie")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_acte_naissance');
    }
}
