<?php
// app/Views/parts/edit.php - Windows Desktop Style
$this->setData(['title' => 'Alkatrész/szolgáltatás szerkesztése']);
?>

<style>
/* Windows Style for Parts Edit */
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

.win-btn-primary {
    background-color: #0054E3;
    color: white;
    border-color: #003CCB;
}

.win-btn-primary:hover {
    background-color: #0050DB;
    color: white;
}

.win-btn-danger {
    background-color: #DC3545;
    color: white;
    border-color: #A02730;
}

.win-btn-danger:hover {
    background-color: #C82333;
    color: white;
}

/* Content Layout */
.win-content-wrapper {
    display: flex;
    gap: 8px;
}

.win-main-content {
    flex: 1;
}

.win-sidebar {
    width: 320px;
}

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

.win-panel-body {
    padding: 10px;
    font-size: 11px;
}

/* Form Elements */
.win-form-group {
    margin-bottom: 10px;
}

.win-form-label {
    display: block;
    margin-bottom: 4px;
    font-size: 11px;
    color: #000;
}

.win-form-control {
    width: 100%;
    padding: 4px 6px;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    border: 1px solid #D4D0C8;
    background-color: #FFFFFF;
}

.win-form-control:focus {
    outline: none;
    border-color: #0078D7;
}

.win-form-select {
    width: 100%;
    padding: 4px 6px;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    border: 1px solid #D4D0C8;
    background-color: #FFFFFF;
}

.win-form-textarea {
    width: 100%;
    padding: 4px 6px;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    border: 1px solid #D4D0C8;
    background-color: #FFFFFF;
    resize: vertical;
}

.win-form-row {
    display: flex;
    gap: 10px;
}

.win-form-col {
    flex: 1;
}

/* Input Group */
.win-input-group {
    display: flex;
}

.win-input-group .win-form-control {
    border-right: none;
}

.win-input-group-text {
    padding: 4px 8px;
    font-size: 11px;
    background-color: #F0F0F0;
    border: 1px solid #D4D0C8;
    font-family: 'Segoe UI', Tahoma, sans-serif;
}

/* Checkbox/Switch */
.win-checkbox {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 6px;
}

.win-checkbox input[type="checkbox"] {
    margin: 0;
}

.win-checkbox-label {
    font-size: 11px;
}

/* Sidebar specific */
.win-sidebar .win-panel-header.info {
    background-color: #17A2B8;
    color: white;
}

/* Stats */
.win-stats-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 4px;
    padding: 2px 0;
}

.win-stats-label {
    color: #666;
}

.win-stats-value {
    font-weight: bold;
}

/* Usage list */
.win-usage-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.win-usage-item {
    padding: 6px;
    border: 1px solid #E8E8E8;
    margin-bottom: 4px;
    background-color: #FAFAFA;
    text-decoration: none;
    color: #000;
    display: block;
    transition: background-color 0.2s;
}

.win-usage-item:hover {
    background-color: #E5F1FB;
    border-color: #7DA2CE;
    text-decoration: none;
    color: #000;
}

.win-usage-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2px;
}

.win-usage-number {
    font-weight: bold;
    color: #0066CC;
}

.win-usage-date {
    color: #666;
    font-size: 10px;
}

.win-usage-customer {
    font-size: 11px;
    margin-bottom: 2px;
}

.win-usage-details {
    font-size: 10px;
    color: #666;
}

/* Required field indicator */
.required {
    color: #DC3545;
}

