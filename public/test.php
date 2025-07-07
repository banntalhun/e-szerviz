<?php
// list_structure.php - Teljes fájlstruktúra listázó
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root = '/home/timbowor/mwshop.hu/data/data/szerviz';

function listDirectory($dir, $prefix = '') {
    $files = [];
    
    if (is_dir($dir)) {
        $items = scandir($dir);
        
        foreach ($items as $item) {
            if ($item == '.' || $item == '..') continue;
            
            $path = $dir . '/' . $item;
            $relativePath = str_replace($GLOBALS['root'] . '/', '', $path);
            
            if (is_dir($path)) {
                $files[] = $prefix . '📁 ' . $item . '/';
                // Rekurzív hívás almappákra
                $subFiles = listDirectory($path, $prefix . '  ');
                $files = array_merge($files, $subFiles);
            } else {
                $size = filesize($path);
                $files[] = $prefix . '📄 ' . $item . ' (' . formatBytes($size) . ')';
            }
        }
    }
    
    return $files;
}

function formatBytes($size, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB'];
    
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    
    return round($size, $precision) . ' ' . $units[$i];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>E-Szerviz Fájlstruktúra</title>
    <style>
        body { 
            font-family: 'Courier New', monospace; 
            background: #1e1e1e; 
            color: #d4d4d4;
            padding: 20px;
            line-height: 1.4;
        }
        pre { 
            background: #2d2d2d; 
            padding: 20px; 
            border-radius: 5px;
            overflow-x: auto;
            border: 1px solid #3e3e3e;
        }
        h1 { color: #569cd6; }
        h2 { color: #4ec9b0; margin-top: 30px; }
        .warning { color: #f48771; }
        .success { color: #6a9955; }
        .info { color: #9cdcfe; }
        .folder { color: #dcdc8b; }
        .file { color: #d4d4d4; }
        .php { color: #9cdcfe; }
        .missing { color: #f48771; background: #5a1e1e; padding: 2px 5px; }
        .exists { color: #6a9955; }
    </style>
</head>
<body>
    <h1>🔍 E-Szerviz Fájlstruktúra Ellenőrzés</h1>
    
    <h2>📊 Összesítés</h2>
    <?php
    $dirs = ['app', 'config', 'database', 'public', 'storage'];
    $totalFiles = 0;
    $totalSize = 0;
    
    echo "<pre>";
    echo "Projekt gyökér: <span class='info'>$root</span>\n\n";
    
    foreach ($dirs as $dir) {
        $path = $root . '/' . $dir;
        if (is_dir($path)) {
            $count = count(glob($path . '/*'));
            echo "✅ <span class='folder'>$dir/</span> - $count elem\n";
        } else {
            echo "❌ <span class='missing'>$dir/ - HIÁNYZIK</span>\n";
        }
    }
    echo "</pre>";
    ?>
    
    <h2>📁 Teljes Struktúra</h2>
    <pre><?php
    echo "<span class='info'>$root/</span>\n";
    $allFiles = listDirectory($root);
    foreach ($allFiles as $file) {
        // Színezés fájltípus alapján
        if (strpos($file, '.php') !== false) {
            $file = str_replace('.php', '<span class="php">.php</span>', $file);
        }
        if (strpos($file, '📁') !== false) {
            $file = str_replace('📁', '<span class="folder">📁</span>', $file);
        }
        echo $file . "\n";
    }
    ?></pre>
    
    <h2>🔍 Kritikus Fájlok Ellenőrzése</h2>
    <pre><?php
    $criticalFiles = [
        'config/config.php' => 'Konfiguráció',
        'public/index.php' => 'Belépési pont',
        'public/.htaccess' => 'Apache konfig',
        'app/routes.php' => 'Útvonalak',
        'app/Core/Database.php' => 'Adatbázis osztály',
        'app/Core/Router.php' => 'Router osztály',
        'app/Core/Controller.php' => 'Controller alap',
        'app/Core/Model.php' => 'Model alap',
        'app/Core/View.php' => 'View osztály',
        'app/Core/Auth.php' => 'Auth osztály',
        'app/Controllers/AuthController.php' => 'Auth controller',
        'app/Controllers/DashboardController.php' => 'Dashboard controller',
        'app/Models/User.php' => 'User model',
        'app/Views/layouts/main.php' => 'Fő layout',
        'app/Views/auth/login.php' => 'Login view',
        'app/Views/dashboard/index.php' => 'Dashboard view'
    ];
    
    foreach ($criticalFiles as $file => $desc) {
        $path = $root . '/' . $file;
        if (file_exists($path)) {
            $size = formatBytes(filesize($path));
            echo "✅ <span class='exists'>$file</span> - $desc ($size)\n";
        } else {
            echo "❌ <span class='missing'>$file - HIÁNYZIK</span> - $desc\n";
        }
    }
    ?></pre>
    
    <h2>🎯 Specifikus Mappák Tartalma</h2>
    
    <h3>Controllers:</h3>
    <pre><?php
    $controllerDir = $root . '/app/Controllers';
    if (is_dir($controllerDir)) {
        $files = scandir($controllerDir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && substr($file, -4) == '.php') {
                echo "  📄 $file\n";
            }
        }
    } else {
        echo "<span class='missing'>Controllers mappa nem található!</span>";
    }
    ?></pre>
    
    <h3>Models:</h3>
    <pre><?php
    $modelDir = $root . '/app/Models';
    if (is_dir($modelDir)) {
        $files = scandir($modelDir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && substr($file, -4) == '.php') {
                echo "  📄 $file\n";
            }
        }
    } else {
        echo "<span class='missing'>Models mappa nem található!</span>";
    }
    ?></pre>
    
    <h3>Views struktúra:</h3>
    <pre><?php
    $viewDir = $root . '/app/Views';
    if (is_dir($viewDir)) {
        $subdirs = scandir($viewDir);
        foreach ($subdirs as $subdir) {
            if ($subdir != '.' && $subdir != '..' && is_dir($viewDir . '/' . $subdir)) {
                echo "  📁 <span class='folder'>$subdir/</span>\n";
                $files = scandir($viewDir . '/' . $subdir);
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && substr($file, -4) == '.php') {
                        echo "    📄 $file\n";
                    }
                }
            }
        }
    } else {
        echo "<span class='missing'>Views mappa nem található!</span>";
    }
    ?></pre>
    
    <h2>📈 Statisztikák</h2>
    <pre><?php
    // PHP fájlok száma
    $phpFiles = shell_exec("find $root -name '*.php' -type f | wc -l");
    echo "PHP fájlok száma: <span class='info'>" . trim($phpFiles) . "</span>\n";
    
    // Összes méret
    $totalSize = shell_exec("du -sh $root | awk '{print $1}'");
    echo "Projekt teljes mérete: <span class='info'>" . trim($totalSize) . "</span>\n";
    
    // Mappák száma
    $dirCount = shell_exec("find $root -type d | wc -l");
    echo "Mappák száma: <span class='info'>" . trim($dirCount) . "</span>\n";
    ?></pre>
    
    <h2>🔧 Jogosultságok</h2>
    <pre><?php
    $checkDirs = [
        'storage' => 'Tároló',
        'storage/uploads' => 'Feltöltések',
        'storage/logs' => 'Logok',
        'storage/cache' => 'Cache'
    ];
    
    foreach ($checkDirs as $dir => $desc) {
        $path = $root . '/' . $dir;
        if (is_dir($path)) {
            $perms = substr(sprintf('%o', fileperms($path)), -4);
            $writable = is_writable($path);
            echo "$desc ($dir): $perms ";
            echo $writable ? "<span class='success'>✅ Írható</span>\n" : "<span class='warning'>⚠️ Nem írható</span>\n";
        } else {
            echo "$desc ($dir): <span class='missing'>HIÁNYZIK</span>\n";
        }
    }
    ?></pre>
</body>
</html>