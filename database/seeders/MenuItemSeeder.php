<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $path = __DIR__.'/Data/menu_items_definitions.php';
        if (! is_file($path)) {
            $this->command?->error('Fichier manquant : '.$path);

            return;
        }

        /** @var array<int, array<string, mixed>> $rows */
        $rows = require $path;

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('tr_menu_item')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        foreach ($rows as $row) {
            MenuItem::create([
                'code_menu_item' => $row['code_menu_item'],
                'code_parent' => $row['code_parent'] ?: null,
                'libelle' => $row['libelle'],
                'lib_icone' => $row['lib_icone'] ?? null,
                'route_name' => $row['route_name'] ?? null,
                'external_path' => $row['external_path'] ?? null,
                'permission_gate' => $row['permission_gate'] ?? null,
                'sort_order' => (int) ($row['sort_order'] ?? 0),
                'is_group' => (bool) ($row['is_group'] ?? false),
                'visibility_hide_fonctions' => $row['visibility_hide_fonctions'] ?? null,
                'visibility_show_only_fonctions' => $row['visibility_show_only_fonctions'] ?? null,
                'anchor_class' => $row['anchor_class'] ?? null,
                'anchor_extra_classes' => $row['anchor_extra_classes'] ?? null,
            ]);
        }

        $this->command?->info('tr_menu_item : '.count($rows).' ligne(s).');
    }
}
