<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTDeclarationDecesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_declaration_deces', function (Blueprint $table) {
            $table->primary("code_declaration_deces");
            $table->string("code_declaration_deces",16);
            $table->timestamp('date_heure_declaration')->nullable();
            $table->timestamp('date_heure_deces')->nullable();
            $table->string("num_acte_naissance",20)->nullable();
            $table->date("date_mariage")->nullable();
            $table->string('cec_naissance',75)->nullable();
            $table->string('code_situation_matrimoniale',16)->nullable();
            $table->string("domicile_defunt")->nullable();
            $table->string("cec_mariage",75)->nullable();
            $table->string("num_acte_mariage",20)->nullable();
            $table->enum("type_declarant",["Personne morale","Personne physique"]);
            $table->string("code_regime",16)->nullable();
            $table->string("code_religion")->nullable();
            $table->string("code_lieu_survenance",16)->nullable();
            $table->string("code_document")->nullable();
            $table->string('code_user_institution')->nullable();
            $table->string("lieu_deces",50)->nullable(); //lieu du décès
            $table->boolean("top_requisition")->default(false);
            $table->string("numero_req",16)->nullable();
            $table->string("numero_certificat",16)->nullable();
            $table->enum('type_declaration',["DECLARATION DE DECES","DECLARATION TARDIVE","CERTIFICAT DE CONSTATATION DE DECES","CERTIFICAT DE NON INSCRIPTION", "CERTIFICAT DE DESTRUCTION DE L\'ACTE","FICHE DE TRANSCRIPTION"])->nullable();
            $table->enum("fonction_medecin",["Medécin","Infirmier(e)","Autre personne de la santé"])->nullable();
            $table->string('nom_medecin')->nullable();
            $table->string('code_conjoint')->nullable();
            $table->string('code_filiation')->nullable();
            $table->string('code_declarant');
            $table->string('code_defunt');
            $table->string("code_cause_deces",16)->nullable();
            $table->string('code_pere')->nullable();
            $table->string('code_mere')->nullable();
            $table->enum("approuver", ["OUI","NON"])->nullable()->default("NON")->comment("Permet de savoir si le docuement a été lu et approuvé par le déclarant");
            $table->enum("cec_approuver", ["OUI","NON"])->default("NON")->comment("permet de savoir si la declaration est prête ou pas pour la transcription de l'acte");

            $table->string("cec_approuve_par")->nullable();
            $table->enum("tribunal_approuver",["NON","OUI"])->default("NON");
            $table->string("tribunal_approuve_par")->nullable();

            $table->timestamp("cec_approuve_le")->nullable();
            $table->timestamp("tribunal_approuve_le")->nullable();

            $table->foreign("cec_approuve_par")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("tribunal_approuve_par")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");


            $table->string("code_institution", 16)->nullable()->comment("institution à qui appartient cette declaration");
            $table->string("code_institution_destinataire", 16)->nullable()->comment("institution destinataire de la déclaration");
            $table->string("numero_ancien_acte", 16)->nullable();

            $table->string('piece_declarant')->nullable();
            $table->string('piece_defunt')->nullable();
            $table->string('piece_conjoint')->nullable();
            $table->string('piece_pere')->nullable();
            $table->string('piece_mere')->nullable();


            $table->timestamps();
            $table->softDeletes();

            $table->foreign("code_cause_deces")->references("code_cause_deces")->on("tr_cause_deces")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign('code_situation_matrimoniale')->references('code_situation_matrimoniale')->on('tr_situation_matrimoniale')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('code_regime')->references('code_regime')->on('tr_regime')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign("code_lieu_survenance")->references("code_lieu_survenance")->on("tr_lieu_survenance")->onDelete('cascade')->onUpdate('cascade');
            $table->foreign("code_religion")->references("code_religion")->on("tr_religion")->onDelete('cascade')->onUpdate('cascade');
            $table->foreign("code_document")->references("code_document")->on("t_document")->onDelete('cascade')->onUpdate('cascade');
            $table->foreign("code_user_institution")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_filiation")->references('code_filiation')->on("tr_filiation")->onDelete('cascade')->onUpdate('cascade');
            $table->foreign("code_declarant")->references("code_personne")->on("tr_identification_personne")->onDelete('cascade')->onUpdate('cascade');
            $table->foreign("code_defunt")->references("code_personne")->on("tr_identification_personne")->onDelete('cascade')->onUpdate('cascade');
            $table->foreign("code_conjoint")->references("code_personne")->on("tr_identification_personne")->onDelete('cascade')->onUpdate('cascade');
            $table->foreign("code_pere")->references("code_personne")->on("tr_identification_personne")->onDelete('cascade')->onUpdate('cascade');
            $table->foreign("code_mere")->references("code_personne")->on("tr_identification_personne")->onDelete('cascade')->onUpdate('cascade');

            $table->foreign("code_institution_destinataire")->references("code_institution")->on("tr_institution")->onDelete('cascade')->onUpdate('cascade');
            $table->foreign("code_institution")->references("code_institution")->on("tr_institution")->onDelete('cascade')->onUpdate('cascade');
        });

         Schema::create("t_ddecescause", function (Blueprint $table) {
             $table->primary(["code_declaration_deces","code_cause_deces"]);
             $table->string('code_declaration_deces',16);
             $table->string("code_cause_deces",16)->nullable();
             $table->timestamps();

             $table->foreign("code_declaration_deces")->references("code_declaration_deces")->on('t_declaration_deces')->onDelete('cascade')->onUpdate('cascade');
             $table->foreign("code_cause_deces")->references("code_cause_deces")->on("tr_cause_deces")->onDelete('cascade')->onUpdate('cascade');
         });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //  Schema::dropIfExists('t_ddecescause');
         Schema::dropIfExists('t_declaration_deces');
    }
}
