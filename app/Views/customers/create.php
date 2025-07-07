<?php
// app/Views/customers/create.php - Windows Desktop Style
$this->setData(['title' => 'Új ügyfél']);
?>

<style>
/* Windows Style for Customer Create */
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

/* Icon styles */
.win-panel-header i,
.win-page-title i {
    font-size: 14px;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-user-plus"></i>
        Új ügyfél
    </h1>
    <a href="<?= $this->url('customers') ?>" class="win-btn">
        <i class="fas fa-arrow-left"></i> Vissza a listához
    </a>
</div>

<form method="POST" action="<?= $this->url('customers/store') ?>">
    <?= $this->csrfField() ?>
    
    <div class="win-main-container">
        <!-- Customer Information Panel -->
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-user"></i> Ügyfél adatai
            </div>
            <div class="win-panel-body">
                <div class="win-form-row">
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Név <span class="required">*</span></label>
                            <input type="text" name="name" class="win-form-control" 
                                   placeholder="pl. Kovács János" required>
                        </div>
                    </div>
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Telefon <span class="required">*</span></label>
                            <input type="text" name="phone" class="win-form-control" 
                                   placeholder="pl. +36 30 123 4567" required>
                            <div class="win-help-text">Elsődleges telefonszám</div>
                        </div>
                    </div>
                </div>
                
                <div class="win-form-row">
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Email</label>
                            <input type="email" name="email" class="win-form-control" 
                                   placeholder="pl. pelda@email.hu">
                            <div class="win-help-text">Opcionális, értesítésekhez</div>
                        </div>
                    </div>
                    <div class="win-form-col">
                        <div class="win-form-group">
                            <label class="win-form-label">Prioritás</label>
                            <select name="priority_id" class="win-form-select">
                                <?php foreach ($priorityTypes as $priority): ?>
                                <option value="<?= $priority['id'] ?>"><?= $this->escape($priority['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="win-help-text">Ügyfél fontossági besorolása</div>
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
                <a href="<?= $this->url('customers') ?>" class="win-btn">
                    <i class="fas fa-times"></i> Mégsem
                </a>
            </div>
        </div>
    </div>
</form>

<script>
// Form validation and formatting
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const phoneInput = document.querySelector('input[name="phone"]');
    
    // Phone number formatting (optional enhancement)
    phoneInput.addEventListener('input', function(e) {
        // Remove non-numeric characters except +, space, and -
        let value = e.target.value.replace(/[^\d\s\-\+]/g, '');
        e.target.value = value;
    });
    
    // Form validation
    form.addEventListener('submit', function(event) {
        const nameInput = document.querySelector('input[name="name"]');
        const emailInput = document.querySelector('input[name="email"]');
        
        let isValid = true;
        let errorMessage = '';
        
        // Name validation
        if (nameInput.value.trim() === '') {
            isValid = false;
            errorMessage += 'Kérjük adja meg az ügyfél nevét!\n';
            nameInput.style.borderColor = '#DC3545';
        } else {
            nameInput.style.borderColor = '#D4D0C8';
        }
        
        // Phone validation
        if (phoneInput.value.trim() === '') {
            isValid = false;
            errorMessage += 'Kérjük adja meg a telefonszámot!\n';
            phoneInput.style.borderColor = '#DC3545';
        } else if (phoneInput.value.trim().length < 6) {
            isValid = false;
            errorMessage += 'Kérjük adjon meg érvényes telefonszámot!\n';
            phoneInput.style.borderColor = '#DC3545';
        } else {
            phoneInput.style.borderColor = '#D4D0C8';
        }
        
        // Email validation (if provided)
        if (emailInput.value.trim() !== '') {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value)) {
                isValid = false;
                errorMessage += 'Kérjük adjon meg érvényes email címet!\n';
                emailInput.style.borderColor = '#DC3545';
            } else {
                emailInput.style.borderColor = '#D4D0C8';
            }
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