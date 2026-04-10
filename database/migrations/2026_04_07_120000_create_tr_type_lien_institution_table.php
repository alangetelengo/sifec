<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTrTypeLienInstitutionTable extends Migration
{
    public function up(): void
    {
        Schema::create('tr_type_lien_institution', function (Blueprint $table) {
            $table->string('code_type_lien', 16)->primary();
            $table->string('lib_type_lien', 150);
            $table->string('description', 500)->nullable();
            $table->timestamps();
        });

        $now = now();
        DB::table('tr_type_lien_institution')->insert([
            [
                'code_type_lien' => 'TPLIEN_0001',
                'lib_type_lien' => 'Partenaire pompe funèbre (décès vers CEC)',
                'description' => 'L’institution source (pompe / partenaire) transmet les dossiers décès vers le CEC cible. Reprend l’ancien tr_institution.code_pompe_funebre (cible = CEC).',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code_type_lien' => 'TPLIEN_0002',
                'lib_type_lien' => 'Tribunal de ressort',
                'description' => 'Lien métier tribunal ↔ centre d’état civil (réquisitions, jugements). À alimenter progressivement.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code_type_lien' => 'TPLIEN_0003',
                'lib_type_lien' => 'CEC destinataire naissances',
                'description' => 'Formation sanitaire ou structure émettrice → CEC qui reçoit les certificats / déclarations de naissance.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tr_type_lien_institution');
    }
}
