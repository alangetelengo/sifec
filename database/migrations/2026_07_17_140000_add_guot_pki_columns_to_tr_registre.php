<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tr_registre', function (Blueprint $table) {
            if (! Schema::hasColumn('tr_registre', 'proof_id')) {
                $table->string('proof_id', 128)->nullable()->after('approbation_tribunal');
            }
            if (! Schema::hasColumn('tr_registre', 'payload_hash')) {
                $table->string('payload_hash', 64)->nullable()->after('proof_id');
            }
            if (! Schema::hasColumn('tr_registre', 'actor_id')) {
                $table->string('actor_id', 128)->nullable()->after('payload_hash');
            }
            if (! Schema::hasColumn('tr_registre', 'actor_nom')) {
                $table->string('actor_nom', 190)->nullable()->after('actor_id');
            }
            if (! Schema::hasColumn('tr_registre', 'certificate_ref')) {
                $table->string('certificate_ref', 255)->nullable()->after('actor_nom');
            }
            if (! Schema::hasColumn('tr_registre', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('certificate_ref');
            }
            if (! Schema::hasColumn('tr_registre', 'rfc3161_l1_serial')) {
                $table->string('rfc3161_l1_serial', 128)->nullable()->after('signed_at');
            }
            if (! Schema::hasColumn('tr_registre', 'pdf_content_hash')) {
                $table->string('pdf_content_hash', 64)->nullable()->after('rfc3161_l1_serial');
            }
            if (! Schema::hasColumn('tr_registre', 'doc_sig_id')) {
                $table->string('doc_sig_id', 128)->nullable()->after('pdf_content_hash');
            }
            if (! Schema::hasColumn('tr_registre', 'doc_sig_signed_at')) {
                $table->timestamp('doc_sig_signed_at')->nullable()->after('doc_sig_id');
            }
            if (! Schema::hasColumn('tr_registre', 'rfc3161_l2_serial')) {
                $table->string('rfc3161_l2_serial', 128)->nullable()->after('doc_sig_signed_at');
            }
            if (! Schema::hasColumn('tr_registre', 'doc_seal_id')) {
                $table->string('doc_seal_id', 128)->nullable()->after('rfc3161_l2_serial');
            }
            if (! Schema::hasColumn('tr_registre', 'doc_seal_sealed_at')) {
                $table->timestamp('doc_seal_sealed_at')->nullable()->after('doc_seal_id');
            }
            if (! Schema::hasColumn('tr_registre', 'rfc3161_l3_serial')) {
                $table->string('rfc3161_l3_serial', 128)->nullable()->after('doc_seal_sealed_at');
            }
            if (! Schema::hasColumn('tr_registre', 'pdf_path')) {
                $table->string('pdf_path', 255)->nullable()->after('rfc3161_l3_serial');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tr_registre', function (Blueprint $table) {
            $cols = [
                'proof_id',
                'payload_hash',
                'actor_id',
                'actor_nom',
                'certificate_ref',
                'signed_at',
                'rfc3161_l1_serial',
                'pdf_content_hash',
                'doc_sig_id',
                'doc_sig_signed_at',
                'rfc3161_l2_serial',
                'doc_seal_id',
                'doc_seal_sealed_at',
                'rfc3161_l3_serial',
                'pdf_path',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('tr_registre', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
