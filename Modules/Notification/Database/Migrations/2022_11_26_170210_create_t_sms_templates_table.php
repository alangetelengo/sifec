<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTSmsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_sms_templates', function (Blueprint $table) {
            $table->string("code_template",16);
            $table->primary("code_template");
            $table->text("message");
            $table->string("code_action",16);
            $table->foreign("code_action")->references("code_action")->on("t_action")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('t_sms_templates');
    }
}
