<?php
// complete_fix.php - Teljes javítás
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(300);

require_once '/home/timbowor/mwshop.hu/data/data/szerviz/config/config.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>E-Szerviz - Teljes javítás</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .section { margin: 20px 0; padding: 10px; background: #f0f0f0; border-radius: 5px; }
        h2 { color: #333; }
        pre { background: #fff; padding: 10px; overflow: auto; }
    </style>
</head>
<body>
    <h1>E-Szerviz - Teljes rendszer javítás</h1>
    
    <div class="section">
        <h2>1. Hiányzó táblák létrehozása</h2>
        <?php
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Összes szükséges tábla
            $tables = [
                'roles' => "CREATE TABLE IF NOT EXISTS `SZE_roles` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(50) NOT NULL,
                    `display_name` varchar(100) NOT NULL,
                    `description` text,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `name` (`name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                
                'permissions' => "CREATE TABLE IF NOT EXISTS `SZE_permissions` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(100) NOT NULL,
                    `display_name` varchar(100) NOT NULL,
                    `category` varchar(50) NOT NULL,
                    `description` text,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `name` (`name`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                
                'role_permissions' => "CREATE TABLE IF NOT EXISTS `SZE_role_permissions` (
                    `role_id` int(11) NOT NULL,
                    `permission_id` int(11) NOT NULL,
                    PRIMARY KEY (`role_id`,`permission_id`),
                    KEY `permission_id` (`permission_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                
                'locations' => "CREATE TABLE IF NOT EXISTS `SZE_locations` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(100) NOT NULL,
                    `address` varchar(200),
                    `phone` varchar(20),
                    `is_default` tinyint(1) NOT NULL DEFAULT '0',
                    `is_active` tinyint(1) NOT NULL DEFAULT '1',
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                
                'priority_types' => "CREATE TABLE IF NOT EXISTS `SZE_priority_types` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(50) NOT NULL,
                    `color` varchar(7) NOT NULL DEFAULT '#6c757d',
                    `level` int(11) NOT NULL DEFAULT '0',
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                
                'device_conditions' => "CREATE TABLE IF NOT EXISTS `SZE_device_conditions` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(50) NOT NULL,
                    `description` text,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                
                'repair_types' => "CREATE TABLE IF NOT EXISTS `SZE_repair_types` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(50) NOT NULL,
                    `description` text,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                
                'status_types' => "CREATE TABLE IF NOT EXISTS `SZE_status_types` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(50) NOT NULL,
                    `color` varchar(7) NOT NULL DEFAULT '#6c757d',
                    `is_closed` tinyint(1) NOT NULL DEFAULT '0',
                    `sort_order` int(11) NOT NULL DEFAULT '0',
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                
                'worksheet_items' => "CREATE TABLE IF NOT EXISTS `SZE_worksheet_items` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `worksheet_id` int(11) NOT NULL,
                    `part_service_id` int(11) NOT NULL,
                    `quantity` decimal(10,2) NOT NULL,
                    `unit_price` decimal(10,2) NOT NULL,
                    `discount` decimal(5,2) NOT NULL DEFAULT '0.00',
                    `total_price` decimal(10,2) NOT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `worksheet_id` (`worksheet_id`),
                    KEY `part_service_id` (`part_service_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                
                'attachments' => "CREATE TABLE IF NOT EXISTS `SZE_attachments` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `worksheet_id` int(11) NOT NULL,
                    `filename` varchar(255) NOT NULL,
                    `original_name` varchar(255) NOT NULL,
                    `uploaded_by` int(11) NOT NULL,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `worksheet_id` (`worksheet_id`),
                    KEY `uploaded_by` (`uploaded_by`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
                
                'worksheet_history' => "CREATE TABLE IF NOT EXISTS `SZE_worksheet_history` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `worksheet_id` int(11) NOT NULL,
                    `user_id` int(11) NOT NULL,
                    `action` varchar(50) NOT NULL,
                    `old_status_id` int(11),
                    `new_status_id` int(11),
                    `note` text,
                    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    KEY `worksheet_id` (`worksheet_id`),
                    KEY `user_id` (`user_id`),
                    KEY `old_status_id` (`old_status_id`),
                    KEY `new_status_id` (`new_status_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            ];
            
            foreach ($tables as $name => $sql) {
                try {
                    $pdo->exec($sql);
                    echo "<span class='success'>✓ Tábla: SZE_$name</span><br>";
                } catch (PDOException $e) {
                    echo "<span class='error'>✗ Tábla: SZE_$name - " . $e->getMessage() . "</span><br>";
                }
            }
            
        } catch (PDOException $e) {
            echo "<span class='error'>Adatbázis kapcsolat hiba: " . $e->getMessage() . "</span>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>2. Alapadatok feltöltése</h2>
        <?php
        try {
            // Roles
            $pdo->exec("INSERT IGNORE INTO `SZE_roles` (`id`, `name`, `display_name`) VALUES
                (1, 'admin', 'Adminisztrátor'),
                (2, 'technician', 'Szerelő'),
                (3, 'viewer', 'Megtekintő')");
            echo "<span class='success'>✓ Szerepkörök</span><br>";
            
            // Permissions
            $pdo->exec("INSERT IGNORE INTO `SZE_permissions` (`name`, `display_name`, `category`) VALUES
                ('worksheet.view', 'Munkalapok megtekintése', 'worksheet'),
                ('worksheet.create', 'Munkalap létrehozása', 'worksheet'),
                ('worksheet.edit', 'Munkalap szerkesztése', 'worksheet'),
                ('worksheet.delete', 'Munkalap törlése', 'worksheet'),
                ('customer.view', 'Ügyfelek megtekintése', 'customer'),
                ('customer.create', 'Ügyfél létrehozása', 'customer'),
                ('customer.edit', 'Ügyfél szerkesztése', 'customer'),
                ('customer.delete', 'Ügyfél törlése', 'customer'),
                ('device.view', 'Eszközök megtekintése', 'device'),
                ('device.create', 'Eszköz létrehozása', 'device'),
                ('device.edit', 'Eszköz szerkesztése', 'device'),
                ('device.delete', 'Eszköz törlése', 'device'),
                ('part.view', 'Alkatrészek megtekintése', 'part'),
                ('part.create', 'Alkatrész létrehozása', 'part'),
                ('part.edit', 'Alkatrész szerkesztése', 'part'),
                ('part.delete', 'Alkatrész törlése', 'part'),
                ('report.view', 'Kimutatások megtekintése', 'report'),
                ('admin.access', 'Admin felület elérése', 'admin')");
            echo "<span class='success'>✓ Jogosultságok</span><br>";
            
            // Admin role permissions (összes jogosultság)
            $pdo->exec("INSERT IGNORE INTO `SZE_role_permissions` 
                SELECT 1, id FROM `SZE_permissions`");
            echo "<span class='success'>✓ Admin jogosultságok</span><br>";
            
            // Basic data
            $pdo->exec("INSERT IGNORE INTO `SZE_locations` (`id`, `name`, `is_default`) VALUES (1, 'Főszerviz', 1)");
            $pdo->exec("INSERT IGNORE INTO `SZE_priority_types` (`id`, `name`, `color`, `level`) VALUES
                (1, 'Normál', '#6c757d', 0),
                (2, 'Sürgős', '#ffc107', 1),
                (3, 'Nagyon sürgős', '#dc3545', 2)");
            $pdo->exec("INSERT IGNORE INTO `SZE_status_types` (`name`, `color`, `is_closed`, `sort_order`) VALUES
                ('Felvéve', '#17a2b8', 0, 1),
                ('Bevizsgálás alatt', '#ffc107', 0, 2),
                ('Javítás alatt', '#fd7e14', 0, 3),
                ('Kész', '#28a745', 1, 4),
                ('Átadva', '#6c757d', 1, 5)");
            $pdo->exec("INSERT IGNORE INTO `SZE_repair_types` (`name`) VALUES
                ('Garanciális'), ('Fizetős'), ('Szerviz'), ('Átvizsgálás')");
            $pdo->exec("INSERT IGNORE INTO `SZE_device_conditions` (`name`) VALUES
                ('Új'), ('Jó'), ('Használt'), ('Sérült'), ('Hibás')");
            echo "<span class='success'>✓ Törzsadatok</span><br>";
            
        } catch (PDOException $e) {
            echo "<span class='error'>Hiba: " . $e->getMessage() . "</span><br>";
        }
        ?>
    </div>
    
    <div class="section">
        <h2>3. Admin View fájlok létrehozása</h2>
        <?php
        $root = '/home/timbowor/mwshop.hu/data/data/szerviz';
        
        $adminViews = [
            'admin/users.php' => '<?php
$this->setData([\'title\' => \'Felhasználók\']);
?>
<div class="row mb-4">
    <div class="col-md-6">
        <h1 class="h3"><i class="fas fa-users"></i> Felhasználók</h1>
    </div>
    <div class="col-md-6 text-end">
        <a href="<?= $this->url(\'admin/users/create\') ?>" class="btn btn-success">
            <i class="fas fa-plus"></i> Új felhasználó
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Felhasználónév</th>
                        <th>Név</th>
                        <th>Email</th>
                        <th>Szerepkör</th>
                        <th>Aktív</th>
                        <th>Munkalapok</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagination[\'items\'] as $user): ?>
                    <tr>
                        <td><?= $this->escape($user[\'username\']) ?></td>
                        <td><?= $this->escape($user[\'full_name\']) ?></td>
                        <td><?= $this->escape($user[\'email\']) ?></td>
                        <td><?= $this->escape($user[\'role\'][\'display_name\'] ?? \'-\') ?></td>
                        <td>
                            <?php if ($user[\'is_active\']): ?>
                                <span class="badge bg-success">Aktív</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inaktív</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $user[\'worksheet_count\'] ?></td>
                        <td>
                            <a href="<?= $this->url(\'admin/users/\' . $user[\'id\'] . \'/edit\') ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>',

            'admin/user_form.php' => '<?php
$this->setData([\'title\' => $user ? \'Felhasználó szerkesztése\' : \'Új felhasználó\']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">
            <i class="fas fa-user-<?= $user ? \'edit\' : \'plus\' ?>"></i> 
            <?= $user ? \'Felhasználó szerkesztése\' : \'Új felhasználó\' ?>
        </h1>
    </div>
</div>

<form method="POST" action="<?= $user ? $this->url(\'admin/users/\' . $user[\'id\'] . \'/update\') : $this->url(\'admin/users/store\') ?>">
    <?= $this->csrfField() ?>
    
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Felhasználónév *</label>
                    <input type="text" name="username" class="form-control" value="<?= $this->escape($user[\'username\'] ?? \'\') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Teljes név *</label>
                    <input type="text" name="full_name" class="form-control" value="<?= $this->escape($user[\'full_name\'] ?? \'\') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-control" value="<?= $this->escape($user[\'email\'] ?? \'\') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telefon</label>
                    <input type="text" name="phone" class="form-control" value="<?= $this->escape($user[\'phone\'] ?? \'\') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jelszó <?= !$user ? \'*\' : \'(üresen hagyva nem változik)\' ?></label>
                    <input type="password" name="password" class="form-control" <?= !$user ? \'required\' : \'\' ?>>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jelszó megerősítés <?= !$user ? \'*\' : \'\' ?></label>
                    <input type="password" name="password_confirmation" class="form-control" <?= !$user ? \'required\' : \'\' ?>>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Szerepkör *</label>
                    <select name="role_id" class="form-select" required>
                        <?php foreach ($roles as $role): ?>
                        <option value="<?= $role[\'id\'] ?>" <?= ($user[\'role_id\'] ?? \'\') == $role[\'id\'] ? \'selected\' : \'\' ?>>
                            <?= $this->escape($role[\'display_name\']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telephely</label>
                    <select name="location_id" class="form-select">
                        <option value="">- Nincs -</option>
                        <?php foreach ($locations as $location): ?>
                        <option value="<?= $location[\'id\'] ?>" <?= ($user[\'location_id\'] ?? \'\') == $location[\'id\'] ? \'selected\' : \'\' ?>>
                            <?= $this->escape($location[\'name\']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" 
                               <?= ($user[\'is_active\'] ?? 1) ? \'checked\' : \'\' ?>>
                        <label class="form-check-label" for="is_active">
                            Aktív felhasználó
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Mentés
            </button>
            <a href="<?= $this->url(\'admin/users\') ?>" class="btn btn-secondary">Mégsem</a>
        </div>
    </div>
</form>',

            'admin/settings.php' => '<?php
$this->setData([\'title\' => \'Beállítások\']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-cog"></i> Beállítások</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Telephelyek</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Név</th>
                                <th>Alapértelmezett</th>
                                <th>Aktív</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($settings[\'locations\'] as $location): ?>
                            <tr>
                                <td><?= $this->escape($location[\'name\']) ?></td>
                                <td><?= $location[\'is_default\'] ? \'<i class="fas fa-check text-success"></i>\' : \'\' ?></td>
                                <td><?= $location[\'is_active\'] ? \'<i class="fas fa-check text-success"></i>\' : \'<i class="fas fa-times text-danger"></i>\' ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Státuszok</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Név</th>
                                <th>Szín</th>
                                <th>Lezárt</th>
                                <th>Sorrend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($settings[\'status_types\'] as $status): ?>
                            <tr>
                                <td><?= $this->escape($status[\'name\']) ?></td>
                                <td><span class="badge" style="background-color: <?= $status[\'color\'] ?>"><?= $status[\'color\'] ?></span></td>
                                <td><?= $status[\'is_closed\'] ? \'<i class="fas fa-check text-success"></i>\' : \'\' ?></td>
                                <td><?= $status[\'sort_order\'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Prioritások</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Név</th>
                                <th>Szín</th>
                                <th>Szint</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($settings[\'priority_types\'] as $priority): ?>
                            <tr>
                                <td><?= $this->escape($priority[\'name\']) ?></td>
                                <td><span class="badge" style="background-color: <?= $priority[\'color\'] ?>"><?= $priority[\'color\'] ?></span></td>
                                <td><?= $priority[\'level\'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Javítás típusok</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <?php foreach ($settings[\'repair_types\'] as $type): ?>
                    <li><?= $this->escape($type[\'name\']) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>',

            'admin/permissions.php' => '<?php
$this->setData([\'title\' => \'Jogosultságok\']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-key"></i> Jogosultságok kezelése</h1>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= $this->url(\'admin/permissions/update\') ?>">
            <?= $this->csrfField() ?>
            
            <div class="mb-4">
                <label class="form-label">Szerepkör kiválasztása:</label>
                <select name="role_id" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($roles as $role): ?>
                    <option value="<?= $role[\'id\'] ?>" <?= ($_POST[\'role_id\'] ?? 1) == $role[\'id\'] ? \'selected\' : \'\' ?>>
                        <?= $this->escape($role[\'display_name\']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <?php $selectedRole = $_POST[\'role_id\'] ?? 1; ?>
            
            <h5>Jogosultságok:</h5>
            
            <?php foreach ($permissionsByCategory as $category => $perms): ?>
            <div class="mb-3">
                <h6 class="text-muted"><?= ucfirst($category) ?></h6>
                <?php foreach ($perms as $permission): ?>
                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="permissions[]" 
                           value="<?= $permission[\'id\'] ?>"
                           id="perm_<?= $permission[\'id\'] ?>"
                           <?= in_array($permission[\'id\'], $rolePermissions[$selectedRole] ?? []) ? \'checked\' : \'\' ?>>
                    <label class="form-check-label" for="perm_<?= $permission[\'id\'] ?>">
                        <?= $this->escape($permission[\'display_name\']) ?>
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Mentés
            </button>
        </form>
    </div>
</div>'
        ];
        
        foreach ($adminViews as $path => $content) {
            $fullPath = $root . '/app/Views/' . $path;
            $dir = dirname($fullPath);
            
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            
            if (file_put_contents($fullPath, $content)) {
                echo "<span class='success'>✓ Létrehozva: Views/$path</span><br>";
            } else {
                echo "<span class='error'>✗ Hiba: Views/$path</span><br>";
            }
        }
        ?>
    </div>
    
    <div class="section">
        <h2>4. Hiányzó további View fájlok</h2>
        <?php
        $additionalViews = [
            'worksheets/edit.php' => '<?php
$this->setData([\'title\' => \'Munkalap szerkesztése\']);
$this->addJs(\'js/worksheet-edit.js\');
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3">
            <i class="fas fa-edit"></i> Munkalap szerkesztése: <?= $this->escape($worksheet[\'worksheet_number\']) ?>
        </h1>
    </div>
</div>

<form method="POST" action="<?= $this->url(\'worksheets/\' . $worksheet[\'id\'] . \'/update\') ?>">
    <?= $this->csrfField() ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Alapadatok</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telephely</label>
                            <select name="location_id" class="form-select">
                                <?php foreach ($locations as $location): ?>
                                <option value="<?= $location[\'id\'] ?>" <?= $worksheet[\'location_id\'] == $location[\'id\'] ? \'selected\' : \'\' ?>>
                                    <?= $this->escape($location[\'name\']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Szerelő</label>
                            <select name="technician_id" class="form-select">
                                <?php foreach ($technicians as $tech): ?>
                                <option value="<?= $tech[\'id\'] ?>" <?= $worksheet[\'technician_id\'] == $tech[\'id\'] ? \'selected\' : \'\' ?>>
                                    <?= $this->escape($tech[\'full_name\']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Javítás típusa</label>
                            <select name="repair_type_id" class="form-select">
                                <?php foreach ($repairTypes as $type): ?>
                                <option value="<?= $type[\'id\'] ?>" <?= $worksheet[\'repair_type_id\'] == $type[\'id\'] ? \'selected\' : \'\' ?>>
                                    <?= $this->escape($type[\'name\']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Státusz</label>
                            <select name="status_id" class="form-select">
                                <?php foreach ($statusTypes as $status): ?>
                                <option value="<?= $status[\'id\'] ?>" <?= $worksheet[\'status_id\'] == $status[\'id\'] ? \'selected\' : \'\' ?>>
                                    <?= $this->escape($status[\'name\']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vállalási határidő</label>
                            <input type="date" name="warranty_date" class="form-control" value="<?= $worksheet[\'warranty_date\'] ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_paid" id="is_paid" value="1" <?= $worksheet[\'is_paid\'] ? \'checked\' : \'\' ?>>
                                <label class="form-check-label" for="is_paid">
                                    Kifizetve
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Hibaleírás</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <textarea name="description" class="form-control" rows="4" required><?= $this->escape($worksheet[\'description\']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Belső megjegyzés</label>
                        <textarea name="internal_note" class="form-control" rows="2"><?= $this->escape($worksheet[\'internal_note\'] ?? \'\') ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Ügyfél</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong><?= $this->escape($worksheet[\'customer_name\']) ?></strong></p>
                    <p class="mb-1"><?= $this->escape($worksheet[\'customer_phone\']) ?></p>
                    <?php if ($worksheet[\'customer_email\']): ?>
                    <p class="mb-0"><?= $this->escape($worksheet[\'customer_email\']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($worksheet[\'device_name\']): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Eszköz</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong><?= $this->escape($worksheet[\'device_name\']) ?></strong></p>
                    <?php if ($worksheet[\'serial_number\']): ?>
                    <p class="mb-0">Gyári szám: <?= $this->escape($worksheet[\'serial_number\']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mentés
                    </button>
                    <a href="<?= $this->url(\'worksheets/\' . $worksheet[\'id\']) ?>" class="btn btn-secondary">
                        Mégsem
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>',

            'reports/device.php' => '<?php
$this->setData([\'title\' => \'Eszköz statisztikák\']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-bicycle"></i> Eszköz statisztikák</h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Összes eszköz</h5>
                <h2><?= $deviceStats[\'total_devices\'] ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>Javítás alatt</h5>
                <h2><?= $deviceStats[\'under_repair\'] ?? 0 ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Ügyfelek száma</h5>
                <h2><?= $deviceStats[\'unique_customers\'] ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Állapot szerinti megoszlás</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Állapot</th>
                        <th>Darabszám</th>
                        <th>Százalék</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = array_sum(array_column($byCondition, \'count\'));
                    foreach ($byCondition as $condition): 
                        $percent = $total > 0 ? round(($condition[\'count\'] / $total) * 100, 1) : 0;
                    ?>
                    <tr>
                        <td><?= $this->escape($condition[\'name\']) ?></td>
                        <td><?= $condition[\'count\'] ?></td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" style="width: <?= $percent ?>%"><?= $percent ?>%</div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>',

            'reports/technician.php' => '<?php
$this->setData([\'title\' => \'Szerelő teljesítmény\']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-user-cog"></i> Szerelő teljesítmény</h1>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row">
            <div class="col-md-3">
                <label>Kezdő dátum</label>
                <input type="date" name="date_from" class="form-control" value="<?= $dateFrom ?>">
            </div>
            <div class="col-md-3">
                <label>Záró dátum</label>
                <input type="date" name="date_to" class="form-control" value="<?= $dateTo ?>">
            </div>
            <div class="col-md-3">
                <label>Szerelő</label>
                <select name="technician_id" class="form-select">
                    <option value="">Összes</option>
                    <?php foreach ($technicians as $tech): ?>
                    <option value="<?= $tech[\'id\'] ?>" <?= $technicianId == $tech[\'id\'] ? \'selected\' : \'\' ?>>
                        <?= $this->escape($tech[\'full_name\']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">Szűrés</button>
            </div>
        </form>
    </div>
</div>

<?php if (!$technicianId && !empty($performanceData)): ?>
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Összehasonlítás</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Szerelő</th>
                        <th>Munkalapok</th>
                        <th>Befejezett</th>
                        <th>Bevétel</th>
                        <th>Átlag bevétel</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($performanceData as $data): ?>
                    <tr>
                        <td><?= $this->escape($data[\'technician\'][\'full_name\']) ?></td>
                        <td><?= $data[\'total_worksheets\'] ?></td>
                        <td><?= $data[\'completed_worksheets\'] ?? 0 ?></td>
                        <td><?= $this->formatPrice($data[\'total_revenue\'] ?? 0) ?></td>
                        <td><?= $this->formatPrice($data[\'avg_revenue\'] ?? 0) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>',

            'reports/customer.php' => '<?php
$this->setData([\'title\' => \'Ügyfél elemzés\']);
?>
<div class="row mb-4">
    <div class="col">
        <h1 class="h3"><i class="fas fa-users"></i> Ügyfél elemzés</h1>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row">
            <div class="col-md-4">
                <label>Kezdő dátum</label>
                <input type="date" name="date_from" class="form-control" value="<?= $dateFrom ?>">
            </div>
            <div class="col-md-4">
                <label>Záró dátum</label>
                <input type="date" name="date_to" class="form-control" value="<?= $dateTo ?>">
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">Szűrés</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Top ügyfelek bevétel szerint</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Ügyfél</th>
                                <th>Munkalapok</th>
                                <th>Bevétel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($topByRevenue, 0, 10) as $customer): ?>
                            <tr>
                                <td><?= $this->escape($customer[\'name\']) ?></td>
                                <td><?= $customer[\'worksheet_count\'] ?></td>
                                <td><?= $this->formatPrice($customer[\'total_revenue\'] ?? 0) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Prioritás szerinti megoszlás</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Prioritás</th>
                                <th>Ügyfelek száma</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($byPriority as $priority): ?>
                            <tr>
                                <td>
                                    <span class="badge" style="background-color: <?= $priority[\'color\'] ?>">
                                        <?= $this->escape($priority[\'name\']) ?>
                                    </span>
                                </td>
                                <td><?= $priority[\'count\'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>'
        ];
        
        foreach ($additionalViews as $path => $content) {
            $fullPath = $root . '/app/Views/' . $path;
            if (!file_exists($fullPath)) {
                if (file_put_contents($fullPath, $content)) {
                    echo "<span class='success'>✓ Létrehozva: Views/$path</span><br>";
                } else {
                    echo "<span class='error'>✗ Hiba: Views/$path</span><br>";
                }
            } else {
                echo "<span class=''>⚠️ Már létezik: Views/$path</span><br>";
            }
        }
        ?>
    </div>
    
    <div class="section">
        <h2>✅ Kész!</h2>
        <p>Minden hiányzó tábla és fájl létrehozva. A rendszernek most már teljesen működnie kell!</p>
        <p>
            <a href="<?= APP_URL ?>" class="btn btn-success">Vissza az alkalmazáshoz</a>
        </p>
        <p class="text-danger mt-3">
            <strong>FONTOS:</strong> Töröld ezt a fájlt a biztonság kedvéért!
        </p>
    </div>
</body>
</html>