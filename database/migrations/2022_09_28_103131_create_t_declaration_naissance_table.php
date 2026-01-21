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

            $table->string('cec_naissance',75)->nullable()->comment("Centre d'état civil de naissance,autre que ce qui est dans le referentiel");
            $table->string('pays_naissance_enfant',75)->nullable();
            $table->string('code_declarant')->nullable();
            $table->string('code_adoptant')->nullable();
            $table->string('code_enfant')->nullable();
            $table->string('code_pere')->nullable();
            $table->string('code_mere')->nullable();
            $table->string('code_filiation')->nullable();
            $table->string('code_user_institution')->comment("utilisateur à qui appartient cet enregistrement");
            $table->string("code_institution", 16)->nullable()->comment("institution à qui appartient cette declaration");


            $table->string('code_lieu_survenance')->nullable();
            $table->string('code_situation_mat')->nullable(); //code situation matrimoniale des parents
            $table->timestamp("date_heure_naissance")->nullable();
            // $table->boolean("top_requisition")->default(false);
            $table->string("numero_req",16)->nullable();
            $table->string("numero_certificat",16)->nullable();
            $table->enum('type_declaration',["DECLARATION DE NAISSANCE","CERTIFICAT DE NON INSCRIPTION", "CERTIFICAT DE DESTRUCTION DE L\'ACTE",'FICHE DE MATERNITE',"FICHE DE TRANSCRIPTION"])->nullable();
            $table->string("formation_sanitaire_naissance")->nullable();
            $table->enum("cec_approuver", ["OUI","NON"])->default("NON")->comment("permet de savoir si la declaration est prête ou pas pour la transcription de l'acte");
            $table->string("cec_approuve_par")->nullable();
            $table->enum("tribunal_approuver",["NON","OUI"])->default("NON");
            $table->string("tribunal_approuve_par")->nullable();
            $table->timestamp("cec_approuve_le")->nullable();
            $table->timestamp("tribunal_approuve_le")->nullable();

            $table->enum("declarant_approuver", ["OUI","NON"])->nullable()->default("NON")->comment("Permet de savoir si le docuement a été lu et approuvé par le déclarant");

            $table->string("code_institution_destinataire", 16)->nullable()->comment("institution destinataire de la déclaration");
            $table->string("numero_ancien_acte", 16)->nullable();
            $table->string('code_jugement', 16)->nullable();
            
            // Champs pour enfant abandonné
            $table->string("lieu_placement",150)->nullable()->comment("qui permet de renseigner la structure au quel l'enfant trouvé ou abandonné a été placé");
            $table->string("piece_extrait_main_courante",175)->nullable()->comment("qui permet de renseigner la structure au quel l'enfant trouvé ou abandonné a été placé");
            $table->string("num_jugement_placement_provisoir",20)->nullable()->comment("qui permet de renseigner la structure au quel l'enfant trouvé ou abandonné a été placé");
            $table->string("num_fiche_placement",20)->nullable()->comment("qui permet de renseigner la structure au quel l'enfant trouvé ou abandonné a été placé");

            $table->string('piece_declarant')->nullable();
            $table->string('piece_pere')->nullable();
            $table->string('piece_mere')->nullable();

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

            $table->foreign("code_institution_destinataire")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate("cascade");

            $table->foreign("cec_approuve_par")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("tribunal_approuve_par")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");

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
