# Tests de charge k6 — back-office SIFEC

## Prérequis

1. Installer [k6](https://k6.io/docs/get-started/installation/) (Windows : `choco install k6` ou binaire officiel).
2. Application SIFEC joignable (Laragon, `php artisan serve`, ou staging).
3. Un **compte de test** avec :
   - **2FA désactivé** (sinon le script s’arrête après détection du flux 2FA) ;
   - **affectation active** (sinon message d’erreur métier à la connexion) ;
   - mot de passe connu.

## Flux reproduit par `back-office.js`

1. `GET /login` — page de formulaire (`Auth\LoginController@showLoginForm`).
2. Extraction du jeton CSRF (`_token` dans le HTML).
3. `POST /store` — route nommée `dashboard.login` (`AuthentificationController@authentification`), **pas** `POST /login`.
4. Pause optionnelle (`THINK_MS`).
5. `GET /` — tableau de bord (`dashboard.index`, utilisateur connecté).

## Commande type

```bash
cd /chemin/vers/sifec

k6 run -e BASE_URL=http://sifec.test ^
  -e SIFEC_EMAIL=votre@email.test ^
  -e SIFEC_PASSWORD=motdepasse ^
  benchmarks/k6/back-office.js
```

Sous PowerShell, préférer des guillemets si besoin : `-e "BASE_URL=http://127.0.0.1:8000"`.

### Variables d’environnement

| Variable | Obligatoire | Description |
|----------|-------------|-------------|
| `BASE_URL` | Non | **Doit être identique à `APP_URL`** du `.env` (sans slash final), ex. `http://192.168.100.11:8010/sifec`. Si l’app est sous `.../sifec/public`, inclure `/public`. |
| `SIFEC_EMAIL` | Oui | Email du compte |
| `SIFEC_PASSWORD` | Oui | Mot de passe |
| `THINK_MS` | Non | Pause en ms entre connexion et `GET /` (défaut : `500`) |

### Essai rapide (1 utilisateur, 1 itération)

```bash
k6 run --vus 1 --iterations 1 -e BASE_URL=http://sifec.test -e SIFEC_EMAIL=... -e SIFEC_PASSWORD=... benchmarks/k6/back-office.js
```

## Limitations

- **Throttle Laravel** sur le login : en cas de trop nombreuses tentatives, ajuster le rate limit ou espacer les VU.
- **Première connexion / mot de passe provisoire** : le compte ne doit pas être forcé vers `premiere-connexion/mot-de-passe` pour un scénario stable.
- **Environnement local** : les résultats ne reflètent pas la production (CPU partagé, MySQL sur la même machine).

## Fichiers

- [back-office.js](back-office.js) — scénario principal.
