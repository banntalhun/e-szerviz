<?php
// app/Views/worksheets/show.php - Windows Desktop Style
$this->setData(['title' => 'Munkalap - ' . $worksheet['worksheet_number']]);
$this->addJs('js/worksheet-show.js');
?>

<!-- CSRF Token a JS számára -->
<meta name="csrf-token" content="<?= \Core\Auth::csrfToken() ?>">

<style>
/* Windows Style for Worksheet Show */
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

.win-action-buttons {
    display: flex;
    gap: 4px;
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

/* Data Display */
.win-data-row {
    display: flex;
    margin-bottom: 4px;
}

.win-data-label {
    width: 120px;
    font-weight: normal;
    color: #666;
}

.win-data-value {
    flex: 1;
    color: #000;
}

/* Status Badge */
.win-badge {
    padding: 2px 6px;
    font-size: 10px;
    font-weight: normal;
    color: white;
    display: inline-block;
    margin-right: 4px;
}

/* Table Styles */
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

/* Sidebar specific */
.win-sidebar .win-panel-header {
    background-color: #0054E3;
    color: white;
    border-bottom: 1px solid #003CCB;
}

.win-sidebar .win-panel-header.normal {
    background-color: #F0F0F0;
    color: #000;
    border-bottom: 1px solid #D4D0C8;
}

/* Timeline */
.win-timeline {
    font-size: 11px;
}

.win-timeline-item {
    padding: 6px 0;
    border-bottom: 1px solid #F0F0F0;
}

.win-timeline-item:last-child {
    border-bottom: none;
}

/* File upload */
.win-file-upload {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #E8E8E8;
}

.win-file-input {
    font-size: 11px;
    padding: 2px 4px;
    border: 1px solid #D4D0C8;
    width: 100%;
    margin-bottom: 4px;
}

/* Links */
.win-link {
    color: #0066CC;
    text-decoration: none;
}

.win-link:hover {
    text-decoration: underline;
}
</style>

<!-- Page Header -->
<div class="win-page-header">
    <h1 class="win-page-title">
        <i class="fas fa-file-alt"></i>
        Munkalap: <?= $this->escape($worksheet['worksheet_number']) ?>
    </h1>
    <div class="win-action-buttons">
        <?php if (\Core\Auth::hasPermission('worksheet.edit')): ?>
        <a href="<?= $this->url('worksheets/' . $worksheet['id'] . '/edit') ?>" class="win-btn">
            <i class="fas fa-edit"></i> Szerkesztés
        </a>
        <?php endif; ?>
        
        <?php if (\Core\Auth::hasPermission('worksheet.print')): ?>
        <a href="<?= $this->url('worksheets/' . $worksheet['id'] . '/print') ?>" 
           class="win-btn" 
           target="_blank">
            <i class="fas fa-print"></i> Nyomtatás
        </a>
        <?php endif; ?>
        
        <?php if ($worksheet['customer_email']): ?>
        <button type="button" class="win-btn" onclick="sendEmail()">
            <i class="fas fa-envelope"></i> Email
        </button>
        <?php endif; ?>
    </div>
</div>

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
                <div style="display: flex; gap: 40px;">
                    <div style="flex: 1;">
                        <div class="win-data-row">
                            <div class="win-data-label">Munkalap szám:</div>
                            <div class="win-data-value"><strong><?= $this->escape($worksheet['worksheet_number']) ?></strong></div>
                        </div>
                        <div class="win-data-row">
                            <div class="win-data-label">Létrehozva:</div>
                            <div class="win-data-value"><?= $this->formatDate($worksheet['created_at'], 'Y.m.d H:i:s') ?></div>
                        </div>
                        <div class="win-data-row">
                            <div class="win-data-label">Telephely:</div>
                            <div class="win-data-value"><?= $this->escape($worksheet['location_name']) ?></div>
                        </div>
                        <div class="win-data-row">
                            <div class="win-data-label">Szerelő:</div>
                            <div class="win-data-value"><?= $this->escape($worksheet['technician_name']) ?></div>
                        </div>
                    </div>
                    <div style="flex: 1;">
                        <div class="win-data-row">
                            <div class="win-data-label">Státusz:</div>
                            <div class="win-data-value">
                                <span class="win-badge" style="background-color: <?= $worksheet['status_color'] ?>">
                                    <?= $this->escape($worksheet['status_name']) ?>
                                </span>
                                <?php if (\Core\Auth::hasPermission('worksheet.edit')): ?>
                                <a href="#" onclick="changeStatus(); return false;" style="font-size: 10px;">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="win-data-row">
                            <div class="win-data-label">Javítás típusa:</div>
                            <div class="win-data-value"><?= $this->escape($worksheet['repair_type_name']) ?></div>
                        </div>
                        <div class="win-data-row">
                            <div class="win-data-label">Vállalási határidő:</div>
                            <div class="win-data-value"><?= $this->formatDate($worksheet['warranty_date'], 'Y.m.d') ?></div>
                        </div>
                        <div class="win-data-row">
                            <div class="win-data-label">Prioritás:</div>
                            <div class="win-data-value">
                                <?php if ($worksheet['priority_name']): ?>
                                <span class="win-badge" style="background-color: <?= $worksheet['priority_color'] ?>">
                                    <?= $this->escape($worksheet['priority_name']) ?>
                                </span>
                                <?php else: ?>
                                <span class="win-badge" style="background-color: #6c757d">Normál</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Information -->
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-user"></i> Ügyfél adatok
            </div>
            <div class="win-panel-body">
                <div style="margin-bottom: 8px;">
                    <strong><?= $this->escape($worksheet['customer_name']) ?></strong>
                    <?php if ($worksheet['is_company']): ?>
                    <br>
                    Cég: <?= $this->escape($worksheet['company_name']) ?><br>
                    Adószám: <?= $this->escape($worksheet['tax_number']) ?>
                    <?php endif; ?>
                </div>
                <div class="win-data-row">
                    <div class="win-data-label"><i class="fas fa-phone"></i></div>
                    <div class="win-data-value"><?= $this->escape($worksheet['customer_phone']) ?></div>
                </div>
                <?php if ($worksheet['customer_email']): ?>
                <div class="win-data-row">
                    <div class="win-data-label"><i class="fas fa-envelope"></i></div>
                    <div class="win-data-value">
                        <a href="mailto:<?= $this->escape($worksheet['customer_email']) ?>" class="win-link">
                            <?= $this->escape($worksheet['customer_email']) ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($worksheet['customer_address']): ?>
                <div class="win-data-row">
                    <div class="win-data-label"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="win-data-value"><?= $this->escape($worksheet['customer_address']) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Device Information -->
        <?php if ($worksheet['device_id']): ?>
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-bicycle"></i> Eszköz adatok
            </div>
            <div class="win-panel-body">
                <div class="win-data-row">
                    <div class="win-data-label">Eszköz:</div>
                    <div class="win-data-value"><?= $this->escape($worksheet['device_name']) ?></div>
                </div>
                <?php if ($worksheet['serial_number']): ?>
                <div class="win-data-row">
                    <div class="win-data-label">Gyári szám:</div>
                    <div class="win-data-value"><?= $this->escape($worksheet['serial_number']) ?></div>
                </div>
                <?php endif; ?>
                <?php if ($worksheet['accessories']): ?>
                <div class="win-data-row">
                    <div class="win-data-label">Tartozékok:</div>
                    <div class="win-data-value"><?= nl2br($this->escape($worksheet['accessories'])) ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Description -->
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-tools"></i> Hibaleírás
            </div>
            <div class="win-panel-body">
                <div style="margin-bottom: 8px;">
                    <?= nl2br($this->escape($worksheet['description'])) ?>
                </div>
                <?php if ($worksheet['internal_note']): ?>
                <div style="border-top: 1px solid #E8E8E8; padding-top: 8px; color: #666;">
                    <strong>Belső megjegyzés:</strong><br>
                    <?= nl2br($this->escape($worksheet['internal_note'])) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Costs -->
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-coins"></i> Költségek
            </div>
            <div class="win-panel-body">
                <?php if (!empty($items)): ?>
                <table class="win-data-table">
                    <thead>
                        <tr>
                            <th>Megnevezés</th>
                            <th>Típus</th>
                            <th style="text-align: center;">Mennyiség</th>
                            <th style="text-align: right;">Egységár</th>
                            <th style="text-align: center;">Kedvezmény</th>
                            <th style="text-align: center;">Belső</th>
                            <th style="text-align: right;">Összesen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= $this->escape($item['name']) ?></td>
                            <td>
                                <?php if ($item['type'] == 'part'): ?>
                                    <span style="color: #17a2b8;">Alkatrész</span>
                                <?php else: ?>
                                    <span style="color: #28a745;">Szolgáltatás</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;"><?= $item['quantity'] ?> <?= $this->escape($item['unit']) ?></td>
                            <td style="text-align: right;"><?= $this->formatPrice($item['unit_price']) ?></td>
                            <td style="text-align: center;">
                                <?php if ($item['discount'] > 0): ?>
                                    <?= $item['discount'] ?>%
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($item['is_internal']): ?>
                                    <span class="win-badge" style="background-color: #FFC107;" title="Belső tétel - nem jelenik meg a nyomtatott munkalapon">
                                        <i class="fas fa-eye-slash"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="win-badge" style="background-color: #6C757D;" title="Publikus tétel - megjelenik a munkalapon">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;"><?= $this->formatPrice($item['total_price']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="border-top: 2px solid #D4D0C8;">
                            <th colspan="6" style="text-align: right;">Fizetendő összesen:</th>
                            <th style="text-align: right;">
                                <?php 
                                $publicTotal = 0;
                                foreach ($items as $item) {
                                    if (!$item['is_internal']) {
                                        $publicTotal += $item['total_price'];
                                    }
                                }
                                echo $this->formatPrice($publicTotal);
                                ?>
                            </th>
                        </tr>
                        <?php if ($publicTotal != $worksheet['total_price']): ?>
                        <tr>
                            <th colspan="6" style="text-align: right; color: #666; font-size: 10px;">Belső tételekkel együtt:</th>
                            <th style="text-align: right; color: #666; font-size: 10px;">
                                <?= $this->formatPrice($worksheet['total_price']) ?>
                            </th>
                        </tr>
                        <?php endif; ?>
                    </tfoot>
                </table>
                <?php else: ?>
                <p style="color: #666; margin: 0;">Még nincsenek költségek rögzítve.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Right Sidebar -->
    <div class="win-sidebar">
        <!-- Summary -->
        <div class="win-panel">
            <div class="win-panel-header">
                <i class="fas fa-calculator"></i> Összesítő
            </div>
            <div class="win-panel-body">
                <div class="win-data-row">
                    <div class="win-data-label">Végösszeg:</div>
                    <div class="win-data-value">
                        <strong style="font-size: 16px;"><?= $this->formatPrice($worksheet['total_price']) ?></strong>
                    </div>
                </div>
                <div class="win-data-row">
                    <div class="win-data-label">Fizetési státusz:</div>
                    <div class="win-data-value">
                        <?php if ($worksheet['is_paid']): ?>
                            <span class="win-badge" style="background-color: #28a745;">Fizetve</span>
                        <?php else: ?>
                            <span class="win-badge" style="background-color: #dc3545;">Fizetetlen</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Attachments -->
        <div class="win-panel">
            <div class="win-panel-header normal">
                <i class="fas fa-paperclip"></i> Csatolmányok
            </div>
            <div class="win-panel-body">
                <?php if (!empty($attachments)): ?>
                <div style="margin-bottom: 8px;">
                    <?php foreach ($attachments as $attachment): ?>
                    <?php 
                    $isImage = preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $attachment['original_name']);
                    ?>
                    <div style="margin-bottom: 4px;">
                        <?php if ($isImage): ?>
                            <a href="<?= $this->url('worksheets/' . $worksheet['id'] . '/attachment/' . $attachment['id'] . '/download?preview=1') ?>" 
                               class="win-link preview-image" 
                               data-title="<?= $this->escape($attachment['original_name']) ?>">
                                <i class="fas fa-image"></i> <?= $this->escape($attachment['original_name']) ?>
                            </a>
                        <?php else: ?>
                            <a href="<?= $this->url('worksheets/' . $worksheet['id'] . '/attachment/' . $attachment['id'] . '/download') ?>" 
                               class="win-link"
                               target="_blank">
                                <i class="fas fa-file"></i> <?= $this->escape($attachment['original_name']) ?>
                            </a>
                        <?php endif; ?>
                        <br>
                        <small style="color: #666;">
                            <?= $this->formatDate($attachment['created_at']) ?>
                        </small>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p style="color: #666; margin: 0 0 8px 0;">Nincs csatolt fájl.</p>
                <?php endif; ?>
                
                <?php if (\Core\Auth::hasPermission('worksheet.edit')): ?>
                <div class="win-file-upload">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <input type="file" class="win-file-input" id="fileInput" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        <button type="submit" class="win-btn" style="width: 100%;">
                            <i class="fas fa-upload"></i> Feltöltés
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- History -->
        <?php if (!empty($history)): ?>
        <div class="win-panel">
            <div class="win-panel-header normal">
                <i class="fas fa-history"></i> Előzmények
            </div>
            <div class="win-panel-body">
                <div class="win-timeline">
                    <?php foreach ($history as $event): ?>
                    <div class="win-timeline-item">
                        <strong><?= $this->escape($event['user_name']) ?></strong>
                        <?php if ($event['action'] == 'status_change'): ?>
                            státuszt váltott: 
                            <span class="win-badge" style="background-color: <?= $event['status_color'] ?? '#6c757d' ?>">
                                <?= $this->escape($event['status_name'] ?? 'N/A') ?>
                            </span>
                        <?php endif; ?>
                        <?php if ($event['note']): ?>
                        <br><?= $this->escape($event['note']) ?>
                        <?php endif; ?>
                        <br>
                        <small style="color: #666;">
                            <?= $this->formatDate($event['created_at'], 'Y.m.d H:i') ?>
                        </small>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Status Change Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="statusForm">
                <div class="modal-header">
                    <h5 class="modal-title" style="font-size: 14px;">Státusz váltás</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="worksheet_id" value="<?= $worksheet['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="status_id" class="form-label" style="font-size: 11px;">Új státusz</label>
                        <select class="form-select form-select-sm" id="status_id" required style="font-size: 11px;">
                            <?php foreach ($statusTypes as $status): ?>
                            <option value="<?= $status['id'] ?>" <?= $status['id'] == $worksheet['status_id'] ? 'selected' : '' ?>>
                                <?= $this->escape($status['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status_note" class="form-label" style="font-size: 11px;">Megjegyzés</label>
                        <textarea class="form-control form-control-sm" id="status_note" rows="2" style="font-size: 11px;"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 11px;">Mégsem</button>
                    <button type="submit" class="btn btn-sm btn-primary" style="font-size: 11px;">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Globális változók és függvények
document.addEventListener('DOMContentLoaded', function() {
    window.worksheetId = <?= $worksheet['id'] ?>;
    
    window.changeStatus = function() {
        if (typeof jQuery !== 'undefined') {
            jQuery('#statusModal').modal('show');
        }
    }
    
    window.sendEmail = function() {
        if (typeof jQuery === 'undefined' || typeof Swal === 'undefined') {
            alert('A szükséges könyvtárak még nem töltődtek be!');
            return;
        }
        
        Swal.fire({
            title: 'Email küldése',
            text: 'Biztosan elküldi a munkalapot emailben az ügyfélnek?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Igen, küldöm!',
            cancelButtonText: 'Mégsem',
            confirmButtonColor: '#0054E3',
            cancelButtonColor: '#6c757d',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return jQuery.ajax({
                    url: '<?= $this->url('worksheets/' . $worksheet['id'] . '/email') ?>',
                    type: 'POST',
                    data: {
                        <?= CSRF_TOKEN_NAME ?>: '<?= \Core\Auth::csrfToken() ?>'
                    }
                }).then(response => {
                    if (!response.success) {
                        throw new Error(response.message || 'Email küldés sikertelen');
                    }
                    return response;
                }).catch(error => {
                    Swal.showValidationMessage(`Hiba: ${error.message || 'Email küldés sikertelen'}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Email sikeresen elküldve!',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    }
});
</script>