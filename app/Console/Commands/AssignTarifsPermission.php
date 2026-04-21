<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignTarifsPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sifec:assign-tarifs-permission {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigner les permissions tarifs + paramètres validité documents (demandes) à un administrateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        // Trouver l'utilisateur
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("Utilisateur avec email {$email} non trouvé!");
            $this->info("\nUtilisateurs disponibles:");

            User::select('code_user', 'pseudo', 'email')
                ->limit(10)
                ->get()
                ->each(function ($u) {
                    $this->line("  - {$u->email} ({$u->pseudo})");
                });

            return 1;
        }

        $this->info("Utilisateur trouvé: {$user->pseudo} ({$user->email})");

        // Vérifier que les fonctionnalités existent
        $codesAdminTarifsEtValidite = ['FNC_0059', 'FNC_0060', 'FNC_0068', 'FNC_0069'];

        $fonctionnalites = DB::table('tr_fonctionnalite')
            ->whereIn('code_fonctionnalite', $codesAdminTarifsEtValidite)
            ->get();

        if ($fonctionnalites->count() !== count($codesAdminTarifsEtValidite)) {
            $this->error('Les fonctionnalités FNC_0059, FNC_0060, FNC_0068 et FNC_0069 doivent exister en base.');
            $this->info("Veuillez d'abord exécuter le seeder des fonctionnalités (ex. php artisan db:seed --class=FonctionnaliteSeeder).");

            return 1;
        }

        $this->info("\nFonctionnalités trouvées:");
        foreach ($fonctionnalites as $fonc) {
            $this->line("  ✓ {$fonc->code_fonctionnalite} - {$fonc->lib_fonctionnalite}");
        }

        // Assigner les permissions à l'utilisateur
        $added = 0;
        $skipped = 0;

        foreach ($codesAdminTarifsEtValidite as $codeFonc) {
            // Vérifier si la permission existe déjà
            $exists = DB::table('tr_uf')
                ->where('code_user', $user->code_user)
                ->where('code_fonctionnalite', $codeFonc)
                ->exists();

            if (! $exists) {
                DB::table('tr_uf')->insert([
                    'code_user' => $user->code_user,
                    'code_fonctionnalite' => $codeFonc,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $added++;
                $this->info("  ✅ Permission {$codeFonc} ajoutée");
            } else {
                $skipped++;
                $this->warn("  ⚠ Permission {$codeFonc} déjà existante");
            }
        }

        $this->info("\n✅ Opération terminée:");
        $this->line("  • Permissions ajoutées: {$added}");
        if ($skipped > 0) {
            $this->line("  • Permissions déjà existantes: {$skipped}");
        }

        $this->newLine();
        $this->info("🎉 L'utilisateur {$user->pseudo} peut maintenant accéder à:");
        $this->line('  → /admin/tarifs (gestion des tarifs)');
        $this->line('  → /admin/demande-document-config (durée de validité des documents délivrés)');

        return 0;
    }
}
