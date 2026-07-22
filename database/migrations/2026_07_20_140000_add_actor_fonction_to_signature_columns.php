<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fonction institutionnelle du signataire au moment de la signature GUOT.
 */
class AddActorFonctionToSignatureColumns extends Migration
{
    /** @var array<string, list<string>> */
    private array $tables = [
        't_declaration_deces' => ['sig_fs_', 'sig_ch_', 'sig_cec_'],
        't_declaration_naissance' => ['sig_fs_', 'sig_cec_'],
        't_declaration_mariage' => ['sig_cec_'],
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName => $prefixes) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $prefixes) {
                foreach ($prefixes as $prefix) {
                    $col = $prefix.'actor_fonction';
                    if (! Schema::hasColumn($tableName, $col)) {
                        $table->string($col, 150)->nullable()->after($prefix.'actor_nom');
                    }
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName => $prefixes) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName, $prefixes) {
                $columns = [];
                foreach ($prefixes as $prefix) {
                    $col = $prefix.'actor_fonction';
                    if (Schema::hasColumn($tableName, $col)) {
                        $columns[] = $col;
                    }
                }
                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
}
