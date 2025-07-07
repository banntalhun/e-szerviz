<?php
// app/Views/customers/index.php - Windows Desktop Style
$this->setData(['title' => 'Ügyfelek']);
?>

<style>
/* Windows Style for Customers */
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

.win-table td {
    padding: 4px 8px;
    font-size: 11px;
    border-right: 1px solid #F5F5F5;
    white-space: nowrap;
}

.win-table td:last-child {
    border-right: none;
}

/* Priority badge */
.win-priority-badge {
    padding: 2px 6px;
    font-size: 10px;
    font-weight: normal;
    color: white;
    background-color: #6C757D;
    display: inline-block;
}

/* Action buttons */
.win-action-btn {
    display: inline-block;
    padding: 2px 6px;
    font-size: 10px;
    text-decoration: none;
    color: #000;
    border: 1px solid #D4D0C8;
    background-color: #F0F0F0;
    margin-right: 2px;
}

.win-action-btn:hover {
    background-color: #E5F1FB;
    border-color: #0078D7;
    color: #000;
}

/* Empty state */
.win-empty-state {
    text-align: center;
    padding: 40px;
    color: #666;
    font-size: 12px;
}
</style>

<!-- Page Header -->
<div class="win-page-header" style="background-color: #FFFFFF; border: 1px solid #D4D0C8; padding: 8px 12px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="font-size: 14px; font-weight: bold; color: #000; margin: 0; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-users"></i>
        Ügyfelek
    </h1>
</div>

<!-- Windows Toolbar -->
<div class="win-toolbar">
    <?php if (\Core\Auth::hasPermission('customer.create')): ?>
    <a href="<?= $this->url('customers/create') ?>" class="win-toolbar-btn">
        <i class="fas fa-user-plus"></i> új ügyfél
    </a>
    <?php endif; ?>
    
    <button class="win-toolbar-btn" onclick="editCustomer()">
        <i class="fas fa-edit"></i> szerkesztés
    </button>
    
    <button class="win-toolbar-btn" onclick="deleteCustomer()">
        <i class="fas fa-trash"></i> törlés
    </button>
    
    <button class="win-toolbar-btn" onclick="viewWorksheets()">
        <i class="fas fa-file-alt"></i> munkalapok
    </button>
    
    <button class="win-toolbar-btn" onclick="createWorksheet()">
        <i class="fas fa-plus-circle"></i> új munkalap
    </button>
    
    <button class="win-toolbar-btn" onclick="exportCustomers()">
        <i class="fas fa-file-export"></i> export
    </button>
</div>

<!-- Search Bar -->
<form method="GET" action="<?= $this->url('customers') ?>" class="win-searchbar">
    <input type="text" 
           name="search" 
           class="win-search-input" 
           placeholder="Keresés..." 
           value="<?= $this->escape($search) ?>">
    <button type="submit" class="win-search-btn">
        Keresés
    </button>
</form>

<!-- Windows Style Table -->
<div class="win-table-wrapper">
    <table class="win-table" id="customersTable">
        <thead>
            <tr>
                <th style="width: 30px;"></th>
                <th>NÉV</th>
                <th>TELEFON</th>
                <th>EMAIL</th>
                <th>PRIORITÁS</th>
                <th style="text-align: center;">MUNKALAPOK</th>
                <th style="text-align: center;">MŰVELETEK</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pagination['items'])): ?>
            <tr>
                <td colspan="7" class="win-empty-state">
                    <i class="fas fa-users" style="font-size: 48px; color: #DDD; display: block; margin-bottom: 16px;"></i>
                    Nincs megjeleníthető ügyfél
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($pagination['items'] as $customer): ?>
                <tr data-id="<?= $customer['id'] ?>" onclick="selectRow(this, event)">
                    <td style="text-align: center;">
                        <i class="fas fa-user" style="color: #666; font-size: 10px;"></i>
                    </td>
                    <td>
                        <strong><?= $this->escape($customer['name']) ?></strong>
                        <?php if ($customer['is_company']): ?>
                            <br><small style="color: #666;"><?= $this->escape($customer['company_name']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?= $this->escape($customer['phone']) ?></td>
                    <td><?= $this->escape($customer['email'] ?? '-') ?></td>
                    <td>
                        <?php if (isset($customer['priority_name']) && $customer['priority_name']): ?>
                            <span class="win-priority-badge" style="background-color: <?= $customer['priority_color'] ?? '#6C757D' ?>">
                                <?= $this->escape($customer['priority_name']) ?>
                            </span>
                        <?php else: ?>
                            <span class="win-priority-badge">Normál</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $customer['stats']['total_worksheets'] ?? 0 ?>
                    </td>
                    <td style="text-align: center;">
                        <a href="<?= $this->url('customers/' . $customer['id'] . '/edit') ?>" 
                           class="win-action-btn"
                           onclick="event.stopPropagation();">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination (if needed) -->
<?php if (!empty($pagination['items']) && $pagination['pages'] > 1): ?>
<div style="background-color: #F0F0F0; border: 1px solid #D4D0C8; padding: 8px; margin-top: 8px; text-align: center; font-size: 11px;">
    <?php if ($pagination['current'] > 1): ?>
        <a href="?page=<?= $pagination['current'] - 1 ?>&search=<?= urlencode($search) ?>" style="padding: 4px 8px; text-decoration: none;">
            <i class="fas fa-chevron-left"></i> Előző
        </a>
    <?php endif; ?>
    
    <span style="margin: 0 16px;">
        Oldal: <?= $pagination['current'] ?> / <?= $pagination['pages'] ?>
    </span>
    
    <?php if ($pagination['current'] < $pagination['pages']): ?>
        <a href="?page=<?= $pagination['current'] + 1 ?>&search=<?= urlencode($search) ?>" style="padding: 4px 8px; text-decoration: none;">
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

// Get selected customer ID
function getSelectedId() {
    return selectedRow ? selectedRow.getAttribute('data-id') : null;
}

// Toolbar functions
function editCustomer() {
    const id = getSelectedId();
    if (id) {
        window.location.href = '<?= $this->url('customers') ?>/' + id + '/edit';
    } else {
        alert('Válasszon ki egy ügyfelet!');
    }
}

function deleteCustomer() {
    const id = getSelectedId();
    if (id) {
        if (confirm('Biztosan törölni szeretné a kiválasztott ügyfelet?')) {
            alert('Törlés funkció még nincs implementálva');
        }
    } else {
        alert('Válasszon ki egy ügyfelet!');
    }
}

function viewWorksheets() {
    const id = getSelectedId();
    if (id) {
        window.location.href = '<?= $this->url('worksheets') ?>?customer_id=' + id;
    } else {
        alert('Válasszon ki egy ügyfelet!');
    }
}

function createWorksheet() {
    const id = getSelectedId();
    if (id) {
        window.location.href = '<?= $this->url('worksheets/create') ?>?customer_id=' + id;
    } else {
        alert('Válasszon ki egy ügyfelet!');
    }
}

function exportCustomers() {
    alert('Export funkció még nincs implementálva');
}

// Select first row by default
document.addEventListener('DOMContentLoaded', function() {
    const firstRow = document.querySelector('#customersTable tbody tr[data-id]');
    if (firstRow) {
        selectRow(firstRow);
    }
});
</script>