<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrRegistreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_registre', function (Blueprint $table) {
            $table->string("code_registre",30);
            $table->primary("code_registre");
            $table->string("lib_registre");
            $table->date("date_ouverture")->nullable();
            $table->date("date_fermeture")->nullable();
            $table->integer("nombre_acte_prevu")->default(50);
            $table->smallInteger("nombre_acte_transcrit")->default(0);
            $table->string("code_type_registre",16);
            $table->string("cui",16);
            $table->boolean('statut')->default(true);
            $table->string("sceau",175)->nullable();
            $table->string("otp_paraphage",8)->nullable();
            $table->string("identifiant_registre",32);



            $table->string("approbation_tribunal", 16)->nullable();
            $table->foreign("approbation_tribunal")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->string("signature_tribunal", 175)->nullable();
            $table->string("cloture_cec", 16)->nullable();
            $table->foreign("cloture_cec")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->string("signature_cloture_cec", 175)->nullable();


            $table->foreign("code_type_registre")->references("code_type_registre")->on("tr_type_registre")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('tr_registre');
    }
}
