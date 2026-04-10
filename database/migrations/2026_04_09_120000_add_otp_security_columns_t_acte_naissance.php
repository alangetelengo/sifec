<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtpSecurityColumnsTActeNaissance extends Migration
{
    public function up()
    {
        Schema::table('t_acte_naissance', function (Blueprint $table) {
            $table->unsignedTinyInteger('otp_mairie_resend_attempts')->default(0)->after('otp_expire_at');
            $table->unsignedTinyInteger('otp_mairie_wrong_attempts')->default(0)->after('otp_mairie_resend_attempts');
            $table->timestamp('otp_mairie_locked_until')->nullable()->after('otp_mairie_wrong_attempts');
        });
    }

    public function down()
    {
        Schema::table('t_acte_naissance', function (Blueprint $table) {
            $table->dropColumn(['otp_mairie_resend_attempts', 'otp_mairie_wrong_attempts', 'otp_mairie_locked_until']);
        });
    }
}
