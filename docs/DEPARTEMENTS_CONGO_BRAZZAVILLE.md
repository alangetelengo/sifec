# 📍 Départements de la République du Congo

## Référence Officielle : Chapitre 3, Article 11

---

## 📋 Liste Complète des 15 Départements

| Code Postal | Code Interne | Nom Département | Chef-lieu |
|------------|--------------|-----------------|-----------|
| **BO** | LOC_BO | Bouenza | Madingou |
| **BZ** | LOC_BZ | Brazzaville | Brazzaville |
| **CV** | LOC_CV | Cuvette | Owando |
| **CO** | LOC_CO | Cuvette-Ouest | Ewo |
| **DL** | LOC_DL | Djoué-Léfini | Kindamba |
| **KL** | LOC_KL | Kouilou | Loango |
| **LE** | LOC_LE | Lékoumou | Sibiti |
| **LK** | LOC_LK | Likouala | Impfondo |
| **NA** | LOC_NA | Nkeni-Alima | Makoua |
| **NI** | LOC_NI | Niari | Dolisie |
| **OU** | LOC_OU | Congo-Oubangui | Dongou |
| **PL** | LOC_PL | Plateaux | Djambala |
| **PO** | LOC_PO | Pool | Kinkala |
| **PN** | LOC_PN | Pointe-Noire | Pointe-Noire |
| **SA** | LOC_SA | Sangha | Ouesso |

**Total : 15 départements**

---

## 🗺️ Répartition Géographique

### Nord du Congo
1. **Likouala (LK)** - Impfondo
2. **Sangha (SA)** - Ouesso
3. **Cuvette (CV)** - Owando
4. **Cuvette-Ouest (CO)** - Ewo
5. **Nkeni-Alima (NA)** - Makoua
6. **Plateaux (PL)** - Djambala
7. **Congo-Oubangui (OU)** - Dongou

### Centre du Congo
8. **Pool (PO)** - Kinkala
9. **Djoué-Léfini (DL)** - Kindamba

### Sud du Congo
10. **Lékoumou (LE)** - Sibiti
11. **Bouenza (BO)** - Madingou
12. **Niari (NI)** - Dolisie
13. **Kouilou (KL)** - Loango

### Villes départements
14. **Brazzaville (BZ)** - Capitale politique
15. **Pointe-Noire (PN)** - Capitale économique

---

## 📊 Modifications par rapport à l'ancien système

### ✅ Nouveaux Départements (3)
1. **Djoué-Léfini (DL)** - Créé récemment
2. **Nkeni-Alima (NA)** - Créé récemment
3. **Congo-Oubangui (OU)** - Créé récemment

### 🔄 Départements Existants (12)
- Bouenza (BO)
- Brazzaville (BZ)
- Cuvette (CV)
- Cuvette-Ouest (CO)
- Kouilou (KL)
- Lékoumou (LE)
- Likouala (LK)
- Niari (NI)
- Plateaux (PL)
- Pool (PO)
- Pointe-Noire (PN)
- Sangha (SA)

**Évolution : 12 → 15 départements (+3)**

---

## 🔧 Structure Base de Données

### Table : `tr_localite`

```sql
CREATE TABLE tr_localite (
    code_localite VARCHAR(16) PRIMARY KEY,
    lib_localite VARCHAR(150) NOT NULL,
    code_type_localite VARCHAR(16) NOT NULL,
    pompes_funebres BOOLEAN DEFAULT false,
    code_localite_parent VARCHAR(16) NULL,
    supprimer BOOLEAN DEFAULT false,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (code_type_localite) 
        REFERENCES tr_type_localite(code_type_localite),
    FOREIGN KEY (code_localite_parent) 
        REFERENCES tr_localite(code_localite)
);
```

### Types de Localités

| Code | Type | Description |
|------|------|-------------|
| TPLOC_0001 | Département | Niveau administratif principal |
| TPLOC_0002 | Commune | Subdivision urbaine |
| TPLOC_0003 | Arrondissement | Subdivision de commune |
| TPLOC_0004 | District | Subdivision de département |

---

## 📝 Données du Seeder

### LocaliteSeeder.php

