<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeInstitutionDestinataireToTDeclarationDecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_declaration_deces', function (Blueprint $table) {
            $table->string('code_jugement',16)->nullable();
            $table->string('code_requisition',16)->nullable();
            $table->enum("cec_approuver", ["OUI","NON"])->default("NON")->comment("permet de savoir si la declaration est prête ou pas pour la transcription de l'acte");
            $table->enum("declarant_approuver", ["OUI","NON"])->nullable()->default("NON")->comment("Permet de savoir si le docuement a été lu et approuvé par le déclarant");
            $table->string("code_institution", 16)->nullable()->comment("institution à qui appartient cette declaration");

            $table->string("code_institution_destinataire", 16)->nullable()->comment("institution destinataire de la déclaration");


            $table->foreign("code_jugement")->references("code_jugement")->on("t_jugement")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_requisition")->references("code_requisition")->on("t_requisition")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_institution")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_institution_destinataire")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_declaration_deces', function (Blueprint $table) {
            //
        });
    }
}
