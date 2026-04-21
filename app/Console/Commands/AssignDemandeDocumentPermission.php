<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AssignDemandeDocumentPermission extends Command
{
    protected $signature = 'demande-document:assign-permission {email? : Email de l\'utilisateur}';

    protected $description = 'Attribuer la permission "Gestion des demandes de documents" à un utilisateur';

    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Email de l\'utilisateur');

        if (! $email) {
            $this->error('Email requis');

            return 1;
        }

        // Trouver l'utilisateur
        $user = DB::table('tr_user')->where('email', $email)->first();

        if (! $user) {
            $this->error("Utilisateur avec email {$email} non trouvé");

            return 1;
        }

        $this->info("Utilisateur trouvé: {$user->code_user}");

        // Récupérer la fonctionnalité demande_document
        $fonctionnalite = DB::table('tr_fonctionnalite')
            ->where('lib_technique', 'module.demande_document')
            ->first();

        if (! $fonctionnalite) {
            $this->error("Fonctionnalité 'module.demande_document' non trouvée");
            $this->info('Exécutez: php artisan db:seed --class=FonctionnaliteSeeder');

            return 1;
        }

        $this->info("Fonctionnalité trouvée: {$fonctionnalite->code_fonctionnalite}");

        // Vérifier si déjà assignée
        $exists = DB::table('tr_uf')
            ->where('code_user', $user->code_user)
            ->where('code_fonctionnalite', $fonctionnalite->code_fonctionnalite)
            ->exists();

        if ($exists) {
            $this->warn("Permission déjà assignée à l'utilisateur");
        } else {
            DB::table('tr_uf')->insert([
                'code_user' => $user->code_user,
                'code_fonctionnalite' => $fonctionnalite->code_fonctionnalite,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->info("✓ Permission 'Gestion des demandes de documents' assignée");
        }

        // Assigner aussi la permission de signature
        $fonctionnaliteSignature = DB::table('tr_fonctionnalite')
            ->where('lib_technique', 'module.demande_document.signature')
            ->first();

        if ($fonctionnaliteSignature) {
            $existsSignature = DB::table('tr_uf')
                ->where('code_user', $user->code_user)
                ->where('code_fonctionnalite', $fonctionnaliteSignature->code_fonctionnalite)
                ->exists();

            if (! $existsSignature) {
                DB::table('tr_uf')->insert([
                    'code_user' => $user->code_user,
                    'code_fonctionnalite' => $fonctionnaliteSignature->code_fonctionnalite,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->info("✓ Permission 'Signature électronique' assignée");
            } else {
                $this->warn('Permission signature déjà assignée');
            }
        }

        $this->info("\n✅ Permissions assignées avec succès!");
        $this->info("Vous pouvez maintenant vous reconnect et voir le menu 'Demandes de documents'");

        return 0;
    }
}
