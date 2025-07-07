<?php
// config/config.php

// Adatbázis beállítások
define('DB_HOST', 'localhost');
define('DB_NAME', 'xxxx');
define('DB_USER', 'xxxx');
define('DB_PASS', 'xxxx'); 
define('DB_CHARSET', 'utf8mb4');
define('DB_PREFIX', 'SZE_');

// Alkalmazás beállítások
define('APP_NAME', 'E-Szerviz Program');
define('APP_URL', 'https://mwshop.hu/data/data/szerviz/public');
define('APP_ROOT', '/home/timbowor/mwshop.hu/data/data/szerviz');
define('APP_VERSION', '1.0.0');

// Időzóna
define('TIMEZONE', 'Europe/Budapest');
date_default_timezone_set(TIMEZONE);

// Session beállítások
define('SESSION_NAME', 'sze_session');
define('SESSION_LIFETIME', 3600); // 1 óra

// Email beállítások
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'your-email@gmail.com');
define('MAIL_PASSWORD', 'your-password');
define('MAIL_FROM_EMAIL', 'noreply@szerviz.hu');
define('MAIL_FROM_NAME', 'E-Szerviz');

// Fájl feltöltés
define('UPLOAD_PATH', APP_ROOT . '/storage/uploads/');
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx']);

// Hibakezelés
define('DEBUG_MODE', true);
define('LOG_PATH', APP_ROOT . '/storage/logs/');

// Biztonsági beállítások
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_SALT_ROUNDS', 10);

// Oldal beállítások
define('ITEMS_PER_PAGE', 20);

// Munkalap beállítások
define('DEFAULT_WARRANTY_DAYS', 10);
define('WORKSHEET_NUMBER_FORMAT', 'YmdHi'); // ÉVHÓNAPNAPÓRAPERC