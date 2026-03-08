<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixOtpExpireAtCommentActeNaissance extends Migration
{
    public function up()
    {
        // Correction du commentaire du champ otp_expire_at (durée réelle : 30 secondes)
        DB::statement(
            "ALTER TABLE t_acte_naissance MODIFY COLUMN otp_expire_at TIMESTAMP NULL
             COMMENT 'Expiration de l\\'OTP (30 secondes après génération)'"
        );
    }

    public function down()
    {
        DB::statement(
            "ALTER TABLE t_acte_naissance MODIFY COLUMN otp_expire_at TIMESTAMP NULL
             COMMENT 'Expiration de l\\'OTP (1 minute après génération)'"
        );
    }
}
