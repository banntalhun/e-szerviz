<?php
// app/Views/devices/edit.php - Windows Desktop Style
$this->setData(['title' => 'Eszköz szerkesztése']);
?>

<style>
/* Windows Style for Device Edit */
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

.win-form-control[readonly] {
    background-color: #F0F0F0;
    color: #666;
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

/* History list */
.win-history-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.win-history-item {
    padding: 6px;
    border: 1px solid #E8E8E8;
    margin-bottom: 4px;
    background-color: #FAFAFA;
    text-decoration: none;
    color: #000;
    display: block;
    transition: background-color 0.2s;
}

.win-history-item:hover {
    background-color: #E5F1FB;
    border-color: #7DA2CE;
    text-decoration: none;
    color: #000;
}

.win-history-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2px;
}

.win-history-number {
    font-weight: bold;
    color: #0066CC;
}

.win-history-date {
    color: #666;
    font-size: 10px;
}

.win-history-details {
    font-size: 10px;
    color: #666;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-edit"></i>
        Eszköz szerkesztése
    </h1>
    <a href="<?= $this->url('devices') ?>" class="win-btn">
        <i class="fas fa-arrow-left"></i> Vissza a listához
    </a>
</div>

<form method="POST" action="<?= $this->url('devices/' . $device['id'] . '/update') ?>">
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
                                <label class="win-form-label">Ügyfél</label>
                                <input type="text" class="win-form-control" 
                                       value="<?= $this->escape($customer['name'] ?? '') ?>" readonly>
                                <div class="win-help-text">Az eszköz tulajdonosa</div>
                            </div>
                        </div>
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Eszköz neve <span class="required">*</span></label>
                                <input type="text" name="name" class="win-form-control" 
                                       value="<?= $this->escape($device['name']) ?>" 
                                       placeholder="pl. Kerékpár, Motor" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="win-form-row">
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Gyári szám</label>
                                <input type="text" name="serial_number" class="win-form-control" 
                                       value="<?= $this->escape($device['serial_number'] ?? '') ?>"
                                       placeholder="pl. SN123456">
                                <div class="win-help-text">Egyedi azonosító (opcionális)</div>
                            </div>
                        </div>
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Állapot <span class="required">*</span></label>
                                <select name="condition_id" class="win-form-select" required>
                                    <?php foreach ($deviceConditions as $condition): ?>
                                    <option value="<?= $condition['id'] ?>" 
                                            <?= $device['condition_id'] == $condition['id'] ? 'selected' : '' ?>>
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
                                       value="<?= $this->escape($device['type'] ?? '') ?>"
                                       placeholder="pl. Mountain bike, Robogó">
                            </div>
                        </div>
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Modell</label>
                                <input type="text" name="model" class="win-form-control" 
                                       value="<?= $this->escape($device['model'] ?? '') ?>"
                                       placeholder="pl. Trek 820, Vespa LX">
                            </div>
                        </div>
                    </div>
                    
                    <div class="win-form-group">
                        <label class="win-form-label">Megjegyzések</label>
                        <textarea name="notes" class="win-form-textarea" rows="3" 
                                  placeholder="További információk, speciális tulajdonságok..."><?= $this->escape($device['notes'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="win-panel">
                <div class="win-panel-body">
                    <button type="submit" class="win-btn win-btn-primary">
                        <i class="fas fa-save"></i> Mentés
                    </button>
                    <a href="<?= $this->url('devices') ?>" class="win-btn">
                        <i class="fas fa-times"></i> Mégsem
                    </a>
                    
                    <?php if (\Core\Auth::hasPermission('device.delete') && empty($worksheetHistory)): ?>
                    <button type="button" class="win-btn win-btn-danger" onclick="deleteDevice()" style="float: right;">
                        <i class="fas fa-trash"></i> Törlés
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Right Sidebar -->
        <div class="win-sidebar">
            <!-- Device Info -->
            <div class="win-panel">
                <div class="win-panel-header info">
                    <i class="fas fa-info"></i> Eszköz információk
                </div>
                <div class="win-panel-body">
                    <div class="win-stats-row">
                        <span class="win-stats-label">Létrehozva:</span>
                        <span class="win-stats-value">
                            <?= $this->formatDate($device['created_at'] ?? 'now', 'Y.m.d H:i') ?>
                        </span>
                    </div>
                    <?php if (!empty($device['updated_at'])): ?>
                    <div class="win-stats-row">
                        <span class="win-stats-label">Módosítva:</span>
                        <span class="win-stats-value">
                            <?= $this->formatDate($device['updated_at'], 'Y.m.d H:i') ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    <div class="win-stats-row" style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #D4D0C8;">
                        <span class="win-stats-label">Munkalapok száma:</span>
                        <span class="win-stats-value"><?= count($worksheetHistory ?? []) ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Worksheet History -->
            <?php if (!empty($worksheetHistory)): ?>
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-history"></i> Munkalap előzmények
                </div>
                <div class="win-panel-body">
                    <ul class="win-history-list">
                        <?php foreach (array_slice($worksheetHistory, 0, 5) as $worksheet): ?>
                        <li>
                            <a href="<?= $this->url('worksheets/' . $worksheet['id']) ?>" 
                               class="win-history-item">
                                <div class="win-history-header">
                                    <span class="win-history-number">
                                        <?= $this->escape($worksheet['worksheet_number']) ?>
                                    </span>
                                    <span class="win-history-date">
                                        <?= $this->formatDate($worksheet['created_at'], 'Y.m.d') ?>
                                    </span>
                                </div>
                                <div class="win-history-details">
                                    Státusz: <?= $this->escape($worksheet['status_name']) ?>
                                    <?php if (!empty($worksheet['total_price'])): ?>
                                    - <?= $this->formatPrice($worksheet['total_price']) ?>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if (count($worksheetHistory) > 5): ?>
                    <div style="text-align: center; margin-top: 8px;">
                        <a href="<?= $this->url('worksheets?device_id=' . $device['id']) ?>" 
                           class="win-btn win-btn-sm">
                            Összes munkalap (<?= count($worksheetHistory) ?>)
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<?php if (\Core\Auth::hasPermission('device.delete') && empty($worksheetHistory)): ?>
<script>
function deleteDevice() {
    if (confirm('Biztosan törli az eszközt?\n\nAz eszköz véglegesen törlődik!')) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= $this->url('devices/' . $device['id'] . '/delete') ?>';
        
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
        const conditionSelect = document.querySelector('select[name="condition_id"]');
        
        let isValid = true;
        let errorMessage = '';
        
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
});
</script>