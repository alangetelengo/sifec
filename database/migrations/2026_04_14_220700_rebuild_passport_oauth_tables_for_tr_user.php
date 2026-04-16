<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Recrée les tables Passport (OAuth2) avec un schéma compatible Laravel Passport 13
 * et user_id aligné sur tr_user.code_user (string 16).
 *
 * À utiliser en développement lorsque d’anciennes tables OAuth (ex. id entier sur oauth_clients)
 * empêchent passport:install / émission de tokens.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('oauth_auth_codes');
        Schema::dropIfExists('oauth_access_tokens');
        Schema::dropIfExists('oauth_refresh_tokens');
        Schema::dropIfExists('oauth_device_codes');
        Schema::dropIfExists('oauth_clients');

        Schema::enableForeignKeyConstraints();

        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('owner_id', 16)->nullable();
            $table->string('owner_type')->nullable();
            $table->index(['owner_id', 'owner_type']);
            $table->string('name');
            $table->string('secret')->nullable();
            $table->string('provider')->nullable();
            $table->text('redirect_uris');
            $table->text('grant_types');
            $table->boolean('revoked');
            $table->timestamps();
        });

        Schema::create('oauth_auth_codes', function (Blueprint $table) {
            $table->char('id', 80)->primary();
            $table->string('user_id', 16);
            $table->foreign('user_id')->references('code_user')->on('tr_user')->cascadeOnDelete();
            $table->foreignUuid('client_id')->constrained('oauth_clients');
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->dateTime('expires_at')->nullable();
        });

        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->char('id', 80)->primary();
            $table->string('user_id', 16)->nullable();
            $table->foreign('user_id')->references('code_user')->on('tr_user')->nullOnDelete();
            $table->foreignUuid('client_id')->constrained('oauth_clients');
            $table->string('name')->nullable();
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->timestamps();
            $table->dateTime('expires_at')->nullable();
        });

        Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
            $table->char('id', 80)->primary();
            $table->char('access_token_id', 80)->index();
            $table->boolean('revoked');
            $table->dateTime('expires_at')->nullable();
        });

        Schema::create('oauth_device_codes', function (Blueprint $table) {
            $table->char('id', 80)->primary();
            $table->string('user_id', 16)->nullable();
            $table->foreign('user_id')->references('code_user')->on('tr_user')->nullOnDelete();
            $table->foreignUuid('client_id')->constrained('oauth_clients');
            $table->char('user_code', 8)->unique();
            $table->text('scopes');
            $table->boolean('revoked');
            $table->dateTime('user_approved_at')->nullable();
            $table->dateTime('last_polled_at')->nullable();
            $table->dateTime('expires_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('oauth_device_codes');
        Schema::dropIfExists('oauth_refresh_tokens');
        Schema::dropIfExists('oauth_access_tokens');
        Schema::dropIfExists('oauth_auth_codes');
        Schema::dropIfExists('oauth_clients');
        Schema::enableForeignKeyConstraints();
    }

    public function getConnection(): ?string
    {
        return $this->connection ?? config('passport.connection');
    }
};
