# SIFEC — Analyse Sécurité & Authenticité des Actes d'État Civil

> Document de référence — Mars 2026  
> Portée : Signature électronique des actes de naissance, de décès et de mariage

---

## 1. Architecture actuelle du processus de signature

### Flux de validation OTP (commun aux 3 modules)

```
[Officier]                  [SIFEC Serveur]              [SMS / Email]
    │                             │                            │
    ├── Demande signature ──────► │                            │
    │                             ├── Génère OTP ─────────────►│ SMS envoyé
    │                             ├── Stocke OTP en DB         │ Email envoyé
    │                             │                            │
    ◄── Saisit OTP ───────────────┤                            │
    │                             │                            │
    ├── Soumet OTP ─────────────► │                            │
    │                             ├── Vérifie OTP              │
    │                             ├── Enregistre signataire    │
    │                             ├── Enregistre date/heure    │
    │                             ├── Enregistre MAC + appareil│
    │                             └── Notifie le déclarant     │
```

### Fichiers concernés

| Module     | Service OTP                                          | Job Notification Email                        |
|------------|------------------------------------------------------|-----------------------------------------------|
| Naissance  | `Modules/Naissance/Services/OtpService.php`          | `ValidationacteNaissanceJob`                  |
| Décès      | `Modules/Deces/Services/OtpDecesService.php`         | `ValidationacteDecesJob`                      |
| Mariage    | `Modules/Mariage/Services/OtpService.php`            | `ValidationActeMariageJob`                    |

### Colonnes de traçabilité stockées par acte

| Colonne                          | Naissance | Décès | Mariage | Description                              |
|----------------------------------|:---------:|:-----:|:-------:|------------------------------------------|
| `approbation_mairie`             | ✅        | —     | ✅      | CUI de l'officier signataire (CEC)       |
| `approbation_pompe_funebre`      | —         | ✅    | —       | CUI de l'officier signataire (PF)        |
| `date_heure_approbation_mairie`  | ✅        | —     | ✅      | Horodatage de la signature               |
| `date_heure_approbation_pompe_funebre` | —   | ✅    | —       | Horodatage de la signature               |
| `otp_approbation_mairie`         | ✅        | —     | ✅      | Code OTP utilisé pour signer             |
| `otp_approbation_pompe_funebre`  | —         | ✅    | —       | Code OTP utilisé pour signer             |
| `adresse_mac_approbation`        | ✅        | ✅    | ✅      | Adresse MAC de l'appareil                |
| `nom_appareil_approbation`       | ✅        | ✅    | ✅      | Nom de l'appareil                        |
| `signature_mairie`               | ✅        | —     | ✅      | Image de signature (base64)              |
| `signature_pompe_funebre`        | —         | ✅    | —       | Image de signature (base64)              |

---

## 2. Points forts en place

### ✅ 2.1 Double canal de notification (SMS + Email)
L'OTP est envoyé simultanément par :
- **SMS** via `SifecFacade::sendSms()` et `SendSmsJob`
- **Email** via `ValidationacteNaissanceJob` / `ValidationacteDecesJob` / `ValidationActeMariageJob`

C'est un mécanisme **2 facteurs** (connaissance du mot de passe + possession du téléphone/email).

### ✅ 2.2 Horodatage de la signature
`date_heure_approbation_*` crée une preuve temporelle incontestable de la signature, utile en cas de contestation juridique.

### ✅ 2.3 Non-répudiation par le CUI
Le `cui` (Code Utilisateur Institution) lie la signature à une personne physique identifiée dans le système, dans une institution donnée.

### ✅ 2.4 Traçabilité de l'appareil
Les champs `adresse_mac_approbation` et `nom_appareil_approbation` permettent d'identifier l'équipement physique utilisé.

### ✅ 2.5 Image de signature
`signature_mairie` / `signature_pompe_funebre` stocke la représentation graphique de la signature de l'officier.

---

## 3. Failles identifiées et corrections planifiées

### 🔴 CRITIQUE — Faille 1 : OTP prédictible (Naissance & Décès)

**Fichiers concernés :**
- `Modules/Naissance/Services/OtpService.php` ligne 16
- `Modules/Deces/Services/OtpDecesService.php` ligne 20

**Code problématique :**
```php
$otp = substr(time(), 2);  // Prévisible ! = derniers chiffres de l'heure Unix
```

**Risque :** Un attaquant connaissant l'heure approximative de la demande peut bruteforcer
l'OTP en quelques secondes (espace de recherche < 1 000 000 valeurs).

**Correction appliquée :**
```php
$otp = str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
// random_int() utilise le générateur cryptographiquement sûr du système d'exploitation
```

---

### 🔴 CRITIQUE — Faille 2 : Absence d'expiration de l'OTP

**Fichiers concernés :** Les 3 services OTP

**Problème :** L'OTP stocké dans la colonne `otp_approbation_*` reste valide **indéfiniment**.
Un OTP intercepté ou une fuite de la base de données permet une signature frauduleuse à tout moment.

