<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Signature électronique GUOT sur t_declaration_deces :
 *  - sig_fs_*  : certificat de décès (formation sanitaire, envoi au CEC/PF) ;
 *  - sig_ch_*  : certificat de constatation (centre d'hygiène, envoi au CEC/PF) ;
 *  - sig_cec_* : validation CEC/PF (déclaration générée depuis certificat FS, ou validation du certificat de constatation CH).
 */
class AddSignatureColumnsToTDeclarationDeces extends Migration
{
    /** @var list<string> */
    private array $prefixes = ['sig_fs_', 'sig_ch_', 'sig_cec_'];

    public function up(): void
    {
        Schema::table('t_declaration_deces', function (Blueprint $table) {
            foreach ($this->prefixes as $prefix) {
                $this->addSignatureColumns($table, $prefix);
            }

            $table->index('sig_fs_proof_id', 'idx_t_declaration_deces_sig_fs_proof');
            $table->index('sig_ch_proof_id', 'idx_t_declaration_deces_sig_ch_proof');
            $table->index('sig_cec_proof_id', 'idx_t_declaration_deces_sig_cec_proof');
        });
    }

    public function down(): void
    {
        Schema::table('t_declaration_deces', function (Blueprint $table) {
            $table->dropIndex('idx_t_declaration_deces_sig_fs_proof');
            $table->dropIndex('idx_t_declaration_deces_sig_ch_proof');
            $table->dropIndex('idx_t_declaration_deces_sig_cec_proof');

            $columns = [];
            foreach ($this->prefixes as $prefix) {
                $columns = array_merge($columns, $this->signatureColumnNames($prefix));
            }
            $table->dropColumn($columns);
        });
    }

    private function addSignatureColumns(Blueprint $table, string $prefix): void
    {
        $table->string($prefix.'proof_id', 128)->nullable();
        $table->string($prefix.'payload_hash', 64)->nullable();
        $table->string($prefix.'actor_id', 128)->nullable();
        $table->string($prefix.'actor_nom', 150)->nullable();
        $table->string($prefix.'cui', 64)->nullable();
        $table->string($prefix.'certificate_ref', 128)->nullable();
        $table->timestamp($prefix.'signed_at')->nullable();
        $table->string($prefix.'rfc3161_l1_serial', 128)->nullable();
        $table->string($prefix.'pdf_content_hash', 64)->nullable();
        $table->string($prefix.'doc_sig_id', 128)->nullable();
        $table->timestamp($prefix.'doc_sig_signed_at')->nullable();
        $table->string($prefix.'rfc3161_l2_serial', 128)->nullable();
        $table->string($prefix.'doc_seal_id', 128)->nullable();
        $table->timestamp($prefix.'doc_seal_sealed_at')->nullable();
        $table->string($prefix.'rfc3161_l3_serial', 128)->nullable();
        $table->string($prefix.'pdf_path')->nullable();
        $table->string($prefix.'institution_id', 128)->nullable();
    }

    /** @return list<string> */
    private function signatureColumnNames(string $prefix): array
    {
        return [
            $prefix.'proof_id',
            $prefix.'payload_hash',
            $prefix.'actor_id',
            $prefix.'actor_nom',
            $prefix.'cui',
            $prefix.'certificate_ref',
            $prefix.'signed_at',
            $prefix.'rfc3161_l1_serial',
            $prefix.'pdf_content_hash',
            $prefix.'doc_sig_id',
            $prefix.'doc_sig_signed_at',
            $prefix.'rfc3161_l2_serial',
            $prefix.'doc_seal_id',
            $prefix.'doc_seal_sealed_at',
            $prefix.'rfc3161_l3_serial',
            $prefix.'pdf_path',
            $prefix.'institution_id',
        ];
    }
}
