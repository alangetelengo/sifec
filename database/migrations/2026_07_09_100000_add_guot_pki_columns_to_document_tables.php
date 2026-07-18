<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuotPkiColumnsToDocumentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_acte_naissance', function (Blueprint $table) {
            $table->string('proof_id', 128)->nullable()->after('nom_appareil_approbation');
            $table->string('payload_hash', 64)->nullable()->after('proof_id');
            $table->string('actor_id', 128)->nullable()->after('payload_hash');
            $table->string('actor_nom', 150)->nullable()->after('actor_id');
            $table->string('certificate_ref', 128)->nullable()->after('actor_nom');
            $table->timestamp('signed_at')->nullable()->after('certificate_ref');
            $table->string('rfc3161_l1_serial', 128)->nullable()->after('signed_at');
            $table->string('pdf_content_hash', 64)->nullable()->after('rfc3161_l1_serial');
            $table->string('doc_sig_id', 128)->nullable()->after('pdf_content_hash');
            $table->timestamp('doc_sig_signed_at')->nullable()->after('doc_sig_id');
            $table->string('rfc3161_l2_serial', 128)->nullable()->after('doc_sig_signed_at');
            $table->string('doc_seal_id', 128)->nullable()->after('rfc3161_l2_serial');
            $table->timestamp('doc_seal_sealed_at')->nullable()->after('doc_seal_id');
            $table->string('rfc3161_l3_serial', 128)->nullable()->after('doc_seal_sealed_at');
            $table->string('pdf_path')->nullable()->after('rfc3161_l3_serial');
            $table->unsignedInteger('print_count')->default(0)->after('pdf_path');
            $table->timestamp('first_delivered_at')->nullable()->after('print_count');

            $table->index('proof_id', 'idx_t_acte_naissance_proof_id');
            $table->index('actor_id', 'idx_t_acte_naissance_actor_id');
            $table->index('doc_sig_id', 'idx_t_acte_naissance_doc_sig_id');
        });

        Schema::table('t_acte_deces', function (Blueprint $table) {
            $table->string('proof_id', 128)->nullable()->after('nom_appareil_approbation');
            $table->string('payload_hash', 64)->nullable()->after('proof_id');
            $table->string('actor_id', 128)->nullable()->after('payload_hash');
            $table->string('actor_nom', 150)->nullable()->after('actor_id');
            $table->string('certificate_ref', 128)->nullable()->after('actor_nom');
            $table->timestamp('signed_at')->nullable()->after('certificate_ref');
            $table->string('rfc3161_l1_serial', 128)->nullable()->after('signed_at');
            $table->string('pdf_content_hash', 64)->nullable()->after('rfc3161_l1_serial');
            $table->string('doc_sig_id', 128)->nullable()->after('pdf_content_hash');
            $table->timestamp('doc_sig_signed_at')->nullable()->after('doc_sig_id');
            $table->string('rfc3161_l2_serial', 128)->nullable()->after('doc_sig_signed_at');
            $table->string('doc_seal_id', 128)->nullable()->after('rfc3161_l2_serial');
            $table->timestamp('doc_seal_sealed_at')->nullable()->after('doc_seal_id');
            $table->string('rfc3161_l3_serial', 128)->nullable()->after('doc_seal_sealed_at');
            $table->string('pdf_path')->nullable()->after('rfc3161_l3_serial');
            $table->unsignedInteger('print_count')->default(0)->after('pdf_path');
            $table->timestamp('first_delivered_at')->nullable()->after('print_count');

            $table->index('proof_id', 'idx_t_acte_deces_proof_id');
            $table->index('actor_id', 'idx_t_acte_deces_actor_id');
            $table->index('doc_sig_id', 'idx_t_acte_deces_doc_sig_id');
        });

        Schema::table('t_acte_mariage', function (Blueprint $table) {
            $table->string('proof_id', 128)->nullable()->after('nom_appareil_approbation');
            $table->string('payload_hash', 64)->nullable()->after('proof_id');
            $table->string('actor_id', 128)->nullable()->after('payload_hash');
            $table->string('actor_nom', 150)->nullable()->after('actor_id');
            $table->string('certificate_ref', 128)->nullable()->after('actor_nom');
            $table->timestamp('signed_at')->nullable()->after('certificate_ref');
            $table->string('rfc3161_l1_serial', 128)->nullable()->after('signed_at');
            $table->string('pdf_content_hash', 64)->nullable()->after('rfc3161_l1_serial');
            $table->string('doc_sig_id', 128)->nullable()->after('pdf_content_hash');
            $table->timestamp('doc_sig_signed_at')->nullable()->after('doc_sig_id');
            $table->string('rfc3161_l2_serial', 128)->nullable()->after('doc_sig_signed_at');
            $table->string('doc_seal_id', 128)->nullable()->after('rfc3161_l2_serial');
            $table->timestamp('doc_seal_sealed_at')->nullable()->after('doc_seal_id');
            $table->string('rfc3161_l3_serial', 128)->nullable()->after('doc_seal_sealed_at');
            $table->string('pdf_path')->nullable()->after('rfc3161_l3_serial');
            $table->unsignedInteger('print_count')->default(0)->after('pdf_path');
            $table->timestamp('first_delivered_at')->nullable()->after('print_count');

            $table->index('proof_id', 'idx_t_acte_mariage_proof_id');
            $table->index('actor_id', 'idx_t_acte_mariage_actor_id');
            $table->index('doc_sig_id', 'idx_t_acte_mariage_doc_sig_id');
        });

        Schema::table('t_demande_document', function (Blueprint $table) {
            $table->string('proof_id', 128)
                ->nullable()
                ->after('statut')
                ->comment('Identifiant de preuve Layer 1 retourné par trust-api');
            $table->string('payload_hash', 64)
                ->nullable()
                ->after('proof_id')
                ->comment('Hash du payload métier signé (JSON canonicalisé)');
            $table->string('actor_id', 128)
                ->nullable()
                ->after('payload_hash')
                ->comment('actor_id GUOT du signataire');
            $table->string('actor_nom', 150)
                ->nullable()
                ->after('actor_id')
                ->comment('Nom ou libellé du signataire utilisé pour affichage');
            $table->string('certificate_ref', 128)
                ->nullable()
                ->after('actor_nom')
                ->comment('Référence du certificat X.509 du signataire');
            $table->timestamp('signed_at')
                ->nullable()
                ->after('certificate_ref')
                ->comment('Horodatage de signature Layer 1');
            $table->string('rfc3161_l1_serial', 128)
                ->nullable()
                ->after('signed_at')
                ->comment('Numéro de série RFC3161 du timestamp L1');
            $table->string('pdf_content_hash', 64)
                ->nullable()
                ->after('rfc3161_l1_serial')
                ->comment('Hash SHA-256 du PDF généré avant cartouche');
            $table->string('doc_sig_id', 128)
                ->nullable()
                ->after('pdf_content_hash')
                ->comment('Identifiant de signature documentaire Layer 2');
            $table->timestamp('doc_sig_signed_at')
                ->nullable()
                ->after('doc_sig_id')
                ->comment('Horodatage de signature documentaire L2');
            $table->string('rfc3161_l2_serial', 128)
                ->nullable()
                ->after('doc_sig_signed_at')
                ->comment('Numéro de série RFC3161 du timestamp L2');
            $table->string('doc_seal_id', 128)
                ->nullable()
                ->after('rfc3161_l2_serial')
                ->comment('Identifiant de cachet institutionnel Layer 3');
            $table->timestamp('doc_seal_sealed_at')
                ->nullable()
                ->after('doc_seal_id')
                ->comment('Horodatage du cachet institutionnel L3');
            $table->string('rfc3161_l3_serial', 128)
                ->nullable()
                ->after('doc_seal_sealed_at')
                ->comment('Numéro de série RFC3161 du timestamp L3');
            $table->string('pdf_path')
                ->nullable()
                ->after('rfc3161_l3_serial')
                ->comment('Chemin de stockage du PDF final signé');
            $table->unsignedInteger('print_count')
                ->default(0)
                ->after('pdf_path')
                ->comment('Nombre d\'impressions du document final');
            $table->timestamp('first_delivered_at')
                ->nullable()
                ->after('print_count')
                ->comment('Date de première livraison ou mise à disposition');

            $table->index('proof_id', 'idx_t_demande_document_proof_id');
            $table->index('actor_id', 'idx_t_demande_document_actor_id');
            $table->index('doc_sig_id', 'idx_t_demande_document_doc_sig_id');
        });

        Schema::table('tr_institution', function (Blueprint $table) {
            $table->string('guot_institution_id', 128)
                ->nullable()
                ->after('sceau')
                ->comment('Identifiant institutionnel GUOT/Signum pour le cachet L3');
            $table->string('guot_institution_cert_serial', 128)
                ->nullable()
                ->after('guot_institution_id')
                ->comment('Numéro de série du certificat institutionnel GUOT');
            $table->timestamp('guot_institution_cert_not_before')
                ->nullable()
                ->after('guot_institution_cert_serial')
                ->comment('Début de validité du certificat institutionnel GUOT');
            $table->timestamp('guot_institution_cert_not_after')
                ->nullable()
                ->after('guot_institution_cert_not_before')
                ->comment('Fin de validité du certificat institutionnel GUOT');
            $table->string('guot_institution_verifier_url', 255)
                ->nullable()
                ->after('guot_institution_cert_not_after')
                ->comment('URL publique de vérification fournie par GUOT / Signum');
        });

        Schema::table('tr_ins_user', function (Blueprint $table) {
            $table->string('guot_user_id', 128)
                ->nullable()
                ->after('code_fonction')
                ->comment('Identifiant utilisateur GUOT/Signum pour la signature ou le cachet');
            $table->string('guot_user_cert_serial', 128)
                ->nullable()
                ->after('guot_user_id')
                ->comment('Numéro de série du certificat utilisateur GUOT');
            $table->timestamp('guot_user_cert_not_before')
                ->nullable()
                ->after('guot_user_cert_serial')
                ->comment('Début de validité du certificat utilisateur GUOT');
            $table->timestamp('guot_user_cert_not_after')
                ->nullable()
                ->after('guot_user_cert_not_before')
                ->comment('Fin de validité du certificat utilisateur GUOT');
            $table->string('guot_user_verifier_url', 255)
                ->nullable()
                ->after('guot_user_cert_not_after')
                ->comment('URL publique de vérification du certificat utilisateur GUOT / Signum');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_acte_naissance', function (Blueprint $table) {
            $table->dropIndex('idx_t_acte_naissance_proof_id');
            $table->dropIndex('idx_t_acte_naissance_actor_id');
            $table->dropIndex('idx_t_acte_naissance_doc_sig_id');
            $table->dropColumn([
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
                'print_count',
                'first_delivered_at',
            ]);
        });

        Schema::table('t_acte_deces', function (Blueprint $table) {
            $table->dropIndex('idx_t_acte_deces_proof_id');
            $table->dropIndex('idx_t_acte_deces_actor_id');
            $table->dropIndex('idx_t_acte_deces_doc_sig_id');
            $table->dropColumn([
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
                'print_count',
                'first_delivered_at',
            ]);
        });

        Schema::table('t_acte_mariage', function (Blueprint $table) {
            $table->dropIndex('idx_t_acte_mariage_proof_id');
            $table->dropIndex('idx_t_acte_mariage_actor_id');
            $table->dropIndex('idx_t_acte_mariage_doc_sig_id');
            $table->dropColumn([
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
                'print_count',
                'first_delivered_at',
            ]);
        });

        Schema::table('t_demande_document', function (Blueprint $table) {
            $table->dropIndex('idx_t_demande_document_proof_id');
            $table->dropIndex('idx_t_demande_document_actor_id');
            $table->dropIndex('idx_t_demande_document_doc_sig_id');
            $table->dropColumn([
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
                'print_count',
                'first_delivered_at',
            ]);
        });

        Schema::table('tr_institution', function (Blueprint $table) {
            $table->dropColumn([
                'guot_institution_id',
                'guot_institution_cert_serial',
                'guot_institution_cert_not_before',
                'guot_institution_cert_not_after',
                'guot_institution_verifier_url',
            ]);
        });

        Schema::table('tr_ins_user', function (Blueprint $table) {
            $table->dropColumn([
                'guot_user_id',
                'guot_user_cert_serial',
                'guot_user_cert_not_before',
                'guot_user_cert_not_after',
                'guot_user_verifier_url',
            ]);
        });
    }
}
