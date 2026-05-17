# 🔐 NOUVELLE PERMISSION : CRÉER/IMPORTER UN JUGEMENT AU TRIBUNAL

## 📋 **PROBLÈME**

Aucune permission spécifique n'existait pour contrôler qui peut créer et importer des jugements au tribunal. Cette opération critique nécessite une autorisation explicite.

---

## ✅ **SOLUTION IMPLÉMENTÉE**

### **1. Nouvelle Fonctionnalité ajoutée**

**Fichier modifié :** `database/seeders/Data/fonctionnalites_definitions.php`

**Nouvelle entrée ajoutée :**
```php
['code_fonctionnalite' => 'FNC_0070', 
 'lib_fonctionnalite' => 'Créer et importer un jugement au tribunal', 
 'lib_technique' => 'module.tribunal.jugement.create', 
 'description_fonctionnalite' => "Permet à l'agent du tribunal de créer et importer un jugement (annulation, adoption, supplétif, homologation) avec le numéro de l'ancien acte si nécessaire.", 
 'code_module' => 'MOD_0001', 
 'etat_fonctionnalite' => 'Activé', 
 'code_fonctionnalite_parent' => 'FNC_0010']
```

**Détails de la permission :**
- **Code :** FNC_0070
- **Libellé :** Créer et importer un jugement au tribunal
- **lib_technique :** `module.tribunal.jugement.create`
- **Module :** MOD_0001 (Gestion générale)
- **Parent :** FNC_0010 (Voir menu tribunal)
- **État :** Activé

---

### **2. Routes protégées**

**Fichier modifié :** `Modules/Tribunal/Routes/web.php`

Les routes suivantes sont maintenant protégées par la permission `module.tribunal.jugement.create` :

```php
Route::middleware('can:module.tribunal.jugement.create')->group(function() {
    Route::get('document/create/{type}/{id}', [TribunalController::class,'create'])
        ->name("tribunal.document.create");
    
    Route::post('document/store/{type}/{id}', [TribunalController::class,'store'])
        ->name("tribunal.document.store");
    
    Route::get('document/importer/{type}/{code}', [TribunalController::class, 'importDocumentTribunal'])
        ->name('tribunal.document.importer');
});
```

**Routes protégées :**
1. `tribunal.document.create` - Formulaire de création de jugement
2. `tribunal.document.store` - Enregistrement du jugement
3. `tribunal.document.importer` - Import du document jugement

---

## 🚀 **DÉPLOIEMENT**

### **Étape 1 : Exécuter les seeders**

```bash
# 1. Exécuter le seeder de fonctionnalités
php artisan db:seed --class=FonctionnaliteSeeder

# 2. Vérifier que la fonctionnalité a été créée
php artisan tinker
>>> DB::table('tr_fonctionnalite')->where('code_fonctionnalite', 'FNC_0070')->first()
```

### **Étape 2 : Attribuer la permission aux fonctions appropriées**

Vous devez maintenant attribuer cette fonctionnalité aux fonctions (rôles) qui doivent pouvoir créer des jugements, typiquement :
- **Greffier du Tribunal**
- **Agent du Tribunal**
- **Président du Tribunal**

**Via l'interface d'administration :**
1. Aller dans : **Administration > Gestion des fonctions**
2. Sélectionner la fonction (ex: "Greffier du Tribunal")
3. Cocher la permission **"Créer et importer un jugement au tribunal"** (FNC_0070)
4. Enregistrer

**Via SQL (si accès direct) :**
```sql
-- Exemple : Attribuer la permission à la fonction "Greffier"
-- (Remplacer 'FONC_XXXX' par le code de votre fonction)

INSERT INTO tr_ff (code_fonction, code_fonctionnalite, created_at, updated_at)
VALUES ('FONC_XXXX', 'FNC_0070', NOW(), NOW());
```

---

## 📊 **IMPACT SUR L'EXISTANT**

