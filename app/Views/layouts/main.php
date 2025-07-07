<?php
// app/Views/layouts/main.php - Windows Desktop Style with All Features
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->escape($title ?? APP_NAME) ?></title>
    
    <!-- jQuery first (required for other libraries) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <!-- Bootstrap CSS (we'll override with our Windows style) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- DatePicker CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Windows Desktop Style CSS (this overrides everything) -->
    <style>
        /* Windows Desktop Application Style */
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
            font-size: 11px !important;
            background-color: #ECE9D8 !important;
            color: #000000 !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: hidden;
        }
        
        /* Override Bootstrap */
        .container-fluid {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        /* Windows Style Navigation Bar */
        .win-navbar {
            background-color: #FFFFFF;
            border-bottom: 1px solid #A0A0A0;
            height: 60px;
            display: flex;
            flex-direction: column;
            box-shadow: inset 0 -1px 0 #FFFFFF;
        }
        
        .win-titlebar {
            background: linear-gradient(to bottom, #0054E3 0%, #0050DB 50%, #003CCB 100%);
            color: white;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .win-menubar {
            background-color: #F0F0F0;
            border-bottom: 1px solid #D4D0C8;
            display: flex;
            padding: 2px 4px;
            height: 40px;
            align-items: stretch;
        }
        
        .win-menu-item {
            padding: 4px 15px;
            text-decoration: none;
            color: #000000;
            font-size: 11px;
            border: 1px solid transparent;
            margin: 0 1px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 80px;
            justify-content: center;
            position: relative;
        }
        
        .win-menu-item:hover {
            background-color: #C1D2EE;
            border: 1px solid #7D9FDB;
            color: #000000;
            text-decoration: none;
        }
        
        .win-menu-item.active {
            background-color: #E5F1FB;
            border: 1px solid #7DA2CE;
        }
        
        .win-menu-item i {
            font-size: 16px;
        }
        
        .win-menu-text {
            font-size: 11px;
            white-space: nowrap;
            text-align: center;
        }
        
        /* Special width for two-line menu items */
        .win-menu-item:has(br) {
            min-width: 100px;
        }
        
        /* User info */
        .win-user-info {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-right: 10px;
            font-size: 11px;
            color: #333;
        }
        
        /* Main Content Area */
        .win-content {
            background-color: #ECE9D8;
            min-height: calc(100vh - 60px);
            padding: 8px;
        }
        
        /* Windows Style Alerts */
        .alert {
            border-radius: 0 !important;
            border: 1px solid #A0A0A0 !important;
            font-size: 11px !important;
            padding: 8px 12px !important;
            margin: 8px !important;
            box-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        
        .alert-success {
            background-color: #DFF0D8 !important;
            border-color: #D6E9C6 !important;
            color: #3C763D !important;
        }
        
        .alert-danger {
            background-color: #F2DEDE !important;
            border-color: #EBCCD1 !important;
            color: #A94442 !important;
        }
        
        .btn-close {
            font-size: 10px !important;
            opacity: 0.5;
        }
        
        /* Windows Style Footer */
        .win-footer {
            background-color: #F0F0F0;
            border-top: 1px solid #D4D0C8;
            padding: 4px 10px;
            font-size: 11px;
            color: #666;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        
        /* Fix DataTables and other components */
        .dataTables_wrapper {
            font-size: 11px !important;
        }
        
        .page-link {
            font-size: 11px !important;
        }
        
        select.form-select {
            font-size: 11px !important;
        }
    </style>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $this->asset('css/style.css') ?>">
    
    <?php if (isset($css)): ?>
        <?php foreach ($css as $cssFile): ?>
            <link rel="stylesheet" href="<?= $this->asset($cssFile) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Windows Style Navigation -->
    <nav class="win-navbar">
        <div class="win-titlebar">
            <i class="fas fa-tools"></i>
            <?= APP_NAME ?> - [<?= $this->escape($title ?? 'Főoldal') ?>]
        </div>
        <div class="win-menubar">
            <?php if (\Core\Auth::hasPermission('worksheet.view')): ?>
            <a class="win-menu-item <?= strpos($_SERVER['REQUEST_URI'], 'worksheets') !== false ? 'active' : '' ?>" 
               href="<?= $this->url('worksheets') ?>">
                <i class="fas fa-file-alt"></i>
                <span class="win-menu-text">Munkalapok</span>
            </a>
            <?php endif; ?>
            
            <?php if (\Core\Auth::hasPermission('customer.view')): ?>
            <a class="win-menu-item <?= strpos($_SERVER['REQUEST_URI'], 'customers') !== false ? 'active' : '' ?>" 
               href="<?= $this->url('customers') ?>">
                <i class="fas fa-users"></i>
                <span class="win-menu-text">Ügyfelek</span>
            </a>
            <?php endif; ?>
            
            <?php if (\Core\Auth::hasPermission('part.view')): ?>
            <a class="win-menu-item <?= strpos($_SERVER['REQUEST_URI'], 'parts') !== false ? 'active' : '' ?>" 
               href="<?= $this->url('parts') ?>">
                <i class="fas fa-cogs"></i>
                <span class="win-menu-text">Költségek /<br>Alkatrészek</span>
            </a>
            <?php endif; ?>
            
            <?php if (\Core\Auth::hasPermission('device.view')): ?>
            <a class="win-menu-item <?= strpos($_SERVER['REQUEST_URI'], 'devices') !== false ? 'active' : '' ?>" 
               href="<?= $this->url('devices') ?>">
                <i class="fas fa-bicycle"></i>
                <span class="win-menu-text">Eszközök</span>
            </a>
            <?php endif; ?>
            
            <?php if (\Core\Auth::hasPermission('report.view')): ?>
            <a class="win-menu-item <?= strpos($_SERVER['REQUEST_URI'], 'reports') !== false ? 'active' : '' ?>" 
               href="<?= $this->url('reports') ?>">
                <i class="fas fa-chart-bar"></i>
                <span class="win-menu-text">Kimutatások</span>
            </a>
            <?php endif; ?>
            
            <?php if (\Core\Auth::isAdmin()): ?>
            <a class="win-menu-item <?= strpos($_SERVER['REQUEST_URI'], 'admin') !== false ? 'active' : '' ?>" 
               href="<?= $this->url('admin') ?>">
                <i class="fas fa-cog"></i>
                <span class="win-menu-text">Törzsadatok /<br>Beállítások</span>
            </a>
            <?php endif; ?>
            
            <div class="win-user-info">
                <?php if (\Core\Auth::check()): ?>
                    <span>
                        <i class="fas fa-user-circle"></i> <?= $this->escape(\Core\Auth::username() ?? 'Felhasználó') ?>
                    </span>
                    <a href="<?= $this->url('logout') ?>" style="color: #333; text-decoration: none;">
                        <i class="fas fa-sign-out-alt"></i> Kijelentkezés
                    </a>
                <?php else: ?>
                    <a href="<?= $this->url('login') ?>" style="color: #333; text-decoration: none;">
                        <i class="fas fa-sign-in-alt"></i> Bejelentkezés
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="win-content">
        <div class="container-fluid">
            <!-- Flash Messages -->
            <?= $this->success() ?>
            <?= $this->errorMessage() ?>
            
            <!-- Page Content -->
            <?= $content ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="win-footer">
        <span>&copy; <?= date('Y') ?> <?= APP_NAME ?> - Verzió: <?= APP_VERSION ?></span>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/hu.js"></script>
    
    <!-- DatePicker JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/hu.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom JS -->
    <script src="<?= $this->asset('js/app.js') ?>"></script>
    
    <script>
        // DataTables alapértelmezett beállítások
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/hu.json'
            },
            pageLength: 25,
            responsive: true,
            order: [[0, 'desc']]
        });
        
        // Select2 alapértelmezett beállítások
        $.fn.select2.defaults.set('theme', 'bootstrap-5');
        $.fn.select2.defaults.set('language', 'hu');
        
        // Flatpickr alapértelmezett beállítások
        flatpickr.localize(flatpickr.l10ns.hu);
        flatpickr.setDefaults({
            dateFormat: 'Y-m-d',
            altFormat: 'Y.m.d',
            altInput: true
        });
        
        // Windows style for Select2
        $(document).ready(function() {
            // Override Select2 styles
            $('.select2-container').css({
                'font-size': '11px'
            });
        });
    </script>
    
    <?php if (isset($js)): ?>
        <?php foreach ($js as $jsFile): ?>
            <script src="<?= $this->asset($jsFile) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>