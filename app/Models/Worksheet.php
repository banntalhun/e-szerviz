<?php
// app/Models/Worksheet.php

namespace Models;

use Core\Model;

class Worksheet extends Model {
    protected string $table = 'worksheets';
    protected array $fillable = [
        'worksheet_number', 'location_id', 'technician_id', 'customer_id',
        'device_id', 'repair_type_id', 'status_id', 'warranty_date',
        'description', 'internal_note', 'total_price', 'is_paid'
    ];
    
    public function generateWorksheetNumber(): string {
        $prefix = date(WORKSHEET_NUMBER_FORMAT);
        
        // Az adott prefix-szel kezdődő utolsó munkalap lekérése
        $sql = "SELECT worksheet_number FROM " . DB_PREFIX . "worksheets 
                WHERE worksheet_number LIKE ? 
                ORDER BY worksheet_number DESC LIMIT 1";
        
        $result = $this->db->fetchOne($sql, [$prefix . '%']);
        
        if ($result) {
            // Kivonjuk a sorszámot és növeljük
            $lastNumber = substr($result['worksheet_number'], -4);
            $nextNumber = intval($lastNumber) + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
    
    public function getItems(int $worksheetId): array {
        $sql = "SELECT wi.*, ps.name, ps.sku, ps.type, ps.unit, ps.price as default_price
                FROM " . DB_PREFIX . "worksheet_items wi
                JOIN " . DB_PREFIX . "parts_services ps ON wi.part_service_id = ps.id
                WHERE wi.worksheet_id = ?
                ORDER BY wi.id";
        
        return $this->db->fetchAll($sql, [$worksheetId]);
    }
    
    public function addItem(int $worksheetId, array $data): int {
        $itemData = [
            'worksheet_id' => $worksheetId,
            'part_service_id' => $data['part_service_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'discount' => $data['discount'] ?? 0,
            'is_internal' => $data['is_internal'] ?? 0,
            'total_price' => ($data['quantity'] * $data['unit_price']) * (1 - ($data['discount'] ?? 0) / 100)
        ];
        
        return $this->db->insert('worksheet_items', $itemData);
    }
    
    public function removeItem(int $itemId): bool {
        $result = $this->db->delete('worksheet_items', ['id' => $itemId]);
        return $result > 0;
    }
    
    public function updateTotalPrice(int $worksheetId): void {
        // MINDEN tétel összegét számoljuk (belső is)
        $sql = "SELECT SUM(total_price) as total FROM " . DB_PREFIX . "worksheet_items 
                WHERE worksheet_id = ?";
        $result = $this->db->fetchOne($sql, [$worksheetId]);
        
        $total = (float) ($result['total'] ?? 0);
        
        $this->update($worksheetId, ['total_price' => $total]);
    }
    
    /**
     * Munkalap csatolmányainak lekérdezése - JAVÍTOTT VERZIÓ
     */
    public function getAttachments(int $worksheetId): array {
        $sql = "SELECT a.*, u.full_name as uploaded_by_name
                FROM " . DB_PREFIX . "attachments a
                LEFT JOIN " . DB_PREFIX . "users u ON a.uploaded_by = u.id
                WHERE a.worksheet_id = ?
                ORDER BY a.created_at DESC";
        
        return $this->db->fetchAll($sql, [$worksheetId]);
    }
    
    public function addAttachment(int $worksheetId, string $filename, string $originalName): int {
        $data = [
            'worksheet_id' => $worksheetId,
            'filename' => $filename,
            'original_name' => $originalName,
            'uploaded_by' => \Core\Auth::id()
        ];
        
        return $this->db->insert('attachments', $data);
    }
    
    public function getFullData(int $worksheetId): ?array {
        $sql = "SELECT w.*, 
                c.name as customer_name, c.email as customer_email, 
                c.phone as customer_phone, c.address as customer_address,
                c.is_company, c.company_name, c.tax_number,
                d.name as device_name, d.serial_number, d.accessories,
                u.full_name as technician_name,
                l.name as location_name,
                st.name as status_name, st.color as status_color,
                rt.name as repair_type_name,
                pt.name as priority_name, pt.color as priority_color
                FROM " . DB_PREFIX . "worksheets w
                LEFT JOIN " . DB_PREFIX . "customers c ON w.customer_id = c.id
                LEFT JOIN " . DB_PREFIX . "devices d ON w.device_id = d.id
                LEFT JOIN " . DB_PREFIX . "users u ON w.technician_id = u.id
                LEFT JOIN " . DB_PREFIX . "locations l ON w.location_id = l.id
                LEFT JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
                LEFT JOIN " . DB_PREFIX . "repair_types rt ON w.repair_type_id = rt.id
                LEFT JOIN " . DB_PREFIX . "priority_types pt ON c.priority_id = pt.id
                WHERE w.id = ?";
        
        return $this->db->fetchOne($sql, [$worksheetId]);
    }
    
    public function updateStatus(int $worksheetId, int $statusId): bool {
        return $this->update($worksheetId, ['status_id' => $statusId]);
    }
    
    public function search(string $query, array $searchFields = [], array $conditions = []): array {
        $sql = "SELECT w.*, 
                c.name as customer_name, 
                d.name as device_name,
                st.name as status_name, st.color as status_color
                FROM " . DB_PREFIX . "worksheets w
                LEFT JOIN " . DB_PREFIX . "customers c ON w.customer_id = c.id
                LEFT JOIN " . DB_PREFIX . "devices d ON w.device_id = d.id
                LEFT JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
                WHERE w.worksheet_number LIKE ? 
                OR c.name LIKE ? 
                OR d.name LIKE ?
                ORDER BY w.created_at DESC
                LIMIT 50";
        
        $searchTerm = "%{$query}%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    public function getFilteredWorksheets(array $filters): array {
        $sql = "SELECT w.*, 
                c.name as customer_name, 
                d.name as device_name,
                u.full_name as technician_name,
                st.name as status_name, st.color as status_color,
                pt.name as priority_name, pt.color as priority_color
                FROM " . DB_PREFIX . "worksheets w
                LEFT JOIN " . DB_PREFIX . "customers c ON w.customer_id = c.id
                LEFT JOIN " . DB_PREFIX . "devices d ON w.device_id = d.id
                LEFT JOIN " . DB_PREFIX . "users u ON w.technician_id = u.id
                LEFT JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
                LEFT JOIN " . DB_PREFIX . "priority_types pt ON c.priority_id = pt.id
                WHERE 1=1";
        
        $params = [];
        
        // Státusz szűrő
        if (!empty($filters['status_id'])) {
            $sql .= " AND w.status_id = ?";
            $params[] = $filters['status_id'];
        }
        
        // Szerelő szűrő
        if (!empty($filters['technician_id'])) {
            $sql .= " AND w.technician_id = ?";
            $params[] = $filters['technician_id'];
        }
        
        // Dátum szűrő
        if (!empty($filters['date_from'])) {
            $sql .= " AND w.created_at >= ?";
            $params[] = $filters['date_from'] . ' 00:00:00';
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND w.created_at <= ?";
            $params[] = $filters['date_to'] . ' 23:59:59';
        }
        
        // Prioritás szűrő
        if (!empty($filters['priority_id'])) {
            $sql .= " AND c.priority_id = ?";
            $params[] = $filters['priority_id'];
        }
        
        $sql .= " ORDER BY w.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getDashboardStats(): array {
        $stats = [];
        
        // Mai munkalapok
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "worksheets 
                WHERE DATE(created_at) = CURDATE()";
        $result = $this->db->fetchOne($sql);
        $stats['today'] = (int) $result['count'];
        
        // Aktív munkalapok
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "worksheets w
                JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
                WHERE st.is_closed = 0";
        $result = $this->db->fetchOne($sql);
        $stats['active'] = (int) $result['count'];
        
        // Sürgős munkalapok
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "worksheets w
                JOIN " . DB_PREFIX . "customers c ON w.customer_id = c.id
                JOIN " . DB_PREFIX . "priority_types pt ON c.priority_id = pt.id
                WHERE pt.level >= 2 AND w.status_id IN (
                    SELECT id FROM " . DB_PREFIX . "status_types WHERE is_closed = 0
                )";
        $result = $this->db->fetchOne($sql);
        $stats['urgent'] = (int) $result['count'];
        
        // Havi bevétel
        $sql = "SELECT SUM(total_price) as total FROM " . DB_PREFIX . "worksheets 
                WHERE MONTH(created_at) = MONTH(CURDATE()) 
                AND YEAR(created_at) = YEAR(CURDATE())
                AND is_paid = 1";
        $result = $this->db->fetchOne($sql);
        $stats['monthly_revenue'] = (float) ($result['total'] ?? 0);
        
        return $stats;
    }
}