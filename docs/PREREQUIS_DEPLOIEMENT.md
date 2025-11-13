# Prérequis pour le Déploiement de SIFEC

## 📋 Table des Matières
1. [Prérequis Matériels](#prérequis-matériels)
2. [Prérequis Logiciels](#prérequis-logiciels)
3. [Extensions PHP Requises](#extensions-php-requises)
4. [Base de Données](#base-de-données)
5. [Serveur Web](#serveur-web)
6. [Outils de Déploiement](#outils-de-déploiement)
7. [Configuration du Serveur](#configuration-du-serveur)
8. [Services Additionnels](#services-additionnels)

---

## 🖥️ Prérequis Matériels

### Configuration Minimale (Petite Institution - < 100 utilisateurs)
- **Processeur**: 2 vCPU / Cores
- **Mémoire RAM**: 4 GB
- **Stockage**: 50 GB SSD
- **Bande passante**: 10 Mbps

### Configuration Recommandée (Institution Moyenne - 100-500 utilisateurs)
- **Processeur**: 4 vCPU / Cores
- **Mémoire RAM**: 8 GB
- **Stockage**: 100 GB SSD
- **Bande passante**: 50 Mbps

### Configuration Optimale (Grande Institution - > 500 utilisateurs)
- **Processeur**: 8+ vCPU / Cores
- **Mémoire RAM**: 16+ GB
- **Stockage**: 250+ GB SSD (NVMe recommandé)
- **Bande passante**: 100+ Mbps

---

## 💻 Prérequis Logiciels

### 1. Système d'Exploitation
**Options supportées** :
- Ubuntu 20.04 LTS / 22.04 LTS (Recommandé)
- Debian 10/11
- CentOS 7/8 / Rocky Linux 8
- Windows Server 2019/2022 (avec précautions)

**Recommandation** : Ubuntu 22.04 LTS pour une meilleure stabilité et support

### 2. PHP
**Version requise** :
- **Minimum** : PHP 7.3
- **Recommandé** : PHP 8.1 ou supérieur

**Installation sur Ubuntu** :
```bash
sudo apt update
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.1 php8.1-fpm
```

### 3. Composer
**Version** : 2.x (dernière version stable)

**Installation** :
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### 4. Node.js et NPM
**Version Node.js** : 14.x ou supérieur (16.x LTS recommandé)
**Version NPM** : 6.x ou supérieur

**Installation sur Ubuntu** :
```bash
curl -fsSL https://deb.nodesource.com/setup_16.x | sudo -E bash -
sudo apt install -y nodejs
```

---

## 🔧 Extensions PHP Requises

### Extensions Essentielles Laravel
```
✅ BCMath      - Calculs mathématiques de précision
✅ Ctype       - Vérification des types de caractères
✅ JSON        - Manipulation JSON
✅ Mbstring    - Support multi-octets
✅ OpenSSL     - Cryptographie et sécurité
✅ PDO         - Connexion base de données
✅ PDO_MySQL   - Driver MySQL pour PDO
✅ Tokenizer   - Analyse syntaxique PHP
✅ XML         - Traitement XML
```

### Extensions pour Fonctionnalités Spécifiques
```
✅ GD ou Imagick - Génération de QR codes et manipulation d'images
✅ Fileinfo      - Détection de type de fichiers
✅ CURL          - Requêtes HTTP
✅ Zip           - Compression/décompression
✅ Intl          - Internationalisation
✅ Redis         - Cache et sessions (optionnel mais recommandé)
```

### Installation des Extensions (Ubuntu + PHP 8.1)
```bash
sudo apt install -y \
    php8.1-bcmath \
    php8.1-ctype \
    php8.1-json \
    php8.1-mbstring \
    php8.1-openssl \
    php8.1-pdo \
    php8.1-mysql \
    php8.1-tokenizer \
    php8.1-xml \
    php8.1-gd \
    php8.1-fileinfo \
    php8.1-curl \
    php8.1-zip \
    php8.1-intl \
    php8.1-redis
```

### Vérification des Extensions
```bash
php -m | grep -E 'bcmath|ctype|json|mbstring|openssl|pdo|mysql|tokenizer|xml|gd|fileinfo|curl|zip'
```

---

## 🗄️ Base de Données

### MySQL (Recommandé)
**Version** : 8.0 ou supérieur
**Alternative** : MariaDB 10.6 ou supérieur

### Configuration Recommandée

**Installation MySQL 8.0 sur Ubuntu** :
```bash
sudo apt update
sudo apt install mysql-server
sudo mysql_secure_installation
```

### Paramètres de Configuration MySQL (`/etc/mysql/mysql.conf.d/mysqld.cnf`)

```ini
[mysqld]
# Charset et Collation
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

# Moteur de stockage
default-storage-engine = InnoDB

# Performance
innodb_buffer_pool_size = 2G           # 50-70% de la RAM disponible
innodb_log_file_size = 256M
max_connections = 200
query_cache_size = 0                    # Désactivé dans MySQL 8.0
query_cache_type = 0

# Sécurité
sql_mode = STRICT_TRANS_TABLES,NO_ENGINE_SUBSTITUTION

# Logs
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2
```

### Création de la Base de Données

```sql
CREATE DATABASE sifec CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sifec_user'@'localhost' IDENTIFIED BY 'mot_de_passe_fort';
GRANT ALL PRIVILEGES ON sifec.* TO 'sifec_user'@'localhost';
FLUSH PRIVILEGES;
```

### Taille Estimée de la Base de Données
- **Démarrage** : < 100 MB
- **Après 1 an d'utilisation (institution moyenne)** : 2-5 GB
- **Après 5 ans (institution moyenne)** : 10-20 GB

---

## 🌐 Serveur Web

### Option 1 : Nginx (Recommandé pour Production)

**Version** : 1.18 ou supérieur

**Installation** :
```bash
sudo apt update
sudo apt install nginx
```

**Configuration Nginx** (`/etc/nginx/sites-available/sifec`):
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name votre-domaine.com;
    
    root /var/www/sifec/public;
    index index.php index.html;

    # Logs
    access_log /var/log/nginx/sifec-access.log;
    error_log /var/log/nginx/sifec-error.log;

    # Taille maximale des uploads
    client_max_body_size 100M;

    # Sécurité
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        
        # Timeout pour les requêtes longues
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Activation du site** :
```bash
sudo ln -s /etc/nginx/sites-available/sifec /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Option 2 : Apache

**Version** : 2.4 ou supérieur

**Installation** :
```bash
sudo apt install apache2 libapache2-mod-php8.1
```

**Configuration Apache** (`/etc/apache2/sites-available/sifec.conf`):
```apache
<VirtualHost *:80>
    ServerName votre-domaine.com
    DocumentRoot /var/www/sifec/public

    <Directory /var/www/sifec/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # Logs
    ErrorLog ${APACHE_LOG_DIR}/sifec-error.log
    CustomLog ${APACHE_LOG_DIR}/sifec-access.log combined

    # Taille maximale des uploads
    php_value upload_max_filesize 100M
    php_value post_max_size 100M
</VirtualHost>
```

**Activation des modules et du site** :
```bash
sudo a2enmod rewrite
sudo a2ensite sifec.conf
sudo systemctl reload apache2
```

---

## 🛠️ Outils de Déploiement

### 1. Git
**Version** : 2.x ou supérieur

```bash
sudo apt install git
```

### 2. Supervisord (Pour les Jobs Queue)
```bash
sudo apt install supervisor
```

**Configuration** (`/etc/supervisor/conf.d/sifec-worker.conf`):
```ini
[program:sifec-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/sifec/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/sifec/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start sifec-worker:*
```

### 3. Cron (Pour les Tâches Planifiées)

Ajouter dans crontab (`crontab -e` en tant que www-data):
```bash
* * * * * cd /var/www/sifec && php artisan schedule:run >> /dev/null 2>&1
```

---

## ⚙️ Configuration du Serveur

### 1. PHP Configuration (`/etc/php/8.1/fpm/php.ini`)

```ini
; Performance
memory_limit = 512M
max_execution_time = 300
max_input_time = 300

; Uploads
upload_max_filesize = 100M
post_max_size = 100M

; Sessions
session.lifetime = 7200

; Opcache (Important pour la performance)
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1

; Timezone
date.timezone = Africa/Brazzaville
```

### 2. Permissions des Fichiers

```bash
# Propriétaire des fichiers
sudo chown -R www-data:www-data /var/www/sifec

# Permissions des répertoires
sudo find /var/www/sifec -type d -exec chmod 755 {} \;
sudo find /var/www/sifec -type f -exec chmod 644 {} \;

# Permissions spéciales pour storage et bootstrap/cache
sudo chmod -R 775 /var/www/sifec/storage
sudo chmod -R 775 /var/www/sifec/bootstrap/cache

# Permissions pour les uploads
sudo chmod -R 775 /var/www/sifec/public/uploads
sudo chmod -R 775 /var/www/sifec/public/signatures
```

### 3. Pare-feu (UFW)

```bash
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable
```

---

## 🔐 Services Additionnels

### 1. SSL/TLS (Certbot + Let's Encrypt)

**Installation** :
```bash
sudo apt install certbot python3-certbot-nginx
```

**Génération du certificat** :
```bash
sudo certbot --nginx -d votre-domaine.com
```

**Renouvellement automatique** :
```bash
sudo certbot renew --dry-run
```

### 2. Redis (Cache et Sessions - Optionnel mais Recommandé)

**Installation** :
```bash
sudo apt install redis-server
```

**Configuration** (`/etc/redis/redis.conf`):
```ini
maxmemory 256mb
maxmemory-policy allkeys-lru
```

```bash
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

### 3. Système de Sauvegarde

**Exemple de script de sauvegarde** (`/root/backup-sifec.sh`):
```bash
#!/bin/bash
BACKUP_DIR="/backups/sifec"
DATE=$(date +%Y%m%d_%H%M%S)

# Backup Base de données
mysqldump -u sifec_user -p'mot_de_passe' sifec > "$BACKUP_DIR/db_$DATE.sql"

# Backup Fichiers
tar -czf "$BACKUP_DIR/files_$DATE.tar.gz" /var/www/sifec/storage /var/www/sifec/public/uploads

# Nettoyage (garder seulement les 7 derniers jours)
find $BACKUP_DIR -type f -mtime +7 -delete
```

**Cron pour backup quotidien** (à 2h du matin):
```bash
0 2 * * * /root/backup-sifec.sh >> /var/log/sifec-backup.log 2>&1
```

### 4. Monitoring (Optionnel)

**Options recommandées** :
- **New Relic** : Monitoring APM complet
- **DataDog** : Infrastructure et applications
- **Nagios / Zabbix** : Monitoring open source
- **Uptime Robot** : Monitoring de disponibilité

---

## 📦 Dépendances du Projet

### Dépendances PHP (Composer)

Les principales dépendances sont définies dans `composer.json` :

```json
{
    "require": {
        "php": "^7.3|^8.1",
        "laravel/framework": "^8.75",
        "laravel/passport": "10.3.3",
        "laravel/sanctum": "^2.11",
        "laravel/tinker": "^2.5",
        "laravel/ui": "^3.4",
        "barryvdh/laravel-dompdf": "^2.2",
        "spipu/html2pdf": "^5.2",
        "bacon/bacon-qr-code": "^2.0",
        "pragmarx/google2fa": "^8.0",
        "nwidart/laravel-modules": "^8.3",
        "guzzlehttp/guzzle": "^7.0.1",
        "kalnoy/nestedset": "^6.0",
        "staudenmeir/laravel-adjacency-list": "^1.0",
        "webpatser/laravel-uuid": "^4.0",
        "fruitcake/laravel-cors": "^2.0",
        "yoeunes/toastr": "^2.0"
    }
}
```

**Fonctionnalités fournies** :
- Génération de PDF (DOMPDF, HTML2PDF)
- QR Codes (bacon-qr-code)
- Authentification 2FA (google2fa)
- Architecture modulaire (laravel-modules)
- API REST (Passport, Sanctum)

### Dépendances Frontend (NPM)

```json
{
    "devDependencies": {
        "axios": "^0.21",
        "laravel-mix": "^6.0.6",
        "lodash": "^4.17.19",
        "postcss": "^8.1.14"
    }
}
```

---

## 📝 Variables d'Environnement Requises

Créer un fichier `.env` à la racine du projet avec les paramètres suivants :

```env
# Application
APP_NAME="SIFEC"
APP_ENV=production
APP_KEY=base64:GENERER_AVEC_php_artisan_key_generate
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sifec
DB_USERNAME=sifec_user
DB_PASSWORD=mot_de_passe_fort

# Cache & Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=database

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"

# Passport
PASSPORT_PERSONAL_ACCESS_CLIENT_ID=
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=
```

---

## ✅ Checklist de Déploiement

### Avant le Déploiement
- [ ] Serveur avec configuration matérielle adéquate
- [ ] Système d'exploitation à jour (Ubuntu 22.04 LTS recommandé)
- [ ] PHP 8.1 installé avec toutes les extensions
- [ ] MySQL 8.0 installé et configuré
- [ ] Nginx ou Apache installé et configuré
- [ ] Composer installé (version 2.x)
- [ ] Node.js et NPM installés
- [ ] Git installé
- [ ] Certificat SSL configuré (Let's Encrypt)
- [ ] Redis installé (optionnel mais recommandé)
- [ ] Pare-feu configuré

### Pendant le Déploiement
- [ ] Clone du dépôt Git
- [ ] Installation des dépendances Composer (`composer install --optimize-autoloader --no-dev`)
- [ ] Installation des dépendances NPM (`npm install && npm run production`)
- [ ] Configuration du fichier `.env`
- [ ] Génération de la clé d'application (`php artisan key:generate`)
- [ ] Exécution des migrations (`php artisan migrate --force`)
- [ ] Exécution des seeders (`php artisan db:seed` ou `php artisan db:reseed`)
- [ ] Création du lien symbolique storage (`php artisan storage:link`)
- [ ] Configuration des permissions
- [ ] Configuration de Supervisor pour les queues
- [ ] Configuration du cron pour les tâches planifiées

### Après le Déploiement
- [ ] Test de connexion à l'application
- [ ] Test de création d'acte
- [ ] Test de génération de PDF
- [ ] Test d'envoi d'email
- [ ] Vérification des logs (`storage/logs/laravel.log`)
- [ ] Configuration du système de sauvegarde
- [ ] Configuration du monitoring
- [ ] Documentation des accès

---

## 🆘 Support et Ressources

### Documentation Laravel
- [Documentation officielle Laravel 8.x](https://laravel.com/docs/8.x)
- [Laravel Deployment](https://laravel.com/docs/8.x/deployment)

### Commandes Utiles

```bash
# Vérification de l'environnement
php artisan about
php artisan env

# Optimisation pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Nettoyage du cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Vérification de la santé de l'application
php artisan queue:work --once
php artisan schedule:list
```

### Logs Importants

```bash
# Logs Laravel
tail -f /var/www/sifec/storage/logs/laravel.log

# Logs Nginx
tail -f /var/log/nginx/sifec-error.log
tail -f /var/log/nginx/sifec-access.log

# Logs MySQL
tail -f /var/log/mysql/error.log

# Logs PHP-FPM
tail -f /var/log/php8.1-fpm.log
```

---

## 📊 Estimation des Coûts (Hébergement Cloud)

### Option 1 : VPS DigitalOcean / Linode / Vultr
- **Configuration Minimale** : 10-20 $/mois (2 vCPU, 4GB RAM)
- **Configuration Recommandée** : 40-80 $/mois (4 vCPU, 8GB RAM)
- **Configuration Optimale** : 160+ $/mois (8 vCPU, 16GB RAM)

### Option 2 : AWS / Azure / Google Cloud
- **Configuration Minimale** : 30-50 $/mois
- **Configuration Recommandée** : 100-150 $/mois
- **Configuration Optimale** : 300+ $/mois

### Coûts Additionnels
- **Nom de domaine** : 10-15 $/an
- **Certificat SSL** : Gratuit (Let's Encrypt) ou 50-200 $/an (certificat premium)
- **Backup** : 5-20 $/mois selon le volume
- **Monitoring** : 0-100 $/mois (gratuit avec solutions open source)

---

## 🔒 Sécurité

### Bonnes Pratiques

1. **Toujours utiliser HTTPS** (SSL/TLS)
2. **Mettre à jour régulièrement** le système et les dépendances
3. **Utiliser des mots de passe forts** (minimum 16 caractères)
4. **Désactiver le mode debug** en production (`APP_DEBUG=false`)
5. **Configurer le pare-feu** (UFW, iptables)
6. **Limiter les tentatives de connexion** (rate limiting)
7. **Effectuer des sauvegardes régulières** (quotidiennes recommandées)
8. **Surveiller les logs** régulièrement
9. **Utiliser Fail2ban** pour bloquer les attaques par force brute
10. **Configurer CSP** (Content Security Policy)

### Installation de Fail2ban

```bash
sudo apt install fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

---

## 📞 Contact et Support

Pour toute question ou assistance concernant le déploiement de SIFEC, veuillez contacter l'équipe technique.

---

**Document créé le** : Novembre 2025  
**Version du document** : 1.0  
**Dernière mise à jour** : 06/11/2025

