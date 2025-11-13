# Guide d'utilisation des Notifications par Module

## Types de notifications disponibles

### 1. Notifications pour Actes de Naissance
**Classe :** `ActeAValiderNotification`
**Badge :** Vert (badge-success)
**Route :** `acteNaissance.print.acte`

```php
use Modules\Notification\Notifications\ActeAValiderNotification;

// Exemple d'utilisation
NotificationService::notifierAgentsInstitution(
    $codeInstitution,
    new ActeAValiderNotification($numeroActe, $observation),
    "FONC_0006" // Code fonction optionnel
);
```

### 2. Notifications pour Actes de Décès
**Classe :** `ActeDecesAValiderNotification`
**Badge :** Rouge (badge-danger)
**Route :** `acteDeces.print.acte`

```php
use Modules\Notification\Notifications\ActeDecesAValiderNotification;

// Exemple d'utilisation
NotificationService::notifierAgentsInstitution(
    $codeInstitution,
    new ActeDecesAValiderNotification($numeroActe, $observation),
    "FONC_0006"
);
```

### 3. Notifications pour Actes de Mariage
**Classe :** `ActeMariageAValiderNotification`
**Badge :** Orange (badge-warning)
**Route :** `acteMariage.print.acte`

```php
use Modules\Notification\Notifications\ActeMariageAValiderNotification;

// Exemple d'utilisation
NotificationService::notifierAgentsInstitution(
    $codeInstitution,
    new ActeMariageAValiderNotification($numeroActe, $observation),
    "FONC_0006"
);
```

### 4. Autres types de notifications
- **DeclarationEnvoyeeCentreNotification** - Badge bleu (badge-primary)
- **DocumentImporteTribunalNotification** - Badge noir (badge-dark)
- **RectificationEnvoyeeTribunalNotification** - Badge gris (badge-secondary)

## Affichage dans l'interface

Le système de notifications affiche automatiquement :
- Un badge coloré selon le type de notification
- Un libellé descriptif (ex: "Acte Naissance", "Acte Décès", "Acte Mariage")
- Un bouton "Voir" qui redirige vers la page d'impression de l'acte
- Un bouton "Lu" pour marquer la notification comme lue

## Structure des données de notification

Chaque notification contient :
- `message` : Le texte descriptif de la notification
- `observation` : Observation optionnelle
- `url` : URL vers la page de visualisation de l'acte

## Routes disponibles

- `notifications.index` - Liste des notifications
- `notifications.read` - Marquer une notification comme lue
- `notifications.markAllAsRead` - Marquer toutes les notifications comme lues
