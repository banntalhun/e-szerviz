<?php
// app/Views/dashboard/index.php - Windows Desktop Style
$this->setData(['title' => 'Vezérlőpult']);
?>

<style>
/* Windows Style for Dashboard */
.win-page-header {
    background-color: #FFFFFF;
    border: 1px solid #D4D0C8;
    padding: 8px 12px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.win-page-title {
    font-size: 14px;
    font-weight: bold;
    color: #000;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Statistics Cards */
.win-stats-container {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
}

.win-stat-card {
    flex: 1;
    background-color: #FFFFFF;
    border: 1px solid #D4D0C8;
    padding: 10px;
}

.win-stat-card-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.win-stat-label {
    font-size: 11px;
    color: #666;
    margin-bottom: 4px;
}

.win-stat-value {
    font-size: 20px;
    font-weight: bold;
    color: #000;
    font-family: 'Segoe UI', Tahoma, sans-serif;
}

.win-stat-icon {
    font-size: 24px;
    opacity: 0.8;
}

.win-stat-card.primary { border-color: #0054E3; }
.win-stat-card.primary .win-stat-icon { color: #0054E3; }

.win-stat-card.warning { border-color: #FFC107; }
.win-stat-card.warning .win-stat-icon { color: #FFC107; }

.win-stat-card.danger { border-color: #DC3545; }
.win-stat-card.danger .win-stat-icon { color: #DC3545; }

.win-stat-card.success { border-color: #28A745; }
.win-stat-card.success .win-stat-icon { color: #28A745; }

/* Windows Panels */
.win-panel {
    background-color: #FFFFFF;
    border: 1px solid #D4D0C8;
    margin-bottom: 8px;
}

.win-panel-header {
    background-color: #F0F0F0;
    border-bottom: 1px solid #D4D0C8;
    padding: 6px 10px;
    font-size: 12px;
    font-weight: bold;
    color: #000;
    display: flex;
    align-items: center;
    gap: 6px;
}

.win-panel-header.danger {
    background-color: #DC3545;
    color: white;
}

.win-panel-body {
    padding: 0;
    font-size: 11px;
}

.win-panel-footer {
    background-color: #F0F0F0;
    border-top: 1px solid #D4D0C8;
    padding: 6px 10px;
    text-align: center;
}

/* Tables */
.win-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
}

.win-table thead {
    background-color: #F0F0F0;
}

.win-table th {
    padding: 6px 8px;
    border: 1px solid #D4D0C8;
    text-align: left;
    font-weight: normal;
    color: #000;
}

.win-table td {
    padding: 6px 8px;
    border: 1px solid #D4D0C8;
    background-color: #FFFFFF;
}

.win-table tbody tr:hover td {
    background-color: #E5F1FB;
}

.win-table a {
    color: #0066CC;
    text-decoration: none;
}

.win-table a:hover {
    text-decoration: underline;
}

.win-table-small {
    font-size: 10px;
    color: #666;
}

/* Badges */
.win-badge {
    display: inline-block;
    padding: 2px 6px;
    font-size: 10px;
    border-radius: 2px;
    font-weight: normal;
}

.win-badge.warning {
    background-color: #FFC107;
    color: #000;
}

/* Buttons */
.win-btn {
    padding: 4px 12px;
    font-size: 11px;
    border: 1px solid #D4D0C8;
    background-color: #F0F0F0;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
    color: #000;
    font-family: 'Segoe UI', Tahoma, sans-serif;
}

.win-btn:hover {
    background-color: #E5F1FB;
    border-color: #7DA2CE;
    color: #000;
}

.win-btn-sm {
    padding: 2px 8px;
    font-size: 10px;
}

.win-btn-primary {
    background-color: #0054E3;
    color: white;
    border-color: #003CCB;
}

.win-btn-primary:hover {
    background-color: #0050DB;
    color: white;
}

.win-btn-success {
    background-color: #28A745;
    color: white;
    border-color: #1E7E34;
}

.win-btn-success:hover {
    background-color: #218838;
    color: white;
}

.win-btn-info {
    background-color: #17A2B8;
    color: white;
    border-color: #117A8B;
}

.win-btn-info:hover {
    background-color: #138496;
    color: white;
}

.win-btn-secondary {
    background-color: #6C757D;
    color: white;
    border-color: #545B62;
}

.win-btn-secondary:hover {
    background-color: #5A6268;
    color: white;
}

/* Layout helpers */
.win-row {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
}

.win-col-6 {
    flex: 1;
}

.win-col-12 {
    width: 100%;
}

/* Quick actions */
.win-quick-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

/* Text alignment */
.text-center { text-align: center; }
.text-end { text-align: right; }
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-tachometer-alt"></i>
        Vezérlőpult
    </h1>
</div>

<!-- Statistics Cards -->
<div class="win-stats-container">
    <div class="win-stat-card primary">
        <div class="win-stat-card-content">
            <div>
                <div class="win-stat-label">Mai munkalapok</div>
                <div class="win-stat-value"><?= $stats['today'] ?></div>
            </div>
            <div class="win-stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
    </div>
    
    <div class="win-stat-card warning">
        <div class="win-stat-card-content">
            <div>
                <div class="win-stat-label">Aktív munkalapok</div>
                <div class="win-stat-value"><?= $stats['active'] ?></div>
            </div>
            <div class="win-stat-icon">
                <i class="fas fa-wrench"></i>
            </div>
        </div>
    </div>
    
    <div class="win-stat-card danger">
        <div class="win-stat-card-content">
            <div>
                <div class="win-stat-label">Sürgős munkalapok</div>
                <div class="win-stat-value"><?= $stats['urgent'] ?></div>
            </div>
            <div class="win-stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    
    <div class="win-stat-card success">
        <div class="win-stat-card-content">
            <div>
                <div class="win-stat-label">Havi bevétel</div>
                <div class="win-stat-value"><?= $this->formatPrice($stats['monthly_revenue']) ?></div>
            </div>
            <div class="win-stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
</div>

<div class="win-row">
    <!-- Urgent Worksheets -->
    <?php if (!empty($urgentWorksheets)): ?>
    <div class="win-col-6">
        <div class="win-panel">
            <div class="win-panel-header danger">
                <i class="fas fa-exclamation-triangle"></i>
                Sürgős munkalapok
            </div>
            <div class="win-panel-body">
                <table class="win-table">
                    <thead>
                        <tr>
                            <th>Munkalap</th>
                            <th>Ügyfél</th>
                            <th>Eszköz</th>
                            <th>Prioritás</th>
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($urgentWorksheets as $worksheet): ?>
                        <tr>
                            <td>
                                <a href="<?= $this->url('worksheets/' . $worksheet['id']) ?>">
                                    <?= $this->escape($worksheet['worksheet_number']) ?>
                                </a>
                                <br>
                                <span class="win-table-small">
                                    <?= $this->formatDate($worksheet['created_at']) ?>
                                </span>
                            </td>
                            <td><?= $this->escape($worksheet['customer_name']) ?></td>
                            <td><?= $this->escape($worksheet['device_name'] ?? 'N/A') ?></td>
                            <td>
                                <span class="win-badge" style="background-color: <?= $worksheet['priority_color'] ?>; color: white;">
                                    <?= $this->escape($worksheet['priority_name']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= $this->url('worksheets/' . $worksheet['id']) ?>" 
                                   class="win-btn win-btn-sm win-btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Recent Worksheets -->
    <div class="<?= empty($urgentWorksheets) ? 'win-col-12' : 'win-col-6' ?>">
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-clock"></i>
                Legutóbbi munkalapok
            </div>
            <div class="win-panel-body">
                <table class="win-table">
                    <thead>
                        <tr>
                            <th>Munkalap</th>
                            <th>Ügyfél</th>
                            <th>Státusz</th>
                            <th>Összeg</th>
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentWorksheets as $worksheet): ?>
                        <tr>
                            <td>
                                <a href="<?= $this->url('worksheets/' . $worksheet['id']) ?>">
                                    <?= $this->escape($worksheet['worksheet_number']) ?>
                                </a>
                                <br>
                                <span class="win-table-small">
                                    <?= $this->formatDate($worksheet['created_at']) ?>
                                </span>
                            </td>
                            <td><?= $this->escape($worksheet['customer_name']) ?></td>
                            <td>
                                <span class="win-badge" style="background-color: <?= $worksheet['status_color'] ?>; color: white;">
                                    <?= $this->escape($worksheet['status_name']) ?>
                                </span>
                            </td>
                            <td><?= $this->formatPrice($worksheet['total_price']) ?></td>
                            <td>
                                <a href="<?= $this->url('worksheets/' . $worksheet['id']) ?>" 
                                   class="win-btn win-btn-sm win-btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="win-panel-footer">
                <a href="<?= $this->url('worksheets') ?>" class="win-btn win-btn-sm win-btn-primary">
                    <i class="fas fa-list"></i> Összes munkalap
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Technician Statistics (Admin only) -->
<?php if (\Core\Auth::isAdmin() && !empty($technicianStats)): ?>
<div class="win-row">
    <div class="win-col-12">
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-users"></i>
                Szerelő teljesítmény
            </div>
            <div class="win-panel-body">
                <table class="win-table">
                    <thead>
                        <tr>
                            <th>Szerelő</th>
                            <th class="text-center">Összes munkalap</th>
                            <th class="text-center">Aktív munkalap</th>
                            <th class="text-end">Összes bevétel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($technicianStats as $tech): ?>
                        <tr>
                            <td><?= $this->escape($tech['full_name']) ?></td>
                            <td class="text-center"><?= $tech['total_worksheets'] ?></td>
                            <td class="text-center">
                                <span class="win-badge warning"><?= $tech['active_worksheets'] ?></span>
                            </td>
                            <td class="text-end"><?= $this->formatPrice($tech['total_revenue'] ?? 0) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Quick Actions -->
<div class="win-panel" style="margin-top: 16px;">
    <div class="win-panel-header">
        <i class="fas fa-rocket"></i>
        Gyors műveletek
    </div>
    <div class="win-panel-body" style="padding: 10px;">
        <div class="win-quick-actions">
            <?php if (\Core\Auth::hasPermission('worksheet.create')): ?>
            <a href="<?= $this->url('worksheets/create') ?>" class="win-btn win-btn-success">
                <i class="fas fa-plus"></i> Új munkalap
            </a>
            <?php endif; ?>
            
            <?php if (\Core\Auth::hasPermission('customer.create')): ?>
            <a href="<?= $this->url('customers/create') ?>" class="win-btn win-btn-info">
                <i class="fas fa-user-plus"></i> Új ügyfél
            </a>
            <?php endif; ?>
            
            <?php if (\Core\Auth::hasPermission('device.create')): ?>
            <a href="<?= $this->url('devices/create') ?>" class="win-btn win-btn-secondary">
                <i class="fas fa-bicycle"></i> Új eszköz
            </a>
            <?php endif; ?>
            
            <?php if (\Core\Auth::hasPermission('report.view')): ?>
            <a href="<?= $this->url('reports/revenue') ?>" class="win-btn win-btn-primary">
                <i class="fas fa-chart-line"></i> Bevételi kimutatás
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>