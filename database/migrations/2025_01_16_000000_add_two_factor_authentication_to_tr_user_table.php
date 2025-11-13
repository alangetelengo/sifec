<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoFactorAuthenticationToTrUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tr_user', function (Blueprint $table) {
            // Secret key chiffré pour Google2FA
            $table->text('google2fa_secret')->nullable()->after('password')
                ->comment('Secret chiffré pour l\'authentification 2FA');

            // Flag d'activation de la 2FA
            $table->boolean('google2fa_enabled')->default(false)->after('google2fa_secret')
                ->comment('Indique si la 2FA est activée pour cet utilisateur');

            // Codes de récupération chiffrés (backup codes)
            $table->text('recovery_codes')->nullable()->after('google2fa_enabled')
                ->comment('Codes de récupération chiffrés (8 codes)');

            // Date de la dernière vérification 2FA réussie
            $table->timestamp('two_factor_verified_at')->nullable()->after('recovery_codes')
                ->comment('Date et heure de la dernière vérification 2FA réussie');

            // Méthode 2FA préférée (pour évolution future: totp, sms, email)
            $table->enum('two_factor_method', ['totp', 'sms', 'email'])->default('totp')->after('two_factor_verified_at')
                ->comment('Méthode de 2FA utilisée (TOTP par défaut)');

            // Forcer la 2FA pour cet utilisateur (pour admins)
            $table->boolean('two_factor_required')->default(false)->after('two_factor_method')
                ->comment('Si true, l\'utilisateur doit activer la 2FA');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tr_user', function (Blueprint $table) {
            $table->dropColumn([
                'google2fa_secret',
                'google2fa_enabled',
                'recovery_codes',
                'two_factor_verified_at',
                'two_factor_method',
                'two_factor_required'
            ]);
        });
    }
}

