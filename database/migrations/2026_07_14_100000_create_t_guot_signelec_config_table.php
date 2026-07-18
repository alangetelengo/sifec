<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('t_guot_signelec_config', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->json('signataire_fonctions');
            $table->timestamps();
        });

        $defaults = config('sifec.guot.signataire_fonctions', [
            'FONC_0002',
            'FONC_0021',
            'FONC_0009',
        ]);

        DB::table('t_guot_signelec_config')->insert([
            'id' => 1,
            'signataire_fonctions' => json_encode(array_values($defaults)),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('t_guot_signelec_config');
    }
};