```php
$departements = [
    ['code_postal' => 'BO', 'code_localite' => 'LOC_BO', 'lib_localite' => 'BOUENZA'],
    ['code_postal' => 'BZ', 'code_localite' => 'LOC_BZ', 'lib_localite' => 'BRAZZAVILLE'],
    ['code_postal' => 'CV', 'code_localite' => 'LOC_CV', 'lib_localite' => 'CUVETTE'],
    ['code_postal' => 'CO', 'code_localite' => 'LOC_CO', 'lib_localite' => 'CUVETTE-OUEST'],
    ['code_postal' => 'DL', 'code_localite' => 'LOC_DL', 'lib_localite' => 'DJOUE-LEFINI'],
    ['code_postal' => 'KL', 'code_localite' => 'LOC_KL', 'lib_localite' => 'KOUILOU'],
    ['code_postal' => 'LE', 'code_localite' => 'LOC_LE', 'lib_localite' => 'LEKOUMOU'],
    ['code_postal' => 'LK', 'code_localite' => 'LOC_LK', 'lib_localite' => 'LIKOUALA'],
    ['code_postal' => 'NA', 'code_localite' => 'LOC_NA', 'lib_localite' => 'NKENI-ALIMA'],
    ['code_postal' => 'NI', 'code_localite' => 'LOC_NI', 'lib_localite' => 'NIARI'],
    ['code_postal' => 'OU', 'code_localite' => 'LOC_OU', 'lib_localite' => 'CONGO-OUBANGUI'],
    ['code_postal' => 'PL', 'code_localite' => 'LOC_PL', 'lib_localite' => 'PLATEAUX'],
    ['code_postal' => 'PO', 'code_localite' => 'LOC_PO', 'lib_localite' => 'POOL'],
    ['code_postal' => 'PN', 'code_localite' => 'LOC_PN', 'lib_localite' => 'POINTE-NOIRE'],
    ['code_postal' => 'SA', 'code_localite' => 'LOC_SA', 'lib_localite' => 'SANGHA'],
];
```

---

## 🚀 Exécution du Seeder

### Commandes

```bash
# Exécuter uniquement le seeder des localités
php artisan db:seed --class=LocaliteSeeder

# OU exécuter tous les seeders
php artisan db:seed

# OU avec migration fresh
php artisan migrate:fresh --seed
```

### Résultat Attendu

```
✅ 15 départements insérés avec succès
📍 Codes postaux conformes au Chapitre 3, Article 11
```

---

## 📌 Utilisation dans l'Application

### Requête des Départements

```php
use Modules\Referentiel\Entities\Localite;

// Tous les départements
$departements = Localite::where('code_type_localite', 'TPLOC_0001')
    ->orderBy('lib_localite')
    ->get();

// Un département spécifique par code postal
$brazzaville = Localite::where('code_localite', 'LOC_BZ')->first();

// Département par nom
$pool = Localite::where('lib_localite', 'POOL')->first();
```

### Affichage dans un Select

```blade
<select name="departement" class="form-control">
    <option value="">-- Sélectionner un département --</option>
    @foreach($departements as $dept)
        <option value="{{ $dept->code_localite }}">
            {{ $dept->lib_localite }}
        </option>
    @endforeach
</select>
```

---

## 🗂️ Hiérarchie Administrative

### Niveau 1 : Département (15)
```
LOC_BO → BOUENZA
LOC_BZ → BRAZZAVILLE
LOC_CV → CUVETTE
...
```

### Niveau 2 : Communes (exemple Brazzaville)
```
LOC_BZ (Département)
  ├── LOC_BZ_01 (Commune Makélékélé)
  ├── LOC_BZ_02 (Commune Bacongo)
  ├── LOC_BZ_03 (Commune Poto-Poto)
  └── ...
```

### Niveau 3 : Arrondissements (exemple)
```
LOC_BZ_01 (Commune Makélékélé)
  ├── LOC_BZ_01_01 (Arrondissement 1)
  ├── LOC_BZ_01_02 (Arrondissement 2)
  └── ...
```

---

## 🔄 Migration des Anciennes Données

### Script de Migration

Si vous avez des données existantes avec les anciens codes :

```php
// Mapping ancien code → nouveau code
$mapping = [
    'LOC_0001' => 'LOC_BZ', // Brazzaville
    'LOC_0002' => 'LOC_PN', // Pointe-Noire
    'LOC_0003' => 'LOC_LK', // Likouala
    'LOC_0004' => 'LOC_SA', // Sangha
    'LOC_0005' => 'LOC_CO', // Cuvette-Ouest
    'LOC_0006' => 'LOC_CV', // Cuvette
    'LOC_0007' => 'LOC_PL', // Plateaux
    'LOC_0008' => 'LOC_PO', // Pool
    'LOC_0009' => 'LOC_LE', // Lékoumou
    'LOC_0010' => 'LOC_BO', // Bouenza
    'LOC_0011' => 'LOC_NI', // Niari
    'LOC_0012' => 'LOC_KL', // Kouilou
];

// Mettre à jour les références
foreach ($mapping as $old => $new) {
    DB::table('votre_table')
        ->where('code_localite', $old)
        ->update(['code_localite' => $new]);
}
```

---

## 📖 Référence Légale

**Source :** Codes départementaux de la République du Congo pour les services postaux  
**Chapitre :** 3  
**Article :** 11  
**Date de mise à jour :** 04 Novembre 2025

---

## ✅ Checklist de Vérification

- [x] 15 départements définis
- [x] Codes postaux officiels (BO, BZ, CV, etc.)
- [x] Structure conforme à la migration
- [x] Tous les champs requis renseignés
- [x] Clés étrangères respectées
- [x] Documentation complète
- [x] Seeder testé

---

**Auteur :** SIFEC Team  
**Date :** 04 Novembre 2025  
**Version :** 2.0 (15 départements)

