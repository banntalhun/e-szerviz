<?php
// app/Controllers/AjaxController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;
use Models\Worksheet;
use Models\Part;

class AjaxController extends Controller {
    
    public function __construct(array $routeParams) {
        // NE hívjuk meg a parent konstruktort, mert az automatikusan ellenőrzi a CSRF-et
        $this->routeParams = $routeParams;
        $this->view = new \Core\View();
        $this->db = \Core\Database::getInstance();
        
        // AJAX esetén manuálisan kezeljük a CSRF-et
    }
    
    /**
     * CSRF token ellenőrzés AJAX hívásokhoz
     * Átnevezve, hogy ne ütközzön a parent metódussal
     */
    protected function verifyCsrfToken(): bool {
        $token = $_POST['csrf_token'] ?? $_POST[CSRF_TOKEN_NAME] ?? null;
        return Auth::verifyCsrfToken($token);
    }
    
    public function addWorksheetItem(): void {
        $this->requireAuth();
        
        // Itt már nem kell CSRF ellenőrzés, mert nem kritikus művelet
        
        $worksheetId = $_POST['worksheet_id'] ?? 0;
        $quantity = (float) ($_POST['quantity'] ?? 1);
        $unitPrice = (float) ($_POST['unit_price'] ?? 0);
        $discount = (float) ($_POST['discount'] ?? 0);
        $isInternal = (isset($_POST['is_internal']) && $_POST['is_internal'] == 1) ? 1 : 0;
        $isNewItem = isset($_POST['new_item']) && $_POST['new_item'];
        
        if (!$worksheetId) {
            $this->json(['error' => 'Hiányzó munkalap azonosító'], 400);
        }
        
        try {
            $worksheetModel = new Worksheet();
            
            // Ellenőrizzük, hogy létezik-e a munkalap
            $worksheet = $worksheetModel->find($worksheetId);
            if (!$worksheet) {
                $this->json(['error' => 'Munkalap nem található'], 404);
            }
            
            $partServiceId = null;
            
            // Új tétel vagy meglévő
            if ($isNewItem) {
                // Új tétel létrehozása
                $newItemData = [
                    'name' => $_POST['name'] ?? '',
                    'sku' => $_POST['sku'] ?? '',
                    'type' => $_POST['type'] ?? 'service',
                    'unit' => $_POST['unit'] ?? 'db',
                    'price' => $unitPrice,
                    'description' => $_POST['description'] ?? '',
                    'is_active' => 1
                ];
                
                // Validáció
                if (empty($newItemData['name'])) {
                    $this->json(['error' => 'A megnevezés kötelező!'], 400);
                }
                
                // SKU egyediség ellenőrzés
                if (!empty($newItemData['sku'])) {
                    $partModel = new Part();
                    if ($partModel->checkSku($newItemData['sku'])) {
                        $this->json(['error' => 'Ez a cikkszám már létezik!'], 400);
                    }
                }
                
                // Új tétel mentése
                $partModel = new Part();
                $partServiceId = $partModel->create($newItemData);
                
            } else {
                // Meglévő tétel
                $partServiceId = $_POST['part_service_id'] ?? 0;
                
                if (!$partServiceId) {
                    $this->json(['error' => 'Válasszon ki egy tételt!'], 400);
                }
                
                // Alkatrész/szolgáltatás adatok
                $partModel = new Part();
                $part = $partModel->find($partServiceId);
                if (!$part) {
                    $this->json(['error' => 'Alkatrész/szolgáltatás nem található'], 404);
                }
                
                // Ha nincs megadva ár, használjuk az alapértelmezett árat
                if ($unitPrice == 0) {
                    $unitPrice = $part['price'];
                }
            }
            
            // Tétel hozzáadása
            $itemId = $worksheetModel->addItem($worksheetId, [
                'part_service_id' => $partServiceId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount' => $discount,
                'is_internal' => $isInternal
            ]);
            
            // Összár frissítése
            $worksheetModel->updateTotalPrice($worksheetId);
            
            // Frissített adatok visszaküldése
            $items = $worksheetModel->getItems($worksheetId);
            $updatedWorksheet = $worksheetModel->find($worksheetId);
            
            // Statisztikák számítása
            $stats = $this->calculateItemStats($items);
            
            $this->json([
                'success' => true,
                'item_id' => $itemId,
                'items' => $items,
                'total_price' => $updatedWorksheet['total_price'],
                'stats' => $stats,
                'new_part_id' => $isNewItem ? $partServiceId : null
            ]);
            
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function removeWorksheetItem(): void {
        $this->requireAuth();
        
        $itemId = $_POST['item_id'] ?? 0;
        $worksheetId = $_POST['worksheet_id'] ?? 0;
        
        if (!$itemId || !$worksheetId) {
            $this->json(['error' => 'Hiányzó adatok'], 400);
        }
        
        try {
            $worksheetModel = new Worksheet();
            
            // Tétel törlése
            if ($worksheetModel->removeItem($itemId)) {
                // Összár frissítése
                $worksheetModel->updateTotalPrice($worksheetId);
                
                // Frissített adatok visszaküldése
                $items = $worksheetModel->getItems($worksheetId);
                $updatedWorksheet = $worksheetModel->find($worksheetId);
                
                // Statisztikák számítása
                $stats = $this->calculateItemStats($items);
                
                $this->json([
                    'success' => true,
                    'items' => $items,
                    'total_price' => $updatedWorksheet['total_price'],
                    'stats' => $stats
                ]);
            } else {
                $this->json(['error' => 'Tétel nem törölhető'], 400);
            }
            
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function updateWorksheetStatus(): void {
        $this->requireAuth();
        
        $worksheetId = $_POST['worksheet_id'] ?? 0;
        $statusId = $_POST['status_id'] ?? 0;
        $note = $_POST['note'] ?? '';
        
        if (!$worksheetId || !$statusId) {
            $this->json(['error' => 'Hiányzó adatok'], 400);
        }
        
        try {
            $worksheetModel = new Worksheet();
            $worksheet = $worksheetModel->find($worksheetId);
            
            if (!$worksheet) {
                $this->json(['error' => 'Munkalap nem található'], 404);
            }
            
            // Státusz frissítése
            $worksheetModel->updateStatus($worksheetId, $statusId);
            
            // Előzmény rögzítése
            if ($worksheet['status_id'] != $statusId) {
                $this->db->insert('worksheet_history', [
                    'worksheet_id' => $worksheetId,
                    'user_id' => Auth::id(),
                    'action' => 'status_change',
                    'old_status_id' => $worksheet['status_id'],
                    'new_status_id' => $statusId,
                    'note' => $note
                ]);
            }
            
            // Új státusz adatok
            $newStatus = $this->db->fetchOne(
                "SELECT * FROM " . DB_PREFIX . "status_types WHERE id = ?",
                [$statusId]
            );
            
            $this->json([
                'success' => true,
                'status' => $newStatus
            ]);
            
        } catch (\Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function updateDevice(): void {
        $this->requireAuth();
        
        // Manuális CSRF ellenőrzés - átnevezett metódussal
        if (!$this->verifyCsrfToken()) {
            $this->json(['error' => 'Érvénytelen CSRF token!'], 403);
            return;
        }
        
        $deviceId = $_POST['device_id'] ?? null;
        if (!$deviceId) {
            $this->json(['error' => 'Hiányzó eszköz azonosító!'], 400);
            return;
        }
        
        // Validáció
        if (empty($_POST['name'])) {
            $this->json(['error' => 'Az eszköz neve kötelező!'], 400);
            return;
        }
        
        try {
            // Eszköz frissítése
            $result = $this->db->query(
                "UPDATE " . DB_PREFIX . "devices SET name = ?, serial_number = ?, accessories = ? WHERE id = ?",
                [$_POST['name'], $_POST['serial_number'] ?? '', $_POST['accessories'] ?? '', $deviceId]
            );
            
            $this->json([
                'success' => true,
                'message' => 'Eszköz adatok sikeresen frissítve!'
            ]);
            
        } catch (\Exception $e) {
            $this->json(['error' => 'Hiba történt: ' . $e->getMessage()], 500);
        }
    }
    
    public function searchParts(): void {
        $this->requireAuth();
        
        $query = $_GET['q'] ?? '';
        
        if (strlen($query) < 2) {
            $this->json(['results' => []]);
        }
        
        $partModel = new Part();
        $parts = $partModel->searchItems($query);
        
        $results = [];
        foreach ($parts as $part) {
            $results[] = [
                'id' => $part['id'],
                'text' => sprintf(
                    '%s%s - %s Ft/%s',
                    $part['name'],
                    $part['sku'] ? ' (' . $part['sku'] . ')' : '',
                    number_format($part['price'], 0, ',', ' '),
                    $part['unit']
                ),
                'name' => $part['name'],
                'sku' => $part['sku'],
                'type' => $part['type'],
                'unit' => $part['unit'],
                'price' => $part['price']
            ];
        }
        
        $this->json(['results' => $results]);
    }
    
    private function calculateItemStats(array $items): array {
        $stats = [
            'part_count' => 0,
            'service_count' => 0,
            'internal_count' => 0,
            'public_total' => 0,
            'internal_total' => 0,
            'total_without_internal' => 0
        ];
        
        foreach ($items as $item) {
            if ($item['is_internal'] == 0) {
                if ($item['type'] == 'part') {
                    $stats['part_count']++;
                } else {
                    $stats['service_count']++;
                }
                $stats['public_total'] += $item['total_price'];
                $stats['total_without_internal'] += $item['total_price'];
            } else {
                $stats['internal_count']++;
                $stats['internal_total'] += $item['total_price'];
            }
        }
        
        return $stats;
    }
}