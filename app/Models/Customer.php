<?php
// app/Models/Customer.php

namespace Models;

use Core\Model;

class Customer extends Model {
    protected string $table = 'customers';
    protected array $fillable = [
        'name', 'email', 'phone', 'address', 'city', 'postal_code',
        'is_company', 'company_name', 'tax_number', 'company_address',
        'internal_note', 'priority_id'
    ];
    
    public function getWorksheets(int $customerId): array {
        $sql = "SELECT w.*, st.name as status_name, st.color as status_color,
                u.full_name as technician_name, d.name as device_name
                FROM " . DB_PREFIX . "worksheets w
                LEFT JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
                LEFT JOIN " . DB_PREFIX . "users u ON w.technician_id = u.id
                LEFT JOIN " . DB_PREFIX . "devices d ON w.device_id = d.id
                WHERE w.customer_id = ?
                ORDER BY w.created_at DESC";
        
        return $this->db->fetchAll($sql, [$customerId]);
    }
    
    public function getDevices(int $customerId): array {
        $sql = "SELECT * FROM " . DB_PREFIX . "devices 
                WHERE customer_id = ? 
                ORDER BY created_at DESC";
        
        return $this->db->fetchAll($sql, [$customerId]);
    }
    
    public function getPriority(): ?array {
        if (!isset($this->data['priority_id'])) {
            return null;
        }
        
        $sql = "SELECT * FROM " . DB_PREFIX . "priority_types WHERE id = ?";
        return $this->db->fetchOne($sql, [$this->data['priority_id']]);
    }
    
    public function getTotalRevenue(int $customerId): float {
        $sql = "SELECT SUM(total_price) as total FROM " . DB_PREFIX . "worksheets 
                WHERE customer_id = ? AND is_paid = 1";
        $result = $this->db->fetchOne($sql, [$customerId]);
        return (float) ($result['total'] ?? 0);
    }
    
    public function getLastVisit(int $customerId): ?string {
        $sql = "SELECT MAX(created_at) as last_visit FROM " . DB_PREFIX . "worksheets 
                WHERE customer_id = ?";
        $result = $this->db->fetchOne($sql, [$customerId]);
        return $result['last_visit'];
    }
    
    public function searchByNameOrPhone(string $query): array {
        $sql = "SELECT c.*, pt.name as priority_name, pt.color as priority_color
                FROM " . DB_PREFIX . "customers c
                LEFT JOIN " . DB_PREFIX . "priority_types pt ON c.priority_id = pt.id
                WHERE c.name LIKE ? OR c.phone LIKE ? OR c.email LIKE ?
                ORDER BY c.name
                LIMIT 20";
        
        $searchTerm = "%{$query}%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    public function getStatistics(int $customerId): array {
        $stats = [];
        
        // Összes munkalap
        $sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "worksheets WHERE customer_id = ?";
        $result = $this->db->fetchOne($sql, [$customerId]);
        $stats['total_worksheets'] = (int) $result['total'];
        
        // Aktív munkalapok
        $sql = "SELECT COUNT(*) as active FROM " . DB_PREFIX . "worksheets w
                JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
                WHERE w.customer_id = ? AND st.is_closed = 0";
        $result = $this->db->fetchOne($sql, [$customerId]);
        $stats['active_worksheets'] = (int) $result['active'];
        
        // Összes eszköz
        $sql = "SELECT COUNT(*) as total FROM " . DB_PREFIX . "devices WHERE customer_id = ?";
        $result = $this->db->fetchOne($sql, [$customerId]);
        $stats['total_devices'] = (int) $result['total'];
        
        // Összes bevétel
        $stats['total_revenue'] = $this->getTotalRevenue($customerId);
        
        return $stats;
    }
}