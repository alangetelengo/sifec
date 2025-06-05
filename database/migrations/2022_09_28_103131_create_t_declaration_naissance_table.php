<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTDeclarationNaissanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_declaration_naissance', function (Blueprint $table) {
            $table->primary("code_declaration_naissance");
            $table->string("code_declaration_naissance",16);
            $table->integer('nombre_enfant')->default(0);
            $table->timestamp("date_heure_declaration");
            $table->enum("type_declarant",["Personne morale","Personne physique"]);
            $table->string("personne_morale")->nullable(); //fait office d'un declarant
            $table->string("personne_declaree")->nullable();

            $table->string('cec_naissance',75)->nullable();
            $table->string('pays_naissance_enfant',75)->nullable();
            $table->string('code_declarant')->nullable();
            $table->string('code_adoptant')->nullable()->after("code_declarant");
            $table->string('code_enfant')->nullable();
            $table->string('code_pere')->nullable();
            $table->string('code_mere')->nullable();
            $table->string('code_filiation')->nullable();
            $table->string('code_user_institution');
            $table->string("code_institution", 16)->nullable()->after("code_user_institution");


            $table->string('code_lieu_survenance')->nullable();
            $table->string('code_situation_mat')->nullable(); //code situation matrimoniale
            $table->timestamp("date_heure_naissance")->nullable();
            $table->boolean("top_requisition")->default(false);
            $table->string("numero_req",16)->nullable();
            $table->string("numero_certificat",16)->nullable();
            $table->enum('type_declaration',["DECLARATION DE NAISSANCE","CERTIFICAT DE NON INSCRIPTION", "CERTIFICAT DE DESTRUCTION DE L\'ACTE",'FICHE DE MATERNITE',"FICHE DE TRANSCRIPTION"])->nullable();
            $table->string("formation_sanitaire_naissance")->nullable();
            $table->string('code_jugement',16)->nullable();
            $table->string('code_requisition',16)->nullable();
            // $table->date('date_jugement')->nullable();
            // $table->string('code_tribunal_jugement',16)->nullable();
            // $table->string("numero_ancien_acte",20)->nullable();


            $table->boolean("statut")->default("0")->comment("permet de savoir si acte issu de cette déclaration doit être annuler ou pas");
            $table->enum("approuver", ["OUI","NON"])->nullable()->default("NON")->comment("Permet de savoir si le docuement a été lu et approuvé par le déclarant");

            $table->enum("type_adoption",['adoption partielle','adoption pleniere']);
           // $table->enum("statut_enfant",['VIVANT','DECEDE'])->nullable();


            $table->boolean('supprimer')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('code_adoptant')->references('code_personne')->on('tr_identification_personne')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('code_declarant')->references('code_personne')->on('tr_identification_personne')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('code_enfant')->references('code_personne')->on('tr_identification_personne')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('code_pere')->references('code_personne')->on('tr_identification_personne')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('code_mere')->references('code_personne')->on('tr_identification_personne')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign('code_filiation')->references('code_filiation')->on('tr_filiation')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign("code_user_institution")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign('code_lieu_survenance')->references('code_lieu_survenance')->on('tr_lieu_survenance')->onDelete('cascade')->onUpdate("cascade");
            $table->foreign("code_situation_mat")->references('code_situation_matrimoniale')->on("tr_situation_matrimoniale")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_jugement")->references("code_jugement")->on("t_jugement")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_requisition")->references("code_requisition")->on("t_requisition")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('t_declaration_naissance');
    }
}
