<?php
// app/Models/Device.php

namespace Models;

use Core\Model;

class Device extends Model {
    protected string $table = 'devices';
    protected array $fillable = [
        'customer_id', 'name', 'serial_number', 'condition_id',
        'accessories', 'purchase_date', 'purchase_price'
    ];
    
    public function getCustomer(): ?array {
        if (!isset($this->data['customer_id'])) {
            return null;
        }
        
        $sql = "SELECT * FROM " . DB_PREFIX . "customers WHERE id = ?";
        return $this->db->fetchOne($sql, [$this->data['customer_id']]);
    }
    
    public function getCondition(): ?array {
        if (!isset($this->data['condition_id'])) {
            return null;
        }
        
        $sql = "SELECT * FROM " . DB_PREFIX . "device_conditions WHERE id = ?";
        return $this->db->fetchOne($sql, [$this->data['condition_id']]);
    }
    
    public function getWorksheets(int $deviceId): array {
        $sql = "SELECT w.*, st.name as status_name, st.color as status_color,
                u.full_name as technician_name, c.name as customer_name
                FROM " . DB_PREFIX . "worksheets w
                LEFT JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
                LEFT JOIN " . DB_PREFIX . "users u ON w.technician_id = u.id
                LEFT JOIN " . DB_PREFIX . "customers c ON w.customer_id = c.id
                WHERE w.device_id = ?
                ORDER BY w.created_at DESC";
        
        return $this->db->fetchAll($sql, [$deviceId]);
    }
    
    public function getRepairHistory(int $deviceId): array {
        $sql = "SELECT w.worksheet_number, w.created_at, w.description, 
                w.total_price, st.name as status_name
                FROM " . DB_PREFIX . "worksheets w
                LEFT JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
                WHERE w.device_id = ?
                ORDER BY w.created_at DESC";
        
        return $this->db->fetchAll($sql, [$deviceId]);
    }
    
    public function getTotalRepairCost(int $deviceId): float {
        $sql = "SELECT SUM(total_price) as total FROM " . DB_PREFIX . "worksheets 
                WHERE device_id = ?";
        $result = $this->db->fetchOne($sql, [$deviceId]);
        return (float) ($result['total'] ?? 0);
    }
    
    public function getRepairCount(int $deviceId): int {
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "worksheets 
                WHERE device_id = ?";
        $result = $this->db->fetchOne($sql, [$deviceId]);
        return (int) $result['count'];
    }
    
    public function searchBySerialNumber(string $serialNumber): ?array {
        return $this->findBy('serial_number', $serialNumber);
    }
    
    public function getAllWithCustomer(): array {
        $sql = "SELECT d.*, c.name as customer_name, dc.name as condition_name
                FROM " . DB_PREFIX . "devices d
                LEFT JOIN " . DB_PREFIX . "customers c ON d.customer_id = c.id
                LEFT JOIN " . DB_PREFIX . "device_conditions dc ON d.condition_id = dc.id
                ORDER BY d.created_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getStatistics(): array {
        $stats = [];
        
        // Összes eszköz
        $sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "devices";
        $result = $this->db->fetchOne($sql);
        $stats['total'] = (int) $result['total'];
        
        // Állapot szerinti megoszlás
        $sql = "SELECT dc.name, COUNT(d.id) as count 
                FROM " . DB_PREFIX . "devices d
                JOIN " . DB_PREFIX . "device_conditions dc ON d.condition_id = dc.id
                GROUP BY dc.id";
        $stats['by_condition'] = $this->db->fetchAll($sql);
        
        // Aktív javítás alatt
        $sql = "SELECT COUNT(DISTINCT d.id) as count 
                FROM " . DB_PREFIX . "devices d
                JOIN " . DB_PREFIX . "worksheets w ON d.id = w.device_id
                JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
                WHERE st.is_closed = 0";
        $result = $this->db->fetchOne($sql);
        $stats['under_repair'] = (int) $result['count'];
        
        return $stats;
    }
}
