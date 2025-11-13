# 🔐 Commande de Gestion 2FA

## Description

Cette commande permet de gérer la double authentification (2FA) des utilisateurs via la ligne de commande Artisan.

## Utilisation

```bash
php artisan user:2fa {email} {action}
```

### Paramètres

- **email** : Adresse email de l'utilisateur
- **action** : Action à effectuer (`status`, `disable`, `reset`)

---

## Actions disponibles

### 1. 📊 Status - Vérifier le statut de la 2FA

```bash
php artisan user:2fa stephanie@gmail.com status
```

**Affiche :**
- État de la 2FA (activée/désactivée)
- Présence du secret
- Date de dernière vérification
- Nombre de codes de récupération disponibles

**Exemple de sortie :**
```
👤 Utilisateur trouvé : stephanie@gmail.com
📋 Code utilisateur : USR_00000003

📊 Statut de la 2FA :
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
✅ 2FA : Activée
🔑 Secret : Présent
📅 Vérifié le : 16/10/2025 14:30:45
🔐 Codes de récupération : 8 code(s)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

---

### 2. ❌ Disable - Désactiver la 2FA

```bash
php artisan user:2fa stephanie@gmail.com disable
```

**Fonction :**
- Désactive la 2FA pour l'utilisateur
- Supprime le secret, les codes de récupération
- Enregistre l'action dans l'audit trail
- Enregistre dans les logs SIFEC

**Confirmation requise** : Oui

**Exemple :**
```bash
php artisan user:2fa stephanie@gmail.com disable
```

**Sortie :**
```
👤 Utilisateur trouvé : stephanie@gmail.com
📋 Code utilisateur : USR_00000003
⚠️  Êtes-vous sûr de vouloir désactiver la 2FA pour cet utilisateur ? (yes/no) [no]:
> yes

✅ La 2FA a été désactivée avec succès pour stephanie@gmail.com
```

---

### 3. 🔄 Reset - Réinitialiser complètement la 2FA

```bash
php artisan user:2fa stephanie@gmail.com reset
```

**Fonction :**
- Désactive complètement la 2FA
- Supprime tout (secret, codes, dates)
- Permet une réactivation propre
- **Idéal pour résoudre les problèmes 419 ou de synchronisation**

**Utilisation recommandée :**
- Quand un utilisateur ne peut plus activer la 2FA
- En cas d'erreur 419 "Page expirée"
- Pour repartir sur une base propre

**Confirmation requise** : Oui

**Exemple :**
```bash
php artisan user:2fa stephanie@gmail.com reset
```

**Sortie :**
```
👤 Utilisateur trouvé : stephanie@gmail.com
📋 Code utilisateur : USR_00000003
⚠️  Cette action va complètement réinitialiser la 2FA :
   - Désactiver la 2FA
   - Supprimer le secret
   - Supprimer les codes de récupération
Continuer ? (yes/no) [no]:
> yes

✅ La 2FA a été complètement réinitialisée pour stephanie@gmail.com
📌 L'utilisateur peut maintenant activer la 2FA normalement via l'interface web.
```

---

## 🛠️ Résolution du problème 419 "Page expirée"

### Problème
L'erreur **419 "Page expirée"** se produit quand :
- La session a expiré pendant la configuration de la 2FA
- Le token CSRF est invalide
- L'utilisateur reste trop longtemps sur la page de configuration

### ✅ Solution avec la commande

```bash
# Étape 1 : Réinitialiser complètement la 2FA
php artisan user:2fa stephanie@gmail.com reset

# Étape 2 : Demander à l'utilisateur de :
# - Vider le cache du navigateur (Ctrl + Shift + Delete)
# - Se reconnecter à l'application
# - Activer la 2FA via l'interface web
```

### Solution alternative (sans commande)

1. **Vider le cache du navigateur**
   - Chrome/Edge : `Ctrl + Shift + Delete`
   - Firefox : `Ctrl + Shift + Delete`
   
2. **Se déconnecter et reconnecter**
   
3. **Réessayer l'activation de la 2FA**

---

## 📋 Traçabilité

Toutes les actions effectuées via cette commande sont :

✅ **Enregistrées dans l'audit trail** (`tr_user_audit_trail`)
- Action : `2fa_disabled`, `2fa_reset`
- Description détaillée
- Timestamp automatique

✅ **Enregistrées dans les logs SIFEC**
- Canal : `sifec`
- Fichier : `storage/logs/sifec.log`

---

## 🎯 Cas d'usage pour stephanie@gmail.com

### Problème actuel
- Compte désactivé de la 2FA
- Erreur 419 lors de la réactivation

### Solution recommandée

```bash
# 1. Réinitialiser la 2FA
php artisan user:2fa stephanie@gmail.com reset

# 2. Vérifier le statut
php artisan user:2fa stephanie@gmail.com status

# 3. Demander à stephanie de :
#    - Vider son cache navigateur
#    - Se reconnecter
#    - Activer la 2FA normalement
```

---

## 🔒 Sécurité

- ⚠️ Cette commande nécessite un accès au serveur
- ⚠️ Réservée aux administrateurs système
- ✅ Toutes les actions sont tracées
- ✅ Confirmation requise pour les actions sensibles

---

## 📞 Support

En cas de problème, vérifier :
1. Les logs : `storage/logs/sifec.log`
2. L'audit trail : Table `tr_user_audit_trail`
3. Les sessions Laravel : `storage/framework/sessions/`


