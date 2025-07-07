<?php
// app/Views/devices/create.php - Windows Desktop Style
$this->setData(['title' => 'Új eszköz']);
?>

<style>
/* Windows Style for Device Create */
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

/* Customer selector enhancement */
.win-customer-search {
    position: relative;
}

.win-customer-search-input {
    width: 100%;
    padding: 4px 6px;
    font-size: 11px;
    font-family: 'Segoe UI', Tahoma, sans-serif;
    border: 1px solid #D4D0C8;
    background-color: #FFFFFF;
}

.win-customer-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 200px;
    overflow-y: auto;
    background-color: #FFFFFF;
    border: 1px solid #D4D0C8;
    border-top: none;
    display: none;
    z-index: 1000;
}

.win-customer-option {
    padding: 4px 8px;
    font-size: 11px;
    cursor: pointer;
    border-bottom: 1px solid #F0F0F0;
}

.win-customer-option:hover {
    background-color: #E5F1FB;
}

.win-customer-option.selected {
    background-color: #0078D7;
    color: white;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-plus"></i>
        Új eszköz
    </h1>
    <a href="<?= $this->url('devices') ?>" class="win-btn">
        <i class="fas fa-arrow-left"></i> Vissza a listához
    </a>
</div>

<form method="POST" action="<?= $this->url('devices/store') ?>">
    <?= $this->csrfField() ?>
    
    <div class="win-main-container">
        <!-- Basic Information Panel -->
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-bicycle"></i> Eszköz adatai
            </div>
            <div class="win-panel-body">
                <div class="win-form-row">
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Ügyfél <span class="required">*</span></label>
                            <select name="customer_id" class="win-form-select" required id="customerSelect">
                                <option value="">-- Válasszon ügyfelet --</option>
                                <?php foreach ($customers ?? [] as $cust): ?>
                                <option value="<?= $cust['id'] ?>" <?= ($customer && $customer['id'] == $cust['id']) ? 'selected' : '' ?>>
                                    <?= $this->escape($cust['name']) ?>
                                    <?php if (!empty($cust['phone'])): ?>
                                        (<?= $this->escape($cust['phone']) ?>)
                                    <?php endif; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="win-help-text">Válassza ki az eszköz tulajdonosát</div>
                        </div>
                    </div>
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Eszköz neve <span class="required">*</span></label>
                            <input type="text" name="name" class="win-form-control" 
                                   placeholder="pl. Kerékpár, Motor" required>
                        </div>
                    </div>
                </div>
                
                <div class="win-form-row">
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Gyári szám</label>
                            <input type="text" name="serial_number" class="win-form-control" 
                                   placeholder="pl. SN123456">
                            <div class="win-help-text">Egyedi azonosító (opcionális)</div>
                        </div>
                    </div>
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Állapot <span class="required">*</span></label>
                            <select name="condition_id" class="win-form-select" required>
                                <option value="">-- Válasszon állapotot --</option>
                                <?php foreach ($deviceConditions as $condition): ?>
                                <option value="<?= $condition['id'] ?>">
                                    <?= $this->escape($condition['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Additional fields -->
                <div class="win-form-row">
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Típus</label>
                            <input type="text" name="type" class="win-form-control" 
                                   placeholder="pl. Mountain bike, Robogó">
                            <div class="win-help-text">Eszköz típusa (opcionális)</div>
                        </div>
                    </div>
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Modell</label>
                            <input type="text" name="model" class="win-form-control" 
                                   placeholder="pl. Trek 820, Vespa LX">
                            <div class="win-help-text">Gyártó és modell (opcionális)</div>
                        </div>
                    </div>
                </div>
                
                <div class="win-form-group">
                    <label class="win-form-label">Megjegyzések</label>
                    <textarea name="notes" class="win-form-textarea" rows="3" 
                              placeholder="További információk, speciális tulajdonságok..."></textarea>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons Panel -->
        <div class="win-panel">
            <div class="win-panel-body">
                <button type="submit" class="win-btn win-btn-success">
                    <i class="fas fa-save"></i> Mentés
                </button>
                <a href="<?= $this->url('devices') ?>" class="win-btn">
                    <i class="fas fa-times"></i> Mégsem
                </a>
                
                <?php if (\Core\Auth::hasPermission('customer.create')): ?>
                <a href="<?= $this->url('customers/create') ?>" class="win-btn" style="float: right;">
                    <i class="fas fa-user-plus"></i> Új ügyfél
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<script>
// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(event) {
        const customerSelect = document.querySelector('select[name="customer_id"]');
        const nameInput = document.querySelector('input[name="name"]');
        const conditionSelect = document.querySelector('select[name="condition_id"]');
        
        let isValid = true;
        let errorMessage = '';
        
        // Customer validation
        if (!customerSelect.value) {
            isValid = false;
            errorMessage += 'Kérjük válasszon ügyfelet!\n';
            customerSelect.style.borderColor = '#DC3545';
        } else {
            customerSelect.style.borderColor = '#D4D0C8';
        }
        
        // Name validation
        if (nameInput.value.trim() === '') {
            isValid = false;
            errorMessage += 'Kérjük adja meg az eszköz nevét!\n';
            nameInput.style.borderColor = '#DC3545';
        } else {
            nameInput.style.borderColor = '#D4D0C8';
        }
        
        // Condition validation
        if (!conditionSelect.value) {
            isValid = false;
            errorMessage += 'Kérjük válassza ki az állapotot!\n';
            conditionSelect.style.borderColor = '#DC3545';
        } else {
            conditionSelect.style.borderColor = '#D4D0C8';
        }
        
        if (!isValid) {
            event.preventDefault();
            alert(errorMessage);
        }
    });
    
    // Auto-focus on first input
    const firstInput = document.querySelector('select[name="customer_id"]');
    if (firstInput && !firstInput.value) {
        firstInput.focus();
    } else {
        document.querySelector('input[name="name"]').focus();
    }
});
</script>