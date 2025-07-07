<?php
// app/Views/devices/index.php - Windows Desktop Style with Toolbar
$this->setData(['title' => 'Eszközök']);
?>

<style>
/* Windows Style for Devices */
.win-toolbar {
    background-color: #F0F0F0;
    border-top: 1px solid #FFFFFF;
    border-bottom: 1px solid #848484;
    padding: 4px;
    display: flex;
    gap: 0;
    margin-bottom: 8px;
}

.win-toolbar-btn {
    background-color: #F0F0F0;
    border: 1px solid #F0F0F0;
    padding: 4px 8px;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #000;
    text-decoration: none;
    margin-right: 2px;
}

.win-toolbar-btn:hover {
    border: 1px solid #0078D7;
    background-color: #E5F1FB;
    color: #000;
    text-decoration: none;
}

.win-toolbar-btn:active {
    background-color: #CCE4F7;
    border: 1px solid #005A9E;
}

.win-toolbar-btn i {
    font-size: 14px;
}

/* Windows Table */
.win-table-wrapper {
    background-color: #FFFFFF;
    border: 1px solid #7F7F7F;
    overflow: auto;
    max-height: calc(100vh - 250px);
}

.win-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
}

.win-table thead {
    background-color: #F0F0F0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.win-table th {
    padding: 4px 8px;
    text-align: left;
    font-weight: normal;
    font-size: 11px;
    border-right: 1px solid #D4D0C8;
    border-bottom: 1px solid #D4D0C8;
    background-color: #F0F0F0;
    color: #000;
    white-space: nowrap;
    text-transform: uppercase;
}

.win-table th:last-child {
    border-right: none;
}

.win-table tbody tr {
    background-color: #FFFFFF;
    border-bottom: 1px solid #F0F0F0;
}

.win-table tbody tr:hover {
    background-color: #E8F2FE;
}

.win-table tbody tr.selected {
    background-color: #0078D7;
    color: white;
}

.win-table tbody tr.selected td {
    color: white;
}

.win-table tbody tr.selected .win-info-text {
    color: #E0E0E0 !important;
}

.win-table td {
    padding: 4px 8px;
    font-size: 11px;
    border-right: 1px solid #F5F5F5;
    white-space: nowrap;
}

.win-table td:last-child {
    border-right: none;
}

/* Empty state */
.win-empty-state {
    text-align: center;
    padding: 40px;
    color: #666;
    font-size: 12px;
}

/* Search bar */
.win-searchbar {
    background-color: #F0F0F0;
    border: 1px solid #D4D0C8;
    padding: 8px;
    margin-bottom: 8px;
    display: flex;
    gap: 8px;
    align-items: center;
}

.win-search-input {
    flex: 1;
    max-width: 300px;
    padding: 4px 6px;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    border: 1px solid #D4D0C8;
}

.win-filter-select {
    padding: 4px 6px;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    border: 1px solid #D4D0C8;
    background-color: white;
}

.win-search-btn {
    padding: 4px 12px;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background-color: #0054E3;
    color: white;
    border: 1px solid #003CCB;
    cursor: pointer;
}

.win-search-btn:hover {
    background-color: #0050DB;
}

/* Info text */
.win-info-text {
    font-size: 10px;
    color: #666;
    font-style: italic;
}

