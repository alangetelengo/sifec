<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('mobile_money_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->string("code_paiement_document", 16)->nullable();
            $table->string("payer_number",15);
            $table->enum("channel",["AM","MOMO","OTHER"]);
            $table->string("invoice_number")->unique();
            $table->string("transaction_token")->nullable(); // AM_token, MOMO_x_reference
            $table->string("amount")->default(0);
            $table->string("channel_payment_ref")->nullable();
            $table->enum("status",["pending","successful","failed"]);
            $table->timestamps();

            // $table->foreign("transactions_id")->references("id")->on("transactions")->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_money_transaction_details');
    }
};
