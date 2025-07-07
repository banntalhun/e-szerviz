<?php
// app/Views/parts/index.php - Windows Desktop Style
$this->setData(['title' => 'Alkatrészek/Szolgáltatások']);
?>

<style>
/* Windows Style for Parts */
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

.win-table tbody tr.selected .win-type-badge {
    color: white !important;
    border-color: white !important;
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

/* Type badges */
.win-type-badge {
    padding: 2px 6px;
    font-size: 10px;
    font-weight: normal;
    display: inline-block;
    border: 1px solid;
}

.win-type-part {
    background-color: #D1ECF1;
    color: #0C5460;
    border-color: #BEE5EB;
}

.win-type-service {
    background-color: #D4EDDA;
    color: #155724;
    border-color: #C3E6CB;
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
</style>

<!-- Page Header -->
<div class="win-page-header" style="background-color: #FFFFFF; border: 1px solid #D4D0C8; padding: 8px 12px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center;">
    <h1 style="font-size: 14px; font-weight: bold; color: #000; margin: 0; display: flex; align-items: center; gap: 8px;">
        <i class="fas fa-cogs"></i>
        Költségek / Alkatrészek
    </h1>
</div>

<!-- Windows Toolbar -->
<div class="win-toolbar">
    <?php if (\Core\Auth::hasPermission('part.create')): ?>
    <a href="<?= $this->url('parts/create') ?>" class="win-toolbar-btn">
        <i class="fas fa-plus"></i> új tétel
    </a>
    <?php endif; ?>
    
    <button class="win-toolbar-btn" onclick="copyItem()">
        <i class="fas fa-copy"></i> másolás
    </button>
    
    <button class="win-toolbar-btn" onclick="editItem()">
        <i class="fas fa-edit"></i> szerkesztés
    </button>
    
    <button class="win-toolbar-btn" onclick="toggleItem()">
        <i class="fas fa-toggle-on"></i> ki/bekapcsolás
    </button>
    
    <button class="win-toolbar-btn" onclick="deleteItem()">
        <i class="fas fa-trash"></i> törlés
    </button>
    
    <button class="win-toolbar-btn" onclick="importItems()">
        <i class="fas fa-file-import"></i> import
    </button>
    
    <button class="win-toolbar-btn" onclick="exportItems()">
        <i class="fas fa-file-export"></i> export
    </button>
    
    <button class="win-toolbar-btn" onclick="printList()">
    <i class="fas fa-print"></i> lista nyomtatás
</button>

<div style="flex: 1;"></div>

<a href="<?= $this->url('categories') ?>" class="win-toolbar-btn">
    <i class="fas fa-folder-tree"></i> Kategóriák
</a>
</div>

<!-- Search/Filter Bar -->
<form method="GET" action="<?= $this->url('parts') ?>" class="win-searchbar">
    <input type="text" 
           name="search" 
           class="win-search-input" 
           placeholder="Keresés név vagy cikkszám alapján..." 
           value="<?= $this->escape($_GET['search'] ?? '') ?>">
    
    <select name="type" class="win-filter-select">
        <option value="">Minden típus</option>
        <option value="part" <?= ($_GET['type'] ?? '') == 'part' ? 'selected' : '' ?>>Csak alkatrészek</option>
        <option value="service" <?= ($_GET['type'] ?? '') == 'service' ? 'selected' : '' ?>>Csak szolgáltatások</option>
    </select>
    
    <select name="category_id" class="win-filter-select">
        <option value="">Minden kategória</option>
        <?php foreach ($categories as $catId => $catName): ?>
            <option value="<?= $catId ?>" <?= ($categoryId ?? '') == $catId ? 'selected' : '' ?>>
                <?= $this->escape($catName) ?>
            </option>
        <?php endforeach; ?>
    </select>
            
    <button type="submit" class="win-search-btn">
        Szűrés
    </button>
</form>

<!-- Windows Style Table -->
<div class="win-table-wrapper">
    <table class="win-table" id="partsTable">
        <thead>
            <tr>
                <th style="width: 30px;"></th>
                <th style="width: 50px;">KÉP</th>
                <th>NÉV</th>
                <th>KATEGÓRIA</th>
                <th>M.E.</th>
                <th>LISTAÁR N</th>
                <th>EGYSÉGÁR N</th>
                <th>EGYSÉGÁR B</th>
                <th>ÁFA</th>
                <th style="text-align: center;">...</th>
                <th>KÉSZLET</th>
                <th>MEGJEGYZÉS</th>
            </tr>
        </thead>
        <tbody>
    <?php if (empty($pagination['items'])): ?>
    <tr>
        <td colspan="12" class="win-empty-state">
            <i class="fas fa-cogs" style="font-size: 48px; color: #DDD; display: block; margin-bottom: 16px;"></i>
            Nincs megjeleníthető tétel
        </td>
    </tr>
    <?php else: ?>
        <?php foreach ($pagination['items'] as $part): ?>
        <tr data-id="<?= $part['id'] ?>" onclick="selectRow(this, event)">
            <td style="text-align: center;">
                <?php if ($part['type'] == 'part'): ?>
                    <i class="fas fa-cog" style="color: #17A2B8; font-size: 10px;"></i>
                <?php else: ?>
                    <i class="fas fa-wrench" style="color: #28A745; font-size: 10px;"></i>
                <?php endif; ?>
            </td>
            <td style="text-align: center;">
                <?php if (!empty($part['image'])): ?>
                    <img src="/data/data/szerviz/storage/uploads/parts/thumbs/<?= $this->escape($part['image']) ?>"
                         alt="<?= $this->escape($part['name']) ?>" 
                         style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #D4D0C8; cursor: pointer;"
                         onclick="showImageModal('<?= $this->escape($part['image']) ?>', '<?= $this->escape($part['name']) ?>')">
                <?php else: ?>
                    <span style="color: #CCC;">-</span>
                <?php endif; ?>
            </td>
            <td>
                <strong><?= $this->escape($part['name']) ?></strong>
                <?php if ($part['sku']): ?>
                    <span style="color: #666; font-size: 10px;">(<?= $this->escape($part['sku']) ?>)</span>
                <?php endif; ?>
            </td>
            <td>
                <?php if (!empty($part['category_name'])): ?>
                    <span style="color: #666; font-size: 10px;">
                        <?= $this->escape($part['category_name']) ?>
                    </span>
                <?php else: ?>
                    <span style="color: #CCC;">-</span>
                <?php endif; ?>
            </td>
            <td><?= $this->escape($part['unit']) ?></td>
            <td style="text-align: right;"><?= number_format($part['price'], 0, ',', ' ') ?></td>
            <td style="text-align: right;"><?= number_format($part['price'], 0, ',', ' ') ?></td>
            <td style="text-align: right;">
                <?php
                $priceWithVat = $part['price'] * 1.27;
                echo number_format($priceWithVat, 0, ',', ' ');
                ?>
            </td>
            <td style="text-align: center;">27%</td>
            <td style="text-align: center;">Ft.</td>
            <td style="text-align: center;">
                <?php if ($part['type'] == 'part'): ?>
                    <?= isset($part['stock']) ? $part['stock'] : '0' ?>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
            <td>
                <?php if ($part['usage_count'] > 0): ?>
                    <span style="color: #666; font-size: 10px;">
                        Használva: <?= $part['usage_count'] ?>x
                    </span>
                <?php endif; ?>
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
        <a href="?page=<?= $pagination['current'] - 1 ?>&search=<?= urlencode($_GET['search'] ?? '') ?>&type=<?= $_GET['type'] ?? '' ?>&category_id=<?= $_GET['category_id'] ?? '' ?>" 
           style="padding: 4px 8px; text-decoration: none;">
            <i class="fas fa-chevron-left"></i> Előző
        </a>
    <?php endif; ?>
    
    <span style="margin: 0 16px;">
        Oldal: <?= $pagination['current'] ?> / <?= $pagination['pages'] ?>
    </span>
    
    <?php if ($pagination['current'] < $pagination['pages']): ?>
        <a href="?page=<?= $pagination['current'] + 1 ?>&search=<?= urlencode($_GET['search'] ?? '') ?>&type=<?= $_GET['type'] ?? '' ?>&category_id=<?= $_GET['category_id'] ?? '' ?>" 
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

// Toolbar functions
function editItem() {
    const id = getSelectedId();
    if (id) {
        window.location.href = '<?= $this->url('parts') ?>/' + id + '/edit';
    } else {
        alert('Válasszon ki egy tételt!');
    }
}

function copyItem() {
    const id = getSelectedId();
    if (id) {
        alert('Másolás funkció még nincs implementálva');
    } else {
        alert('Válasszon ki egy tételt!');
    }
}

function toggleItem() {
    const id = getSelectedId();
    if (id) {
        alert('Ki/bekapcsolás funkció még nincs implementálva');
    } else {
        alert('Válasszon ki egy tételt!');
    }
}

function deleteItem() {
    const id = getSelectedId();
    if (id) {
        if (confirm('Biztosan törölni szeretné a kiválasztott tételt?')) {
            alert('Törlés funkció még nincs implementálva');
        }
    } else {
        alert('Válasszon ki egy tételt!');
    }
}

function importItems() {
    alert('Import funkció még nincs implementálva');
}

function exportItems() {
    alert('Export funkció még nincs implementálva');
}

function printList() {
    window.print();
}

// Select first row by default
document.addEventListener('DOMContentLoaded', function() {
    const firstRow = document.querySelector('#partsTable tbody tr[data-id]');
    if (firstRow) {
        selectRow(firstRow);
    }
});

// Image modal function
function showImageModal(image, title) {
    // Create modal backdrop
    const backdrop = document.createElement('div');
    backdrop.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;';
    
    // Create image
    const img = document.createElement('img');
    img.src = '/data/data/szerviz/storage/uploads/parts/' + image;
    img.alt = title;
    img.style.cssText = 'max-width: 90%; max-height: 90%; border: 2px solid white; box-shadow: 0 0 20px rgba(0,0,0,0.5);';
    
    // Close on click
    backdrop.onclick = function() {
        document.body.removeChild(backdrop);
    };
    
    backdrop.appendChild(img);
    document.body.appendChild(backdrop);
}

</script>