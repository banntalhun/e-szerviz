<?php
// public/index.php

// Hibakezelés beállítása
error_reporting(E_ALL);
ini_set('display_errors', 0);


// Alkalmazás gyökér könyvtár
define('ROOT', '/home/timbowor/mwshop.hu/data/data/szerviz');

// Vendor autoload (ha van)
if (file_exists('/home/timbowor/mwshop.hu/data/vendor/autoload.php')) {
    require '/home/timbowor/mwshop.hu/data/vendor/autoload.php';
}

// Konfiguráció betöltése
require_once ROOT . '/config/config.php';

// Autoloader
spl_autoload_register(function ($class) {
    $file = ROOT . '/app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Session indítása
session_name(SESSION_NAME);
session_start();

// Core osztályok betöltése
use Core\Router;
use Core\Database;
use Core\Auth;

try {
    // Adatbázis kapcsolat inicializálása
    Database::getInstance();
    
    // Router inicializálása
    $router = new Router();
    
    // Útvonalak definiálása
    require_once ROOT . '/app/routes.php';
    
    // URL feldolgozása
    $url = isset($_GET['url']) ? $_GET['url'] : '';
    $router->dispatch($url);
    
} catch (Exception $e) {
    // Hiba kezelése
    if (DEBUG_MODE) {
        echo '<pre>';
        echo 'Hiba: ' . $e->getMessage() . "\n";
        echo 'Fájl: ' . $e->getFile() . "\n";
        echo 'Sor: ' . $e->getLine() . "\n";
        echo 'Stack trace: ' . "\n";
        echo $e->getTraceAsString();
        echo '</pre>';
    } else {
        // Production módban
        error_log($e->getMessage());
        header('Location: ' . APP_URL . '/error/500');
        exit;
    }
}