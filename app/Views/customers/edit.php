<?php
// app/Views/customers/edit.php - Windows Desktop Style
$this->setData(['title' => 'Ügyfél szerkesztése']);
?>

<style>
/* Windows Style for Customer Edit */
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

.win-btn-success {
    background-color: #28A745;
    color: white;
    border-color: #1E7E34;
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

/* Checkbox */
.win-checkbox {
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 10px;
}

.win-checkbox input[type="checkbox"] {
    margin: 0;
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

/* Worksheet list */
.win-worksheet-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.win-worksheet-item {
    padding: 6px;
    border: 1px solid #E8E8E8;
    margin-bottom: 4px;
    background-color: #FAFAFA;
    text-decoration: none;
    color: #000;
    display: block;
    transition: background-color 0.2s;
}

.win-worksheet-item:hover {
    background-color: #E5F1FB;
    border-color: #7DA2CE;
    text-decoration: none;
    color: #000;
}

.win-worksheet-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2px;
}

.win-worksheet-number {
    font-weight: bold;
    color: #0066CC;
}

.win-worksheet-date {
    color: #666;
    font-size: 10px;
}

/* Status badge */
.win-status-badge {
    padding: 2px 6px;
    font-size: 10px;
    font-weight: normal;
    color: white;
    display: inline-block;
}

/* Required field indicator */
.required {
    color: #DC3545;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-user-edit"></i>
        Ügyfél szerkesztése
    </h1>
    <a href="<?= $this->url('customers') ?>" class="win-btn">
        <i class="fas fa-arrow-left"></i> Vissza a listához
    </a>
</div>

<form method="POST" action="<?= $this->url('customers/' . $customer['id'] . '/update') ?>">
    <?= $this->csrfField() ?>
    
    <!-- Main Content -->
    <div class="win-content-wrapper">
        <!-- Left Column -->
        <div class="win-main-content">
            <!-- Basic Information -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-user"></i> Alapadatok
                </div>
                <div class="win-panel-body">
                    <div class="win-form-row">
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Név <span class="required">*</span></label>
                                <input type="text" name="name" class="win-form-control" 
                                       value="<?= $this->escape($customer['name']) ?>" required>
                            </div>
                        </div>
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Telefon <span class="required">*</span></label>
                                <input type="text" name="phone" class="win-form-control" 
                                       value="<?= $this->escape($customer['phone']) ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="win-form-row">
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Email</label>
                                <input type="email" name="email" class="win-form-control" 
                                       value="<?= $this->escape($customer['email'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Prioritás</label>
                                <select name="priority_id" class="win-form-select">
                                    <?php foreach ($priorityTypes as $priority): ?>
                                    <option value="<?= $priority['id'] ?>" 
                                            <?= $customer['priority_id'] == $priority['id'] ? 'selected' : '' ?>>
                                        <?= $this->escape($priority['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Address Information -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-map-marker-alt"></i> Cím adatok
                </div>
                <div class="win-panel-body">
                    <div class="win-form-row">
                        <div class="win-form-col" style="flex: 2;">
                            <div class="win-form-group">
                                <label class="win-form-label">Cím</label>
                                <input type="text" name="address" class="win-form-control" 
                                       value="<?= $this->escape($customer['address'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Irányítószám</label>
                                <input type="text" name="postal_code" class="win-form-control" 
                                       value="<?= $this->escape($customer['postal_code'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="win-form-group">
                        <label class="win-form-label">Város</label>
                        <input type="text" name="city" class="win-form-control" 
                               value="<?= $this->escape($customer['city'] ?? '') ?>">
                    </div>
                </div>
            </div>
            
            <!-- Company Information -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-building"></i> Céges adatok
                </div>
                <div class="win-panel-body">
                    <div class="win-checkbox">
                        <input type="checkbox" id="is_company" name="is_company" 
                               value="1" <?= $customer['is_company'] ? 'checked' : '' ?>
                               onchange="toggleCompanyFields()">
                        <label for="is_company">Céges ügyfél</label>
                    </div>
                    
                    <div id="company-fields" style="<?= $customer['is_company'] ? '' : 'display: none;' ?>">
                        <div class="win-form-row">
                            <div class="win-form-col">
                                <div class="win-form-group">
                                    <label class="win-form-label">Cégnév</label>
                                    <input type="text" name="company_name" class="win-form-control" 
                                           value="<?= $this->escape($customer['company_name'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="win-form-col">
                                <div class="win-form-group">
                                    <label class="win-form-label">Adószám</label>
                                    <input type="text" name="tax_number" class="win-form-control" 
                                           value="<?= $this->escape($customer['tax_number'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="win-form-group">
                            <label class="win-form-label">Céges cím</label>
                            <input type="text" name="company_address" class="win-form-control" 
                                   value="<?= $this->escape($customer['company_address'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Internal Note -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-sticky-note"></i> Belső megjegyzés
                </div>
                <div class="win-panel-body">
                    <textarea name="internal_note" class="win-form-textarea" rows="3" 
                              placeholder="Ez a megjegyzés csak a munkatársak számára látható..."><?= $this->escape($customer['internal_note'] ?? '') ?></textarea>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="win-panel">
                <div class="win-panel-body">
                    <button type="submit" class="win-btn win-btn-primary">
                        <i class="fas fa-save"></i> Mentés
                    </button>
                    <a href="<?= $this->url('customers') ?>" class="win-btn">
                        <i class="fas fa-times"></i> Mégsem
                    </a>
                    
                    <?php if (\Core\Auth::hasPermission('customer.delete') && $stats['total_worksheets'] == 0): ?>
                    <button type="button" class="win-btn win-btn-danger" onclick="deleteCustomer()" style="float: right;">
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
                        <span class="win-stats-label">Munkalapok:</span>
                        <span class="win-stats-value"><?= $stats['total_worksheets'] ?></span>
                    </div>
                    <div class="win-stats-row">
                        <span class="win-stats-label">Aktív munkalapok:</span>
                        <span class="win-stats-value" style="color: #FFC107;">
                            <?= $stats['active_worksheets'] ?>
                        </span>
                    </div>
                    <div class="win-stats-row">
                        <span class="win-stats-label">Eszközök:</span>
                        <span class="win-stats-value"><?= $stats['total_devices'] ?></span>
                    </div>
                    <div class="win-stats-row" style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #D4D0C8;">
                        <span class="win-stats-label">Összes bevétel:</span>
                        <span class="win-stats-value" style="font-size: 14px;">
                            <?= $this->formatPrice($stats['total_revenue']) ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-tools"></i> Műveletek
                </div>
                <div class="win-panel-body">
                    <?php if (\Core\Auth::hasPermission('worksheet.create')): ?>
                    <a href="<?= $this->url('worksheets/create?customer_id=' . $customer['id']) ?>" 
                       class="win-btn win-btn-success" style="width: 100%; margin-bottom: 6px; justify-content: center;">
                        <i class="fas fa-plus"></i> Új munkalap
                    </a>
                    <?php endif; ?>
                    
                    <?php if (\Core\Auth::hasPermission('device.create')): ?>
                    <a href="<?= $this->url('devices/create?customer_id=' . $customer['id']) ?>" 
                       class="win-btn win-btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-bicycle"></i> Új eszköz
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Recent Worksheets -->
            <?php if (!empty($worksheets)): ?>
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-file-alt"></i> Utolsó munkalapok
                </div>
                <div class="win-panel-body">
                    <ul class="win-worksheet-list">
                        <?php foreach (array_slice($worksheets, 0, 5) as $worksheet): ?>
                        <li>
                            <a href="<?= $this->url('worksheets/' . $worksheet['id']) ?>" 
                               class="win-worksheet-item">
                                <div class="win-worksheet-header">
                                    <span class="win-worksheet-number">
                                        <?= $this->escape($worksheet['worksheet_number']) ?>
                                    </span>
                                    <span class="win-worksheet-date">
                                        <?= $this->formatDate($worksheet['created_at'], 'Y.m.d') ?>
                                    </span>
                                </div>
                                <div style="margin-top: 4px;">
                                    <span class="win-status-badge" style="background-color: <?= $worksheet['status_color'] ?>">
                                        <?= $this->escape($worksheet['status_name']) ?>
                                    </span>
                                </div>
                                <div style="margin-top: 2px; font-size: 10px; color: #666;">
                                    <?= $this->escape($worksheet['device_name'] ?? 'Nincs eszköz') ?>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <?php if ($stats['total_worksheets'] > 5): ?>
                    <div style="text-align: center; margin-top: 8px;">
                        <a href="<?= $this->url('worksheets?customer_id=' . $customer['id']) ?>" 
                           class="win-btn" style="font-size: 10px;">
                            Összes megtekintése (<?= $stats['total_worksheets'] ?>)
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
function toggleCompanyFields() {
    const isCompany = document.getElementById('is_company').checked;
    const companyFields = document.getElementById('company-fields');
    companyFields.style.display = isCompany ? 'block' : 'none';
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    form.addEventListener('submit', function(event) {
        const nameInput = document.querySelector('input[name="name"]');
        const phoneInput = document.querySelector('input[name="phone"]');
        
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
        
        // Phone validation
        if (phoneInput.value.trim() === '') {
            isValid = false;
            errorMessage += 'Kérjük adja meg a telefonszámot!\n';
            phoneInput.style.borderColor = '#DC3545';
        } else {
            phoneInput.style.borderColor = '#D4D0C8';
        }
        
        if (!isValid) {
            event.preventDefault();
            alert(errorMessage);
        }
    });
});

<?php if (\Core\Auth::hasPermission('customer.delete') && $stats['total_worksheets'] == 0): ?>
function deleteCustomer() {
    if (confirm('Biztosan törli az ügyfelet?\n\nAz ügyfél véglegesen törlődik!')) {
        // Create and submit delete form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= $this->url('customers/' . $customer['id'] . '/delete') ?>';
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '<?= CSRF_TOKEN_NAME ?>';
        csrfInput.value = '<?= \Core\Auth::csrfToken() ?>';
        
        form.appendChild(csrfInput);
        document.body.appendChild(form);
        form.submit();
    }
}
<?php endif; ?>

// Phone formatting (optional)
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.querySelector('input[name="phone"]');
    
    phoneInput.addEventListener('input', function(e) {
        // Remove non-numeric characters
        let value = e.target.value.replace(/[^\d+\s-]/g, '');
        e.target.value = value;
    });
});
</script>