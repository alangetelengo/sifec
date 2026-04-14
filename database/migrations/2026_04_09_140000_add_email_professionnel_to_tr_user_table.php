<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailProfessionnelToTrUserTable extends Migration
{
    /**
     * E-mail professionnel : destinataire des messages d’activation / gestion 2FA (bulk admin).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_user', function (Blueprint $table) {
            $table->string('email_professionnel', 255)
                ->nullable()
                ->after('email')
                ->comment('E-mail pour les notifications 2FA (activation, codes) — prioritaire sur email');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('tr_user', function (Blueprint $table) {
            $table->dropColumn('email_professionnel');
        });
    }
}
