<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Colonnes OTP paraphe registre (sendOtp / validateOtp — RegistreController).
     */
    public function up(): void
    {
        Schema::table('tr_registre', function (Blueprint $table) {
            if (! Schema::hasColumn('tr_registre', 'otp_expire_at')) {
                $table->timestamp('otp_expire_at')->nullable()->after('otp_paraphage');
            }
            if (! Schema::hasColumn('tr_registre', 'otp_paraphage_attempts')) {
                $table->unsignedSmallInteger('otp_paraphage_attempts')->default(0)->after('otp_expire_at');
            }
            if (! Schema::hasColumn('tr_registre', 'otp_locked_until')) {
                $table->timestamp('otp_locked_until')->nullable()->after('otp_paraphage_attempts');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tr_registre', function (Blueprint $table) {
            if (Schema::hasColumn('tr_registre', 'otp_locked_until')) {
                $table->dropColumn('otp_locked_until');
            }
            if (Schema::hasColumn('tr_registre', 'otp_paraphage_attempts')) {
                $table->dropColumn('otp_paraphage_attempts');
            }
            if (Schema::hasColumn('tr_registre', 'otp_expire_at')) {
                $table->dropColumn('otp_expire_at');
            }
        });
    }
};