### **Utilisateurs SANS la permission :**
- ❌ Ne pourront plus accéder au formulaire de création de jugement
- ❌ Ne pourront plus importer de jugements
- ❌ Recevront une erreur 403 (Accès refusé) s'ils tentent d'accéder

### **Utilisateurs AVEC la permission :**
- ✅ Peuvent créer et importer des jugements
- ✅ Peuvent saisir le `numero_ancien_acte` pour les jugements d'annulation, adoption, homologation
- ✅ Accès complet au formulaire d'import

---

## 🔍 **VÉRIFICATION**

### **Vérifier que la permission est créée :**

```bash
php artisan tinker
>>> DB::table('tr_fonctionnalite')->where('code_fonctionnalite', 'FNC_0070')->get()
```

### **Vérifier qu'une fonction a la permission :**

```sql
SELECT f.lib_fonction, ff.code_fonctionnalite, fo.lib_fonctionnalite
FROM tr_fonction f
JOIN tr_ff ff ON f.code_fonction = ff.code_fonction
JOIN tr_fonctionnalite fo ON ff.code_fonctionnalite = fo.code_fonctionnalite
WHERE ff.code_fonctionnalite = 'FNC_0070';
```

### **Tester l'accès :**

1. **Avec un utilisateur autorisé :**
   - Connexion
   - Menu : TRIBUNAL > Dossiers reçus > Certificats
   - Clic sur "Importer document"
   - ✅ Le formulaire doit s'afficher

2. **Avec un utilisateur NON autorisé :**
   - Connexion
   - Tentative d'accès direct : `/tribunal/document/create/naissance/123`
   - ❌ Erreur 403 Forbidden

---

## 📝 **FICHIERS MODIFIÉS**

| Fichier | Type | Description |
|---------|------|-------------|
| `database/seeders/Data/fonctionnalites_definitions.php` | Seeder Data | Ajout FNC_0070 |
| `Modules/Tribunal/Routes/web.php` | Routes | Protection des routes |
| `PERMISSION_CREATION_JUGEMENT.md` | Documentation | Ce fichier |

---

## 🎯 **UTILISATEURS CONCERNÉS**

### **Fonctions qui DOIVENT avoir cette permission :**
- Greffier du Tribunal
- Agent du Tribunal
- Président du Tribunal
- Substitut du Procureur
- Tout agent tribunal qui traite les jugements

### **Fonctions qui NE DOIVENT PAS avoir cette permission :**
- Agents du CEC (Centre d'État Civil)
- Agents des formations sanitaires
- Agents des pompes funèbres
- Officiers d'état civil (ils reçoivent les jugements mais ne les créent pas)

---

## ⚠️ **IMPORTANT**

1. **Exécuter la migration AVANT le seeder :**
   ```bash
   php artisan migrate
   php artisan db:seed --class=FonctionnaliteSeeder
   ```

2. **Attribuer la permission aux bonnes fonctions** via l'interface d'administration

3. **Tester avec plusieurs profils utilisateurs** pour s'assurer que les autorisations fonctionnent correctement

4. **Informer les administrateurs** de la nouvelle permission à attribuer

---

## 🔗 **LIEN AVEC LES AUTRES MODIFICATIONS**

Cette permission est liée aux modifications du flux d'annulation :
- ✅ Migration : Ajout du champ `numero_ancien_acte` dans `t_jugement`
- ✅ Formulaire : Champ conditionnel pour saisir le `numero_ancien_acte`
- ✅ Service : Enregistrement du `numero_ancien_acte`
- ✅ **Permission : Contrôle d'accès pour créer les jugements** ← NOUVEAU

---

## ✅ **RÉSUMÉ**

**Problème :** Aucune permission pour contrôler la création de jugements  
**Solution :** Ajout de FNC_0070 avec protection des routes  
**Action requise :** Exécuter le seeder + Attribuer la permission aux fonctions appropriées  
**Statut :** ✅ IMPLÉMENTÉ  

**Date d'implémentation :** 2026-05-07  
**Version :** 2.1.0
