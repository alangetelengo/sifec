# API `/api/v1` — classification et alignement avec les autorisations

Préfixe réel des URLs : **`/api/v1`** (défini dans `bootstrap/app.php`).

Légende :

- **Public portail** : accessible sans session web ; doit être limité (débit), traçable, et ne pas exposer plus que le besoin.
- **Semi-public** : même idée, mais données nominatives ou PDF ; renforcer (jeton de demande, signature URL, clé API).
- **Interne / admin** : ne devrait **pas** rester public ; prévoir `auth:api` + contrôle métier (institution, fonction).
- **Technique** : login, webhooks, redirections paiement.

---

## 1. Inventaire par route

| Méthode | Chemin (après `/api/v1/`) | Contrôleur | Classification | Risques / remarques |
|--------|----------------------------|------------|----------------|----------------------|
| POST | `login` | `UserController@login` | **Technique** | Émet un **token Passport** avec les scopes définis dans `config/sifec_passport.php` (`default_personal_token_scopes`). La réponse JSON inclut `scopes`. |
| POST | `upload-signatures` | `SignatureController@upload` | **API authentifiée** | **`auth:api` + scope `signatures-mariage`**. Obtenir un token via `POST /api/v1/login` puis envoyer `Authorization: Bearer …`. |
| POST | `verificationActe` | `DocumentEtatCivilController@verificationActe` | **Public portail** | ~~Bug : `return "ok"`~~ **Corrigé**. La validation globale `type_acte` (inutilisée dans la suite) a été retirée pour ne pas casser les clients existants ; 422 si ni `numero_declaration` ni branche déclarant exploitable. |
| POST | `authentification` | `AuthentificationActeController@authentification` | **Public portail** | Vérification d’authenticité d’acte (usage grand public). |
| POST | `demandeActe` | `AuthentificationActeController@demandeActe` | **Public portail** | Création de demande (copie / extrait / duplicata). |
| GET | `copie/actenaissance/{id}` | `AuthentificationActeController@displayCopie` | **Semi-public** | PDF / document lié à un **identifiant** : risque d’énumération si `id` prévisible. Préférer jeton lié à la demande payée. |
| GET | `extrait/actenaissance/{id}` | … `displayExtraitActe` | **Semi-public** | Idem. |
| GET | `duplicata/actenaissance/{id}` | … `displayDuplicata` | **Semi-public** | Idem. |
| GET | `copie/actenaissance/portail/{id}` | … `displayCopiePortail` | **Semi-public** | Idem (portail). |
| GET | `extrait/actenaissance/portail/{id}` | … `displayExtraitActePortail` | **Semi-public** | Idem. |
| GET | `copie/actedeces/{id}` | … `displayCopieDeces` | **Semi-public** | Idem. |
| GET | `extrait/actedeces/{id}` | … `displayExtraitActeDeces` | **Semi-public** | Idem. |
| GET | `duplicata/actedeces/{id}` | … `displayDuplicataDeces` | **Semi-public** | Idem. |
| GET | `etatactenaissance/{id}` | … `displayActe` | **Semi-public** | État d’acte. |
| GET | `etatactedeces/{id}` | … `displayActeDeces` | **Semi-public** | Idem. |
| GET | `etatactemariage/{id}` | … `displayActeMariage` | **Semi-public** | Idem. |
| GET | `listeCec` | … `listeCec` | **Public portail** | Liste des CEC pour le portail ; acceptable si données non sensibles. |
| GET | `banMariage` | `BanController@journalMariagesSansActe` | **Interne / admin** | **`auth:api` + scope `mariage-ban` + `can:module.acteMariage`**. Les anciens jetons **sans scopes** doivent être renouvelés (nouvelle connexion). |
| POST | `statutPaiementMomo` | `PayementController@statutPaiementMomo` | **Technique** | Webhook / callback Mobile Money : valider signature / secret opérateur. |
| GET | `etatRecouvrement` | — | **Retirée** | Pointait vers une **méthode inexistante**. Réutiliser la route web authentifiée ou réimplémenter en API. |
| POST | `historiqueAuthentification` | — | **Retirée** | Idem. |
| GET | `etatHistorique/{id}` | — | **Retirée** | Idem. |
| GET | `etatRecetteDemandeEnLigne` | — | **Retirée** | Idem. |
| POST | `rdcPaiement` | `PayementController@rdcpaiement` | **Technique** | Paiement RDC : validation forte (montant, référence, idempotence). |
| GET | `paiement` | `PayementController@paiement` | **Technique / semi-public** | Page ou redirection de paiement : à documenter. |
| GET | `successPaypal` | `PayementController@successPaypal` | **Technique** | Retour PayPal. |
| GET | `cancelPaypal` | `PayementController@cancelPaypal` | **Technique** | Idem. |
| GET | `etatRecuNaissance/{id}` | `AuthentificationActeController@etatRecuNaissance` | **Semi-public** | Reçu lié à une authentification / demande. |
| GET | `etatRecuMariage/{id}` | … `etatRecuMariage` | **Semi-public** | Idem. |
| GET | `etatRecuDeces/{id}` | … `etatRecuDeces` | **Semi-public** | Idem. |
| GET | `etatRecuDecesNA/{id}` | … `etatRecuDecesNA` | **Semi-public** | Idem. |
| GET | `etatRecuNaissanceNA/{id}` | … `etatRecuNaissanceNA` | **Semi-public** | Idem. |
| GET | `etatRecuMariageNA/{id}` | … `etatRecuMariageNA` | **Semi-public** | Idem. |