**Correction appliquée :**
- Ajout d'une colonne `otp_expire_at` (timestamp) sur les tables `t_acte_naissance`,
  `t_acte_deces` et `t_acte_mariage`.
- L'OTP expire **1 minute** après sa génération.
- La validation vérifie `now() <= otp_expire_at` avant d'accepter le code.

```php
// Génération
$acte->otp_expire_at = now()->addMinute();

// Validation
if (now()->gt($acte->otp_expire_at)) {
    return [false, "Code OTP expiré. Veuillez en demander un nouveau."];
}
```

---

### 🟠 IMPORTANT — Faille 3 : Appareil non whitelisté

**Problème :** `adresse_mac_approbation` est enregistrée mais **jamais vérifiée**.
N'importe quel appareil inconnu peut signer un acte. De plus, une adresse MAC
peut être usurpée (MAC spoofing) si aucune liste blanche n'existe.

**Correction planifiée :**
- Création de la table `tr_appareils` pour enregistrer les appareils autorisés par institution.
- Avant signature, le service vérifie que l'adresse MAC soumise figure dans `tr_appareils`
  avec le statut `actif`.

**Structure de `tr_appareils` :**

| Colonne              | Type          | Description                                  |
|----------------------|---------------|----------------------------------------------|
| `code_appareil`      | varchar(16)   | Clé primaire                                 |
| `adresse_mac`        | varchar(50)   | Adresse MAC (unique)                         |
| `nom_appareil`       | varchar(100)  | Nom lisible de l'appareil                    |
| `type_appareil`      | enum          | ordinateur / tablette / smartphone / autre   |
| `code_institution`   | varchar(16)   | Institution propriétaire (FK)                |
| `enregistre_par`     | varchar(16)   | CUI de l'administrateur (FK)                 |
| `statut`             | boolean       | true = actif, false = désactivé              |
| `date_enregistrement`| timestamp     | Date d'ajout dans le système                 |

---

### 🟡 MODÉRÉ — Faille 4 : Intégrité du contenu non garantie

**Problème :** L'OTP prouve que l'officier a confirmé sa signature à un instant T,
mais rien ne prouve que le **contenu de l'acte n'a pas été modifié après la signature**.

**Correction recommandée (future) :**
Calculer un hash SHA-256 des données sensibles de l'acte au moment de la signature
et le stocker dans une colonne `hash_contenu` :

```php
$hash = hash('sha256', json_encode([
    $acte->code_declaration_naissance,
    $acte->cui,
    $acte->date_emission,
    $acte->approbation_mairie,
    $acte->date_heure_approbation_mairie,
]));
$acte->hash_contenu = $hash;
```

La page de vérification QR code recalcule le hash et le compare pour détecter toute altération.

---

### 🟡 MODÉRÉ — Faille 5 : Email envoyé au mauvais champ (Naissance & Décès)

**Fichiers concernés :**
- `OtpService.php` (Naissance) ligne 87 : utilise `Auth::user()->personne->email`
- `OtpDecesService.php` ligne 94 : utilise `Auth::user()->email`

**Problème :** L'email de notification au déclarant utilise l'email de l'officier connecté
au lieu de `contact->email_professionnelle` du déclarant.

**Correction appliquée :** Utiliser systématiquement `$contactDeclarant->email_professionnelle`.

---

## 4. Plan d'implémentation

| Priorité | Action                                    | Statut        |
|:--------:|-------------------------------------------|---------------|
| 🔴 P1    | OTP cryptographiquement sûr (Naissance)   | ✅ Corrigé    |
| 🔴 P1    | OTP cryptographiquement sûr (Décès)       | ✅ Corrigé    |
| 🔴 P1    | Colonne `otp_expire_at` (3 tables)        | ✅ Ajouté     |
| 🔴 P1    | Validation expiration OTP (3 services)    | ✅ Corrigé    |
| 🟠 P2    | Table `tr_appareils`                      | ✅ Créé       |
| 🟠 P2    | Vérification whitelist appareils          | ✅ Ajouté     |
| 🟡 P3    | Hash SHA-256 du contenu de l'acte         | ⏳ Prévu      |
| 🟡 P3    | Correction email déclarant                | ✅ Corrigé    |

---

## 5. Évaluation post-corrections

| Critère de sécurité                    | Avant | Après |
|----------------------------------------|:-----:|:-----:|
| Imprévisibilité de l'OTP               | ❌    | ✅    |
| Expiration de l'OTP                    | ❌    | ✅    |
| Double canal (SMS + Email officier)    | ✅    | ✅    |
| Identification de l'officier (CUI)     | ✅    | ✅    |
| Whitelist des appareils autorisés      | ❌    | ✅    |
| Horodatage de signature                | ✅    | ✅    |
| Intégrité du contenu (hash)            | ❌    | ⏳    |

---

*Document généré automatiquement par l'assistant de développement SIFEC.*
