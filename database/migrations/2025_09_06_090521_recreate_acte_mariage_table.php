<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateActeMariageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        // Supprimer la table existante
        Schema::dropIfExists('t_acte_mariage');

        Schema::create('t_acte_mariage', function (Blueprint $table) {
            $table->string("code_acte_mariage",16);
            $table->primary("code_acte_mariage");
            $table->dateTime("date_emission")->nullable();
            $table->string("code_registre",30)->nullable();
            $table->string("code_declaration_mariage",16);
            $table->string("cui",16);
            $table->string("code_institution", 16)->nullable()->comment("institution à qui appartient cette declaration");
            $table->boolean("approbation_tribunal")->default(false);
            $table->string("approbation_mairie", 16)->nullable();
            $table->timestamp("date_heure_approbation_mairie")->nullable();


            $table->string("otp_approbation_mairie",8)->nullable();
            $table->timestamp("otp_expire_at")->nullable()->comment("Expiration de l'OTP (1 minute après génération)");
            $table->string("adresse_mac_approbation",50)->nullable()->comment("Adresse MAC de l'appareil utilisé pour signer l'acte");
            $table->string("nom_appareil_approbation",100)->nullable()->comment("Nom de l'appareil utilisé pour signer l'acte");
            $table->string("signature_maire",175)->nullable();
            $table->string("sceau_tribunal",175)->nullable();
            $table->boolean("retirer")->default(0);

            $table->boolean("statut")->default("0")->comment("permet de savoir si acte est annule ou pas");

            $table->foreign("code_institution")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("approbation_mairie")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_declaration_mariage")->references("code_declaration_mariage")->on("t_declaration_mariage")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_registre")->references("code_registre")->on("tr_registre")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
