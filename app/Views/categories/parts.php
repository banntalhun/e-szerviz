<?php
// app/Views/categories/parts.php - Windows Desktop Style
$this->setData(['title' => $category['name'] . ' - Alkatrészek/Szolgáltatások']);
?>

<style>
/* Windows Style - reuse from parts/index.php */
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

/* Breadcrumb */
.win-breadcrumb {
    background-color: #FFFFFF;
    border: 1px solid #D4D0C8;
    padding: 6px 12px;
    margin-bottom: 8px;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    display: flex;
    align-items: center;
    gap: 4px;
}

.win-breadcrumb-item {
    color: #0066CC;
    text-decoration: none;
}

.win-breadcrumb-item:hover {
    text-decoration: underline;
}

.win-breadcrumb-separator {
    color: #666;
    margin: 0 4px;
}

.win-breadcrumb-current {
    font-weight: bold;
    color: #000;
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

.win-table td {
    padding: 4px 8px;
    font-size: 11px;
    border-right: 1px solid #F5F5F5;
    white-space: nowrap;
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

/* Empty state */
.win-empty-state {
    text-align: center;
    padding: 40px;
    color: #666;
    font-size: 12px;
}

/* Info bar */
.win-info-bar {
    background-color: #E7F3FF;
    border: 1px solid #B3D9FF;
    padding: 8px 12px;
    margin-bottom: 8px;
    font-size: 11px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.win-checkbox-inline {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
}

/* Category info panel */
.win-category-info {
    background-color: #F8F8F8;
    border: 1px solid #E0E0E0;
    padding: 10px;
    margin-bottom: 8px;
    font-size: 11px;
}

.win-category-stats {
    display: flex;
    gap: 20px;
    margin-top: 8px;
}

.win-category-stat {
    display: flex;
    align-items: center;
    gap: 6px;
}

.win-category-stat-value {
    font-weight: bold;
    color: #0066CC;
}
</style>

<!-- Breadcrumb -->
<div class="win-breadcrumb">
    <a href="<?= $this->url('parts') ?>" class="win-breadcrumb-item">
        <i class="fas fa-cogs"></i> Alkatrészek/Szolgáltatások
    </a>
    <span class="win-breadcrumb-separator">›</span>
    <a href="<?= $this->url('categories') ?>" class="win-breadcrumb-item">
        <i class="fas fa-folder-tree"></i> Cikkcsoportok
    </a>
    <?php foreach ($breadcrumb as $crumb): ?>
        <?php if ($crumb['id'] != $category['id']): ?>
            <span class="win-breadcrumb-separator">›</span>
            <a href="<?= $this->url('categories/' . $crumb['id'] . '/parts') ?>" class="win-breadcrumb-item">
                <?= $this->escape($crumb['name']) ?>
            </a>
        <?php else: ?>
            <span class="win-breadcrumb-separator">›</span>
            <span class="win-breadcrumb-current"><?= $this->escape($crumb['name']) ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<!-- Category Info -->
<div class="win-category-info">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; font-size: 14px; font-weight: bold;">
                <i class="fas fa-folder-open" style="color: #FFC107;"></i>
                <?= $this->escape($category['name']) ?>
                <?php if ($category['code']): ?>
                    <span style="font-weight: normal; color: #666; font-size: 12px;">
                        [<?= $this->escape($category['code']) ?>]
                    </span>
                <?php endif; ?>
            </h2>
            <?php if ($category['description']): ?>
                <p style="margin: 4px 0 0 0; color: #666;">
                    <?= $this->escape($category['description']) ?>
                </p>
            <?php endif; ?>
        </div>
        <a href="<?= $this->url('categories/' . $category['id'] . '/edit') ?>" class="win-toolbar-btn">
            <i class="fas fa-edit"></i> Kategória szerkesztése
        </a>
    </div>
    
    <div class="win-category-stats">
        <div class="win-category-stat">
            <i class="fas fa-cog"></i>
            <span>Közvetlen tételek:</span>
            <span class="win-category-stat-value"><?= count($pagination['items']) ?></span>
        </div>
        <div class="win-category-stat">
            <i class="fas fa-layer-group"></i>
            <span>Összes tétel:</span>
            <span class="win-category-stat-value"><?= $pagination['total'] ?></span>
        </div>
    </div>
</div>

<!-- Filter Options -->
<div class="win-info-bar">
    <form method="GET" action="<?= $this->url('categories/' . $category['id'] . '/parts') ?>" style="margin: 0;">
        <div class="win-checkbox-inline">
            <input type="checkbox" id="include_sub" name="include_sub" value="1" 
                   <?= $includeSubcategories ? 'checked' : '' ?>
                   onchange="this.form.submit()">
            <label for="include_sub" style="cursor: pointer;">
                Alkategóriák tételeinek megjelenítése
            </label>
        </div>
    </form>
    
    <div>
        Megjelenítve: <strong><?= count($pagination['items']) ?></strong> tétel
    </div>
</div>

<!-- Toolbar -->
<div class="win-toolbar">
    <?php if (\Core\Auth::hasPermission('part.create')): ?>
    <a href="<?= $this->url('parts/create?category_id=' . $category['id']) ?>" class="win-toolbar-btn">
        <i class="fas fa-plus"></i> Új tétel
    </a>
    <?php endif; ?>
    
    <a href="<?= $this->url('parts') ?>" class="win-toolbar-btn">
        <i class="fas fa-list"></i> Összes tétel
    </a>
    
    <a href="<?= $this->url('categories') ?>" class="win-toolbar-btn">
        <i class="fas fa-folder-tree"></i> Kategóriák
    </a>
    
    <button class="win-toolbar-btn" onclick="window.print()">
        <i class="fas fa-print"></i> Nyomtatás
    </button>
</div>

<!-- Parts Table -->
<div class="win-table-wrapper">
    <table class="win-table" id="partsTable">
        <thead>
            <tr>
                <th style="width: 30px;"></th>
                <th style="width: 50px;">KÉP</th>
                <th>NÉV</th>
                <th>M.E.</th>
                <th>LISTAÁR N</th>
                <th>EGYSÉGÁR N</th>
                <th>EGYSÉGÁR B</th>
                <th>ÁFA</th>
                <th style="text-align: center;">...</th>
                <th>KÉSZLET</th>
                <th>KATEGÓRIA</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pagination['items'])): ?>
            <tr>
                <td colspan="11" class="win-empty-state">
                    <i class="fas fa-folder-open" style="font-size: 48px; color: #DDD; display: block; margin-bottom: 16px;"></i>
                    Nincs tétel ebben a kategóriában
                    <?php if (!$includeSubcategories): ?>
                        <br><br>
                        <a href="?include_sub=1" style="color: #0066CC;">
                            Alkategóriák tételeinek megjelenítése
                        </a>
                    <?php endif; ?>
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
                                 style="width: 40px; height: 40px; object-fit: cover; border: 1px solid #D4D0C8;">
                        <?php else: ?>
                            <span style="color: #CCC;">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= $this->url('parts/' . $part['id'] . '/edit') ?>" 
                           style="color: inherit; text-decoration: none;">
                            <strong><?= $this->escape($part['name']) ?></strong>
                        </a>
                        <?php if ($part['sku']): ?>
                            <span style="color: #666; font-size: 10px;">
                                (<?= $this->escape($part['sku']) ?>)
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><?= $this->escape($part['unit']) ?></td>
                    <td style="text-align: right;"><?= number_format($part['price'], 0, ',', ' ') ?></td>
                    <td style="text-align: right;"><?= number_format($part['price'], 0, ',', ' ') ?></td>
                    <td style="text-align: right;">
                        <?= number_format($part['price'] * 1.27, 0, ',', ' ') ?>
                    </td>
                    <td style="text-align: center;">27%</td>
                    <td style="text-align: center;">Ft.</td>
                    <td style="text-align: center;">
                        <?= $part['type'] == 'part' ? ($part['stock'] ?? 0) : '-' ?>
                    </td>
                    <td>
                        <?php if ($includeSubcategories && $part['category_id'] != $category['id']): ?>
                            <span style="color: #666; font-size: 10px;">
                                <?= $this->escape($part['category_name'] ?? '') ?>
                            </span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination -->
<?php if (!empty($pagination['items']) && $pagination['pages'] > 1): ?>
<div style="background-color: #F0F0F0; border: 1px solid #D4D0C8; padding: 8px; margin-top: 8px; text-align: center; font-size: 11px;">
    <?php 
    $queryString = $includeSubcategories ? '&include_sub=1' : '';
    ?>
    <?php if ($pagination['current'] > 1): ?>
        <a href="?page=<?= $pagination['current'] - 1 ?><?= $queryString ?>" 
           style="padding: 4px 8px; text-decoration: none;">
            <i class="fas fa-chevron-left"></i> Előző
        </a>
    <?php endif; ?>
    
    <span style="margin: 0 16px;">
        Oldal: <?= $pagination['current'] ?> / <?= $pagination['pages'] ?>
    </span>
    
    <?php if ($pagination['current'] < $pagination['pages']): ?>
        <a href="?page=<?= $pagination['current'] + 1 ?><?= $queryString ?>" 
           style="padding: 4px 8px; text-decoration: none;">
            Következő <i class="fas fa-chevron-right"></i>
        </a>
    <?php endif; ?>
</div>
<?php endif; ?>

<script>
// Row selection
let selectedRow = null;

function selectRow(row, event) {
    if (event && event.target.tagName === 'A') {
        return;
    }
    
    if (selectedRow) {
        selectedRow.classList.remove('selected');
    }
    
    row.classList.add('selected');
    selectedRow = row;
}

// Select first row by default
document.addEventListener('DOMContentLoaded', function() {
    const firstRow = document.querySelector('#partsTable tbody tr[data-id]');
    if (firstRow) {
        selectRow(firstRow);
    }
});
</script>