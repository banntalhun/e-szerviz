<?php
// app/Views/worksheets/create.php
$this->setData(['title' => 'Új munkalap']);
$this->addJs('js/worksheet-create.js');
?>

<div class="row mb-4">
    <div class="col">
        <h1 class="h3">
            <i class="fas fa-file-plus"></i> Új munkalap
        </h1>
    </div>
</div>

<form action="<?= $this->url('worksheets/store') ?>" method="POST" id="worksheetForm">
    <?= $this->csrfField() ?>
    
    <div class="row">
        <!-- Bal oldal -->
        <div class="col-lg-6">
            <!-- Alapadatok -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Alapadatok
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="location_id" class="form-label">Telephely <span class="text-danger">*</span></label>
                            <select class="form-select" id="location_id" name="location_id" required>
                                <?php foreach ($locations as $location): ?>
                                <option value="<?= $location['id'] ?>" <?= $location['is_default'] ? 'selected' : '' ?>>
                                    <?= $this->escape($location['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="technician_id" class="form-label">Szerelő <span class="text-danger">*</span></label>
                            <select class="form-select" id="technician_id" name="technician_id" required>
                                <?php foreach ($technicians as $tech): ?>
                                <option value="<?= $tech['id'] ?>" <?= $tech['id'] == \Core\Auth::id() ? 'selected' : '' ?>>
                                    <?= $this->escape($tech['full_name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="repair_type_id" class="form-label">Javítás típusa <span class="text-danger">*</span></label>
                            <select class="form-select" id="repair_type_id" name="repair_type_id" required>
                                <option value="">Válasszon...</option>
                                <?php foreach ($repairTypes as $type): ?>
                                <option value="<?= $type['id'] ?>">
                                    <?= $this->escape($type['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="status_id" class="form-label">Státusz</label>
                            <select class="form-select" id="status_id" name="status_id">
                                <?php foreach ($statusTypes as $status): ?>
                                <option value="<?= $status['id'] ?>" <?= $status['sort_order'] == 1 ? 'selected' : '' ?>>
                                    <?= $this->escape($status['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ügyfél adatok -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user"></i> Ügyfél adatok
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Ügyfél kiválasztása</label>
                        <div class="input-group">
                            <select class="form-control" id="customer_select" style="width: 100%">
                                <option value="">Új ügyfél vagy keresés...</option>
                                <?php if ($customer): ?>
                                <option value="<?= $customer['id'] ?>" selected>
                                    <?= $this->escape($customer['name']) ?> - <?= $this->escape($customer['phone']) ?>
                                </option>
                                <?php endif; ?>
                            </select>
                            <button class="btn btn-outline-secondary" type="button" onclick="clearCustomer()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <input type="hidden" id="customer_id" name="customer_id" value="<?= $customer['id'] ?? '' ?>">
                    </div>
                    
                    <div id="customerDetails" style="<?= $customer ? '' : 'display:none' ?>">
                        <div class="alert alert-info">
                            <strong>Kiválasztott ügyfél:</strong><br>
                            <span id="selectedCustomerInfo">
                                <?php if ($customer): ?>
                                    <?= $this->escape($customer['name']) ?><br>
                                    Tel: <?= $this->escape($customer['phone']) ?><br>
                                    <?php if ($customer['email']): ?>
                                        Email: <?= $this->escape($customer['email']) ?><br>
                                    <?php endif; ?>
                                    <?php if ($customer['address']): ?>
                                        Cím: <?= $this->escape($customer['address']) ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div id="newCustomerForm" style="<?= $customer ? 'display:none' : '' ?>">
                        <hr>
                        <h6>Új ügyfél adatai</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label">Név <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label">Telefon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="customer_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="customer_email" name="customer_email">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="customer_priority_id" class="form-label">Prioritás</label>
                                <select class="form-select" id="customer_priority_id" name="customer_priority_id">
                                    <?php foreach ($priorityTypes as $priority): ?>
                                    <option value="<?= $priority['id'] ?>">
                                        <?= $this->escape($priority['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="customer_address" class="form-label">Cím</label>
                                <input type="text" class="form-control" id="customer_address" name="customer_address">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="customer_city" class="form-label">Város</label>
                                <input type="text" class="form-control" id="customer_city" name="customer_city" value="Budapest">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="customer_postal_code" class="form-label">Irányítószám</label>
                                <input type="text" class="form-control" id="customer_postal_code" name="customer_postal_code">
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_company" name="is_company" onchange="toggleCompanyFields()">
                                    <label class="form-check-label" for="is_company">
                                        Cég
                                    </label>
                                </div>
                            </div>
                            
                            <div id="companyFields" style="display:none" class="col-12 mt-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="company_name" class="form-label">Cégnév</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name">
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="tax_number" class="form-label">Adószám</label>
                                        <input type="text" class="form-control" id="tax_number" name="tax_number">
                                    </div>
                                    
                                    <div class="col-12 mb-3">
                                        <label for="company_address" class="form-label">Cég címe</label>
                                        <input type="text" class="form-control" id="company_address" name="company_address">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Jobb oldal -->
        <div class="col-lg-6">
            <!-- Eszköz adatok -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bicycle"></i> Eszköz adatok
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Eszköz kiválasztása</label>
                        <select class="form-select" id="device_select">
                            <option value="">Új eszköz...</option>
                            <?php if ($device): ?>
                            <option value="<?= $device['id'] ?>" selected>
                                <?= $this->escape($device['name']) ?> 
                                <?php if ($device['serial_number']): ?>
                                    (<?= $this->escape($device['serial_number']) ?>)
                                <?php endif; ?>
                            </option>
                            <?php endif; ?>
                            <?php foreach ($devices as $dev): ?>
                            <option value="<?= $dev['id'] ?>" <?= $dev['id'] == ($device['id'] ?? 0) ? 'selected' : '' ?>>
                                <?= $this->escape($dev['name']) ?> 
                                <?php if ($dev['serial_number']): ?>
                                    (<?= $this->escape($dev['serial_number']) ?>)
                                <?php endif; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" id="device_id" name="device_id" value="<?= $device['id'] ?? '' ?>">
                    </div>
                    
                    <div id="deviceDetails" style="<?= $device ? '' : 'display:none' ?>">
                        <div class="alert alert-info">
                            <strong>Kiválasztott eszköz:</strong><br>
                            <span id="selectedDeviceInfo">
                                <?php if ($device): ?>
                                    <?= $this->escape($device['name']) ?><br>
                                    <?php if ($device['serial_number']): ?>
                                        Gyári szám: <?= $this->escape($device['serial_number']) ?><br>
                                    <?php endif; ?>
                                    <?php if ($device['accessories']): ?>
                                        Tartozékok: <?= $this->escape($device['accessories']) ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div id="newDeviceForm">
                        <hr>
                        <h6>Új eszköz adatai</h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="device_name" class="form-label">Eszköz neve</label>
                                <input type="text" class="form-control" id="device_name" name="device_name" value="<?= $device['name'] ?? '' ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="serial_number" class="form-label">Gyári szám</label>
                                <input type="text" class="form-control" id="serial_number" name="serial_number" value="<?= $device['serial_number'] ?? '' ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="condition_id" class="form-label">Állapot</label>
                                <select class="form-select" id="condition_id" name="condition_id">
                                    <?php foreach ($deviceConditions as $condition): ?>
                                    <option value="<?= $condition['id'] ?>">
                                        <?= $this->escape($condition['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="purchase_date" class="form-label">Vásárlás dátuma</label>
                                <input type="date" class="form-control" id="purchase_date" name="purchase_date">
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="accessories" class="form-label">Tartozékok</label>
                                <textarea class="form-control" id="accessories" name="accessories" rows="2"><?= $device['accessories'] ?? '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Hibaleírás -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools"></i> Javítás adatai
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Hibaleírás <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?= $this->old('description') ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="internal_note" class="form-label">Belső megjegyzés</label>
                        <textarea class="form-control" id="internal_note" name="internal_note" rows="2"><?= $this->old('internal_note') ?></textarea>
                        <small class="text-muted">Ez nem jelenik meg a munkalapot</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Műveletek -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Munkalap létrehozása
                    </button>
                    <a href="<?= $this->url('worksheets') ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Mégsem
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
function toggleCompanyFields() {
    const isCompany = document.getElementById('is_company').checked;
    document.getElementById('companyFields').style.display = isCompany ? 'block' : 'none';
}

function clearCustomer() {
    $('#customer_select').val(null).trigger('change');
    $('#customer_id').val('');
    $('#customerDetails').hide();
    $('#newCustomerForm').show();
    
    // Eszközök törlése
    $('#device_select').html('<option value="">Új eszköz...</option>');
    $('#device_id').val('');
    $('#deviceDetails').hide();
    $('#newDeviceForm').show();
}
</script>
