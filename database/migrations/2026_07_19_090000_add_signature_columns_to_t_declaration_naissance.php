<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Signature électronique GUOT du certificat de naissance (formation sanitaire, préfixe sig_fs_)
 * et de la déclaration de naissance (centre d'état civil, préfixe sig_cec_).
 *
 * Deux jeux de colonnes de preuve distincts sur la même ligne t_declaration_naissance :
 *  - sig_fs_*  : signature L2 du chef de service + cachet L3 de la formation sanitaire (envoi au CEC) ;
 *  - sig_cec_* : signature L2 du responsable CEC + cachet L3 du CEC (confirmation avant génération de l'acte).
 */
class AddSignatureColumnsToTDeclarationNaissance extends Migration
{
    public function up(): void
    {
        Schema::table('t_declaration_naissance', function (Blueprint $table) {
            // ── Signature Formation sanitaire (certificat de naissance) ──
            $table->string('sig_fs_proof_id', 128)->nullable();
            $table->string('sig_fs_payload_hash', 64)->nullable();
            $table->string('sig_fs_actor_id', 128)->nullable();
            $table->string('sig_fs_actor_nom', 150)->nullable();
            $table->string('sig_fs_cui', 64)->nullable();
            $table->string('sig_fs_certificate_ref', 128)->nullable();
            $table->timestamp('sig_fs_signed_at')->nullable();
            $table->string('sig_fs_rfc3161_l1_serial', 128)->nullable();
            $table->string('sig_fs_pdf_content_hash', 64)->nullable();
            $table->string('sig_fs_doc_sig_id', 128)->nullable();
            $table->timestamp('sig_fs_doc_sig_signed_at')->nullable();
            $table->string('sig_fs_rfc3161_l2_serial', 128)->nullable();
            $table->string('sig_fs_doc_seal_id', 128)->nullable();
            $table->timestamp('sig_fs_doc_seal_sealed_at')->nullable();
            $table->string('sig_fs_rfc3161_l3_serial', 128)->nullable();
            $table->string('sig_fs_pdf_path')->nullable();
            $table->string('sig_fs_institution_id', 128)->nullable();

            // ── Signature Centre d'état civil (déclaration de naissance) ──
            $table->string('sig_cec_proof_id', 128)->nullable();
            $table->string('sig_cec_payload_hash', 64)->nullable();
            $table->string('sig_cec_actor_id', 128)->nullable();
            $table->string('sig_cec_actor_nom', 150)->nullable();
            $table->string('sig_cec_cui', 64)->nullable();
            $table->string('sig_cec_certificate_ref', 128)->nullable();
            $table->timestamp('sig_cec_signed_at')->nullable();
            $table->string('sig_cec_rfc3161_l1_serial', 128)->nullable();
            $table->string('sig_cec_pdf_content_hash', 64)->nullable();
            $table->string('sig_cec_doc_sig_id', 128)->nullable();
            $table->timestamp('sig_cec_doc_sig_signed_at')->nullable();
            $table->string('sig_cec_rfc3161_l2_serial', 128)->nullable();
            $table->string('sig_cec_doc_seal_id', 128)->nullable();
            $table->timestamp('sig_cec_doc_seal_sealed_at')->nullable();
            $table->string('sig_cec_rfc3161_l3_serial', 128)->nullable();
            $table->string('sig_cec_pdf_path')->nullable();
            $table->string('sig_cec_institution_id', 128)->nullable();

            $table->index('sig_fs_proof_id', 'idx_t_declaration_naissance_sig_fs_proof');
            $table->index('sig_cec_proof_id', 'idx_t_declaration_naissance_sig_cec_proof');
        });
    }

    public function down(): void
    {
        Schema::table('t_declaration_naissance', function (Blueprint $table) {
            $table->dropIndex('idx_t_declaration_naissance_sig_fs_proof');
            $table->dropIndex('idx_t_declaration_naissance_sig_cec_proof');
            $table->dropColumn([
                'sig_fs_proof_id',
                'sig_fs_payload_hash',
                'sig_fs_actor_id',
                'sig_fs_actor_nom',
                'sig_fs_cui',
                'sig_fs_certificate_ref',
                'sig_fs_signed_at',
                'sig_fs_rfc3161_l1_serial',
                'sig_fs_pdf_content_hash',
                'sig_fs_doc_sig_id',
                'sig_fs_doc_sig_signed_at',
                'sig_fs_rfc3161_l2_serial',
                'sig_fs_doc_seal_id',
                'sig_fs_doc_seal_sealed_at',
                'sig_fs_rfc3161_l3_serial',
                'sig_fs_pdf_path',
                'sig_fs_institution_id',
                'sig_cec_proof_id',
                'sig_cec_payload_hash',
                'sig_cec_actor_id',
                'sig_cec_actor_nom',
                'sig_cec_cui',
                'sig_cec_certificate_ref',
                'sig_cec_signed_at',
                'sig_cec_rfc3161_l1_serial',
                'sig_cec_pdf_content_hash',
                'sig_cec_doc_sig_id',
                'sig_cec_doc_sig_signed_at',
                'sig_cec_rfc3161_l2_serial',
                'sig_cec_doc_seal_id',
                'sig_cec_doc_seal_sealed_at',
                'sig_cec_rfc3161_l3_serial',
                'sig_cec_pdf_path',
                'sig_cec_institution_id',
            ]);
        });
    }
}
