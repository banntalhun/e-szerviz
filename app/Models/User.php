<?php
// app/Models/User.php

namespace Models;

use Core\Model;

class User extends Model {
    protected string $table = 'users';
    protected array $fillable = [
        'username', 'password', 'email', 'full_name', 'phone',
        'role_id', 'location_id', 'is_active'
    ];
    protected array $hidden = ['password'];
    
    public function findByUsername(string $username): ?array {
        return $this->findBy('username', $username);
    }
    
    public function findByEmail(string $email): ?array {
        return $this->findBy('email', $email);
    }
    
    public function updateLastLogin(int $userId): void {
        $sql = "UPDATE " . DB_PREFIX . $this->table . " SET last_login = NOW() WHERE id = ?";
        $this->db->query($sql, [$userId]);
    }
    
    public function getRole(): ?array {
        if (!isset($this->data['role_id'])) {
            return null;
        }
        
        $sql = "SELECT * FROM " . DB_PREFIX . "roles WHERE id = ?";
        return $this->db->fetchOne($sql, [$this->data['role_id']]);
    }
    
    public function getPermissions(): array {
        if (!isset($this->data['role_id'])) {
            return [];
        }
        
        $sql = "SELECT p.* FROM " . DB_PREFIX . "permissions p
                JOIN " . DB_PREFIX . "role_permissions rp ON p.id = rp.permission_id
                WHERE rp.role_id = ?";
        
        return $this->db->fetchAll($sql, [$this->data['role_id']]);
    }
    
    public function hasPermission(string $permission): bool {
        $permissions = $this->getPermissions();
        
        foreach ($permissions as $perm) {
            if ($perm['name'] === $permission) {
                return true;
            }
        }
        
        return false;
    }
    
    public function isAdmin(): bool {
        $role = $this->getRole();
        return $role && $role['name'] === 'admin';
    }
    
    public function getTechnicians(): array {
        $sql = "SELECT u.* FROM " . DB_PREFIX . "users u
                JOIN " . DB_PREFIX . "roles r ON u.role_id = r.id
                WHERE r.name IN ('admin', 'technician') AND u.is_active = 1
                ORDER BY u.full_name";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getWorksheetCount(int $userId): int {
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "worksheets 
                WHERE technician_id = ?";
        $result = $this->db->fetchOne($sql, [$userId]);
        return (int) $result['count'];
    }
    
    public function getActiveWorksheetCount(int $userId): int {
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "worksheets w
                JOIN " . DB_PREFIX . "status_types st ON w.status_id = st.id
                WHERE w.technician_id = ? AND st.is_closed = 0";
        $result = $this->db->fetchOne($sql, [$userId]);
        return (int) $result['count'];
    }
}
