<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTDeclarationMariageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_declaration_mariage', function (Blueprint $table) {
            $table->primary("code_declaration_mariage");
            $table->string("code_declaration_mariage",16);
            $table->date('date_declaration_mariage');
            $table->date('date_prevue_mariage');
            // $table->date("date_celebration_dot")->nullable();

            $table->string("lieu_ceremonie_mariage")->nullable();
            $table->string("adresse_celebration_mariage")->nullable();
            $table->string("autorisation_ambassade_epoux",75)->nullable();
            $table->date('date_autorisation_ambassade_epoux')->nullable();
            $table->string("autorisation_ambassade_epouse",75)->nullable();
            $table->date('date_autorisation_ambassade_epouse')->nullable();
            $table->string('cec_naissance_epouse',75)->nullable();
            $table->string('cec_naissance_epoux',75)->nullable();
            $table->string('certificat_residence_epoux',75)->nullable();
            $table->date('date_emission_certificat_residence_epoux')->nullable();
            $table->string('certificat_residence_epouse',75)->nullable();
            $table->date('date_emission_certificat_residence_epouse')->nullable();
            $table->string('code_epouse',16)->nullable();
            $table->string('code_epoux',16)->nullable();
            $table->string("nbre_enfant",2)->nullable();
            $table->string('code_temoin_homme_epouse',16)->nullable();
            $table->string('code_temoin_femme_epouse',16)->nullable();
            $table->string('code_temoin_homme_epoux',16)->nullable();
            $table->string('code_temoin_femme_epoux',16)->nullable();
            $table->string('code_filiation_chef_famille',16)->nullable();
            $table->string('chef_famille')->nullable();
            $table->string("pere_epoux")->nullable();
            $table->string("mere_epoux")->nullable();
            $table->string("pere_epouse")->nullable();
            $table->string("mere_epouse")->nullable();
            $table->date('date_emission_acte_naissance_epouse')->nullable();
            $table->date('date_emission_acte_naissance_epoux')->nullable();
            $table->string('numero_acte_naissance_epouse',70)->nullable();//
            $table->string('numero_acte_naissance_epoux',70)->nullable();//
            $table->string('numero_jugement_divorce_epoux',70)->nullable();


            $table->boolean("avis_epouse")->default(0);
            $table->string('reference_avis_epouse',150)->nullable();
            $table->date("date_pre_mariage_epoux")->nullable();
            $table->string('parent_paternel_epoux')->nullable();
            $table->string('parent_maternel_epoux')->nullable();
            $table->string('montant_dot',10)->nullable();
            $table->boolean("examens_prenuptiaux")->default(0);
            $table->boolean("persister_marier_epoux")->default(0);
            $table->boolean("persister_marier_epouse")->default(0);
            $table->date("date_pre_mariage_epouse")->nullable();
            $table->string('parent_paternel_epouse')->nullable();
            $table->string('parent_maternel_epouse')->nullable();


            $table->string('numero_jugement_divorce_epouse',70)->nullable();
            $table->string('numero_acte_mariage_epoux',70)->nullable();
            $table->string('numero_acte_mariage_epouse',70)->nullable();
            $table->string('numero_acte_deces_epoux',70)->nullable();
            $table->string('numero_acte_deces_epouse',70)->nullable();
            $table->string('code_option_mariage',16)->nullable();
            $table->string('code_regime',16)->nullable();
            $table->string('code_situation_mat_epouse',16)->nullable();
            $table->string('code_situation_mat_epoux',16)->nullable();
            $table->enum("type_declaration",["DECLARATION DE MARIAGE","DISPENSE"]);


            $table->enum("type_mariage",['NORMAL','POSTHUME','PROCURATION']);


            $table->enum("titre_requisition",['REQUISITION AUX FINS DE DISPENSE DE PUBLICATION DE BANS ET DE DELAI DE CELEBRATION DU MARIAGE','REQUISITION AUX FINS DE DISPENSE DE PUBLICATION DE BANS ET DE LIEU DE CELEBRATION DU MARIAGE','REQUISITION AUX FINS DE DISPENSE DE PUBLICATION DE BANS,DE DELAI ET DU LIEU DE CELEBRATION DU MARIAGE'])->nullable();
            $table->string('cui', 16);
            $table->boolean("top_requisition")->default(false);
            $table->string("numero_dispense",16)->nullable();

            $table->string('code_profession_epoux',16)->nullable();
            $table->string('code_profession_epouse',16)->nullable();
            $table->string('code_profession_temoin_h_epoux',16)->nullable();
            $table->string('code_profession_temoin_f_epoux',16)->nullable();
            $table->string('code_profession_temoin_h_epouse',16)->nullable();
            $table->string('code_profession_temoin_f_epouse',16)->nullable();
            $table->string("nom_prenom_mandant_epoux",200)->nullable();
            $table->string("nom_prenom_mandant_epouse",200)->nullable();

            $table->foreign("code_epouse")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_epoux")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_temoin_homme_epouse")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_temoin_femme_epouse")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_temoin_homme_epoux")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_temoin_femme_epoux")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_filiation_chef_famille")->references("code_filiation")->on("tr_filiation")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_option_mariage")->references("code_option_mariage")->on("tr_option_mariage")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_regime")->references("code_regime")->on("tr_regime")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_situation_mat_epouse")->references("code_situation_matrimoniale")->on("tr_situation_matrimoniale")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("code_situation_mat_epoux")->references("code_situation_matrimoniale")->on("tr_situation_matrimoniale")->onDelete("cascade")->onUpdate("cascade");
            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");

            $table->foreign('code_profession_epoux')->references('code_profession')->on('tr_profession')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('code_profession_epouse')->references('code_profession')->on('tr_profession')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('code_profession_temoin_h_epoux')->references('code_profession')->on('tr_profession')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('code_profession_temoin_f_epoux')->references('code_profession')->on('tr_profession')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('code_profession_temoin_h_epouse')->references('code_profession')->on('tr_profession')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('code_profession_temoin_f_epouse')->references('code_profession')->on('tr_profession')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('t_declaration_mariage');
    }
}
