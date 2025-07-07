# E-Szerviz Program

Komplett online szerviz menedzsment rendszer elektromos kerékpárokhoz és rollerekhez.

## Főbb funkciók

- **Munkalap kezelés**: Munkalapok létrehozása, szerkesztése, nyomtatása, email küldése
- **Ügyfél nyilvántartás**: Ügyfelek adatainak kezelése, prioritások, cégadatok
- **Eszköz nyilvántartás**: Kerékpárok és rollerek adatai, gyári számok, tartozékok
- **Alkatrész/szolgáltatás katalógus**: Árazás, készletnyilvántartás
- **Kimutatások**: Bevételi riportok, szerelő teljesítmény, ügyfél statisztikák
- **Admin felület**: Felhasználó kezelés, jogosultságok, rendszer beállítások

## Rendszerkövetelmények

### Szerver oldal
- PHP 7.4 vagy újabb
- MySQL 8.0 vagy újabb (MariaDB 10.3+)
- Apache 2.4+ vagy Nginx
- mod_rewrite engedélyezve

### PHP Extensions
- PDO
- pdo_mysql
- mbstring
- json
- fileinfo
- gd vagy imagick
- zip

### Ajánlott konfiguráció
- Minimum 512MB RAM
- 1GB szabad tárhely
- SSL tanúsítvány (HTTPS)

## Telepítés

### 1. Fájlok feltöltése

```bash
# Git használatával
git clone https://github.com/yourusername/e-szerviz.git
cd e-szerviz

# Vagy FTP-vel töltse fel a fájlokat a szerverre
```

### 2. Composer telepítése

```bash
composer install --no-dev
```

### 3. Jogosultságok beállítása

```bash
# Linux/Unix rendszeren
chmod -R 755 .
chmod -R 777 storage/
find . -type f -exec chmod 644 {} \;
```

### 4. Konfiguráció

```bash
# Config fájl másolása
cp config/config.example.php config/config.php

# Szerkessze a config/config.php fájlt és állítsa be:
# - Adatbázis kapcsolat adatait
# - APP_URL címet
# - Email beállításokat
```

### 5. Adatbázis inicializálás

```bash
# Telepítő futtatása
php install.php
```

### 6. Webszerver beállítása

#### Apache (.htaccess már tartalmazza)
```apache
DocumentRoot /path/to/e-szerviz/public
```

#### Nginx
```nginx
server {
    listen 80;
    server_name szerviz.local;
    root /path/to/e-szerviz/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?url=$uri&$args;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## Első bejelentkezés

- **URL**: http://your-domain.com
- **Felhasználónév**: admin
- **Jelszó**: admin123

⚠️ **FONTOS**: Változtassa meg az admin jelszavát az első bejelentkezés után!

## Használati útmutató

### Új munkalap létrehozása

1. Kattintson az "Új munkalap" gombra
2. Válasszon vagy hozzon létre új ügyfelet
3. Adja meg az eszköz adatait
4. Írja le a hibát
5. Mentse a munkalapot

### Költségek hozzáadása

1. Nyissa meg a munkalapot
2. Kattintson a "Tétel hozzáadása" gombra
3. Válasszon alkatrészt vagy szolgáltatást
4. Adja meg a mennyiséget és árat
5. Mentse a tételt

### Státusz váltás

1. Nyissa meg a munkalapot
2. Kattintson a státusz melletti váltás gombra
3. Válassza ki az új státuszt
4. Adjon meg megjegyzést (opcionális)
5. Mentse a változást

## Biztonsági beállítások

### HTTPS beállítása

Ajánlott HTTPS használata. A .htaccess fájlban kapcsolja be:

```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### Backup stratégia

Napi automatikus mentés ajánlott:

```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d)
BACKUP_DIR="/path/to/backups"

# Adatbázis mentés
mysqldump -u root -p szerviz_db > $BACKUP_DIR/db_$DATE.sql

# Fájlok mentése
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /path/to/e-szerviz/storage/uploads/
```

## Hibaelhárítás

### Fehér oldal / 500-as hiba

1. Ellenőrizze a PHP error log-ot
2. Győződjön meg róla, hogy minden PHP extension telepítve van
3. Ellenőrizze a fájl jogosultságokat

### Adatbázis kapcsolat hiba

1. Ellenőrizze a config.php beállításait
2. Győződjön meg róla, hogy a MySQL szerver fut
3. Ellenőrizze a felhasználó jogosultságait

### Email küldés nem működik

1. Ellenőrizze az SMTP beállításokat
2. Gmail esetén engedélyezze a "Less secure apps" opciót
3. Ellenőrizze a tűzfal beállításokat

## Fejlesztés

### Projekt struktúra

```
e-szerviz/
├── app/                    # Alkalmazás kód
│   ├── Controllers/        # Kontrollerek
│   ├── Models/            # Modellek
│   ├── Views/             # Nézetek
│   ├── Core/              # Core osztályok
│   └── Helpers/           # Segéd osztályok
├── config/                # Konfigurációs fájlok
├── database/              # Adatbázis fájlok
│   ├── migrations/        # Migrációk
│   └── seeds/             # Alapadatok
├── public/                # Publikus fájlok
│   ├── assets/            # CSS, JS, képek
│   └── index.php          # Belépési pont
└── storage/               # Tárolt fájlok
    ├── uploads/           # Feltöltött fájlok
    ├── logs/              # Log fájlok
    └── cache/             # Cache fájlok
```

### Új funkció hozzáadása

1. Hozzon létre új Controller-t az `app/Controllers/` mappában
2. Hozzon létre új Model-t az `app/Models/` mappában
3. Adja hozzá az útvonalakat az `app/routes.php` fájlhoz
4. Hozza létre a nézeteket az `app/Views/` mappában

## Licensz

Ez egy zárt forráskódú, kereskedelmi szoftver. Minden jog fenntartva.

## Támogatás

- Email: support@szerviz.hu
- Telefon: +36 1 234 5678
- Dokumentáció: https://docs.szerviz.hu

## Verziótörténet

### v1.0.0 (2024-01-01)
- Kezdeti kiadás
- Alapfunkciók implementálása
- Munkalap kezelés
- Ügyfél és eszköz nyilvántartás
- Kimutatások modul

---

© 2024 E-Szerviz Program. Minden jog fenntartva.