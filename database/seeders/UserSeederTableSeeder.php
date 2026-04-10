<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Utilisateurs liés aux personnes (Data/sifec_comptes_institutions.php).
 * Mot de passe initial : 123456 (à changer en production).
 *
 * tr_user.pseudo : VARCHAR(12) en base — tronqué si besoin (évite les échecs de seed).
 */
class UserSeederTableSeeder extends Seeder
{
    private const PASSWORD = '123456';

    private const PSEUDO_MAX_LENGTH = 12;

    private const INDICATIF_DEFAUT = '+242';

    private const TELEPHONE_MAX_LENGTH = 12;

    private const EMAIL_MAX_LENGTH = 100;

    public function run(): void
    {
        $path = __DIR__.'/Data/sifec_comptes_institutions.php';
        if (! is_file($path)) {
            $this->command?->error('Fichier manquant : database/seeders/Data/sifec_comptes_institutions.php');

            return;
        }

        /** @var array<int, array<string, mixed>> $comptes */
        $comptes = require $path;

        $now = now();
        $hash = Hash::make(self::PASSWORD);

        foreach ($comptes as $row) {
            $pseudo = $this->resolveUniquePseudo($row);

            User::query()->updateOrInsert(
                ['code_user' => $row['code_user']],
                [
                    'code_personne' => $row['code_personne'],
                    'pseudo' => $pseudo,
                    'email' => $row['email'],
                    'password' => $hash,
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );

            $this->upsertContactPersonne($row, $now);
        }
    }

    /**
     * tr_user.pseudo est unique : évite les collisions (ex. deux comptes, même téléphone).
     */
    private function resolveUniquePseudo(array $row): string
    {
        $pseudoBrut = (string) $row['pseudo'];
        $codeUser = (string) $row['code_user'];
        $primary = mb_substr($pseudoBrut, 0, self::PSEUDO_MAX_LENGTH);
        if (mb_strlen($pseudoBrut) > self::PSEUDO_MAX_LENGTH) {
            $this->command?->warn("Pseudo tronqué à 12 caractères pour {$codeUser} (était : {$pseudoBrut}).");
        }

        $digits = preg_replace('/\D/', '', $codeUser) ?: '0';
        $suffix = mb_substr(str_pad($digits, 2, '0', STR_PAD_LEFT), -2);
        $baseLen = max(1, self::PSEUDO_MAX_LENGTH - mb_strlen($suffix));
        $withSuffix = mb_substr(mb_substr($pseudoBrut, 0, $baseLen).$suffix, 0, self::PSEUDO_MAX_LENGTH);
        $fromCode = mb_substr('u'.$digits, 0, self::PSEUDO_MAX_LENGTH);
        $hashFallback = mb_substr(sha1($codeUser), 0, self::PSEUDO_MAX_LENGTH);

        $candidates = array_values(array_unique(array_filter([
            $primary,
            $withSuffix,
            $fromCode,
            $hashFallback,
        ])));

        foreach ($candidates as $pseudo) {
            if (! User::query()->where('pseudo', $pseudo)->where('code_user', '<>', $codeUser)->exists()) {
                return $pseudo;
            }
        }

        return mb_substr(sha1($codeUser.(string) microtime(true)), 0, self::PSEUDO_MAX_LENGTH);
    }

    /**
     * t_contact_personne : un enregistrement de référence par code_personne (téléphone + email pro).
     */
    private function upsertContactPersonne(array $row, \DateTimeInterface $now): void
    {
        $codePersonne = (string) $row['code_personne'];
        $digits = preg_replace('/\D/', '', (string) ($row['telephone'] ?? ''));
        $telephone = $digits !== ''
            ? mb_substr($digits, 0, self::TELEPHONE_MAX_LENGTH)
            : '0000000';

        $emailBrut = (string) ($row['email'] ?? '');
        $emailPro = $emailBrut !== ''
            ? mb_substr($emailBrut, 0, self::EMAIL_MAX_LENGTH)
            : null;

        $indicatif = mb_substr(self::INDICATIF_DEFAUT, 0, 5);

        DB::table('t_contact_personne')->updateOrInsert(
            ['code_personne' => $codePersonne],
            [
                'indicatif' => $indicatif,
                'telephone' => $telephone,
                'email_personnelle' => null,
                'email_professionnelle' => $emailPro,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );
    }
}
