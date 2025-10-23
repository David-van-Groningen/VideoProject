# Video Platform Deployment README

## 1️⃣ Vereisten

* Webserver: Nginx of Apache
* PHP: ≥ 8.0 (met PDO MySQL extension)
* MySQL/MariaDB
* Git (optioneel)
* Composer (optioneel)

## 2️⃣ Projectstructuur

```
video_platform/
├─ index.php
├─ category.php
├─ add_video.php
├─ edit_video.php
├─ delete_video.php
├─ login.php
├─ register.php
├─ logout.php
├─ config.php
├─ assets/
│  ├─ style.css
│  └─ app.js
├─ db.sql
└─ README.md
```

## 3️⃣ Database Setup

1. Maak de database en tabellen aan met db.sql:

```bash
mysql -u root -p < db.sql
```

2. Pas config.php aan met jouw database credentials:

```php
$DB_HOST = 'localhost';
$DB_NAME = 'video_platform';
$DB_USER = 'jouw_db_user';
$DB_PASS = 'jouw_db_password';
```

3. Voeg admin-veld toe aan users-tabel als dit nog niet bestaat:

```sql
ALTER TABLE users ADD COLUMN is_admin TINYINT(1) DEFAULT 0;
```

4. Admin gebruiker aanmaken (optioneel):

```sql
INSERT INTO users (username, password_hash, display_name, is_admin)
VALUES ('admin', '<hash>', 'Administrator', 1);
```

> Voor `<hash>` kun je een PHP-script gebruiken:

```php
<?php
 echo password_hash('jouw_admin_password', PASSWORD_DEFAULT);
```

## 4️⃣ Webserver Setup

### Nginx (PHP-FPM)

```nginx
server {
    listen 80;
    server_name jouwdomein.com;
    root /var/www/video_platform;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### Apache

```apache
<VirtualHost *:80>
    ServerName jouwdomein.com
    DocumentRoot /var/www/video_platform

    <Directory /var/www/video_platform>
        AllowOverride All
        Require all granted
    </Directory>

    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php8.1-fpm.sock|fcgi://localhost/"
    </FilesMatch>
</VirtualHost>
```

## 5️⃣ Bestandenrechten

```bash
sudo chown -R www-data:www-data /var/www/video_platform
sudo find /var/www/video_platform -type d -exec chmod 755 {} \;
sudo find /var/www/video_platform -type f -exec chmod 644 {} \;
```

> `www-data` is meestal de webserver gebruiker.

## 6️⃣ Frontend / Assets

* CSS: assets/style.css
* JS: assets/app.js

> Zorg dat deze bestanden leesbaar zijn door de webserver.

## 7️⃣ Admin Functionaliteiten

1. Categorie aanmaken/verwijderen: via database of admin pagina.
2. Video verwijderen/edit: beschikbaar voor admin of auteur.
3. User management: handmatig via database voor nu.

## 8️⃣ Toegang tot de app

1. Ga naar `http://localhost/` of jouw domein
2. Registreren of inloggen
3. Admin ziet extra knoppen voor edit/delete
4. Klik op categorie om video’s te bekijken
5. Voeg video’s toe via modal

## 9️⃣ Tips voor Veiligheid
* Database gebruiker: geef alleen benodigde rechten
