<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTrRaisonRevocationAndGuotRevokeColumns extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('tr_raison_revocation')) {
            Schema::create('tr_raison_revocation', function (Blueprint $table) {
                $table->string('code_raison_revocation', 16);
                $table->primary('code_raison_revocation');
                $table->string('lib_raison_revocation', 150);
                $table->string('code_guot', 64)->comment('Valeur reason envoyée à trust-api /v1/signers/{id}/revoke');
                $table->boolean('actif')->default(true);
                $table->unsignedSmallInteger('ordre')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        $now = now();
        $raisons = [
            ['code_raison_revocation' => 'RREV_0001', 'lib_raison_revocation' => 'Départ / fin d’activité', 'code_guot' => 'cessation_of_operation', 'actif' => 1, 'ordre' => 1],
            ['code_raison_revocation' => 'RREV_0002', 'lib_raison_revocation' => 'Perte ou compromission de clé', 'code_guot' => 'key_compromise', 'actif' => 1, 'ordre' => 2],
            ['code_raison_revocation' => 'RREV_0003', 'lib_raison_revocation' => 'Changement d’affectation', 'code_guot' => 'affiliation_changed', 'actif' => 1, 'ordre' => 3],
            ['code_raison_revocation' => 'RREV_0004', 'lib_raison_revocation' => 'Remplacé par un nouveau certificat', 'code_guot' => 'superseded', 'actif' => 1, 'ordre' => 4],
            ['code_raison_revocation' => 'RREV_0005', 'lib_raison_revocation' => 'Non précisée', 'code_guot' => 'unspecified', 'actif' => 1, 'ordre' => 5],
        ];

        foreach ($raisons as $raison) {
            $exists = DB::table('tr_raison_revocation')
                ->where('code_raison_revocation', $raison['code_raison_revocation'])
                ->exists();
            if (! $exists) {
                DB::table('tr_raison_revocation')->insert(array_merge($raison, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        if (Schema::hasTable('tr_ins_user')) {
            $after = Schema::hasColumn('tr_ins_user', 'guot_user_verifier_url')
                ? 'guot_user_verifier_url'
                : (Schema::hasColumn('tr_ins_user', 'active') ? 'active' : null);

            Schema::table('tr_ins_user', function (Blueprint $table) use ($after) {
                if (! Schema::hasColumn('tr_ins_user', 'code_raison_revocation')) {
                    $col = $table->string('code_raison_revocation', 16)->nullable();
                    if ($after) {
                        $col->after($after);
                    }
                }
                if (! Schema::hasColumn('tr_ins_user', 'guot_revoke_justificatif')) {
                    $col = $table->string('guot_revoke_justificatif', 512)->nullable();
                    if (Schema::hasColumn('tr_ins_user', 'code_raison_revocation') || true) {
                        $col->after('code_raison_revocation');
                    }
                }
                if (! Schema::hasColumn('tr_ins_user', 'guot_revoked_at')) {
                    $table->timestamp('guot_revoked_at')->nullable()->after('guot_revoke_justificatif');
                }
                if (! Schema::hasColumn('tr_ins_user', 'guot_revoked_actor_id')) {
                    $table->string('guot_revoked_actor_id', 128)->nullable()->after('guot_revoked_at');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('tr_ins_user')) {
            $cols = [];
            foreach (['guot_revoked_actor_id', 'guot_revoked_at', 'guot_revoke_justificatif', 'code_raison_revocation'] as $col) {
                if (Schema::hasColumn('tr_ins_user', $col)) {
                    $cols[] = $col;
                }
            }
            if ($cols !== []) {
                Schema::table('tr_ins_user', function (Blueprint $table) use ($cols) {
                    $table->dropColumn($cols);
                });
            }
        }

        Schema::dropIfExists('tr_raison_revocation');
    }
}
