<?php
// app/Views/worksheets/index.php - Windows Desktop Style with All Features
$this->setData(['title' => 'Munkalapok']);
?>

<style>
/* Windows Desktop specific styles for this page */
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

/* Search bar */
.win-searchbar {
    background-color: #F0F0F0;
    border: 1px solid #D4D0C8;
    padding: 8px;
    margin-bottom: 8px;
}

/* Windows Table */
.win-table-wrapper {
    background-color: #FFFFFF;
    border: 1px solid #7F7F7F;
    overflow: auto;
}

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
    padding: 4px 8px;
    text-align: left;
    font-weight: normal;
    font-size: 11px;
    border-right: 1px solid #D4D0C8;
    border-bottom: 1px solid #D4D0C8;
    background-color: #F0F0F0;
    color: #000;
    white-space: nowrap;
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

.win-table tbody tr.selected a {
    color: white !important;
}

.win-table td {
    padding: 2px 8px;
    font-size: 11px;
    border-right: 1px solid #F5F5F5;
    white-space: nowrap;
}

.win-table td:last-child {
    border-right: none;
}

/* Status badges Windows style */
.win-status {
    font-size: 11px;
    padding: 1px 4px;
    font-weight: normal;
}

/* Link style */
.win-table a {
    color: #D9534F;
    text-decoration: none;
}

.win-table a:hover {
    text-decoration: underline;
}

/* Override Bootstrap and DataTables */
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    font-size: 11px !important;
}

.dataTables_wrapper .dataTables_filter input {
    font-size: 11px !important;
    padding: 2px 4px !important;
    margin-left: 4px !important;
}

.page-link {
    padding: 2px 8px !important;
    font-size: 11px !important;
}

/* Hide DataTables default search */
.dataTables_filter {
    display: none !important;
}
</style>

<!-- Windows Toolbar -->
<div class="win-toolbar">
    <button class="win-toolbar-btn" onclick="toggleSearch()">
        <i class="fas fa-filter"></i> szűrés
    </button>
    
    <?php if (\Core\Auth::hasPermission('worksheet.create')): ?>
    <a href="<?= $this->url('worksheets/create') ?>" class="win-toolbar-btn">
        <i class="fas fa-file-alt"></i> új munkalap
    </a>
    <?php endif; ?>
    
    <button class="win-toolbar-btn" onclick="copyWorksheet()">
        <i class="fas fa-copy"></i> másolás
    </button>
    
    <button class="win-toolbar-btn" onclick="editWorksheet()">
        <i class="fas fa-edit"></i> szerkesztés
    </button>
    
    <button class="win-toolbar-btn" onclick="deleteWorksheet()">
        <i class="fas fa-trash"></i> törlés
    </button>
    
    <button class="win-toolbar-btn" onclick="printWorksheet()">
        <i class="fas fa-print"></i> nyomtatás
    </button>
    
    <button class="win-toolbar-btn" onclick="attachments()">
        <i class="fas fa-paperclip"></i> mellékletek
    </button>
    
    <?php if (\Core\Auth::hasPermission('worksheet.export')): ?>
    <button class="win-toolbar-btn" onclick="exportWorksheets()">
        <i class="fas fa-file-export"></i> export
    </button>
    <?php endif; ?>
    
    <button class="win-toolbar-btn" onclick="changeLocation()">
        <i class="fas fa-building"></i> telephely
    </button>
</div>

