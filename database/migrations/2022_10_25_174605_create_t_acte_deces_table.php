<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTActeDecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_acte_deces', function (Blueprint $table) {
            $table->string("code_acte_deces",16);
            $table->primary("code_acte_deces");
            $table->dateTime("date_emission")->nullable();
            $table->string("code_registre",30)->nullable();
            $table->string("code_declaration_deces",16);
            $table->string("cui",16);
            $table->dateTime("date_heure_approbation_pompe_funebre")->nullable();
            $table->string("code_institution",16)->nullable()->comment('Code de l\'institution qui a généré l\'acte');
            $table->boolean("retirer")->default(0);
            $table->boolean("approbation_tribunal")->default(false);
            $table->string("approbation_pompe_funebre", 16)->nullable();


            $table->string("otp_approbation_pompe_funebre",8)->nullable();
            $table->timestamp("otp_expire_at")->nullable()->comment("Expiration de l'OTP (1 minute après génération)");
            $table->string("adresse_mac_approbation",50)->nullable()->comment("Adresse MAC de l'appareil utilisé pour signer l'acte");
            $table->string("nom_appareil_approbation",100)->nullable()->comment("Nom de l'appareil utilisé pour signer l'acte");
            $table->string("signature_pompe_funebre",175)->nullable();
            $table->string("sceau_tribunal",175)->nullable();

            $table->foreign("code_declaration_deces")->references("code_declaration_deces")->on("t_declaration_deces")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_registre")->references("code_registre")->on("tr_registre")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->boolean("statut")->default("0")->comment("permet de savoir si acte est annule ou pas");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("approbation_pompe_funebre")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_institution")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_acte_deces');
    }
}
