<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTApiParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_api_params', function (Blueprint $table) {
            $table->string("code_api_params",16);
            $table->primary("code_api_params");
            $table->string("params_key",50);
            $table->string("params_value");
            $table->string("code_providers",16);

            $table->foreign("code_providers")->references("code_providers")->on("tr_sms_providers")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('t_api_params');
    }
}
