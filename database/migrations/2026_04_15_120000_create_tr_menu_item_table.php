<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tr_menu_item', function (Blueprint $table) {
            $table->string('code_menu_item', 40)->primary();
            $table->string('code_parent', 40)->nullable()->index();
            $table->string('libelle', 191);
            $table->string('lib_icone', 120)->nullable();
            $table->string('route_name', 160)->nullable();
            $table->string('external_path', 255)->nullable();
            $table->string('permission_gate', 160)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_group')->default(false);
            $table->json('visibility_hide_fonctions')->nullable();
            $table->json('visibility_show_only_fonctions')->nullable();
            $table->string('anchor_class', 255)->nullable();
            $table->string('anchor_extra_classes', 255)->nullable();
            $table->timestamps();

            $table->foreign('code_parent')
                ->references('code_menu_item')
                ->on('tr_menu_item')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_menu_item');
    }
};
