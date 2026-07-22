<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute les colonnes des pièces d'identité (époux, épouse et témoins) à t_declaration_mariage.
 *
 * Ces colonnes sont référencées par le code (MariageController::store et ::storePiece, ainsi que la
 * vue declaration/show), mais la migration d'origine était absente du dépôt, provoquant l'erreur SQL
 * « Unknown column 'piece_epoux' in 'field list' ». Migration idempotente (vérifie hasColumn).
 */
class AddPieceFieldsToTDeclarationMariage extends Migration
{
    /**
     * @var list<array{0: string, 1: string}>  [nom_colonne, commentaire]
     */
    private array $colonnes = [
        ['piece_epoux', "Chemin vers la pièce d'identité de l'époux"],
        ['piece_epouse', "Chemin vers la pièce d'identité de l'épouse"],
        ['piece_temoins', "Chemin vers les pièces d'identité des témoins"],
        ['piece_temoin_homme_epoux', "Chemin vers la pièce d'identité du témoin homme de l'époux"],
        ['piece_temoin_femme_epoux', "Chemin vers la pièce d'identité du témoin femme de l'époux"],
        ['piece_temoin_homme_epouse', "Chemin vers la pièce d'identité du témoin homme de l'épouse"],
        ['piece_temoin_femme_epouse', "Chemin vers la pièce d'identité du témoin femme de l'épouse"],
    ];

    public function up(): void
    {
        Schema::table('t_declaration_mariage', function (Blueprint $table) {
            foreach ($this->colonnes as [$nom, $commentaire]) {
                if (! Schema::hasColumn('t_declaration_mariage', $nom)) {
                    $table->string($nom, 255)->nullable()->comment($commentaire);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('t_declaration_mariage', function (Blueprint $table) {
            $aSupprimer = [];
            foreach ($this->colonnes as [$nom]) {
                if (Schema::hasColumn('t_declaration_mariage', $nom)) {
                    $aSupprimer[] = $nom;
                }
            }

            if ($aSupprimer !== []) {
                $table->dropColumn($aSupprimer);
            }
        });
    }
}
