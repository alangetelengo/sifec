<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaiementDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paiement_details', function (Blueprint $table) {
            $table->id();
            $table->string('payer_number',15)->nullable();
            $table->string('invoice_code',64)->nullable();
            $table->string('x_reference_id',128)->nullable();
            $table->string('code_demande_document',16);
            $table->double('total_amount');
            $table->string('payment_methode');
            $table->string('extra_col_1',64)->nullable();
            $table->string('extra_col_2',64)->nullable();
            $table->enum('statut_payment',["success","failed","pending"])->default("pending");
            $table->timestamps();

            // Note: La clé étrangère pour code_demande_document sera ajoutée dans une migration ultérieure après la création de t_demande_document
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paiement_details');
    }
}
