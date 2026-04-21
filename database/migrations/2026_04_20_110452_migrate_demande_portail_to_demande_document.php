<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifier si la table tr_demande_portail_particulier existe
        if (! Schema::hasTable('tr_demande_portail_particulier')) {
            // Table n'existe pas, migration non nécessaire
            return;
        }

        // Migrer les données de tr_demande_portail_particulier vers t_demande_document
        $demandesPortail = DB::table('tr_demande_portail_particulier')->get();

        foreach ($demandesPortail as $demandePortail) {
            // Mapper le type_acte (Naissance, Mariage, Décès) vers code_type_acte (TAC_0001, TAC_0002, TAC_0004)
            $codeTypeActe = null;
            if ($demandePortail->type_acte == 'Naissance') {
                $codeTypeActe = 'TAC_0001';
            } elseif ($demandePortail->type_acte == 'Mariage') {
                $codeTypeActe = 'TAC_0002';
            } elseif ($demandePortail->type_acte == 'Décès') {
                $codeTypeActe = 'TAC_0004';
            }

            // Mapper le type_document vers code_type_document_demande (TDD_0001=Copie, TDD_0002=Extrait)
            $codeTypeDocument = null;
            if (stripos($demandePortail->type_document ?? '', 'Copie') !== false) {
                $codeTypeDocument = 'TDD_0001';
            } elseif (stripos($demandePortail->type_document ?? '', 'Extrait') !== false) {
                $codeTypeDocument = 'TDD_0002';
            }

            // Mapper le statut_demande
            $statut = 'En traitement';
            if (isset($demandePortail->statut_demande)) {
                $statutPortail = $demandePortail->statut_demande;
                if (stripos($statutPortail, 'attente') !== false && stripos($statutPortail, 'paiement') !== false) {
                    $statut = 'En attente de paiement';
                } elseif (stripos($statutPortail, 'traité') !== false || stripos($statutPortail, 'validé') !== false) {
                    $statut = 'Traitée';
                }
            }

            // Récupérer l'institution CEC associée si possible
            $codeInstitution = null;
            if (! empty($demandePortail->cec_associe)) {
                $institution = DB::table('tr_institution')
                    ->where('lib_institution', $demandePortail->cec_associe)
                    ->first();
                if ($institution) {
                    $codeInstitution = $institution->code_institution;
                }
            }

            // Générer un code_demande_document unique si nécessaire
            $codeDemandeDocument = 'DMDP_'.str_pad($demandePortail->code_demande, 10, '0', STR_PAD_LEFT);

            // Insérer dans t_demande_document
            DB::table('t_demande_document')->insert([
                'code_demande_document' => $codeDemandeDocument,
                'origine_demande' => 'portail',
                'nom_demandeur' => $demandePortail->nom_demandeur ?? '',
                'prenom_demander' => $demandePortail->prenom_demandeur ?? null,
                'sexe_demander' => $demandePortail->sexe_demandeur ?? 'M',
                'telephone_demander' => $demandePortail->telephone_demandeur ?? '',
                'email_demandeur' => $demandePortail->email_demandeur ?? null,
                'numero_acte' => $demandePortail->num_acte ?? null,
                'code_type_acte' => $codeTypeActe,
                'code_type_document_demande' => $codeTypeDocument,
                'code_institution' => $codeInstitution,
                'prix' => $demandePortail->cout ?? null,
                'statut' => $statut,
                'date_demande' => $demandePortail->dateDemande ?? $demandePortail->created_at ?? now(),
                'created_at' => $demandePortail->created_at ?? now(),
                'updated_at' => $demandePortail->updated_at ?? now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les demandes migrées depuis le portail
        DB::table('t_demande_document')
            ->where('origine_demande', 'portail')
            ->delete();
    }
};
