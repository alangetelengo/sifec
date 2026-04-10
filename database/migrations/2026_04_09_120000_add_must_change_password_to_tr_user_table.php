<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tr_user', function (Blueprint $table) {
            $table->boolean('must_change_password')->default(false)->after('password')
                ->comment('Mot de passe provisoire : l’utilisateur doit le changer à la première connexion');
        });
    }

    public function down(): void
    {
        Schema::table('tr_user', function (Blueprint $table) {
            $table->dropColumn('must_change_password');
        });
    }
};