/* Page header */
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
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Icon for device type */
.device-icon {
    font-size: 10px;
    color: #17A2B8;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-bicycle"></i>
        Eszközök
    </h1>
</div>

<!-- Windows Toolbar -->
<div class="win-toolbar">
    <?php if (\Core\Auth::hasPermission('device.create')): ?>
    <a href="<?= $this->url('devices/create') ?>" class="win-toolbar-btn">
        <i class="fas fa-plus"></i> új eszköz
    </a>
    <?php endif; ?>
    
    <button class="win-toolbar-btn" onclick="editItem()">
        <i class="fas fa-edit"></i> szerkesztés
    </button>
    
    <button class="win-toolbar-btn" onclick="viewWorksheets()">
        <i class="fas fa-file-alt"></i> munkalapok
    </button>
    
    <button class="win-toolbar-btn" onclick="deleteItem()">
        <i class="fas fa-trash"></i> törlés
    </button>
    
    <button class="win-toolbar-btn" onclick="exportItems()">
        <i class="fas fa-file-export"></i> export
    </button>
    
    <button class="win-toolbar-btn" onclick="printList()">
        <i class="fas fa-print"></i> nyomtatás
    </button>
</div>

<!-- Search/Filter Bar -->
<form method="GET" action="<?= $this->url('devices') ?>" class="win-searchbar">
    <input type="text" 
           name="search" 
           class="win-search-input" 
           placeholder="Keresés név, gyári szám vagy ügyfél alapján..." 
           value="<?= $this->escape($_GET['search'] ?? '') ?>">
    
    <select name="condition_id" class="win-filter-select">
        <option value="">Minden állapot</option>
        <?php if (isset($conditions)): ?>
            <?php foreach ($conditions as $condition): ?>
            <option value="<?= $condition['id'] ?>" <?= ($_GET['condition_id'] ?? '') == $condition['id'] ? 'selected' : '' ?>>
                <?= $this->escape($condition['name']) ?>
            </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    
    <button type="submit" class="win-search-btn">
        Szűrés
    </button>
</form>

<!-- Windows Style Table -->
<div class="win-table-wrapper">
    <table class="win-table" id="devicesTable">
        <thead>
            <tr>
                <th style="width: 30px;"></th>
                <th>NÉV</th>
                <th>GYÁRI SZÁM</th>
                <th>ÜGYFÉL</th>
                <th>ÁLLAPOT</th>
                <th>TÍPUS</th>
                <th>UTOLSÓ SZERVIZ</th>
                <th>MEGJEGYZÉS</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pagination['items'])): ?>
            <tr>
                <td colspan="8" class="win-empty-state">
                    <i class="fas fa-bicycle" style="font-size: 48px; color: #DDD; display: block; margin-bottom: 16px;"></i>
                    Nincs megjeleníthető eszköz
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($pagination['items'] as $device): ?>
                <tr data-id="<?= $device['id'] ?>" 
                    data-customer-id="<?= $device['customer_id'] ?? '' ?>"
                    onclick="selectRow(this, event)">
                    <td style="text-align: center;">
                        <i class="fas fa-bicycle device-icon"></i>
                    </td>
                    <td>
                        <strong><?= $this->escape($device['name']) ?></strong>
                        <?php if (!empty($device['model'])): ?>
                            <br><span class="win-info-text">Model: <?= $this->escape($device['model']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= $this->escape($device['serial_number'] ?? '-') ?></td>
                    <td>
                        <?= $this->escape($device['customer']['name'] ?? '-') ?>
                        <?php if (!empty($device['customer']['phone'])): ?>
                            <br><span class="win-info-text"><?= $this->escape($device['customer']['phone']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= $this->escape($device['condition']['name'] ?? '-') ?></td>
                    <td><?= $this->escape($device['type'] ?? '-') ?></td>
                    <td>
                        <?php if (!empty($device['last_service_date'])): ?>
                            <?= $this->formatDate($device['last_service_date'], 'Y.m.d') ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($device['notes'])): ?>
                            <span class="win-info-text" title="<?= $this->escape($device['notes']) ?>">
                                <?= $this->escape(mb_substr($device['notes'], 0, 30)) ?>...
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if (!empty($pagination['items']) && isset($pagination['total_pages']) && $pagination['total_pages'] > 1): ?>
<div style="background-color: #F0F0F0; border: 1px solid #D4D0C8; padding: 8px; margin-top: 8px; text-align: center; font-size: 11px;">
    <?php if ($pagination['current_page'] > 1): ?>
        <a href="?page=<?= $pagination['current_page'] - 1 ?>&search=<?= urlencode($_GET['search'] ?? '') ?>&condition_id=<?= $_GET['condition_id'] ?? '' ?>" 
           style="padding: 4px 8px; text-decoration: none;">
            <i class="fas fa-chevron-left"></i> Előző
        </a>
    <?php endif; ?>
    
    <span style="margin: 0 16px;">
        Oldal: <?= $pagination['current_page'] ?> / <?= $pagination['total_pages'] ?>
    </span>
    
    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
        <a href="?page=<?= $pagination['current_page'] + 1 ?>&search=<?= urlencode($_GET['search'] ?? '') ?>&condition_id=<?= $_GET['condition_id'] ?? '' ?>" 
           style="padding: 4px 8px; text-decoration: none;">
            Következő <i class="fas fa-chevron-right"></i>
        </a>
    <?php endif; ?>
</div>
<?php endif; ?>

<script>
// Global selected row
let selectedRow = null;

// Row selection
function selectRow(row, event) {
    if (event && event.target.tagName === 'A') {
        return; // Don't select if clicking on link
    }
    
    // Remove previous selection
    if (selectedRow) {
        selectedRow.classList.remove('selected');
    }
    
    // Add selection
    row.classList.add('selected');
    selectedRow = row;
}

// Get selected item ID
function getSelectedId() {
    return selectedRow ? selectedRow.getAttribute('data-id') : null;
}

// Get selected customer ID
function getSelectedCustomerId() {
    return selectedRow ? selectedRow.getAttribute('data-customer-id') : null;
}

// Toolbar functions
function editItem() {
    const id = getSelectedId();
    if (id) {
        window.location.href = '<?= $this->url('devices') ?>/' + id + '/edit';
    } else {
        alert('Válasszon ki egy eszközt!');
    }
}

function viewWorksheets() {
    const customerId = getSelectedCustomerId();
    if (customerId) {
        window.location.href = '<?= $this->url('worksheets') ?>?customer_id=' + customerId;
    } else {
        alert('Válasszon ki egy eszközt!');
    }
}

function deleteItem() {
    const id = getSelectedId();
    if (id) {
        if (confirm('Biztosan törölni szeretné a kiválasztott eszközt?')) {
            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= $this->url('devices') ?>/' + id + '/delete';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '<?= CSRF_TOKEN_NAME ?>';
            csrfInput.value = '<?= \Core\Auth::csrfToken() ?>';
            
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    } else {
        alert('Válasszon ki egy eszközt!');
    }
}

function exportItems() {
    window.location.href = '<?= $this->url('devices/export') ?>';
}

function printList() {
    window.print();
}

// Select first row by default
document.addEventListener('DOMContentLoaded', function() {
    const firstRow = document.querySelector('#devicesTable tbody tr[data-id]');
    if (firstRow) {
        selectRow(firstRow);
    }
});

// Handle double click for edit
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('devicesTable');
    table.addEventListener('dblclick', function(e) {
        const row = e.target.closest('tr[data-id]');
        if (row) {
            editItem();
        }
    });
});
</script>