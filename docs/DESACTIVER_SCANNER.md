# Guide : Désactiver le Scanner (Optionnel)

## 🔍 Problème

Le fichier `scanner.js` tente de se connecter à un serveur WebSocket local qui génère des erreurs dans la console :

```
WebSocket [ws://127.0.0.1:9713/...] error occurs
Failed to connect to WebSocket server
```

---

## ✅ Solutions

### Option 1 : Désactiver complètement le scanner (Recommandé si non utilisé)

**Fichier à modifier :** `resources/views/layout/app.blade.php`

**Ligne 434 - Commenter le script :**

```blade
{{-- <script src="{{ asset('scanner/scanner.js') }}"></script> --}}
```

**Lignes 607-706 - Commenter les fonctions du scanner :**

```blade
{{--
<script>
    //************* Scanner ***************/
    function scanToLocalDisk() {
        // ... tout le code du scanner ...
    }
    // ... reste du code ...
</script>
--}}
```

---

### Option 2 : Charger le scanner uniquement sur certaines pages

**Dans `resources/views/layout/app.blade.php` :**

Remplacer la ligne 434 par :

```blade
@if(isset($needScanner) && $needScanner)
    <script src="{{ asset('scanner/scanner.js') }}"></script>
@endif
```

**Dans les vues nécessitant le scanner :**

```blade
@php
    $needScanner = true;
@endphp

@extends('layout.app')
```

---

### Option 3 : Garder le scanner avec gestion d'erreur silencieuse

**Créer un fichier :** `public/scanner/scanner-wrapper.js`

```javascript
// Wrapper pour gérer les erreurs du scanner silencieusement
(function() {
    'use strict';
    
    // Vérifier si scanner existe
    if (typeof scanner === 'undefined') {
        console.info('Scanner library not loaded - scanning features disabled');
        
        // Créer des fonctions vides pour éviter les erreurs
        window.scanToLocalDisk = function() {
            console.warn('Scanner not available');
        };
        
        window.scanToPdfWithThumbnails = function() {
            console.warn('Scanner not available');
        };
        
        return;
    }
    
    // Scanner disponible - fonctions normales
    console.info('Scanner library loaded successfully');
})();
```

**Puis dans `resources/views/layout/app.blade.php` :**

```blade
<script src="{{ asset('scanner/scanner.js') }}"></script>
<script src="{{ asset('scanner/scanner-wrapper.js') }}"></script>
```

---

## 📋 Quand utiliser le scanner ?

Le scanner est utilisé pour :
- ✅ Numériser des documents physiques
- ✅ Importer des pièces justificatives scannées
- ✅ Capturer des images de documents d'identité

**Si vous n'utilisez pas ces fonctionnalités, désactivez le scanner (Option 1).**

---

## 🔧 Installation du logiciel Asprise Scanner (si nécessaire)

Si vous souhaitez utiliser le scanner :

1. Télécharger Asprise Scanner : [https://asprise.com/document-scan-image-capture/direct-to-web-ajax-scanner-asp.net-mvc.html](https://asprise.com/document-scan-image-capture/direct-to-web-ajax-scanner-asp.net-mvc.html)

2. Installer le logiciel sur la machine cliente

3. Le service WebSocket sera automatiquement démarré sur les ports 9713 et 9715

4. Rafraîchir la page - les erreurs disparaîtront

---

## ✨ Recommandation

Pour la plupart des utilisateurs qui n'utilisent pas la fonctionnalité de scan :

👉 **Utiliser l'Option 1** (Désactiver complètement)

C'est la solution la plus simple et la plus propre.

---

**Date :** 04 Novembre 2025

