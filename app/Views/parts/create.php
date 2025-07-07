<?php
// app/Views/parts/create.php - Windows Desktop Style
$this->setData(['title' => 'Új alkatrész/szolgáltatás']);
?>

<style>
/* Windows Style for Parts Create */
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
    max-width: 800px;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-plus"></i>
        Új alkatrész/szolgáltatás
    </h1>
    <a href="<?= $this->url('parts') ?>" class="win-btn">
        <i class="fas fa-arrow-left"></i> Vissza a listához
    </a>
</div>

<form method="POST" action="<?= $this->url('parts/store') ?>" enctype="multipart/form-data">
    <?= $this->csrfField() ?>
    
    <div class="win-main-container">
        <!-- Basic Information Panel -->
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
                                   placeholder="pl. Olajszűrő, Munkadíj" required>
                        </div>
                    </div>
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Cikkszám</label>
                            <input type="text" name="sku" class="win-form-control" 
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
                <option value="part">Alkatrész</option>
                <option value="service">Szolgáltatás</option>
            </select>
        </div>
    </div>
    <div class="win-form-col">
        <div class="win-form-group">
            <label class="win-form-label">Kategória</label>
            <select name="category_id" class="win-form-select">
                <option value="">— Nincs kategória —</option>
                <?php foreach ($categories as $catId => $catName): ?>
                    <option value="<?= $catId ?>" <?= ($selectedCategoryId ?? '') == $catId ? 'selected' : '' ?>>
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
                   value="db" 
                   placeholder="pl. db, óra, m" required>
            <div class="win-help-text">Az alkatrész vagy szolgáltatás mértékegysége</div>
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
                                       step="0.01" min="0" 
                                       placeholder="0.00" required>
                                <span class="win-input-group-text">Ft</span>
                            </div>
                                                    </div>
                    </div>
<div class="win-form-row">
    <div class="win-form-col">
        <div class="win-form-group">
            <label class="win-form-label">Készlet</label>
            <input type="number" name="stock" class="win-form-control" 
                   value="0" min="0" 
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
                            <div class="win-checkbox" style="margin-top: 6px;">
                                <input type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label for="is_active" class="win-checkbox-label">
                                    Aktív
                                </label>
                            </div>
                            <div class="win-help-text">Megjelenik a választható tételek között</div>
                        </div>
                    </div>
                </div>
                
                <!-- Image upload - SEPARATE from status -->
                <div class="win-form-group">
                    <label class="win-form-label">Kép</label>
                    <input type="file" name="image" class="win-form-control" 
                           accept="image/jpeg,image/png,image/gif">
                    <div class="win-help-text">JPG, PNG vagy GIF formátum (max. 2MB)</div>
                </div>
                            <div class="win-help-text">Megjelenik a választható tételek között</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons Panel -->
        <div class="win-panel">
            <div class="win-panel-body">
                <button type="submit" class="win-btn win-btn-success">
                    <i class="fas fa-save"></i> Mentés
                </button>
                <a href="<?= $this->url('parts') ?>" class="win-btn">
                    <i class="fas fa-times"></i> Mégsem
                </a>
            </div>
        </div>
    </div>
</form>

<script>
// Image preview
const imageInput = document.querySelector('input[name="image"]');
if (imageInput) {
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            // Could add preview functionality here if needed
            console.log('Image selected:', file.name);
        }
    });
}
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
    
    // Auto-focus on first input
    document.querySelector('input[name="name"]').focus();
});
</script>