<!-- Search/Filter Section -->
<div class="win-searchbar" id="searchBar" style="display: none;">
    <form method="GET" action="<?= $this->url('worksheets') ?>" id="filterForm">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label mb-1" style="font-size: 11px;">Keresés</label>
                <input type="text" 
                       class="form-control form-control-sm" 
                       id="search" 
                       name="search" 
                       value="<?= $this->escape($search) ?>" 
                       placeholder="Munkalap szám, ügyfél, eszköz..."
                       style="font-size: 11px;">
            </div>
            
            <div class="col-md-2">
                <label for="status" class="form-label mb-1" style="font-size: 11px;">Státusz</label>
                <select class="form-select form-select-sm" id="status" name="status" style="font-size: 11px;">
                    <option value="">Mind</option>
                    <?php foreach ($statusTypes as $status): ?>
                    <option value="<?= $status['id'] ?>" <?= $filters['status_id'] == $status['id'] ? 'selected' : '' ?>>
                        <?= $this->escape($status['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="technician" class="form-label mb-1" style="font-size: 11px;">Szerelő</label>
                <select class="form-select form-select-sm" id="technician" name="technician" style="font-size: 11px;">
                    <option value="">Mind</option>
                    <?php foreach ($technicians as $tech): ?>
                    <option value="<?= $tech['id'] ?>" <?= $filters['technician_id'] == $tech['id'] ? 'selected' : '' ?>>
                        <?= $this->escape($tech['full_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="priority" class="form-label mb-1" style="font-size: 11px;">Prioritás</label>
                <select class="form-select form-select-sm" id="priority" name="priority" style="font-size: 11px;">
                    <option value="">Mind</option>
                    <?php foreach ($priorityTypes as $priority): ?>
                    <option value="<?= $priority['id'] ?>" <?= $filters['priority_id'] == $priority['id'] ? 'selected' : '' ?>>
                        <?= $this->escape($priority['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-1">
                <label for="date_from" class="form-label mb-1" style="font-size: 11px;">Dátum -tól</label>
                <input type="date" 
                       class="form-control form-control-sm" 
                       id="date_from" 
                       name="date_from" 
                       value="<?= $filters['date_from'] ?>"
                       style="font-size: 11px;">
            </div>
            
            <div class="col-md-1">
                <label for="date_to" class="form-label mb-1" style="font-size: 11px;">Dátum -ig</label>
                <input type="date" 
                       class="form-control form-control-sm" 
                       id="date_to" 
                       name="date_to" 
                       value="<?= $filters['date_to'] ?>"
                       style="font-size: 11px;">
            </div>
            
            <div class="col-md-1">
                <button type="submit" class="btn btn-sm btn-primary" style="font-size: 11px;">
                    <i class="fas fa-search"></i> Szűrés
                </button>
                <a href="<?= $this->url('worksheets') ?>" class="btn btn-sm btn-secondary" style="font-size: 11px;">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Windows Style Table -->
<div class="win-table-wrapper">
    <table class="win-table" id="worksheetTable">
        <thead>
            <tr>
                <th style="width: 20px; text-align: center;"><i class="fas fa-lock" style="font-size: 10px; color: #666;"></i></th>
                <th>sorszám</th>
                <th>ügyfél</th>
                <th>eszköz</th>
                <th>sorozatszám</th>
                <th>rögzítés</th>
                <th>lezárva</th>
                <th>határidő</th>
                <th>állapot</th>
                <th>prioritás</th>
                <th>jelleg</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($worksheets as $worksheet): ?>
            <tr data-id="<?= $worksheet['id'] ?>" onclick="selectRow(this, event)">
                <td style="text-align: center;">
                    <i class="fas fa-lock" style="font-size: 10px; color: #666;"></i>
                </td>
                <td>
                    <a href="<?= $this->url('worksheets/' . $worksheet['id']) ?>" onclick="event.stopPropagation();">
                        <?= $this->escape($worksheet['worksheet_number']) ?>
                    </a>
                </td>
                <td><?= $this->escape($worksheet['customer_name']) ?></td>
                <td><?= $this->escape($worksheet['device_name'] ?? '') ?></td>
                <td><?= $this->escape($worksheet['serial_number'] ?? '') ?></td>
                <td><?= $this->formatDate($worksheet['created_at'], 'Y. m. d. H:i:s') ?></td>
                <td><?= isset($worksheet['closed_at']) && $worksheet['closed_at'] ? $this->formatDate($worksheet['closed_at'], 'Y. m. d.') : '' ?></td>
                <td><?= $this->formatDate($worksheet['warranty_date'], 'Y. m. d.') ?></td>
                <td>
                    <span class="win-status" style="background-color: <?= $worksheet['status_color'] ?>; color: white; padding: 2px 6px;">
                        <?= $this->escape($worksheet['status_name']) ?>
                    </span>
                </td>
                <td>
                    <?php if (isset($worksheet['priority_name'])): ?>
                    <span class="win-status" style="background-color: <?= $worksheet['priority_color'] ?>; color: white; padding: 2px 6px;">
                        <?= $this->escape($worksheet['priority_name']) ?>
                    </span>
                    <?php else: ?>
                    Alap
                    <?php endif; ?>
                </td>
                <td><?= $this->escape($worksheet['repair_type_name'] ?? 'Általános') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
// Global selected row
let selectedRow = null;

// Initialize DataTable with Windows style
$(document).ready(function() {
    $('#worksheetTable').DataTable({
        searching: true,
        ordering: true,
        paging: true,
        pageLength: 50,
        order: [[5, 'desc']], // Sort by date
        columnDefs: [
            { orderable: false, targets: 0 } // Lock icon column
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/hu.json'
        },
        dom: '<"top"lp>rt<"bottom"ip><"clear">'
    });
    
    // Select first row by default
    const firstRow = document.querySelector('#worksheetTable tbody tr');
    if (firstRow) {
        selectRow(firstRow);
    }
});

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

// Get selected worksheet ID
function getSelectedId() {
    return selectedRow ? selectedRow.getAttribute('data-id') : null;
}

// Toolbar functions
function toggleSearch() {
    const searchBar = document.getElementById('searchBar');
    searchBar.style.display = searchBar.style.display === 'none' ? 'block' : 'none';
}

function editWorksheet() {
    const id = getSelectedId();
    if (id) {
        window.location.href = '<?= $this->url('worksheets') ?>/' + id + '/edit';
    } else {
        alert('Válasszon ki egy munkalapot!');
    }
}

function deleteWorksheet() {
    const id = getSelectedId();
    if (id) {
        if (confirm('Biztosan törölni szeretné a kiválasztott munkalapot?')) {
            // Implement delete
            alert('Törlés funkció még nincs implementálva');
        }
    } else {
        alert('Válasszon ki egy munkalapot!');
    }
}

function printWorksheet() {
    const id = getSelectedId();
    if (id) {
        window.open('<?= $this->url('worksheets') ?>/' + id + '/print', '_blank');
    } else {
        alert('Válasszon ki egy munkalapot!');
    }
}

function copyWorksheet() {
    const id = getSelectedId();
    if (id) {
        alert('Másolás funkció még nincs implementálva');
    } else {
        alert('Válasszon ki egy munkalapot!');
    }
}

function attachments() {
    const id = getSelectedId();
    if (id) {
        window.location.href = '<?= $this->url('worksheets') ?>/' + id + '#attachments';
    } else {
        alert('Válasszon ki egy munkalapot!');
    }
}

function changeLocation() {
    alert('Telephely váltás funkció még nincs implementálva');
}

function exportWorksheets() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '<?= $this->url('worksheets/export') ?>?' + params.toString();
}

// Auto-open search if filters are active
<?php if (!empty($search) || !empty(array_filter($filters))): ?>
$(document).ready(function() {
    toggleSearch();
});
<?php endif; ?>
</script>