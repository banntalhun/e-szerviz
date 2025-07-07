<?php
// app/Views/categories/create.php - Windows Desktop Style
$this->setData(['title' => 'Új cikkcsoport']);
?>

<style>
/* Windows Style for Category Create */
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

.win-btn-success {
    background-color: #28A745;
    color: white;
    border-color: #1E7E34;
}

.win-btn-success:hover {
    background-color: #218838;
    color: white;
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
    font-family: 'Segoe UI', Tahoma, sans-serif;
    cursor: pointer;
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

/* Main content container */
.win-main-container {
    max-width: 600px;
}

/* Tree preview */
.category-preview {
    background-color: #F8F8F8;
    border: 1px solid #E0E0E0;
    padding: 8px;
    margin-top: 8px;
    font-size: 11px;
    font-family: 'Consolas', 'Courier New', monospace;
}

.category-preview-path {
    color: #666;
    margin-bottom: 4px;
}

.category-preview-name {
    font-weight: bold;
    margin-left: 16px;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-folder-plus"></i>
        Új cikkcsoport
    </h1>
    <a href="<?= $this->url('categories') ?>" class="win-btn">
        <i class="fas fa-arrow-left"></i> Vissza a listához
    </a>
</div>

<form method="POST" action="<?= $this->url('categories/store') ?>">
    <?= $this->csrfField() ?>
    
    <div class="win-main-container">
        <!-- Basic Information Panel -->
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-info-circle"></i> Alapadatok
            </div>
            <div class="win-panel-body">
                <div class="win-form-group">
                    <label class="win-form-label">Név <span class="required">*</span></label>
                    <input type="text" name="name" class="win-form-control" 
                           placeholder="pl. Motoralkatrészek, Szűrők" 
                           value="<?= $this->old('name') ?>" required>
                </div>
                
                <div class="win-form-row">
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Kód</label>
                            <input type="text" name="code" class="win-form-control" 
                                   placeholder="pl. MOTOR, SZURO" 
                                   value="<?= $this->old('code') ?>"
                                   style="text-transform: uppercase;">
                            <div class="win-help-text">Egyedi azonosító (opcionális)</div>
                        </div>
                    </div>
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Sorrend</label>
                            <input type="number" name="sort_order" class="win-form-control" 
                                   value="<?= $this->old('sort_order', 0) ?>" 
                                   min="0">
                            <div class="win-help-text">Megjelenítési sorrend</div>
                        </div>
                    </div>
                </div>
                
                <div class="win-form-group">
                    <label class="win-form-label">Leírás</label>
                    <textarea name="description" class="win-form-textarea" rows="3" 
                              placeholder="Kategória részletes leírása..."><?= $this->old('description') ?></textarea>
                </div>
            </div>
        </div>
        
        <!-- Hierarchy Panel -->
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-sitemap"></i> Hierarchia
            </div>
            <div class="win-panel-body">
                <div class="win-form-group">
                    <label class="win-form-label">Szülő kategória</label>
                    <select name="parent_id" class="win-form-select" onchange="updatePreview()">
                        <option value="">— Főkategória —</option>
                        <?php foreach ($categories as $id => $name): ?>
                            <option value="<?= $id ?>" <?= $this->old('parent_id') == $id ? 'selected' : '' ?>>
                                <?= $this->escape($name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="win-help-text">Válassza ki, melyik kategória alá tartozzon</div>
                </div>
                
                <div id="categoryPreview" class="category-preview" style="display: none;">
                    <div class="category-preview-path">Elérési út:</div>
                    <div class="category-preview-name"></div>
                </div>
            </div>
        </div>
        
        <!-- Settings Panel -->
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-cog"></i> Beállítások
            </div>
            <div class="win-panel-body">
                <div class="win-checkbox">
                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                           <?= $this->old('is_active', true) ? 'checked' : '' ?>>
                    <label for="is_active" class="win-checkbox-label">
                        Aktív
                    </label>
                </div>
                <div class="win-help-text">Az aktív kategóriák megjelennek a listákban</div>
            </div>
        </div>
        
        <!-- Action Buttons Panel -->
        <div class="win-panel">
            <div class="win-panel-body">
                <button type="submit" class="win-btn win-btn-success">
                    <i class="fas fa-save"></i> Mentés
                </button>
                <a href="<?= $this->url('categories') ?>" class="win-btn">
                    <i class="fas fa-times"></i> Mégsem
                </a>
            </div>
        </div>
    </div>
</form>

<script>
// Update category preview
function updatePreview() {
    const select = document.querySelector('select[name="parent_id"]');
    const preview = document.getElementById('categoryPreview');
    const nameInput = document.querySelector('input[name="name"]');
    
    if (select.value) {
        const selectedOption = select.options[select.selectedIndex];
        const parentPath = selectedOption.text;
        const newName = nameInput.value || '[új kategória]';
        
        preview.style.display = 'block';
        preview.querySelector('.category-preview-name').innerHTML = 
            parentPath + '<br>' + 
            '<span style="margin-left: ' + ((parentPath.match(/—/g) || []).length + 1) * 16 + 'px">— ' + newName + '</span>';
    } else {
        if (nameInput.value) {
            preview.style.display = 'block';
            preview.querySelector('.category-preview-name').textContent = nameInput.value || '[új kategória]';
        } else {
            preview.style.display = 'none';
        }
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const nameInput = document.querySelector('input[name="name"]');
    const codeInput = document.querySelector('input[name="code"]');
    
    // Auto uppercase for code
    codeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
    
    // Update preview on name change
    nameInput.addEventListener('input', updatePreview);
    
    // Initial preview
    updatePreview();
    
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
        
        // Code validation (only letters, numbers, dash, underscore)
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
    
    // Auto-focus on first input
    nameInput.focus();
});
</script>