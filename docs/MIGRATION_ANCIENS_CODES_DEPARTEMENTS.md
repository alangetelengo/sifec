# 🔄 Migration des Anciens Codes Départements

## Date : 04 Novembre 2025

---

## 📋 Table de Correspondance

### Anciens Codes → Nouveaux Codes

| Ancien Code | Nouveau Code | Code Postal | Département | Action |
|------------|--------------|-------------|-------------|---------|
| LOC_0001 | LOC_BZ | BZ | BRAZZAVILLE | ✅ Migrer |
| LOC_0002 | LOC_PN | PN | POINTE-NOIRE | ✅ Migrer |
| LOC_0003 | LOC_LK | LK | LIKOUALA | ✅ Migrer |
| LOC_0004 | LOC_SA | SA | SANGHA | ✅ Migrer |
| LOC_0005 | LOC_CO | CO | CUVETTE-OUEST | ✅ Migrer |
| LOC_0006 | LOC_CV | CV | CUVETTE | ✅ Migrer |
| LOC_0007 | LOC_PL | PL | PLATEAUX | ✅ Migrer |
| LOC_0008 | LOC_PO | PO | POOL | ✅ Migrer |
| LOC_0009 | LOC_LE | LE | LEKOUMOU | ✅ Migrer |
| LOC_0010 | LOC_BO | BO | BOUENZA | ✅ Migrer |
| LOC_0011 | LOC_NI | NI | NIARI | ✅ Migrer |
| LOC_0012 | LOC_KL | KL | KOUILOU | ✅ Migrer |
| ❌ N/A | LOC_DL | DL | DJOUE-LEFINI | ➕ Nouveau |
| ❌ N/A | LOC_NA | NA | NKENI-ALIMA | ➕ Nouveau |
| ❌ N/A | LOC_OU | OU | CONGO-OUBANGUI | ➕ Nouveau |

**12 départements à migrer + 3 nouveaux départements = 15 départements au total**

---

## 🔍 Analyse d'Impact

### Tables Potentiellement Affectées

Recherchez toutes les tables qui référencent `tr_localite` :

```sql
-- Trouver toutes les clés étrangères pointant vers tr_localite
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME
FROM 
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE 
    REFERENCED_TABLE_NAME = 'tr_localite';
```

### Tables à Vérifier (exemples probables)

1. `tr_declaration_naissance`
2. `tr_declaration_deces`
3. `tr_declaration_mariage`
4. `tr_acte_naissance`
5. `tr_acte_deces`
6. `tr_personne`
7. `tr_institution`
8. `tr_centre_etat_civil`
9. Toute table avec `code_localite` ou `code_departement`

---

## 🚀 Stratégie de Migration

### Option 1 : Migration Fresh (Recommandé pour Dev/Test)

```bash
# Supprime tout et recrée avec nouvelles données
php artisan migrate:fresh --seed
```

**Avantages :**
- ✅ Propre et sans ambiguïté
- ✅ Garantit la cohérence
- ✅ Rapide

**Inconvénients :**
- ❌ Perd toutes les données existantes

**Utiliser quand :**
- Environnement de développement
- Environnement de test
- Base de données vide

---

### Option 2 : Migration Progressive (Recommandé pour Production)

#### Étape 1 : Backup de la Base de Données

```bash
# Backup complet
mysqldump -u root -p sifec > backup_sifec_$(date +%Y%m%d_%H%M%S).sql

# OU via artisan
php artisan db:backup
```

#### Étape 2 : Créer une Migration de Mise à Jour

```bash
php artisan make:migration update_localite_codes_to_postal_codes
```