---

## 2. Alignement avec les `lib_technique` (routes **web** métier)

Les fonctionnalités sont définies dans `database/seeders/Data/fonctionnalites_definitions.php`. Les **`can:`** du back-office s’appuient sur ces `lib_technique` (voir `AuthServiceProvider` et `User::toutesfonctionnalites()`).

Exemples déjà utilisés dans le code :

| Domaine | `lib_technique` (racine ou menu) |
|---------|-----------------------------------|
| Naissance | `module.acteNaissance`, `module.menus.naissance`, `module.acteNaissance.generate`, … |
| Décès | `module.acteDeces`, `module.menus.deces`, … |
| Mariage | `module.acteMariage`, `module.menus.mariage`, … |
| Référentiel | `module.menus.referentiel`, `module.registre.create`, … |
| Administration | `module.menus.administration`, `module.users`, `module.menuItems.manage`, … |

### Pistes pour l’API `/api/v1`

1. **Ne pas réutiliser tel quel les `can:` web** sur des routes **100 % publiques** (pas d’utilisateur). Pour le portail, préférer :
   - jetons **liés à une demande** (hash après paiement),
   - ou **scopes Passport** dédiés (`portail:lecture-acte`, etc.) pour les apps qui ont un compte technique.

2. **Routes internes sensibles** : `banMariage` et `upload-signatures` utilisent **`auth:api` + scopes Passport** (voir section 3). D’autres endpoints pourront exiger le scope `sifec-api` seul ou avec `can:` métier.

3. **Cartographie indicative** (à affiner métier) :

| Route API | Permission / stratégie |
|-----------|-------------------------|
| Affichage acte / copie / extrait / duplicata / reçus | Jeton de **demande** ou identifiant non énumérable + statut payé |
| `banMariage` | `auth:api` + `scope:mariage-ban` + `can:module.acteMariage` |
| `upload-signatures` | `auth:api` + `scope:signatures-mariage` |
| `login` (API) | Émet les scopes configurés dans `config/sifec_passport.php` |

---

## 3. Scopes Passport (jetons personnels)

| Scope | Usage |
|-------|--------|
| `sifec-api` | Accès général API authentifiée (réserve pour routes futures sous `scope:sifec-api`). |
| `mariage-ban` | Route **`GET banMariage`**. |
| `signatures-mariage` | Route **`POST upload-signatures`**. |

Configuration : **`config/sifec_passport.php`** (libellés OAuth + liste des scopes attribués au login).  
Middleware Laravel : alias **`scope`** et **`scope.any`** enregistrés dans **`bootstrap/app.php`** (classes Passport `CheckToken` / `CheckTokenForAnyScope`).

Pour un compte qui ne doit **pas** consulter le BAN ou envoyer des signatures, adaptez la logique de `UserController@login` (scopes différents par rôle) ou utilisez des **clients OAuth** dédiés au lieu du flux login mot de passe.

Si **`can:module.acteMariage`** bloque un profil légitime pour `banMariage`, retirez ce middleware sur la route dans `routes/v1/api.php` ou ajoutez la fonctionnalité **`module.acteMariage`** (FNC_0029) au rôle concerné.

---

## 4. Actions recommandées (priorité)

1. **Réimplémenter si besoin** les endpoints retirés (`etatRecouvrement`, `historiqueAuthentification`, `etatHistorique`, `etatRecetteDemandeEnLigne`) sur un contrôleur API dédié avec `auth:api` et règles métier.
2. **Vérifier les intégrations** (tablettes signatures, afficheurs BAN) : elles doivent envoyer le **Bearer** après login.
3. **Passer en revue** tous les GET avec `{id}` : identifiants non devinables + **throttle** + logs d’accès.

---

## 5. Référence module métier (`EnsureBusinessModuleIsActive`)

Les noms de routes listés dans `config/sifec_domain_modules.php` sont contrôlés pour l’**activation globale** des modules (`MOD_0002`, `MOD_0003`, `MOD_0004`). Les routes **API** nommées dans ce fichier héritent du même contrôle lorsqu’elles passent par le middleware `api` qui inclut `EnsureBusinessModuleIsActive` (voir `bootstrap/app.php`).
