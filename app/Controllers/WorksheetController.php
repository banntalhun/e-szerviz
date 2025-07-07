<?php
// app/Controllers/WorksheetController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Worksheet;
use Models\Customer;
use Models\Device;
use Models\User;
use Models\Part;

class WorksheetController extends Controller {
    
    public function index(): void {
        $this->requireAuth();
        
        $worksheetModel = new Worksheet();
        
        // Szűrők kezelése
        $filters = [
            'status_id' => $_GET['status'] ?? '',
            'technician_id' => $_GET['technician'] ?? '',
            'priority_id' => $_GET['priority'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];
        
        // Keresés
        $search = $_GET['search'] ?? '';
        
        if (!empty($search)) {
            $worksheets = $worksheetModel->search($search);
        } else {
            $worksheets = $worksheetModel->getFilteredWorksheets($filters);
        }
        
        // Szűrő opciók betöltése
        $statusTypes = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "status_types ORDER BY sort_order");
        $priorityTypes = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "priority_types ORDER BY level");
        
        $userModel = new User();
        $technicians = $userModel->getTechnicians();
        
        $this->view->render('worksheets/index', [
            'worksheets' => $worksheets,
            'filters' => $filters,
            'search' => $search,
            'statusTypes' => $statusTypes,
            'priorityTypes' => $priorityTypes,
            'technicians' => $technicians
        ]);
    }
    
    public function create(): void {
        $this->requireAuth();
        
        // Előre betöltött adatok
        $customerId = $_GET['customer_id'] ?? null;
        $deviceId = $_GET['device_id'] ?? null;
        
        $customer = null;
        $device = null;
        $devices = [];
        
        if ($customerId) {
            $customerModel = new Customer();
            $customer = $customerModel->find($customerId);
            if ($customer) {
                $devices = $customerModel->getDevices($customerId);
            }
        }
        
        if ($deviceId) {
            $deviceModel = new Device();
            $device = $deviceModel->find($deviceId);
            if ($device && !$customer) {
                $customerModel = new Customer();
                $customer = $customerModel->find($device['customer_id']);
                $devices = $customerModel->getDevices($device['customer_id']);
            }
        }
        
        // Telephelyek
        $locations = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "locations WHERE is_active = 1 ORDER BY is_default DESC, name");
        
        // Szerelők
        $userModel = new User();
        $technicians = $userModel->getTechnicians();
        
        // Állapot típusok
        $statusTypes = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "status_types ORDER BY sort_order");
        
        // Javítás típusok
        $repairTypes = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "repair_types ORDER BY name");
        
        // Eszköz állapotok
        $deviceConditions = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "device_conditions ORDER BY name");
        
        // Prioritások
        $priorityTypes = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "priority_types ORDER BY level");
        
        $this->view->render('worksheets/create', [
            'customer' => $customer,
            'device' => $device,
            'devices' => $devices,
            'locations' => $locations,
            'technicians' => $technicians,
            'statusTypes' => $statusTypes,
            'repairTypes' => $repairTypes,
            'deviceConditions' => $deviceConditions,
            'priorityTypes' => $priorityTypes
        ]);
    }
    
    public function store(): void {
        $this->requireAuth();
        
        $worksheetModel = new Worksheet();
        
        try {
            $this->db->beginTransaction();
            
            // Ügyfél kezelése
            $customerId = null;
            if (!empty($_POST['customer_id'])) {
                $customerId = $_POST['customer_id'];
            } else {
                // Új ügyfél létrehozása
                $customerData = [
                    'name' => $_POST['customer_name'],
                    'email' => $_POST['customer_email'] ?? '',
                    'phone' => $_POST['customer_phone'],
                    'address' => $_POST['customer_address'] ?? '',
                    'city' => $_POST['customer_city'] ?? '',
                    'postal_code' => $_POST['customer_postal_code'] ?? '',
                    'priority_id' => $_POST['customer_priority_id'] ?? 1,
                    'is_company' => isset($_POST['is_company']) ? 1 : 0,
                    'company_name' => $_POST['company_name'] ?? '',
                    'tax_number' => $_POST['tax_number'] ?? '',
                    'company_address' => $_POST['company_address'] ?? ''
                ];
                
                $customerModel = new Customer();
                $customerId = $customerModel->create($customerData);
            }
            
            // Eszköz kezelése
            $deviceId = null;
            if (!empty($_POST['device_id'])) {
                $deviceId = $_POST['device_id'];
            } else if (!empty($_POST['device_name'])) {
                // Új eszköz létrehozása
                $deviceData = [
                    'customer_id' => $customerId,
                    'name' => $_POST['device_name'],
                    'serial_number' => $_POST['serial_number'] ?? '',
                    'condition_id' => $_POST['condition_id'] ?? 1,
                    'accessories' => $_POST['accessories'] ?? '',
                    'purchase_date' => $_POST['purchase_date'] ?? null,
                    'purchase_price' => $_POST['purchase_price'] ?? 0
                ];
                
                $deviceModel = new Device();
                $deviceId = $deviceModel->create($deviceData);
            }
            
            // Munkalap létrehozása
            $worksheetData = [
                'worksheet_number' => $worksheetModel->generateWorksheetNumber(),
                'location_id' => $_POST['location_id'],
                'technician_id' => $_POST['technician_id'] ?? Auth::id(),
                'customer_id' => $customerId,
                'device_id' => $deviceId,
                'repair_type_id' => $_POST['repair_type_id'],
                'status_id' => $_POST['status_id'] ?? 1,
                'warranty_date' => date('Y-m-d', strtotime('+' . DEFAULT_WARRANTY_DAYS . ' days')),
                'description' => $_POST['description'],
                'internal_note' => $_POST['internal_note'] ?? ''
            ];
            
            $worksheetId = $worksheetModel->create($worksheetData);
            
            $this->db->commit();
            
            $this->success('Munkalap sikeresen létrehozva!');
            $this->redirect('worksheets/' . $worksheetId);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            $this->error('Hiba történt a munkalap létrehozása során: ' . $e->getMessage());
            $this->setOldInput($_POST);
            $this->redirectBack();
        }
    }
    
    public function show(int $id): void {
        $this->requireAuth();
        
        $worksheetModel = new Worksheet();
        $worksheet = $worksheetModel->getFullData($id);
        
        if (!$worksheet) {
            $this->redirect('error/404');
        }
        
        // Munkalap tételek
        $items = $worksheetModel->getItems($id);
        
        // Csatolmányok
        $attachments = $worksheetModel->getAttachments($id);
        
        // Előzmények
        $history = $this->db->fetchAll(
            "SELECT wh.*, u.full_name as user_name, st.name as status_name
             FROM " . DB_PREFIX . "worksheet_history wh
             LEFT JOIN " . DB_PREFIX . "users u ON wh.user_id = u.id
             LEFT JOIN " . DB_PREFIX . "status_types st ON wh.new_status_id = st.id
             WHERE wh.worksheet_id = ?
             ORDER BY wh.created_at DESC",
            [$id]
        );
        
        // Státusz típusok hozzáadása
        $statusTypes = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "status_types ORDER BY sort_order");
        
        $this->view->render('worksheets/show', [
            'worksheet' => $worksheet,
            'items' => $items,
            'attachments' => $attachments,
            'history' => $history,
            'statusTypes' => $statusTypes
        ]);
    }
    
    public function edit(int $id): void {
        $this->requireAuth();
        
        $worksheetModel = new Worksheet();
        $worksheet = $worksheetModel->getFullData($id);
        
        if (!$worksheet) {
            $this->redirect('error/404');
        }
        
        // Szükséges listák betöltése
        $locations = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "locations WHERE is_active = 1 ORDER BY name");
        
        $userModel = new User();
        $technicians = $userModel->getTechnicians();
        
        $statusTypes = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "status_types ORDER BY sort_order");
        $repairTypes = $this->db->fetchAll("SELECT * FROM " . DB_PREFIX . "repair_types ORDER BY name");
        
        // Munkalap tételek
        $items = $worksheetModel->getItems($id);
        
        // Alkatrészek/szolgáltatások
        $partModel = new Part();
        $partsServices = $partModel->getActiveItems();
        
        // Csatolmányok
        $attachments = $worksheetModel->getAttachments($id);
        
        $this->view->render('worksheets/edit', [
            'worksheet' => $worksheet,
            'items' => $items,
            'locations' => $locations,
            'technicians' => $technicians,
            'statusTypes' => $statusTypes,
            'repairTypes' => $repairTypes,
            'partsServices' => $partsServices,
            'attachments' => $attachments
        ]);
    }
    
    public function update(int $id): void {
        $this->requireAuth();
        
        $worksheetModel = new Worksheet();
        $worksheet = $worksheetModel->find($id);
        
        if (!$worksheet) {
            $this->redirect('error/404');
        }
        
        try {
            $updateData = [
                'location_id' => $_POST['location_id'],
                'technician_id' => $_POST['technician_id'],
                'repair_type_id' => $_POST['repair_type_id'],
                'status_id' => $_POST['status_id'],
                'warranty_date' => $_POST['warranty_date'],
                'description' => $_POST['description'],
                'internal_note' => $_POST['internal_note'] ?? '',
                'is_paid' => isset($_POST['is_paid']) ? 1 : 0
            ];
            
            // Státusz változás naplózása
            if ($worksheet['status_id'] != $_POST['status_id']) {
                $historyData = [
                    'worksheet_id' => $id,
                    'user_id' => Auth::id(),
                    'action' => 'status_change',
                    'old_status_id' => $worksheet['status_id'],
                    'new_status_id' => $_POST['status_id'],
                    'note' => $_POST['status_note'] ?? ''
                ];
                $this->db->insert('worksheet_history', $historyData);
            }
            
            $worksheetModel->update($id, $updateData);
            
            $this->success('Munkalap sikeresen frissítve!');
            $this->redirect('worksheets/' . $id);
            
        } catch (\Exception $e) {
            $this->error('Hiba történt a frissítés során: ' . $e->getMessage());
            $this->redirectBack();
        }
    }
    
    public function delete(int $id): void {
        $this->requireAuth();
        $this->requirePermission('worksheet.delete');
        
        $worksheetModel = new Worksheet();
        
        if ($worksheetModel->softDelete($id)) {
            $this->success('Munkalap sikeresen törölve!');
        } else {
            $this->error('Hiba történt a törlés során!');
        }
        
        $this->redirect('worksheets');
    }
    
    public function print(int $id): void {
        $this->requireAuth();
        
        $worksheetModel = new Worksheet();
        $worksheet = $worksheetModel->getFullData($id);
        
        if (!$worksheet) {
            $this->redirect('error/404');
        }
        
        $items = $worksheetModel->getItems($id);
        
        // PDF generálás
        require_once ROOT . '/app/Helpers/PdfGenerator.php';
        
        $pdf = new \Helpers\PdfGenerator();
        $pdf->generateWorksheet($worksheet, $items);
    }
    
    public function email(int $id): void {
        $this->requireAuth();
        
        $worksheetModel = new Worksheet();
        $worksheet = $worksheetModel->getFullData($id);
        
        if (!$worksheet || empty($worksheet['customer_email'])) {
            $this->error('Nem található email cím!');
            $this->redirectBack();
        }
        
        // Email küldés
        require_once ROOT . '/app/Helpers/Mailer.php';
        
        $mailer = new \Helpers\Mailer();
        $subject = 'Munkalap - ' . $worksheet['worksheet_number'];
        $body = $this->view->render('emails/worksheet', ['worksheet' => $worksheet], true);
        
        if ($mailer->send($worksheet['customer_email'], $subject, $body)) {
            $this->success('Email sikeresen elküldve!');
        } else {
            $this->error('Hiba történt az email küldése során!');
        }
        
        $this->redirectBack();
    }
    
    /**
     * Fájl feltöltése - JAVÍTOTT VERZIÓ
     */
    public function uploadFile(int $id): void {
        $this->requireAuth();
        
        $worksheetModel = new Worksheet();
        $worksheet = $worksheetModel->find($id);
        
        if (!$worksheet) {
            $this->json(['error' => 'Munkalap nem található'], 404);
        }
        
        // Fájl ellenőrzés
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $this->json(['error' => 'Fájl feltöltés sikertelen'], 400);
        }
        
        $file = $_FILES['file'];
        
        // Méret ellenőrzés (10MB)
        $maxSize = 10 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            $this->json(['error' => 'A fájl mérete nem lehet nagyobb 10MB-nál!'], 400);
        }
        
        // Típus ellenőrzés
        $allowedMimeTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedMimeTypes)) {
            $this->json(['error' => 'Nem támogatott fájlformátum!'], 400);
        }
        
        // Fájlnév generálás
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'worksheet_' . $id . '_' . uniqid() . '.' . $ext;
        $uploadPath = ROOT . '/storage/uploads/worksheets/' . $id;
        
        // Mappa létrehozása ha nem létezik
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Fájl mozgatás
        $fullPath = $uploadPath . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            // Adatbázisba mentés
            $attachmentData = [
                'worksheet_id' => $id,
                'filename' => $filename,
                'original_name' => $file['name'],
                'uploaded_by' => Auth::id()
            ];
            
            $attachmentId = $this->db->insert('attachments', $attachmentData);

