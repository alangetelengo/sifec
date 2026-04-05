<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
       
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type'); //type de notification

            // Correction ici :
            $table->string('notifiable_id');
            $table->string('notifiable_type');
            $table->index(['notifiable_id', 'notifiable_type']);

            $table->string("cui",16)->nullable();

            $table->text('data'); //l'élément que l'on a notifié
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign("cui")->references("cui")->on("tr_ins_user")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
