<?php
// app/Views/worksheets/edit.php - Windows Desktop Style
$this->setData(['title' => 'Munkalap szerkesztése']);
$this->addJs('js/worksheet-edit.js');
?>

<style>
/* Windows Style for Worksheet Edit */
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
    justify-content: space-between;
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
    margin-top: 6px;
}

.win-checkbox input[type="checkbox"] {
    margin: 0;
}

/* Table */
.win-data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
}

.win-data-table th {
    background-color: #F0F0F0;
    border: 1px solid #D4D0C8;
    padding: 4px 8px;
    text-align: left;
    font-weight: normal;
}

.win-data-table td {
    border: 1px solid #E8E8E8;
    padding: 4px 8px;
}

.win-data-table tbody tr:hover {
    background-color: #F5F5F5;
}

/* Badges */
.win-badge {
    padding: 2px 6px;
    font-size: 10px;
    font-weight: normal;
    color: white;
    display: inline-block;
}

/* Sidebar specific */
.win-sidebar .win-panel-header.info {
    background-color: #17A2B8;
    color: white;
}

.win-sidebar .win-panel-header.primary {
    background-color: #0054E3;
    color: white;
}

/* Attachments List */
.win-attachment-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.win-attachment-item {
    padding: 6px 8px;
    border: 1px solid #E8E8E8;
    margin-bottom: 4px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #FAFAFA;
}

.win-attachment-item:hover {
    background-color: #F0F0F0;
}

.win-attachment-actions {
    display: flex;
    gap: 4px;
}

/* Small buttons */
.win-btn-sm {
    padding: 2px 6px;
    font-size: 10px;
}

/* Modal styles */
.win-modal-header {
    font-size: 12px;
}