/* Help text */
.win-help-text {
    font-size: 10px;
    color: #666;
    margin-top: 2px;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-edit"></i>
        Alkatrész/szolgáltatás szerkesztése
    </h1>
    <a href="<?= $this->url('parts') ?>" class="win-btn">
        <i class="fas fa-arrow-left"></i> Vissza a listához
    </a>
</div>

<form method="POST" action="<?= $this->url('parts/' . $part['id'] . '/update') ?>" enctype="multipart/form-data">
    <?= $this->csrfField() ?>
    
    <!-- Main Content -->
    <div class="win-content-wrapper">
        <!-- Left Column -->
        <div class="win-main-content">
            <!-- Basic Information -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-info-circle"></i> Alapadatok
                </div>
                <div class="win-panel-body">
                    <div class="win-form-row">
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Név <span class="required">*</span></label>
                                <input type="text" name="name" class="win-form-control" 
                                       value="<?= $this->escape($part['name']) ?>" required>
                            </div>
                        </div>
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Cikkszám</label>
                                <input type="text" name="sku" class="win-form-control" 
                                       value="<?= $this->escape($part['sku'] ?? '') ?>"
                                       placeholder="pl. ALK-001">
                                <div class="win-help-text">Egyedi azonosító (opcionális)</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="win-form-row">
    <div class="win-form-col">
        <div class="win-form-group">
            <label class="win-form-label">Típus <span class="required">*</span></label>
            <select name="type" class="win-form-select" required>
                <option value="part" <?= $part['type'] == 'part' ? 'selected' : '' ?>>
                    Alkatrész
                </option>
                <option value="service" <?= $part['type'] == 'service' ? 'selected' : '' ?>>
                    Szolgáltatás
                </option>
            </select>
        </div>
    </div>
    <div class="win-form-col">
        <div class="win-form-group">
            <label class="win-form-label">Kategória</label>
            <select name="category_id" class="win-form-select">
                <option value="">— Nincs kategória —</option>
                <?php foreach ($categories as $catId => $catName): ?>
                    <option value="<?= $catId ?>" <?= $part['category_id'] == $catId ? 'selected' : '' ?>>
                        <?= $this->escape($catName) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="win-help-text">Válassza ki a megfelelő kategóriát</div>
        </div>
    </div>
</div>

<div class="win-form-row">
    <div class="win-form-col">
        <div class="win-form-group">
            <label class="win-form-label">Mértékegység <span class="required">*</span></label>
            <input type="text" name="unit" class="win-form-control" 
                   value="<?= $this->escape($part['unit']) ?>" 
                   placeholder="pl. db, óra, m" required>
        </div>
    </div>
    <div class="win-form-col">
        <!-- Empty for balance -->
    </div>
</div>
                    </div>
                    
                    <div class="win-form-row">
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Egységár (Ft) <span class="required">*</span></label>
                                <div class="win-input-group">
                                    <input type="number" name="price" class="win-form-control" 
                                           value="<?= $part['price'] ?>" 
                                           step="0.01" min="0" required>
                                    <span class="win-input-group-text">Ft</span>
                                </div>
                            </div>
                        </div>

<div class="win-form-row">
    <div class="win-form-col">
        <div class="win-form-group">
            <label class="win-form-label">Készlet</label>
            <input type="number" name="stock" class="win-form-control" 
                   value="<?= $part['stock'] ?? 0 ?>" min="0" 
                   placeholder="0">
            <div class="win-help-text">Csak alkatrészeknél releváns</div>
        </div>
    </div>
    <div class="win-form-col">
        <!-- Empty for balance -->
    </div>
</div>
                        
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Státusz</label>
                                <div class="win-checkbox">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                                           <?= $part['is_active'] ? 'checked' : '' ?>>
                                    <label for="is_active" class="win-checkbox-label">
                                        Aktív
                                    </label>
                                </div>
                                <div class="win-help-text">Megjelenik a választható tételek között</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="win-form-group">
                        <label class="win-form-label">Leírás</label>
                        <textarea name="description" class="win-form-textarea" rows="3" 
                                  placeholder="Részletes leírás, megjegyzés..."><?= $this->escape($part['description'] ?? '') ?></textarea>
                    </div>
                    <!-- Image section -->
                    <div class="win-form-group">
                        <label class="win-form-label">Kép</label>
                        <?php if (!empty($part['image'])): ?>
                            <div style="margin-bottom: 10px;">
                               <img src="/data/data/szerviz/storage/uploads/parts/thumbs/<?= $this->escape($part['image']) ?>" 
                                     alt="<?= $this->escape($part['name']) ?>" 
                                     style="max-width: 150px; border: 1px solid #D4D0C8; padding: 4px;">
                                <div style="margin-top: 6px;">
                                    <button type="submit" name="delete_image" value="1" 
                                            class="win-btn win-btn-danger" 
                                            onclick="return confirm('Biztosan törli a képet?');"
                                            style="font-size: 10px; padding: 2px 8px;">
                                        <i class="fas fa-trash"></i> Kép törlése
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <input type="file" name="image" class="win-form-control" 
                               accept="image/jpeg,image/png,image/gif">
                        <div class="win-help-text">
                            <?= !empty($part['image']) ? 'Új kép feltöltése lecseréli a régit' : 'JPG, PNG vagy GIF formátum (max. 2MB)' ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="win-panel">
                <div class="win-panel-body">
                    <button type="submit" class="win-btn win-btn-primary">
                        <i class="fas fa-save"></i> Mentés
                    </button>
                    <a href="<?= $this->url('parts') ?>" class="win-btn">
                        <i class="fas fa-times"></i> Mégsem
                    </a>
                    
                    <?php if (\Core\Auth::hasPermission('part.delete') && $stats['usage_count'] == 0): ?>
                    <button type="button" class="win-btn win-btn-danger" onclick="deletePart()" style="float: right;">
                        <i class="fas fa-trash"></i> Törlés
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Right Sidebar -->
        <div class="win-sidebar">
            <!-- Statistics -->
            <div class="win-panel">
                <div class="win-panel-header info">
                    <i class="fas fa-chart-bar"></i> Statisztikák
                </div>
                <div class="win-panel-body">
                    <div class="win-stats-row">
                        <span class="win-stats-label">Használatok száma:</span>
                        <span class="win-stats-value"><?= $stats['usage_count'] ?></span>
                    </div>
                    <div class="win-stats-row" style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #D4D0C8;">
                        <span class="win-stats-label">Összes bevétel:</span>
                        <span class="win-stats-value" style="font-size: 14px;">
                            <?= $this->formatPrice($stats['total_revenue']) ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Last Usages -->
            <?php if (!empty($lastUsages)): ?>
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-history"></i> Utolsó használatok
                </div>
                <div class="win-panel-body">
                    <ul class="win-usage-list">
                        <?php foreach ($lastUsages as $usage): ?>
                        <li>
                            <a href="<?= $this->url('worksheets/' . $usage['worksheet_id']) ?>" 
                               class="win-usage-item">
                                <div class="win-usage-header">
                                    <span class="win-usage-number">
                                        <?= $this->escape($usage['worksheet_number']) ?>
                                    </span>
                                    <span class="win-usage-date">
                                        <?= $this->formatDate($usage['created_at'], 'Y.m.d') ?>
                                    </span>
                                </div>
                                <div class="win-usage-customer">
                                    <?= $this->escape($usage['customer_name']) ?>
                                </div>
                                <div class="win-usage-details">
                                    <?= $usage['quantity'] ?> <?= $this->escape($part['unit']) ?> - 
                                    <?= $this->formatPrice($usage['total_price']) ?>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<?php if (\Core\Auth::hasPermission('part.delete') && $stats['usage_count'] == 0): ?>
<script>
function deletePart() {
    if (confirm('Biztosan törli az alkatrészt/szolgáltatást?\n\nAz elem véglegesen törlődik!')) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= $this->url('parts/' . $part['id'] . '/delete') ?>';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= CSRF_TOKEN_NAME ?>';
        csrfInput.value = '<?= \Core\Auth::csrfToken() ?>';
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
<?php endif; ?>

<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(event) {
        const nameInput = document.querySelector('input[name="name"]');
        const unitInput = document.querySelector('input[name="unit"]');
        const priceInput = document.querySelector('input[name="price"]');
        
        let isValid = true;
        let errorMessage = '';
        
        // Name validation
        if (nameInput.value.trim() === '') {
            isValid = false;
            errorMessage += 'Kérjük adja meg a nevet!\n';
            nameInput.style.borderColor = '#DC3545';
        } else {
            nameInput.style.borderColor = '#D4D0C8';
        }
        
        // Unit validation
        if (unitInput.value.trim() === '') {
            isValid = false;
            errorMessage += 'Kérjük adja meg a mértékegységet!\n';
            unitInput.style.borderColor = '#DC3545';
        } else {
            unitInput.style.borderColor = '#D4D0C8';
        }
        
        // Price validation
        if (priceInput.value === '' || parseFloat(priceInput.value) < 0) {
            isValid = false;
            errorMessage += 'Kérjük adjon meg érvényes egységárat!\n';
            priceInput.style.borderColor = '#DC3545';
        } else {
            priceInput.style.borderColor = '#D4D0C8';
        }
        
        if (!isValid) {
            event.preventDefault();
            alert(errorMessage);
        }
    });
});
</script>