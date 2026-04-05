<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAuditTrailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('tr_user_audit_trail', function (Blueprint $table) {
            $table->id();
            $table->string('code_user', 20); // Référence vers tr_user
            $table->string('action', 50); // login, logout, profile_update, password_change, etc.
            $table->string('description')->nullable();
            $table->json('old_values')->nullable(); // Valeurs avant modification
            $table->json('new_values')->nullable(); // Valeurs après modification
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamp('created_at');

            // Index pour les performances
            $table->index(['code_user', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_user_audit_trail');
    }
}