.win-modal-body {
    font-size: 11px;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-edit"></i>
        Munkalap szerkesztése: <?= $this->escape($worksheet['worksheet_number']) ?>
    </h1>
    <a href="<?= $this->url('worksheets/' . $worksheet['id']) ?>" class="win-btn">
        <i class="fas fa-arrow-left"></i> Vissza a munkalaphoz
    </a>
</div>

<form method="POST" action="<?= $this->url('worksheets/' . $worksheet['id'] . '/update') ?>">
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
                                <label class="win-form-label">Telephely</label>
                                <select name="location_id" class="win-form-select">
                                    <?php foreach ($locations as $location): ?>
                                    <option value="<?= $location['id'] ?>" <?= $worksheet['location_id'] == $location['id'] ? 'selected' : '' ?>>
                                        <?= $this->escape($location['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Szerelő</label>
                                <select name="technician_id" class="win-form-select">
                                    <?php foreach ($technicians as $tech): ?>
                                    <option value="<?= $tech['id'] ?>" <?= $worksheet['technician_id'] == $tech['id'] ? 'selected' : '' ?>>
                                        <?= $this->escape($tech['full_name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="win-form-row">
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Javítás típusa</label>
                                <select name="repair_type_id" class="win-form-select">
                                    <?php foreach ($repairTypes as $type): ?>
                                    <option value="<?= $type['id'] ?>" <?= $worksheet['repair_type_id'] == $type['id'] ? 'selected' : '' ?>>
                                        <?= $this->escape($type['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Státusz</label>
                                <select name="status_id" class="win-form-select">
                                    <?php foreach ($statusTypes as $status): ?>
                                    <option value="<?= $status['id'] ?>" <?= $worksheet['status_id'] == $status['id'] ? 'selected' : '' ?>>
                                        <?= $this->escape($status['name']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="win-form-row">
                        <div class="win-form-col">
                            <div class="win-form-group">
                                <label class="win-form-label">Vállalási határidő</label>
                                <input type="date" name="warranty_date" class="win-form-control" value="<?= $worksheet['warranty_date'] ?>">
                            </div>
                        </div>
                        <div class="win-form-col">
                            <div class="win-checkbox">
                                <input type="checkbox" name="is_paid" id="is_paid" value="1" <?= $worksheet['is_paid'] ? 'checked' : '' ?>>
                                <label for="is_paid">Kifizetve</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Description -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-tools"></i> Hibaleírás
                </div>
                <div class="win-panel-body">
                    <div class="win-form-group">
                        <label class="win-form-label">Hibaleírás</label>
                        <textarea name="description" class="win-form-textarea" rows="4" required><?= $this->escape($worksheet['description']) ?></textarea>
                    </div>
                    
                    <div class="win-form-group">
                        <label class="win-form-label">Belső megjegyzés</label>
                        <textarea name="internal_note" class="win-form-textarea" rows="2"><?= $this->escape($worksheet['internal_note'] ?? '') ?></textarea>
                        <small style="color: #666;">Ez nem jelenik meg a nyomtatott munkalapon</small>
                    </div>
                </div>
            </div>
            
            <!-- Costs/Items -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <span><i class="fas fa-coins"></i> Költségek / Tételek</span>
                    <button type="button" class="win-btn win-btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="fas fa-plus"></i> Tétel hozzáadása
                    </button>
                </div>
                <div class="win-panel-body">
                    <?php if (!empty($items)): ?>
                    <table class="win-data-table">
                        <thead>
                            <tr>
                                <th>Megnevezés</th>
                                <th>Típus</th>
                                <th style="text-align: center;">Menny.</th>
                                <th style="text-align: right;">Egységár</th>
                                <th style="text-align: center;">Kedv. %</th>
                                <th style="text-align: center;">Belső</th>
                                <th style="text-align: right;">Összesen</th>
                                <th style="text-align: center;">Művelet</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            <?php foreach ($items as $item): ?>
                            <tr data-item-id="<?= $item['id'] ?>">
                                <td><?= $this->escape($item['name']) ?></td>
                                <td>
                                    <?php if ($item['type'] == 'part'): ?>
                                        <span class="win-badge" style="background-color: #17A2B8;">Alkatrész</span>
                                    <?php else: ?>
                                        <span class="win-badge" style="background-color: #28A745;">Szolgáltatás</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;"><?= $item['quantity'] ?> <?= $this->escape($item['unit']) ?></td>
                                <td style="text-align: right;"><?= $this->formatPrice($item['unit_price']) ?></td>
                                <td style="text-align: center;">
                                    <?= $item['discount'] > 0 ? $item['discount'] . '%' : '-' ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($item['is_internal']): ?>
                                        <span class="win-badge" style="background-color: #FFC107;" title="Belső tétel">
                                            <i class="fas fa-eye-slash"></i>
                                        </span>
                                    <?php else: ?>
                                        <span class="win-badge" style="background-color: #6C757D;" title="Publikus">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;"><?= $this->formatPrice($item['total_price']) ?></td>
                                <td style="text-align: center;">
                                    <button type="button" class="win-btn win-btn-sm delete-item" 
                                            data-id="<?= $item['id'] ?>" 
                                            title="Törlés"
                                            style="background-color: #DC3545; color: white;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="border-top: 2px solid #D4D0C8;">
                                <th colspan="6" style="text-align: right;">Összesen:</th>
                                <th style="text-align: right;" id="totalPrice"><?= $this->formatPrice($worksheet['total_price']) ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                    <?php else: ?>
                    <p style="color: #666; margin: 0;">Még nincsenek tételek hozzáadva a munkalaphoz.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Save Buttons -->
            <div class="win-panel">
                <div class="win-panel-body">
                    <button type="submit" class="win-btn win-btn-primary">
                        <i class="fas fa-save"></i> Mentés
                    </button>
                    <a href="<?= $this->url('worksheets/' . $worksheet['id']) ?>" class="win-btn">
                        <i class="fas fa-times"></i> Mégsem
                    </a>
                </div>
            </div>
        </div>
        <!-- Right Sidebar -->
        <div class="win-sidebar">
            <!-- Customer -->
            <div class="win-panel">
                <div class="win-panel-header info">
                    <i class="fas fa-user"></i> Ügyfél
                </div>
                <div class="win-panel-body">
                    <div style="margin-bottom: 8px;">
                        <strong><?= $this->escape($worksheet['customer_name']) ?></strong>
                    </div>
                    <div style="margin-bottom: 4px;">
                        <i class="fas fa-phone"></i> <?= $this->escape($worksheet['customer_phone']) ?>
                    </div>
                    <?php if ($worksheet['customer_email']): ?>
                    <div style="margin-bottom: 4px;">
                        <i class="fas fa-envelope"></i> <?= $this->escape($worksheet['customer_email']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($worksheet['priority_name']): ?>
                    <div>
                        <span class="win-badge" style="background-color: <?= $worksheet['priority_color'] ?>">
                            <?= $this->escape($worksheet['priority_name']) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Device -->
            <?php if ($worksheet['device_name']): ?>
            <div class="win-panel">
                <div class="win-panel-header">
                    <span><i class="fas fa-bicycle"></i> Eszköz</span>
                    <button type="button" class="win-btn win-btn-sm" onclick="toggleDeviceEdit()">
                        <i class="fas fa-edit"></i> Szerkesztés
                    </button>
                </div>
                <div class="win-panel-body">
                    <!-- Display View -->
                    <div id="deviceDisplay">
                        <div style="margin-bottom: 4px;"><strong><?= $this->escape($worksheet['device_name']) ?></strong></div>
                        <?php if ($worksheet['serial_number']): ?>
                        <div style="margin-bottom: 4px;">Gyári szám: <?= $this->escape($worksheet['serial_number']) ?></div>
                        <?php endif; ?>
                        <?php if ($worksheet['accessories']): ?>
                        <div>Tartozékok: <?= nl2br($this->escape($worksheet['accessories'])) ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Edit Form -->
                    <div id="deviceEdit" style="display: none;">
                        <div class="win-form-group">
                            <label class="win-form-label">Eszköz neve</label>
                            <input type="text" class="win-form-control" id="device_name" 
                                   value="<?= $this->escape($worksheet['device_name']) ?>">
                        </div>
                        <div class="win-form-group">
                            <label class="win-form-label">Gyári szám</label>
                            <input type="text" class="win-form-control" id="device_serial_number" 
                                   value="<?= $this->escape($worksheet['serial_number'] ?? '') ?>">
                        </div>
                        <div class="win-form-group">
                            <label class="win-form-label">Tartozékok</label>
                            <textarea class="win-form-textarea" id="device_accessories" rows="2"><?= $this->escape($worksheet['accessories'] ?? '') ?></textarea>
                        </div>
                        <div style="text-align: right;">
                            <button type="button" class="win-btn win-btn-sm" style="background-color: #28A745; color: white;" onclick="saveDeviceData()">
                                <i class="fas fa-save"></i> Mentés
                            </button>
                            <button type="button" class="win-btn win-btn-sm" onclick="cancelDeviceEdit()">
                                <i class="fas fa-times"></i> Mégsem
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Attachments -->
            <div class="win-panel">
                <div class="win-panel-header">
                    <i class="fas fa-paperclip"></i> Csatolmányok
                </div>
                <div class="win-panel-body">
                    <!-- Existing Attachments -->
                    <?php if (!empty($attachments)): ?>
                    <ul class="win-attachment-list" id="attachmentsList">
                        <?php foreach ($attachments as $attachment): ?>
                        <?php 
                        $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $attachment['original_name']);
                        ?>
                        <li class="win-attachment-item" data-attachment-id="<?= $attachment['id'] ?>">
                            <span>
                                <?php if ($isImage): ?>
                                    <i class="fas fa-image" style="color: #17A2B8;"></i>
                                <?php else: ?>
                                    <i class="fas fa-file"></i>
                                <?php endif; ?>
                                <?= $this->escape($attachment['original_name']) ?>
                            </span>
                            <div class="win-attachment-actions">
                                <?php if ($isImage): ?>
                                    <a href="<?= $this->url('worksheets/' . $worksheet['id'] . '/attachment/' . $attachment['id'] . '/download?preview=1') ?>" 
                                       class="win-btn win-btn-sm preview-image" 
                                       data-title="<?= $this->escape($attachment['original_name']) ?>"
                                       style="background-color: #28A745; color: white;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="<?= $this->url('worksheets/' . $worksheet['id'] . '/attachment/' . $attachment['id'] . '/download') ?>" 
                                   class="win-btn win-btn-sm"
                                   style="background-color: #17A2B8; color: white;">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button type="button" class="win-btn win-btn-sm delete-attachment" 
                                        data-id="<?= $attachment['id'] ?>"
                                        style="background-color: #DC3545; color: white;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p style="color: #666; margin-bottom: 8px;" id="noAttachmentsText">Még nincsenek csatolmányok.</p>
                    <ul class="win-attachment-list" id="attachmentsList" style="display: none;"></ul>
                    <?php endif; ?>
                    
                    <!-- File Upload -->
                    <div style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #E8E8E8;">
                        <label class="win-form-label">Új fájl feltöltése</label>
                        <div style="display: flex; gap: 4px;">
                            <input type="file" class="win-form-control" id="attachmentFile" 
                                   accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.xls,.xlsx"
                                   style="flex: 1;">
                            <button type="button" class="win-btn" id="uploadBtn" style="white-space: nowrap;">
                                <i class="fas fa-upload"></i> Feltöltés
                            </button>
                        </div>
                        <small style="color: #666; display: block; margin-top: 4px;">
                            PDF, JPG, PNG, WEBP, DOC, DOCX, XLS, XLSX. Max: 10MB
                        </small>
                    </div>
                    
                    <!-- Upload Progress -->
                    <div id="uploadProgress" style="display: none; margin-top: 8px;">
                        <div style="background-color: #E8E8E8; height: 8px; border: 1px solid #D4D0C8;">
                            <div class="progress-bar" style="background-color: #0054E3; height: 100%; width: 0%; transition: width 0.3s;"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Summary -->
            <div class="win-panel">
                <div class="win-panel-header primary">
                    <i class="fas fa-calculator"></i> Összesítő
                </div>
                <div class="win-panel-body">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Alkatrészek:</span>
                        <span id="partCount">
                            <?php 
                            $partCount = count(array_filter($items, fn($i) => $i['type'] == 'part' && !$i['is_internal']));
                            echo $partCount;
                            ?>
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Szolgáltatások:</span>
                        <span id="serviceCount">
                            <?php 
                            $serviceCount = count(array_filter($items, fn($i) => $i['type'] == 'service' && !$i['is_internal']));
                            echo $serviceCount;
                            ?>
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span>Belső tételek:</span>
                        <span id="internalCount">
                            <?php 
                            $internalCount = count(array_filter($items, fn($i) => $i['is_internal']));
                            echo $internalCount;
                            ?>
                        </span>
                    </div>
                    <div style="display: flex; justify-content: space-between; border-top: 1px solid #D4D0C8; padding-top: 8px;">
                        <strong>Végösszeg:</strong>
                        <strong style="font-size: 14px;" id="summaryTotal"><?= $this->formatPrice($worksheet['total_price']) ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addItemForm">
                <div class="modal-header">
                    <h5 class="modal-title win-modal-header">
                        <i class="fas fa-plus"></i> Tétel hozzáadása
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body win-modal-body">
                    <input type="hidden" id="worksheet_id" value="<?= $worksheet['id'] ?>">
                    
                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="existing-tab" data-bs-toggle="tab" 
                                    data-bs-target="#existing-panel" type="button" role="tab"
                                    style="font-size: 11px;">
                                <i class="fas fa-list"></i> Meglévő tétel
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="new-tab" data-bs-toggle="tab" 
                                    data-bs-target="#new-panel" type="button" role="tab"
                                    style="font-size: 11px;">
                                <i class="fas fa-plus-circle"></i> Új tétel
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Existing Item Panel -->
                        <div class="tab-pane fade show active" id="existing-panel" role="tabpanel">
                            <div class="win-form-group">
                                <label class="win-form-label">Alkatrész/Szolgáltatás</label>
                                <select class="win-form-select" id="part_service_select">
                                    <option value="">Válasszon vagy kezdjen el gépelni...</option>
                                    <?php foreach ($partsServices as $item): ?>
                                    <option value="<?= $item['id'] ?>" 
                                            data-price="<?= $item['price'] ?>"
                                            data-unit="<?= $this->escape($item['unit']) ?>"
                                            data-type="<?= $item['type'] ?>"
                                            data-name="<?= $this->escape($item['name']) ?>">
                                        <?= $this->escape($item['name']) ?>
                                        <?php if ($item['sku']): ?>
                                            (<?= $this->escape($item['sku']) ?>)
                                        <?php endif; ?>
                                        - <?= $this->formatPrice($item['price']) ?>/<?= $this->escape($item['unit']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="win-form-row">
                                <div class="win-form-col">
                                    <div class="win-form-group">
                                        <label class="win-form-label">Mennyiség</label>
                                        <input type="text" class="win-form-control" id="item_quantity" 
                                               pattern="[0-9.]*" inputmode="decimal"
                                               value="1" min="0.01" step="0.01" required>
                                    </div>
                                </div>
                                <div class="win-form-col">
                                    <div class="win-form-group">
                                        <label class="win-form-label">Egységár</label>
                                        <div style="display: flex; gap: 4px;">
                                            <input type="text" class="win-form-control" id="item_unit_price" 
                                                   pattern="[0-9]*" inputmode="numeric"
                                                   min="0" step="0.01" required>
                                            <span style="padding: 4px;">Ft/<span id="item_unit">db</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="win-form-col">
                                    <div class="win-form-group">
                                        <label class="win-form-label">Kedvezmény %</label>
                                        <input type="text" class="win-form-control" id="item_discount" 
                                               pattern="[0-9.]*" inputmode="decimal"
                                               value="0" min="0" max="100" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- New Item Panel -->
                        <div class="tab-pane fade" id="new-panel" role="tabpanel">
                            <div class="win-form-row">
                                <div class="win-form-col">
                                    <div class="win-form-group">
                                        <label class="win-form-label">Megnevezés *</label>
                                        <input type="text" class="win-form-control" id="new_item_name" 
                                               placeholder="pl. Kerék javítás">
                                    </div>
                                </div>
                                <div class="win-form-col">
                                    <div class="win-form-group">
                                        <label class="win-form-label">Cikkszám</label>
                                        <input type="text" class="win-form-control" id="new_item_sku" 
                                               placeholder="pl. SRV-001">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="win-form-row">
                                <div class="win-form-col">
                                    <div class="win-form-group">
                                        <label class="win-form-label">Típus *</label>
                                        <select class="win-form-select" id="new_item_type">
                                            <option value="service">Szolgáltatás</option>
                                            <option value="part">Alkatrész</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="win-form-col">
                                    <div class="win-form-group">
                                        <label class="win-form-label">Mértékegység *</label>
                                        <input type="text" class="win-form-control" id="new_item_unit" 
                                               value="db" placeholder="pl. db, óra, m">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="win-form-group">
                                <label class="win-form-label">Leírás</label>
                                <textarea class="win-form-textarea" id="new_item_description" rows="2"></textarea>
                            </div>
                            
                            <hr style="border-color: #D4D0C8;">
                            
                            <div class="win-form-row">
                                <div class="win-form-col">
                                    <div class="win-form-group">
                                        <label class="win-form-label">Mennyiség *</label>
                                        <input type="number" class="win-form-control" id="new_item_quantity" 
                                               value="1" min="0.01" step="0.01">
                                    </div>
                                </div>
                                <div class="win-form-col">
                                    <div class="win-form-group">
                                        <label class="win-form-label">Egységár *</label>
                                        <div style="display: flex; gap: 4px;">
                                            <input type="number" class="win-form-control" id="new_item_price" 
                                                   min="0" step="0.01" placeholder="0">
                                            <span style="padding: 4px;">Ft/<span id="new_item_unit_display">db</span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="win-form-col">
                                    <div class="win-form-group">
                                        <label class="win-form-label">Kedvezmény %</label>
                                        <input type="number" class="win-form-control" id="new_item_discount" 
                                               value="0" min="0" max="100" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Common Part -->
                    <div class="win-form-row" style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #D4D0C8;">
                        <div class="win-form-col">
                            <label class="win-form-label">Összesen:</label>
                            <strong style="font-size: 16px;" id="item_total">0 Ft</strong>
                        </div>
                        <div class="win-form-col">
                            <div class="win-checkbox" style="margin-top: 12px;">
                                <input type="checkbox" id="is_internal" value="1">
                                <label for="is_internal">
                                    <i class="fas fa-eye-slash"></i> Belső tétel
                                    <small style="display: block; color: #666; margin-left: 20px;">
                                        Nem jelenik meg a nyomtatott munkalapon
                                    </small>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 11px;">
                        <i class="fas fa-times"></i> Mégsem
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary" style="font-size: 11px;">
                        <i class="fas fa-plus"></i> Hozzáadás
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title win-modal-header" id="imagePreviewTitle">Kép előnézet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" alt="Előnézet" class="img-fluid">
            </div>
            <div class="modal-footer">
                <a id="downloadImageBtn" href="" class="btn btn-sm btn-primary" download style="font-size: 11px;">
                    <i class="fas fa-download"></i> Letöltés
                </a>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 11px;">Bezárás</button>
            </div>
        </div>
    </div>
</div>

<!-- CSRF token a JS számára -->
<meta name="csrf-token" content="<?= \Core\Auth::csrfToken() ?>">

<script>
// Globális változók
const worksheetId = <?= $worksheet['id'] ?>;
const baseUrl = '<?= APP_URL ?>';
</script>

<!-- Egy script blokk az összes kóddal -->
<script>
// Várunk, amíg a jQuery és minden függőség betöltődik
document.addEventListener('DOMContentLoaded', function() {
    // Várunk egy kicsit hogy biztosan minden betöltődjön
    setTimeout(function() {
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
            initializeWorksheetEdit();
        } else {
            // Ha még mindig nincs jQuery, próbáljuk újra
            var checkInterval = setInterval(function() {
                if (typeof jQuery !== 'undefined') {
                    clearInterval(checkInterval);
                    initializeWorksheetEdit();
                }
            }, 100);
        }
    }, 500);
});

function initializeWorksheetEdit() {
    jQuery(document).ready(function($) {
        console.log('Worksheet edit scripts initialized!');
        console.log('BaseURL:', baseUrl);
        console.log('WorksheetId:', worksheetId);
        
        // Oldal betöltésekor frissítsük az összegeket
        updateInitialTotals();
        
        // Select2 inicializálás
        if ($.fn.select2) {
            $('#part_service_select').select2({
                theme: 'bootstrap-5',
                language: 'hu',
                placeholder: 'Válasszon vagy kezdjen el gépelni...',
                allowClear: true,
                dropdownParent: $('#addItemModal'),
                width: '100%'
            });
        }
        
        // Fájl feltöltés
        $('#uploadBtn').on('click', function() {
            console.log('Upload button clicked');
            var fileInput = $('#attachmentFile')[0];
            
            if (!fileInput.files.length) {
                Swal.fire('Hiba!', 'Válasszon ki egy fájlt!', 'error');
                return;
            }
            
            var file = fileInput.files[0];
            var maxSize = 10 * 1024 * 1024; // 10MB
            
            console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);
            
            // Méret ellenőrzés
            if (file.size > maxSize) {
                Swal.fire('Hiba!', 'A fájl mérete nem lehet nagyobb 10MB-nál!', 'error');
                return;
            }
            
            // Formátum ellenőrzés
            var allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/webp',
                               'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                               'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            
            var allowedExtensions = /\.(pdf|jpg|jpeg|png|webp|doc|docx|xls|xlsx)$/i;
            
            if (!allowedTypes.includes(file.type) && !allowedExtensions.test(file.name)) {
                console.log('Invalid file type:', file.type);
                Swal.fire('Hiba!', 'Nem támogatott fájlformátum!', 'error');
                return;
            }
            
            // FormData készítése
            var formData = new FormData();
            formData.append('file', file);
            formData.append('csrf_token', $('meta[name="csrf-token"]').attr('content'));
            
            // Progress bar megjelenítése
            $('#uploadProgress').show();
            $('#uploadBtn').prop('disabled', true);
            
            var uploadUrl = baseUrl + '/worksheets/' + worksheetId + '/upload';
            console.log('Starting upload to:', uploadUrl);
            
            // AJAX feltöltés
            $.ajax({
                url: uploadUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            var percentComplete = (e.loaded / e.total) * 100;
                            $('#uploadProgress .progress-bar').css('width', percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    console.log('Upload success:', response);
                    if (response.success) {
                        // Sikeres feltöltés
                        addAttachmentToList(response);
                        
                        // Reset
                        $('#attachmentFile').val('');
                        $('#uploadProgress').hide();
                        $('#uploadProgress .progress-bar').css('width', '0%');
                        $('#uploadBtn').prop('disabled', false);
                        
                        // Üzenet
                        Swal.fire({
                            icon: 'success',
                            title: 'Fájl feltöltve!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire('Hiba!', response.error || 'Feltöltés sikertelen!', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Upload error:', status, error);
                    console.error('Response:', xhr.responseText);
                    var message = 'Hiba történt a feltöltés során!';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        message = xhr.responseJSON.error;
                    }
                    Swal.fire('Hiba!', message, 'error');
                },
                complete: function() {
                    $('#uploadProgress').hide();
                    $('#uploadProgress .progress-bar').css('width', '0%');
                    $('#uploadBtn').prop('disabled', false);
                }
            });
        });
        
        // Csatolmány hozzáadása a listához
        function addAttachmentToList(attachment) {
            // Elrejtjük a "nincs csatolmány" szöveget
            $('#noAttachmentsText').hide();
            $('#attachmentsList').show();
            
            // Ellenőrizzük, hogy kép-e
            var isImage = /\.(jpg|jpeg|png|gif|webp)$/i.test(attachment.original_name);
            
            // Új elem HTML
            var html = '<li class="list-group-item d-flex justify-content-between align-items-center" data-attachment-id="' + attachment.id + '">' +
                      '<span>';
            
            if (isImage) {
                html += '<i class="fas fa-image text-primary"></i> ';
            } else {
                html += '<i class="fas fa-file"></i> ';
            }
            
            html += escapeHtml(attachment.original_name) + '</span><div>';
            
            if (isImage) {
                // Használjuk a globális baseUrl-t
                html += '<a href="' + baseUrl + '/worksheets/' + worksheetId + '/attachment/' + attachment.id + '/download?preview=1" ' +
                        'class="btn btn-sm btn-success preview-image" data-title="' + escapeHtml(attachment.original_name) + '">' +
                        '<i class="fas fa-eye"></i></a> ';
            }
            
            // Használjuk a globális baseUrl-t
            html += '<a href="' + baseUrl + '/worksheets/' + worksheetId + '/attachment/' + attachment.id + '/download" ' +
                    'class="btn btn-sm btn-info" target="_blank"><i class="fas fa-download"></i></a> ' +
                    '<button type="button" class="btn btn-sm btn-danger delete-attachment" data-id="' + attachment.id + '">' +
                    '<i class="fas fa-trash"></i></button>' +
                    '</div></li>';
            
            $('#attachmentsList').append(html);
        }

        // Csatolmány törlése
        $(document).on('click', '.delete-attachment', function() {
            var attachmentId = $(this).data('id');
            var listItem = $(this).closest('li');
            
            Swal.fire({
                title: 'Biztosan törli?',
                text: 'A csatolmány véglegesen törlődik!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Igen, törlöm!',
                cancelButtonText: 'Mégsem'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        // Használjuk a globális baseUrl-t
                        url: baseUrl + '/worksheets/' + worksheetId + '/attachment/' + attachmentId + '/delete',
                        type: 'POST',
                        data: {
                            csrf_token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                // Eltávolítjuk a listából
                                listItem.fadeOut(300, function() {
                                    $(this).remove();
                                    
                                    // Ha nincs több csatolmány
                                    if ($('#attachmentsList li').length === 0) {
                                        $('#attachmentsList').hide();
                                        $('#noAttachmentsText').show();
                                    }
                                });
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Csatolmány törölve!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        },
                        error: function() {
                            Swal.fire('Hiba!', 'A törlés sikertelen!', 'error');
                        }
                    });
                }
            });
        });
        
        // Kép előnézet
        $(document).on('click', '.preview-image', function(e) {
            e.preventDefault();
            var imageUrl = $(this).attr('href');
            var imageTitle = $(this).data('title');
            
            $('#imagePreviewTitle').text(imageTitle);
            $('#previewImage').attr('src', imageUrl);
            $('#downloadImageBtn').attr('href', imageUrl.replace('?preview=1', ''));
            $('#imagePreviewModal').modal('show');
        });
        
        // HTML escape funkció
        function escapeHtml(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
        
        // Kezdeti összegek frissítése
        function updateInitialTotals() {
            var total = 0;
            var partCount = 0;
            var serviceCount = 0;
            var internalCount = 0;
            
            $('#itemsTableBody tr').each(function() {
                if ($(this).find('td').length > 1) {
                    var totalText = $(this).find('td:eq(6)').text();
                    var itemTotal = parseInt(totalText.replace(/[^\d]/g, '')) || 0;
                    total += itemTotal;
                    
                    var isInternal = $(this).find('td:eq(5) .bg-warning').length > 0;
                    if (isInternal) {
                        internalCount++;
                    } else {
                        if ($(this).find('td:eq(1) .bg-info').length > 0) {
                            partCount++;
                        } else if ($(this).find('td:eq(1) .bg-success').length > 0) {
                            serviceCount++;
                        }
                    }
                }
            });
            
            if (total > 0) {
                $('#totalPrice').text(formatPrice(total));
                $('#summaryTotal').text(formatPrice(total));
                $('#partCount').text(partCount);
                $('#serviceCount').text(serviceCount);
                $('#internalCount').text(internalCount);
            }
        }
        
        // Ár formázás
        function formatPrice(price) {
            return new Intl.NumberFormat('hu-HU', {
                style: 'currency',
                currency: 'HUF',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(price);
        }
        
        // Amikor kiválasztanak egy elemet
        $('#part_service_select').on('change', function() {
            var selectedOption = $(this).find(':selected');
            
            if (selectedOption.val()) {
                var price = selectedOption.attr('data-price');
                var unit = selectedOption.attr('data-unit');
                
                if (price) {
                    var priceValue = parseFloat(price);
                    var intPrice = Math.round(priceValue);
                    
                    $('#item_unit_price').val(intPrice);
                    $('#item_unit').text(unit || 'db');
                    calculateItemTotal();
                }
            } else {
                $('#item_unit_price').val('');
                $('#item_unit').text('db');
                $('#item_total').text('0 Ft');
            }
        });
        
        // Mennyiség, ár, kedvezmény változásakor újraszámolás
        $('#item_quantity, #item_unit_price, #item_discount').on('input', function() {
            calculateItemTotal();
        });
        
        // Új tétel mezők
        $('#new_item_quantity, #new_item_price, #new_item_discount').on('input', function() {
            calculateItemTotal();
        });
        
        // Tab váltás
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("data-bs-target");
            if (target === '#new-panel') {
                $('#new_item_unit').trigger('input');
            }
            calculateItemTotal();
        });
        
        // Mértékegység változás új tételnél
        $('#new_item_unit').on('input', function() {
            $('#new_item_unit_display').text($(this).val() || 'db');
        });
        
        // Modal megnyitásakor
        $('#addItemModal').on('shown.bs.modal', function () {
            // Ha már van kiválasztott elem, trigger a change eseményt
            var currentValue = $('#part_service_select').val();
            if (currentValue) {
                $('#part_service_select').trigger('change');
            }
        });
        
        // Modal bezárásakor reset
        $('#addItemModal').on('hidden.bs.modal', function () {
            resetItemForm();
            $('#existing-tab').tab('show');
        });
        
        // Form submit
        $('#addItemForm').on('submit', function(e) {
            e.preventDefault();
            
            var activeTab = $('.tab-pane.active').attr('id');
            if (activeTab === 'existing-panel') {
                addExistingItem();
            } else {
                addNewItem();
            }
        });
        
        // Tétel törlése
        $(document).on('click', '.delete-item', function() {
            var itemId = $(this).data('id');
            deleteItem(itemId);
        });
        
        // Loading funkciók
        window.showLoading = function() {
            // Egyszerű loading - SweetAlert2 használata
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Feldolgozás...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        };
        
        window.hideLoading = function() {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
        };
        
        // Tétel összeg számítás
        window.calculateItemTotal = function() {
            var activeTab = $('.tab-pane.active').attr('id');
            var quantity, unitPrice, discount;
            
            if (activeTab === 'existing-panel') {
                quantity = parseFloat($('#item_quantity').val()) || 0;
                unitPrice = parseFloat($('#item_unit_price').val()) || 0;
                discount = parseFloat($('#item_discount').val()) || 0;
            } else {
                quantity = parseFloat($('#new_item_quantity').val()) || 0;
                unitPrice = parseFloat($('#new_item_price').val()) || 0;
                discount = parseFloat($('#new_item_discount').val()) || 0;
            }
            
            var subtotal = quantity * unitPrice;
            var discountAmount = subtotal * (discount / 100);
            var total = subtotal - discountAmount;
            
            $('#item_total').text(formatPrice(total));
        };
        
        // Meglévő tétel hozzáadása
        window.addExistingItem = function() {
            var formData = {
                worksheet_id: worksheetId,
                part_service_id: $('#part_service_select').val(),
                quantity: $('#item_quantity').val(),
                unit_price: $('#item_unit_price').val(),
                discount: $('#item_discount').val() || 0,
                is_internal: $('#is_internal').prop('checked') ? 1 : 0,
                csrf_token: $('meta[name="csrf-token"]').attr('content')
            };
            
            // Validáció
            if (!formData.part_service_id) {
                Swal.fire('Hiba!', 'Válasszon ki egy alkatrészt vagy szolgáltatást!', 'error');
                return;
            }
            
            if (formData.quantity <= 0) {
                Swal.fire('Hiba!', 'A mennyiség nagyobb kell legyen nullánál!', 'error');
                return;
            }
            
            if (formData.unit_price < 0) {
                Swal.fire('Hiba!', 'Az egységár nem lehet negatív!', 'error');
                return;
            }
            
            showLoading();
            
            $.ajax({
                url: baseUrl + '/ajax/worksheet/add-item',
                type: 'POST',
                data: formData,
                success: function(response) {
                    hideLoading();
                    console.log('Add item response:', response);
                    if (response.success) {
                        handleAddItemSuccess(response);
                    } else {
                        Swal.fire('Hiba!', response.error || 'Ismeretlen hiba történt!', 'error');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    handleAddItemError(xhr);
                }
            });
        };
        
        // Új tétel hozzáadása
        window.addNewItem = function() {
            var formData = {
                worksheet_id: worksheetId,
                new_item: true,
                name: $('#new_item_name').val(),
                sku: $('#new_item_sku').val(),
                type: $('#new_item_type').val(),
                unit: $('#new_item_unit').val(),
                description: $('#new_item_description').val(),
                quantity: $('#new_item_quantity').val(),
                unit_price: $('#new_item_price').val(),
                discount: $('#new_item_discount').val() || 0,
                is_internal: $('#is_internal').prop('checked') ? 1 : 0,
                csrf_token: $('meta[name="csrf-token"]').attr('content')
            };
            
            // Validáció
            if (!formData.name) {
                Swal.fire('Hiba!', 'A megnevezés kötelező!', 'error');
                return;
            }
            
            if (!formData.unit) {
                Swal.fire('Hiba!', 'A mértékegység kötelező!', 'error');
                return;
            }
            
            if (formData.quantity <= 0) {
                Swal.fire('Hiba!', 'A mennyiség nagyobb kell legyen nullánál!', 'error');
                return;
            }
            
            if (formData.unit_price <= 0) {
                Swal.fire('Hiba!', 'Az egységár nagyobb kell legyen nullánál!', 'error');
                return;
            }
            
            showLoading();
            
            $.ajax({
                url: baseUrl + '/ajax/worksheet/add-item',
                type: 'POST',
                data: formData,
                success: function(response) {
                    hideLoading();
                    if (response.success) {
                        handleAddItemSuccess(response);
                        
                        // Ha új tétel volt, frissítjük a select listát
                        if (response.new_part_id) {
                            var newOption = new Option(
                                formData.name + ' - ' + formatPrice(formData.unit_price) + '/' + formData.unit,
                                response.new_part_id,
                                false,
                                false
                            );
                            $(newOption).attr({
                                'data-price': formData.unit_price,
                                'data-unit': formData.unit,
                                'data-type': formData.type
                            });
                            $('#part_service_select').append(newOption);
                        }
                    } else {
                        Swal.fire('Hiba!', response.error || 'Ismeretlen hiba történt!', 'error');
                    }
                },
                error: function(xhr) {
                    hideLoading();
                    handleAddItemError(xhr);
                }
            });
        };
        
        // Sikeres hozzáadás kezelése
        window.handleAddItemSuccess = function(response) {
            console.log('handleAddItemSuccess response:', response);
            
            $('#addItemModal').modal('hide');
            
            // Táblázat frissítése
            updateItemsTable(response.items || []);
            
            // Sikeres üzenet
            Swal.fire({
                icon: 'success',
                title: 'Tétel hozzáadva!',
                showConfirmButton: false,
                timer: 1500
            });
            
            resetItemForm();
        };
        
        // Hiba kezelése
        window.handleAddItemError = function(xhr) {
            var message = 'Hiba történt a tétel hozzáadása során!';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                message = xhr.responseJSON.error;
            }
            Swal.fire('Hiba!', message, 'error');
        };
        
        // Tétel törlése
        window.deleteItem = function(itemId) {
            Swal.fire({
                title: 'Biztosan törli?',
                text: 'A tétel véglegesen törlődik a munkalapról!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Igen, törlöm!',
                cancelButtonText: 'Mégsem'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading();
                    
                    $.ajax({
                        url: baseUrl + '/ajax/worksheet/remove-item',
                        type: 'POST',
                        data: {
                            item_id: itemId,
                            worksheet_id: worksheetId,
                            csrf_token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            hideLoading();
                            if (response.success) {
                                updateItemsTable(response.items || []);
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Tétel törölve!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        },
                        error: function() {
                            hideLoading();
                            Swal.fire('Hiba!', 'A tétel törlése sikertelen!', 'error');
                        }
                    });
                }
            });
        };
        
        // Táblázat frissítése ÉS összegek számítása
        window.updateItemsTable = function(items) {
            console.log('updateItemsTable called with items:', items);
            
            var tbody = $('#itemsTableBody');
            tbody.empty();
            
            // Változók a számításokhoz
            var totalPrice = 0;
            var partCount = 0;
            var serviceCount = 0;
            var internalCount = 0;
            
            if (!items || items.length === 0) {
                tbody.append(
                    '<tr><td colspan="8" class="text-center text-muted">' +
                    'Még nincsenek tételek hozzáadva a munkalaphoz.' +
                    '</td></tr>'
                );
            } else {
                items.forEach(function(item) {
                    var row = $('<tr>').attr('data-item-id', item.id);
                    
                    row.append($('<td>').text(item.name));
                    
                    var typeHtml = item.type === 'part' 
                        ? '<span class="badge bg-info">Alkatrész</span>'
                        : '<span class="badge bg-success">Szolgáltatás</span>';
                    row.append($('<td>').html(typeHtml));
                    
                    row.append($('<td>').addClass('text-center').text(item.quantity + ' ' + item.unit));
                    row.append($('<td>').addClass('text-end').text(formatPrice(item.unit_price)));
                    
                    var discountText = item.discount > 0 ? item.discount + '%' : '-';
                    row.append($('<td>').addClass('text-center').text(discountText));
                    
                    var internalHtml = item.is_internal == 1
                        ? '<span class="badge bg-warning" title="Belső tétel - nem jelenik meg a nyomtatott munkalapon"><i class="fas fa-eye-slash"></i></span>'
                        : '<span class="badge bg-secondary" title="Publikus tétel - megjelenik a munkalapon"><i class="fas fa-eye"></i></span>';
                    row.append($('<td>').addClass('text-center').html(internalHtml));
                    
                    row.append($('<td>').addClass('text-end').text(formatPrice(item.total_price)));
                    
                    var actionHtml = '<button type="button" class="btn btn-sm btn-danger delete-item" data-id="' + item.id + '" title="Törlés">' +
                                   '<i class="fas fa-trash"></i></button>';
                    row.append($('<td>').addClass('text-center').html(actionHtml));
                    
                    tbody.append(row);
                    
                    // Számítások
                    totalPrice += parseFloat(item.total_price) || 0;
                    
                    if (item.is_internal == 1) {
                        internalCount++;
                    } else {
                        if (item.type === 'part') {
                            partCount++;
                        } else if (item.type === 'service') {
                            serviceCount++;
                        }
                    }
                });
            }
            
            // Összegek frissítése
            console.log('Calculated totals:', {
                totalPrice: totalPrice,
                partCount: partCount,
                serviceCount: serviceCount,
                internalCount: internalCount
            });
            
            $('#totalPrice').text(formatPrice(totalPrice));
            $('#summaryTotal').text(formatPrice(totalPrice));
            $('#partCount').text(partCount);
            $('#serviceCount').text(serviceCount);
            $('#internalCount').text(internalCount);
        };
        
        // Összegek frissítése (ha külön hívják meg)
        window.updateTotals = function(totalPrice, stats) {
            console.log('updateTotals called with:', {
                totalPrice: totalPrice,
                stats: stats
            });
            
            if (totalPrice !== undefined) {
                $('#totalPrice').text(formatPrice(totalPrice));
                $('#summaryTotal').text(formatPrice(totalPrice));
            }
            
            if (stats) {
                $('#partCount').text(stats.part_count || 0);
                $('#serviceCount').text(stats.service_count || 0);
                $('#internalCount').text(stats.internal_count || 0);
            }
        };
        
        // Form reset
        window.resetItemForm = function() {
            $('#addItemForm')[0].reset();
            
            $('#part_service_select').val(null).trigger('change');
            $('#item_quantity').val(1);
            $('#item_unit_price').val('');
            $('#item_discount').val(0);
            
            $('#new_item_name').val('');
            $('#new_item_sku').val('');
            $('#new_item_type').val('service');
            $('#new_item_unit').val('db');
            $('#new_item_description').val('');
            $('#new_item_quantity').val(1);
            $('#new_item_price').val('');
            $('#new_item_discount').val(0);
            
            $('#is_internal').prop('checked', false);
            $('#item_unit').text('db');
            $('#new_item_unit_display').text('db');
            $('#item_total').text('0 Ft');
        };
        
        console.log('All worksheet edit functions initialized!');
    });
}

// Eszköz szerkesztés megjelenítése/elrejtése
window.toggleDeviceEdit = function() {
    $('#deviceDisplay').hide();
    $('#deviceEdit').show();
};

window.cancelDeviceEdit = function() {
    $('#deviceEdit').hide();
    $('#deviceDisplay').show();
    
    // Visszaállítjuk az eredeti értékeket
    $('#device_name').val('<?= $this->escape($worksheet['device_name']) ?>');
    $('#device_serial_number').val('<?= $this->escape($worksheet['serial_number'] ?? '') ?>');
    $('#device_accessories').val('<?= $this->escape($worksheet['accessories'] ?? '') ?>');
};

// Eszköz adatok mentése
window.saveDeviceData = function() {
    var deviceData = {
        device_id: <?= $worksheet['device_id'] ?? 'null' ?>,
        name: $('#device_name').val(),
        serial_number: $('#device_serial_number').val(),
        accessories: $('#device_accessories').val(),
        csrf_token: $('meta[name="csrf-token"]').attr('content')
    };
    
    // Validáció
    if (!deviceData.name) {
        Swal.fire('Hiba!', 'Az eszköz neve kötelező!', 'error');
        return;
    }
    
    if (!deviceData.device_id) {
        Swal.fire('Hiba!', 'Nincs eszköz azonosító!', 'error');
        return;
    }
    
    showLoading();
    
    $.ajax({
        url: baseUrl + '/ajax/device/update',
        type: 'POST',
        data: deviceData,
        success: function(response) {
            hideLoading();
            if (response.success) {
                // Frissítjük a megjelenítést
                $('#deviceDisplay').html(
                    '<p class="mb-1"><strong>' + escapeHtml(deviceData.name) + '</strong></p>' +
                    (deviceData.serial_number ? '<p class="mb-1">Gyári szám: ' + escapeHtml(deviceData.serial_number) + '</p>' : '') +
                    (deviceData.accessories ? '<p class="mb-0">Tartozékok: ' + escapeHtml(deviceData.accessories).replace(/\n/g, '<br>') + '</p>' : '')
                );
                
                // Visszaváltunk megjelenítő nézetre
                cancelDeviceEdit();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Eszköz adatok frissítve!',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire('Hiba!', response.error || 'Frissítés sikertelen!', 'error');
            }
        },
        error: function() {
            hideLoading();
            Swal.fire('Hiba!', 'Hiba történt a mentés során!', 'error');
        }
    });
};

</script>