<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetupSignelecAdmin extends Command
{
    protected $signature = 'sifec:setup-signelec-admin {email? : E-mail admin à qui assigner la permission}';

    protected $description = 'Crée la fonctionnalité + menus SIGNELEC et assigne éventuellement la permission à un admin';

    public function handle(): int
    {
        if (! Schema::hasTable('tr_fonctionnalite') || ! Schema::hasTable('tr_menu_item')) {
            $this->error('Tables tr_fonctionnalite / tr_menu_item introuvables.');

            return 1;
        }

        $this->upsertFonctionnalite();
        $this->upsertMenus();

        $email = $this->argument('email');
        if ($email) {
            $this->assignPermission($email);
        } else {
            $this->newLine();
            $this->comment('Astuce : php artisan sifec:setup-signelec-admin admin@exemple.cg');
        }

        $this->newLine();
        $this->info('Routes disponibles :');
        $this->line('  → /admin/signelec');
        $this->line('  → /admin/signelec/institutions');
        $this->line('  → /admin/signelec/signataires');
        $this->line('  → /admin/signelec/parametres');

        return 0;
    }

    private function upsertFonctionnalite(): void
    {
        $exists = DB::table('tr_fonctionnalite')
            ->where('code_fonctionnalite', 'FNC_0072')
            ->orWhere('lib_technique', 'module.admin.signelec')
            ->exists();

        if ($exists) {
            $this->warn('Fonctionnalité FNC_0072 / module.admin.signelec déjà présente.');

            return;
        }

        $row = [
            'code_fonctionnalite' => 'FNC_0072',
            'lib_fonctionnalite' => 'Administration SIGNELEC (signature électronique GUOT)',
            'lib_technique' => 'module.admin.signelec',
            'description_fonctionnalite' => 'Réservé aux administrateurs : tableau de bord SIGNELEC, checklist cachets institutionnels (CEC) et suivi des enrôlements signataires.',
            'code_module' => 'MOD_0001',
            'etat_fonctionnalite' => 'Activé',
            'code_fonctionnalite_parent' => 'FNC_0011',
        ];

        if (Schema::hasColumn('tr_fonctionnalite', 'created_at')) {
            $row['created_at'] = now();
            $row['updated_at'] = now();
        }

        DB::table('tr_fonctionnalite')->insert($row);
        $this->info('✅ Fonctionnalité FNC_0072 créée (module.admin.signelec).');
    }

    private function upsertMenus(): void
    {
        $parentAdmin = DB::table('tr_menu_item')->where('code_menu_item', 'MENU_ADM')->first();
        if (! $parentAdmin) {
            $this->error('Menu parent MENU_ADM introuvable.');

            return;
        }

        $menus = [
            [
                'code_menu_item' => 'MENU_ADM_SIGNELEC',
                'code_parent' => 'MENU_ADM',
                'libelle' => 'Signature électronique (SIGNELEC)',
                'route_name' => null,
                'is_group' => 1,
                'sort_order' => 78,
                'anchor_class' => 'has-arrow ai-icon',
            ],
            [
                'code_menu_item' => 'MENU_ADM_SIGNELEC_DASH',
                'code_parent' => 'MENU_ADM_SIGNELEC',
                'libelle' => 'Tableau de bord',
                'route_name' => 'admin.signelec.dashboard',
                'is_group' => 0,
                'sort_order' => 10,
                'anchor_class' => null,
            ],
            [
                'code_menu_item' => 'MENU_ADM_SIGNELEC_INST',
                'code_parent' => 'MENU_ADM_SIGNELEC',
                'libelle' => 'Institutions & cachets',
                'route_name' => 'admin.signelec.institutions',
                'is_group' => 0,
                'sort_order' => 20,
                'anchor_class' => null,
            ],
            [
                'code_menu_item' => 'MENU_ADM_SIGNELEC_SIGN',
                'code_parent' => 'MENU_ADM_SIGNELEC',
                'libelle' => 'Signataires & enrôlements',
                'route_name' => 'admin.signelec.signataires',
                'is_group' => 0,
                'sort_order' => 30,
                'anchor_class' => null,
            ],
            [
                'code_menu_item' => 'MENU_ADM_SIGNELEC_PARAM',
                'code_parent' => 'MENU_ADM_SIGNELEC',
                'libelle' => 'Paramètres (fonctions éligibles)',
                'route_name' => 'admin.signelec.parametres',
                'is_group' => 0,
                'sort_order' => 40,
                'anchor_class' => null,
            ],
        ];

        foreach ($menus as $menu) {
            $exists = DB::table('tr_menu_item')
                ->where('code_menu_item', $menu['code_menu_item'])
                ->exists();

            if ($exists) {
                $this->warn("Menu {$menu['code_menu_item']} déjà présent.");

                continue;
            }

            $payload = array_merge($menu, [
                'lib_icone' => null,
                'external_path' => null,
                'permission_gate' => 'module.admin.signelec',
                'visibility_hide_fonctions' => null,
                'visibility_show_only_fonctions' => null,
                'anchor_extra_classes' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('tr_menu_item')->insert($payload);
            $this->info("✅ Menu {$menu['code_menu_item']} créé ({$menu['libelle']}).");
        }
    }

    private function assignPermission(string $email): void
    {
        $user = User::where('email', $email)->first();
        if (! $user) {
            $this->error("Utilisateur {$email} introuvable.");

            return;
        }

        if (! Schema::hasTable('tr_uf')) {
            $this->error('Table tr_uf introuvable.');

            return;
        }

        $exists = DB::table('tr_uf')
            ->where('code_user', $user->code_user)
            ->where('code_fonctionnalite', 'FNC_0072')
            ->exists();

        if ($exists) {
            $this->warn("Permission FNC_0072 déjà assignée à {$email}.");

            return;
        }

        $row = [
            'code_user' => $user->code_user,
            'code_fonctionnalite' => 'FNC_0072',
        ];
        if (Schema::hasColumn('tr_uf', 'created_at')) {
            $row['created_at'] = now();
            $row['updated_at'] = now();
        }

        DB::table('tr_uf')->insert($row);
        $this->info("✅ Permission module.admin.signelec assignée à {$email}.");
    }
}
