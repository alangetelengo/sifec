<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrSmsProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_sms_providers', function (Blueprint $table) {
            $table->string("code_providers",16);
            $table->primary("code_providers");
            $table->string("lib_provider",10)->unique();
            $table->enum("content_type",['JSON', 'XML', 'TEXT']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_sms_providers');
    }
}
