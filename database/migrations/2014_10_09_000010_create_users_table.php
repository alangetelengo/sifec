<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_user', function (Blueprint $table) {
            $table->primary("code_user");
            $table->string("code_user",16);
            $table->string("pseudo",30)->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('pseudo_verified_at')->nullable();
            $table->string('password');
            $table->text('google2fa_secret')->nullable()->comment('Secret chiffré pour l\'authentification 2FA');
            $table->boolean('google2fa_enabled')->default(false)->comment('Indique si la 2FA est activée pour cet utilisateur');
            $table->text('recovery_codes')->nullable()->comment('Codes de récupération chiffrés (8 codes)');
            $table->timestamp('two_factor_verified_at')->nullable()->comment('Date et heure de la dernière vérification 2FA réussie');
            $table->enum('two_factor_method', ['totp', 'sms', 'email'])->default('totp')->comment('Méthode de 2FA utilisée (TOTP par défaut)');
            $table->boolean('two_factor_required')->default(false)->comment('Si true, l\'utilisateur doit activer la 2FA');
            $table->rememberToken();
            $table->boolean('status')->default(true);
            $table->string("code_personne",16);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("code_personne")->references("code_personne")->on("tr_identification_personne")->onDelete("cascade")->onUpdate('cascade');
        });



        Schema::create("tr_uf", function (Blueprint $table) {
            $table->primary(["code_user","code_fonctionnalite"]);
            $table->string("code_user",16);
            $table->string("code_fonctionnalite",16);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("code_user")->references("code_user")->on("tr_user")->onDelete("cascade")->onUpdate('cascade');
            $table->foreign("code_fonctionnalite")->references("code_fonctionnalite")->on("tr_fonctionnalite")->onDelete("cascade")->onUpdate('cascade');
        });

        Schema::create("tr_ins_user", function (Blueprint $table) {
            $table->primary(["cui","code_institution","code_user"]); //cui : code user institution
            $table->string("cui",16);
            $table->string("code_institution",16);
            $table->string("code_user",16);
            $table->string("code_fonction",16);
            $table->boolean("active")->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Requis pour les FK qui référencent uniquement `cui` (la PK composite ne suffit pas pour MySQL).
            $table->unique('cui');

            $table->foreign("code_institution")->references("code_institution")->on("tr_institution")->onDelete("cascade")->onUpdate('cascade');
            $table->foreign("code_user")->references("code_user")->on("tr_user")->onDelete("cascade")->onUpdate('cascade');
            $table->foreign("code_fonction")->references("code_fonction")->on("tr_fonction")->onDelete("cascade")->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_institution_user');
        Schema::dropIfExists('users');
    }
}