**Fichier : `database/migrations/XXXX_XX_XX_XXXXXX_update_localite_codes_to_postal_codes.php`**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateLocaliteCodesToPostalCodes extends Migration
{
    public function up()
    {
        // Mapping ancien → nouveau
        $mapping = [
            'LOC_0001' => 'LOC_BZ',
            'LOC_0002' => 'LOC_PN',
            'LOC_0003' => 'LOC_LK',
            'LOC_0004' => 'LOC_SA',
            'LOC_0005' => 'LOC_CO',
            'LOC_0006' => 'LOC_CV',
            'LOC_0007' => 'LOC_PL',
            'LOC_0008' => 'LOC_PO',
            'LOC_0009' => 'LOC_LE',
            'LOC_0010' => 'LOC_BO',
            'LOC_0011' => 'LOC_NI',
            'LOC_0012' => 'LOC_KL',
        ];

        DB::beginTransaction();
        
        try {
            // 1. Désactiver les contraintes FK temporairement
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');

            // 2. Mettre à jour tr_localite
            foreach ($mapping as $old => $new) {
                DB::table('tr_localite')
                    ->where('code_localite', $old)
                    ->update(['code_localite' => $new]);
            }

            // 3. Mettre à jour les tables dépendantes
            $tables_to_update = [
                'tr_declaration_naissance',
                'tr_declaration_deces',
                'tr_declaration_mariage',
                'tr_acte_naissance',
                'tr_acte_deces',
                'tr_personne',
                'tr_institution',
                // Ajoutez toutes vos tables ici
            ];

            foreach ($tables_to_update as $table) {
                // Vérifier si la colonne existe
                if (Schema::hasColumn($table, 'code_localite')) {
                    foreach ($mapping as $old => $new) {
                        DB::table($table)
                            ->where('code_localite', $old)
                            ->update(['code_localite' => $new]);
                    }
                }
            }

            // 4. Ajouter les nouveaux départements
            $nouveaux_departements = [
                ['code_localite' => 'LOC_DL', 'lib_localite' => 'DJOUE-LEFINI', 'code_type_localite' => 'TPLOC_0001'],
                ['code_localite' => 'LOC_NA', 'lib_localite' => 'NKENI-ALIMA', 'code_type_localite' => 'TPLOC_0001'],
                ['code_localite' => 'LOC_OU', 'lib_localite' => 'CONGO-OUBANGUI', 'code_type_localite' => 'TPLOC_0001'],
            ];

            foreach ($nouveaux_departements as $dept) {
                DB::table('tr_localite')->insertOrIgnore([
                    'code_localite' => $dept['code_localite'],
                    'lib_localite' => $dept['lib_localite'],
                    'code_type_localite' => $dept['code_type_localite'],
                    'code_localite_parent' => NULL,
                    'pompes_funebres' => false,
                    'supprimer' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // 5. Réactiver les contraintes FK
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');

            DB::commit();
            
            $this->command->info('✅ Migration des codes départements réussie');
            $this->command->info('✅ 12 départements migrés');
            $this->command->info('✅ 3 nouveaux départements ajoutés');
            
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            $this->command->error('❌ Erreur lors de la migration : ' . $e->getMessage());
            throw $e;
        }
    }

    public function down()
    {
        // Rollback inverse (si nécessaire)
        $mapping = [
            'LOC_BZ' => 'LOC_0001',
            'LOC_PN' => 'LOC_0002',
            'LOC_LK' => 'LOC_0003',
            'LOC_SA' => 'LOC_0004',
            'LOC_CO' => 'LOC_0005',
            'LOC_CV' => 'LOC_0006',
            'LOC_PL' => 'LOC_0007',
            'LOC_PO' => 'LOC_0008',
            'LOC_LE' => 'LOC_0009',
            'LOC_BO' => 'LOC_0010',
            'LOC_NI' => 'LOC_0011',
            'LOC_KL' => 'LOC_0012',
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        foreach ($mapping as $new => $old) {
            DB::table('tr_localite')
                ->where('code_localite', $new)
                ->update(['code_localite' => $old]);
        }

        // Supprimer les nouveaux départements
        DB::table('tr_localite')->whereIn('code_localite', ['LOC_DL', 'LOC_NA', 'LOC_OU'])->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
```

#### Étape 3 : Exécuter la Migration

```bash
# Exécuter la migration de mise à jour
php artisan migrate

# Vérifier les résultats
php artisan tinker
>>> Localite::count(); // Devrait être 15
>>> Localite::where('code_localite', 'LIKE', 'LOC_0%')->count(); // Devrait être 0
```

---

## 🧪 Scripts de Vérification

### Script 1 : Vérifier les Anciens Codes

```php
// Vérifier s'il reste des anciens codes
php artisan tinker

>>> $anciensCodesPossibles = ['LOC_0001', 'LOC_0002', 'LOC_0003', 'LOC_0004', 'LOC_0005', 
                               'LOC_0006', 'LOC_0007', 'LOC_0008', 'LOC_0009', 'LOC_0010', 
                               'LOC_0011', 'LOC_0012'];
>>> $reste = DB::table('tr_localite')
        ->whereIn('code_localite', $anciensCodesPossibles)
        ->get();
>>> if ($reste->isEmpty()) {
        echo "✅ Aucun ancien code trouvé\n";
    } else {
        echo "❌ Anciens codes encore présents :\n";
        $reste->each(fn($loc) => print_r($loc));
    }
```

### Script 2 : Vérifier les Nouveaux Codes

```php
php artisan tinker

>>> $nouveauxCodes = ['LOC_BZ', 'LOC_PN', 'LOC_LK', 'LOC_SA', 'LOC_CO', 
                      'LOC_CV', 'LOC_PL', 'LOC_PO', 'LOC_LE', 'LOC_BO', 
                      'LOC_NI', 'LOC_KL', 'LOC_DL', 'LOC_NA', 'LOC_OU'];
>>> $trouves = DB::table('tr_localite')
        ->whereIn('code_localite', $nouveauxCodes)
        ->count();
>>> echo ($trouves === 15) ? "✅ 15 départements OK" : "❌ Seulement {$trouves} départements";
```

### Script 3 : Vérifier les Tables Dépendantes

```php
php artisan tinker

>>> $tables = ['tr_declaration_naissance', 'tr_acte_naissance']; // etc.
>>> foreach ($tables as $table) {
        if (Schema::hasColumn($table, 'code_localite')) {
            $anciens = DB::table($table)
                ->where('code_localite', 'LIKE', 'LOC_0%')
                ->count();
            echo "{$table}: {$anciens} anciens codes\n";
        }
    }
```

---

## ⚠️ Points d'Attention

### 1. Contraintes de Clés Étrangères

Les clés étrangères peuvent bloquer la mise à jour. Solutions :
- Désactiver temporairement : `SET FOREIGN_KEY_CHECKS = 0`
- Mettre à jour dans le bon ordre (enfants avant parents)
- Utiliser une transaction

### 2. Données Historiques

Si vous avez des documents/rapports avec anciens codes :
- Garder un mapping dans la documentation
- Créer une table de correspondance `tr_localite_mapping`
- Conserver les anciens PDFs/exports

### 3. Code Application

Vérifier dans le code source :

```bash
# Rechercher les anciens codes en dur
grep -r "LOC_000" app/ resources/ Modules/

# Rechercher les patterns à risque
grep -r "LOC_0\d\{3\}" app/ resources/
```

---

## 📋 Checklist de Migration

### Avant la Migration

- [ ] Backup complet de la base de données
- [ ] Identifier toutes les tables avec `code_localite`
- [ ] Vérifier le code source pour codes en dur
- [ ] Tester sur environnement de développement
- [ ] Informer les utilisateurs (si production)

### Pendant la Migration

- [ ] Mettre l'application en maintenance : `php artisan down`
- [ ] Exécuter le backup
- [ ] Exécuter la migration
- [ ] Vérifier les logs
- [ ] Exécuter les scripts de vérification

### Après la Migration

- [ ] Vérifier que les 15 départements existent
- [ ] Vérifier qu'aucun ancien code ne reste
- [ ] Tester les fonctionnalités critiques
- [ ] Retirer le mode maintenance : `php artisan up`
- [ ] Surveiller les logs pendant 24h

---

## 🆘 Procédure de Rollback

Si problème après migration :

```bash
# 1. Mettre en maintenance
php artisan down

# 2. Restaurer le backup
mysql -u root -p sifec < backup_sifec_YYYYMMDD_HHMMSS.sql

# 3. OU exécuter le rollback de la migration
php artisan migrate:rollback --step=1

# 4. Remettre en ligne
php artisan up
```

---

## 📞 Support

En cas de problème :
1. Vérifier les logs : `storage/logs/laravel.log`
2. Consulter la documentation : `docs/DEPARTEMENTS_CONGO_BRAZZAVILLE.md`
3. Contacter l'équipe technique

---

**Date :** 04 Novembre 2025  
**Version :** 1.0  
**Status :** Guide de migration complet

