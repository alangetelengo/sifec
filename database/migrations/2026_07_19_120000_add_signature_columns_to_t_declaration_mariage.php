<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Signature électronique GUOT de la déclaration de mariage (centre d'état civil, préfixe sig_cec_).
 *
 * La déclaration de mariage est signée par le responsable du centre d'état civil au moment de la
 * confirmation du dossier (MOUV_0019). Cette signature (L2 personnelle + cachet L3 institutionnel)
 * est un prérequis à la génération de l'acte de mariage — même patron que la naissance.
 */
class AddSignatureColumnsToTDeclarationMariage extends Migration
{
    public function up(): void
    {
        Schema::table('t_declaration_mariage', function (Blueprint $table) {
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

            $table->index('sig_cec_proof_id', 'idx_t_declaration_mariage_sig_cec_proof');
        });
    }

    public function down(): void
    {
        Schema::table('t_declaration_mariage', function (Blueprint $table) {
            $table->dropIndex('idx_t_declaration_mariage_sig_cec_proof');
            $table->dropColumn([
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
