# 📍 LocaliteSeeder - Départements du Congo-Brazzaville

## ✅ Configuration Actuelle

---

## 🗺️ 15 Départements Officiels

| # | Code Postal | Code Base | Département | Chef-lieu | Status |
|---|-------------|-----------|-------------|-----------|--------|
| 1 | **BO** | LOC_BO | Bouenza | Madingou | ✅ Actif |
| 2 | **BZ** | LOC_BZ | Brazzaville | Brazzaville | ✅ Actif |
| 3 | **CV** | LOC_CV | Cuvette | Owando | ✅ Actif |
| 4 | **CO** | LOC_CO | Cuvette-Ouest | Ewo | ✅ Actif |
| 5 | **DL** | LOC_DL | Djoué-Léfini | Kindamba | ✅ Actif |
| 6 | **KL** | LOC_KL | Kouilou | Loango | ✅ Actif |
| 7 | **LE** | LOC_LE | Lékoumou | Sibiti | ✅ Actif |
| 8 | **LK** | LOC_LK | Likouala | Impfondo | ✅ Actif |
| 9 | **NA** | LOC_NA | Nkeni-Alima | Makoua | ✅ Actif |
| 10 | **NI** | LOC_NI | Niari | Dolisie | ✅ Actif |
| 11 | **OU** | LOC_OU | Congo-Oubangui | Dongou | ✅ Actif |
| 12 | **PL** | LOC_PL | Plateaux | Djambala | ✅ Actif |
| 13 | **PO** | LOC_PO | Pool | Kinkala | ✅ Actif |
| 14 | **PN** | LOC_PN | Pointe-Noire | Pointe-Noire | ✅ Actif |
| 15 | **SA** | LOC_SA | Sangha | Ouesso | ✅ Actif |

**Total : 15 départements**

---

## 🆕 Nouveaux Départements (3)

| Code Postal | Département | Ajouté le |
|-------------|-------------|-----------|
| **DL** | Djoué-Léfini | 04/11/2025 |
| **NA** | Nkeni-Alima | 04/11/2025 |
| **OU** | Congo-Oubangui | 04/11/2025 |

---

## 🔄 Anciens Codes (Migration)

| Ancien | Nouveau | Département |
|--------|---------|-------------|
| LOC_0001 | LOC_BZ | Brazzaville |
| LOC_0002 | LOC_PN | Pointe-Noire |
| LOC_0003 | LOC_LK | Likouala |
| LOC_0004 | LOC_SA | Sangha |
| LOC_0005 | LOC_CO | Cuvette-Ouest |
| LOC_0006 | LOC_CV | Cuvette |
| LOC_0007 | LOC_PL | Plateaux |
| LOC_0008 | LOC_PO | Pool |
| LOC_0009 | LOC_LE | Lékoumou |
| LOC_0010 | LOC_BO | Bouenza |
| LOC_0011 | LOC_NI | Niari |
| LOC_0012 | LOC_KL | Kouilou |

---

## 🚀 Utilisation

### Exécuter le Seeder

```bash
# Seeder uniquement
php artisan db:seed --class=LocaliteSeeder

# Tous les seeders
php artisan db:seed

# Migration fresh + seed
php artisan migrate:fresh --seed
```

### Résultat Attendu

```
✅ 15 départements insérés avec succès
📍 Codes postaux conformes au Chapitre 3, Article 11
```

---

## 📖 Référence Légale

**Source :** Codes départementaux de la République du Congo pour les services postaux  
**Chapitre :** 3  
**Article :** 11  

---

## 🔍 Vérification Rapide

```php
// Dans Tinker
php artisan tinker

>>> \Modules\Referentiel\Entities\Localite::count()
=> 15

>>> \Modules\Referentiel\Entities\Localite::pluck('lib_localite', 'code_localite')
=> [
    "LOC_BO" => "BOUENZA",
    "LOC_BZ" => "BRAZZAVILLE",
    "LOC_CV" => "CUVETTE",
    // ... etc
]
```

---

## 📝 Modifications Récentes

### Version 2.0 - 04 Novembre 2025
- ✅ Mise à jour avec 15 départements officiels
- ✅ Ajout codes postaux (Article 11)
- ✅ Ajout 3 nouveaux départements (DL, NA, OU)
- ✅ Codes modernisés (LOC_XX au lieu de LOC_00XX)
- ✅ Respect de la migration (tous les champs)
- ✅ Messages informatifs ajoutés

### Version 1.0 - Avant 04/11/2025
- 12 départements
- Codes LOC_0001 à LOC_0012
- Structure basique

---

**Maintenu par :** Équipe SIFEC  
**Dernière mise à jour :** 04 Novembre 2025  
**Version :** 2.0

