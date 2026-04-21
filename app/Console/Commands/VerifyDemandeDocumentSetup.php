<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class VerifyDemandeDocumentSetup extends Command
{
    protected $signature = 'demande-document:verify {--user= : E-mail tr_user pour tester Gate::allows(module.demande_document)}';

    protected $description = 'Vérifier la configuration du menu Demandes de documents';

    public function handle()
    {
        $this->info("=== Vérification de la configuration ===\n");

        // 1. Vérifier le menu (toutes les entrées)
        $menus = DB::table('tr_menu_item')
            ->where('route_name', 'demandeDocument.index')
            ->orderBy('code_menu_item')
            ->get();

        if ($menus->isEmpty()) {
            $this->error('✗ Aucune entrée tr_menu_item pour route demandeDocument.index — exécuter : php artisan db:seed --class=MenuItemSeeder');
        } else {
            $this->info('✓ Entrées menu (sidebar filtre avec Gate sur permission_gate) :');
            foreach ($menus as $menu) {
                $this->line("  - {$menu->code_menu_item} ← parent {$menu->code_parent} — {$menu->permission_gate}");
            }
        }

        $mod = DB::table('tr_module')->where('code_module', 'MOD_0006')->first();
        if (! $mod) {
            $this->error('✗ Module MOD_0006 (Demandes de documents) absent de tr_module — lancer ModuleSeeder ou créer la ligne.');
        } elseif (($mod->etat_module ?? '') !== 'Activé') {
            $this->warn('⚠ MOD_0006 n’est pas « Activé » : les utilisateurs non super-admin n’ont pas module.demande_document dans toutesfonctionnalites().');
            $this->line('  SQL : UPDATE tr_module SET etat_module = \'Activé\' WHERE code_module = \'MOD_0006\';');
        } else {
            $this->info('✓ Module MOD_0006 : Activé');
        }

        // 2. Vérifier les fonctionnalités
        $this->info("\n✓ Fonctionnalités:");
        $fonctionnalites = DB::table('tr_fonctionnalite')
            ->whereIn('lib_technique', ['module.demande_document', 'module.demande_document.signature'])
            ->get();

        foreach ($fonctionnalites as $fonc) {
            $this->line("  - {$fonc->code_fonctionnalite}: {$fonc->lib_technique}");
        }

        // 3. Vérifier les permissions de agentcec@sifec.cg
        $this->info("\n✓ Permissions de agentcec@sifec.cg:");
        $user = DB::table('tr_user')->where('email', 'agentcec@sifec.cg')->first();

        if ($user) {
            $permissions = DB::table('tr_uf')
                ->join('tr_fonctionnalite', 'tr_uf.code_fonctionnalite', '=', 'tr_fonctionnalite.code_fonctionnalite')
                ->where('tr_uf.code_user', $user->code_user)
                ->whereIn('tr_fonctionnalite.lib_technique', ['module.demande_document', 'module.demande_document.signature'])
                ->get(['tr_fonctionnalite.lib_technique']);

            foreach ($permissions as $perm) {
                $this->line("  - {$perm->lib_technique}");
            }

            if ($permissions->isEmpty()) {
                $this->error('  Aucune permission trouvée!');
            }
        } else {
            $this->error('  Utilisateur non trouvé');
        }

        // 4. Vérifier les routes
        $this->info("\n✓ Routes:");
        if (\Route::has('demandeDocument.index')) {
            $this->line('  - demandeDocument.index existe');
        } else {
            $this->error("  - demandeDocument.index n'existe pas!");
        }

        $email = $this->option('user');
        if (is_string($email) && $email !== '') {
            $this->info("\n✓ Test Gate pour {$email} :");
            $u = User::query()->where('email', $email)->first();
            if (! $u) {
                $this->error('  Utilisateur introuvable.');
            } else {
                $ok = Gate::forUser($u)->allows('module.demande_document');
                $fonc = optional($u->fonction())->code_fonction ?? '(aucune affectation active ?)';
                $this->line('  Fonction active : '.$fonc);
                $this->line($ok ? '  module.demande_document → OUI' : '  module.demande_document → NON (menu masqué)');
            }
        }

        return 0;
    }
}