$this->json([
    'success' => true,
    'id' => $attachmentId,
    'filename' => $filename,
    'original_name' => $file['name']
]);

        } else {
            $this->json(['error' => 'Fájl mentése sikertelen'], 500);
        }
    }
    
    /**
 * Csatolmány letöltése/megjelenítése
 */
public function downloadAttachment(int $worksheetId, int $attachmentId): void {
    $this->requireAuth();
    
    // Csatolmány lekérdezése
    $attachment = $this->db->fetchOne(
        "SELECT * FROM " . DB_PREFIX . "attachments 
         WHERE id = ? AND worksheet_id = ?",
        [$attachmentId, $worksheetId]
    );
    
    if (!$attachment) {
        $this->redirect('error/404');
    }
    
    // A fájl pontos útvonala
    $filePath = ROOT . '/storage/uploads/worksheets/' . $worksheetId . '/' . $attachment['filename'];
    
    // Debug log
    error_log("Looking for file: " . $filePath);
    
    if (!file_exists($filePath)) {
        error_log("File not found at: " . $filePath);
        $this->redirect('error/404');
    }
    
    // MIME típus meghatározása
    $mimeType = mime_content_type($filePath);
    
    // Képek esetén inline megjelenítés
    $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $isImage = in_array($mimeType, $imageTypes);
    
    // Ha kép és van preview paraméter
    $preview = isset($_GET['preview']) && $_GET['preview'] == '1';
    
    // Fejlécek beállítása
    header('Content-Type: ' . $mimeType);
    
    if ($isImage && $preview) {
        // Képek inline megjelenítése
        header('Content-Disposition: inline; filename="' . $attachment['original_name'] . '"');
    } else {
        // Egyéb fájlok letöltése
        header('Content-Disposition: attachment; filename="' . $attachment['original_name'] . '"');
    }
    
    header('Content-Length: ' . filesize($filePath));
    header('Cache-Control: no-cache, must-revalidate');
    
    // Fájl küldése
    readfile($filePath);
    exit;
}
    
    /**
     * Csatolmány törlése
     */
    public function deleteAttachment(int $worksheetId, int $attachmentId): void {
        $this->requireAuth();
        
        // Csatolmány lekérdezése
        $attachment = $this->db->fetchOne(
            "SELECT * FROM " . DB_PREFIX . "attachments 
             WHERE id = ? AND worksheet_id = ?",
            [$attachmentId, $worksheetId]
        );
        
        if (!$attachment) {
            $this->json(['error' => 'Csatolmány nem található'], 404);
        }
        
        // Fájl törlése
        $filePath = ROOT . '/storage/uploads/worksheets/' . $worksheetId . '/' . $attachment['filename'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        // Adatbázisból törlés
        $this->db->delete('attachments', ['id' => $attachmentId]);
        
        $this->json(['success' => true]);
    }
    
    public function export(): void {
        $this->requireAuth();
        
        $worksheetModel = new Worksheet();
        
        // Szűrők alkalmazása
        $filters = [
            'status_id' => $_GET['status'] ?? '',
            'technician_id' => $_GET['technician'] ?? '',
            'priority_id' => $_GET['priority'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];
        
        $worksheets = $worksheetModel->getFilteredWorksheets($filters);
        
        // CSV generálás
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="munkalapok_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // UTF-8 BOM
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Fejléc
        fputcsv($output, [
            'Munkalap szám',
            'Dátum',
            'Ügyfél',
            'Eszköz',
            'Szerelő',
            'Státusz',
            'Prioritás',
            'Összeg',
            'Fizetett'
        ], ';');
        
        // Adatok
        foreach ($worksheets as $worksheet) {
            fputcsv($output, [
                $worksheet['worksheet_number'],
                date('Y.m.d H:i', strtotime($worksheet['created_at'])),
                $worksheet['customer_name'],
                $worksheet['device_name'] ?? 'N/A',
                $worksheet['technician_name'],
                $worksheet['status_name'],
                $worksheet['priority_name'] ?? 'Normal',
                number_format($worksheet['total_price'], 0, ',', ' ') . ' Ft',
                $worksheet['is_paid'] ? 'Igen' : 'Nem'
            ], ';');
        }
        
        fclose($output);
        exit;
    }
}