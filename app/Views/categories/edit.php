<?php
// app/Views/categories/edit.php - Windows Desktop Style
$this->setData(['title' => 'Cikkcsoport szerkesztése']);
?>

<style>
/* Windows Style for Category Edit */
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
    max-width: 600px;
}

.win-sidebar {
    width: 280px;
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

.win-panel-header.info {
    background-color: #17A2B8;
    color: white;
}

.win-panel-header.warning {
    background-color: #FFC107;
    color: #000;
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

/* Checkbox */
.win-checkbox {
    display: flex;
    align-items: center;
    gap: 4px;
}

.win-checkbox input[type="checkbox"] {
    margin: 0;
}

.win-checkbox-label {
    font-size: 11px;
}

/* Stats */
.win-stats-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
    padding: 2px 0;
}

.win-stats-label {
    color: #666;
}

.win-stats-value {
    font-weight: bold;
}

/* Warning box */
.win-warning-box {
    background-color: #FFF3CD;
    border: 1px solid #FFE69C;
    color: #856404;
    padding: 8px;
    margin-bottom: 10px;
    font-size: 11px;
    display: flex;
    align-items: center;
    gap: 8px;
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

/* Path display */
.category-path {
    background-color: #F8F8F8;
    border: 1px solid #E0E0E0;
    padding: 6px 8px;
    margin-bottom: 10px;
    font-size: 10px;
    color: #666;
    font-family: 'Consolas', 'Courier New', monospace;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-folder-edit"></i>
        Cikkcsoport szerkesztése
    </h1>
    <a href="<?= $this->url('categories') ?>" class="win-btn">
        <i class="fas fa-arrow-left"></i> Vissza a listához
    </a>
</div>

<form method="POST" action="<?= $this->url('categories/' . $category['id'] . '/update') ?>">
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
                    <div class="win-form-group">
                        <label class="win-form-label">Név <span class="required">*</span></label>
                        <input type="text" name="name" class="win-form-control" 
                               value="<?= $this->escape($category['name']) ?>" required>
                    </div>
                    
                    <div class="win-form-row">
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Kód</label>
                                <input type="text" name="code" class="win-form-control" 
                                       value="<?= $this->escape($category['code'] ?? '') ?>"
                                       style="text-transform: uppercase;">
                                <div class="win-help-text">Egyedi azonosító (opcionális)</div>
                            </div>
                        </div>
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Sorrend</label>
                                <input type="number" name="sort_order" class="win-form-control" 
                                       value="<?= $category['sort_order'] ?>" 
                                       min="0">
                                <div class="win-help-text">Megjelenítési sorrend</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="win-form-group">
                        <label class="win-form-label">Leírás</label>
                        <textarea name="description" class="win-form-textarea" rows="3"><?= $this->escape($category['description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Hierarchy -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-sitemap"></i> Hierarchia
                </div>
                <div class="win-panel-body">
                    <div class="category-path">
                        Jelenlegi elérési út: <?= $this->escape($category['path']) ?>
                    </div>
                    
                    <?php if ($stats['subcategories'] > 0 || $stats['total_parts'] > 0): ?>
                        <div class="win-warning-box">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>
                                <?php if ($stats['subcategories'] > 0): ?>
                                    Ennek a kategóriának <?= $stats['subcategories'] ?> alkategóriája van.
                                <?php endif; ?>
                                <?php if ($stats['total_parts'] > 0): ?>
                                    <?= $stats['total_parts'] ?> tétel tartozik ehhez a kategóriához vagy alkategóriáihoz.
                                <?php endif; ?>
                                A szülő módosítása ezeket is áthelyezi.
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="win-form-group">
                        <label class="win-form-label">Szülő kategória</label>
                        <select name="parent_id" class="win-form-select">
                            <option value="">— Főkategória —</option>
                            <?php foreach ($categories as $id => $name): ?>
                                <option value="<?= $id ?>" <?= $category['parent_id'] == $id ? 'selected' : '' ?>>
                                    <?= $this->escape($name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="win-help-text">Figyelem: A szülő módosítása az összes alkategóriát és tételt is áthelyezi!</div>
                    </div>
                </div>
            </div>
            
            <!-- Settings -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-cog"></i> Beállítások
                </div>
                <div class="win-panel-body">
                    <div class="win-checkbox">
                        <input type="checkbox" id="is_active" name="is_active" value="1" 
                               <?= $category['is_active'] ? 'checked' : '' ?>>
                        <label for="is_active" class="win-checkbox-label">
                            Aktív
                        </label>
                    </div>
                    <div class="win-help-text">Az inaktív kategóriák és az alattuk lévő tételek nem jelennek meg</div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="win-panel">
                <div class="win-panel-body">
                    <button type="submit" class="win-btn win-btn-primary">
                        <i class="fas fa-save"></i> Mentés
                    </button>
                    <a href="<?= $this->url('categories') ?>" class="win-btn">
                        <i class="fas fa-times"></i> Mégsem
                    </a>
                    
                    <?php if (\Core\Auth::hasPermission('part.delete') && $stats['subcategories'] == 0 && $stats['total_parts'] == 0): ?>
                    <button type="button" class="win-btn win-btn-danger" onclick="deleteCategory()" style="float: right;">
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
                        <span class="win-stats-label">Közvetlen tételek:</span>
                        <span class="win-stats-value"><?= $stats['direct_parts'] ?></span>
                    </div>
                    <div class="win-stats-row">
                        <span class="win-stats-label">Összes tétel:</span>
                        <span class="win-stats-value"><?= $stats['total_parts'] ?></span>
                    </div>
                    <div class="win-stats-row">
                        <span class="win-stats-label">Alkategóriák:</span>
                        <span class="win-stats-value"><?= $stats['subcategories'] ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-tasks"></i> Műveletek
                </div>
                <div class="win-panel-body">
                    <a href="<?= $this->url('categories/' . $category['id'] . '/parts') ?>" 
                       class="win-btn" style="width: 100%; margin-bottom: 4px;">
                        <i class="fas fa-list"></i> Tételek megtekintése
                    </a>
                    
                    <a href="<?= $this->url('categories/create?parent_id=' . $category['id']) ?>" 
                       class="win-btn" style="width: 100%;">
                        <i class="fas fa-folder-plus"></i> Alkategória létrehozása
                    </a>
                </div>
            </div>
            
            <!-- Info -->
            <div class="win-panel">
                <div class="win-panel-header warning">
                    <i class="fas fa-info-circle"></i> Információ
                </div>
                <div class="win-panel-body">
                    <p style="margin: 0 0 8px 0;">
                        <strong>Létrehozva:</strong><br>
                        <?= $this->formatDate($category['created_at'], 'Y.m.d H:i') ?>
                    </p>
                    <?php if ($category['updated_at']): ?>
                    <p style="margin: 0;">
                        <strong>Módosítva:</strong><br>
                        <?= $this->formatDate($category['updated_at'], 'Y.m.d H:i') ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</form>

<?php if (\Core\Auth::hasPermission('part.delete') && $stats['subcategories'] == 0 && $stats['total_parts'] == 0): ?>
<script>
function deleteCategory() {
    if (confirm('Biztosan törli a cikkcsoportot?\n\n"<?= $this->escape($category['name']) ?>"\n\nA művelet nem vonható vissza!')) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= $this->url('categories/' . $category['id'] . '/delete') ?>';
        
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
    const nameInput = document.querySelector('input[name="name"]');
    const codeInput = document.querySelector('input[name="code"]');
    
    // Auto uppercase for code
    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    form.addEventListener('submit', function(event) {
        let isValid = true;
        let errorMessage = '';
        
        // Name validation
        if (nameInput.value.trim() === '') {
            isValid = false;
            errorMessage += 'Kérjük adja meg a kategória nevét!\n';
            nameInput.style.borderColor = '#DC3545';
        } else {
            nameInput.style.borderColor = '#D4D0C8';
        }
        
        // Code validation
        if (codeInput.value && !/^[A-Z0-9_-]+$/.test(codeInput.value)) {
            isValid = false;
            errorMessage += 'A kód csak nagybetűket, számokat, kötőjelet és aláhúzást tartalmazhat!\n';
            codeInput.style.borderColor = '#DC3545';
        } else {
            codeInput.style.borderColor = '#D4D0C8';
        }
        
        if (!isValid) {
            event.preventDefault();
            alert(errorMessage);
        }
    });
});
</script>