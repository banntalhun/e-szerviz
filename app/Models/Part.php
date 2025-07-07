<?php
// app/Models/Part.php

namespace Models;

use Core\Model;

class Part extends Model {
    protected string $table = 'parts_services';
    protected int $perPage = 20;
    protected array $fillable = [
        'name', 'sku', 'type', 'category_id', 'unit', 'price', 'description', 'is_active', 'image', 'stock'
    ];
    
    public function getActiveItems(): array {
        $sql = "SELECT ps.*, pc.name as category_name 
                FROM " . DB_PREFIX . "parts_services ps
                LEFT JOIN " . DB_PREFIX . "parts_categories pc ON ps.category_id = pc.id
                WHERE ps.is_active = 1 
                ORDER BY ps.name ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function searchItems(string $query): array {
        $sql = "SELECT ps.*, pc.name as category_name 
                FROM " . DB_PREFIX . "parts_services ps
                LEFT JOIN " . DB_PREFIX . "parts_categories pc ON ps.category_id = pc.id
                WHERE ps.is_active = 1 
                AND (ps.name LIKE ? OR ps.sku LIKE ? OR ps.description LIKE ?)
                ORDER BY ps.name
                LIMIT 20";
        
        $searchTerm = "%{$query}%";
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
    
    public function getUsageCount(int $partId): int {
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "worksheet_items 
                WHERE part_service_id = ?";
        $result = $this->db->fetchOne($sql, [$partId]);
        return (int) $result['count'];
    }
    
    public function getTotalRevenue(int $partId): float {
        $sql = "SELECT SUM(total_price) as total FROM " . DB_PREFIX . "worksheet_items 
                WHERE part_service_id = ?";
        $result = $this->db->fetchOne($sql, [$partId]);
        return (float) ($result['total'] ?? 0);
    }
    
    public function getMostUsed(int $limit = 10): array {
        $sql = "SELECT ps.*, COUNT(wi.id) as usage_count, SUM(wi.total_price) as total_revenue
                FROM " . DB_PREFIX . "parts_services ps
                JOIN " . DB_PREFIX . "worksheet_items wi ON ps.id = wi.part_service_id
                GROUP BY ps.id
                ORDER BY usage_count DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    public function getByType(string $type): array {
        return $this->all(['type' => $type, 'is_active' => 1], 'name ASC');
    }
    
    public function checkSku(string $sku, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "parts_services 
                WHERE sku = ?";
        $params = [$sku];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Get parts by category (with optional subcategories)
     */
    public function getByCategory(int $categoryId, bool $includeSubcategories = false): array {
        if ($includeSubcategories) {
            $sql = "SELECT ps.*, pc.name as category_name, ppc.name as parent_category_name
                    FROM " . DB_PREFIX . "parts_services ps
                    LEFT JOIN " . DB_PREFIX . "parts_categories pc ON ps.category_id = pc.id
                    LEFT JOIN " . DB_PREFIX . "parts_categories ppc ON pc.parent_id = ppc.id
                    WHERE ps.is_active = 1 
                    AND (ps.category_id = ? OR pc.path LIKE ?)
                    ORDER BY ps.name ASC";
            
            return $this->db->fetchAll($sql, [$categoryId, '%/' . $categoryId . '/%']);
        } else {
            $sql = "SELECT ps.*, pc.name as category_name 
                    FROM " . DB_PREFIX . "parts_services ps
                    LEFT JOIN " . DB_PREFIX . "parts_categories pc ON ps.category_id = pc.id
                    WHERE ps.is_active = 1 AND ps.category_id = ?
                    ORDER BY ps.name ASC";
            
            return $this->db->fetchAll($sql, [$categoryId]);
        }
    }
    
    /**
     * Get paginated parts with category info
     */
    public function paginateWithCategory(int $page = 1, ?int $perPage = null, array $conditions = [], string $orderBy = 'id DESC'): array {
        $perPage = $perPage ?? $this->perPage;
        $offset = ($page - 1) * $perPage;
        
        // Build WHERE clause
        $where = [];
        $params = [];
        foreach ($conditions as $field => $value) {
            if (is_array($value)) {
                $placeholders = array_fill(0, count($value), '?');
                $where[] = "ps.{$field} IN (" . implode(',', $placeholders) . ")";
                $params = array_merge($params, $value);
            } else {
                $where[] = "ps.{$field} = ?";
                $params[] = $value;
            }
        }
        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
        
        // Get total count
        $countSql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "parts_services ps {$whereClause}";
        $result = $this->db->fetchOne($countSql, $params);
        $total = (int) $result['count'];
        
        // Get items with category
        $sql = "SELECT ps.*, pc.name as category_name 
                FROM " . DB_PREFIX . "parts_services ps
                LEFT JOIN " . DB_PREFIX . "parts_categories pc ON ps.category_id = pc.id
                {$whereClause}
                ORDER BY {$orderBy}
                LIMIT {$perPage} OFFSET {$offset}";
        
        $items = $this->db->fetchAll($sql, $params);
        
        return [
            'items' => $items,
            'total' => $total,
            'current' => $page,
            'pages' => ceil($total / $perPage),
            'per_page' => $perPage
        ];
    }
    
    /**
     * Search items with filters
     */
    public function searchWithFilters(array $filters): array {
        $conditions = ['ps.is_active = 1'];
        $params = [];
        
        // Search term
        if (!empty($filters['search'])) {
            $searchTerm = "%{$filters['search']}%";
            $conditions[] = "(ps.name LIKE ? OR ps.sku LIKE ? OR ps.description LIKE ?)";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Type filter
        if (!empty($filters['type'])) {
            $conditions[] = "ps.type = ?";
            $params[] = $filters['type'];
        }
        
        // Category filter
        if (!empty($filters['category_id'])) {
            if (!empty($filters['include_subcategories'])) {
                $conditions[] = "(ps.category_id = ? OR pc.path LIKE ?)";
                $params[] = $filters['category_id'];
                $params[] = '%/' . $filters['category_id'] . '/%';
            } else {
                $conditions[] = "ps.category_id = ?";
                $params[] = $filters['category_id'];
            }
        }
        
        $whereClause = implode(' AND ', $conditions);
        
        $sql = "SELECT ps.*, pc.name as category_name 
                FROM " . DB_PREFIX . "parts_services ps
                LEFT JOIN " . DB_PREFIX . "parts_categories pc ON ps.category_id = pc.id
                WHERE {$whereClause}
                ORDER BY ps.name ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    // IMAGE HANDLING METHODS:
    
    public function saveImage(array $file, int $partId): ?string {
        $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/parts/';
        $thumbDir = $uploadDir . 'thumbs/';
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'part_' . $partId . '_' . time() . '.' . $extension;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
            // Create thumbnail (150x150)
            $this->createThumbnail($uploadDir . $filename, $thumbDir . $filename, 150, 150);
            return $filename;
        }
        
        return null;
    }
    
    public function deleteImage(string $filename): void {
        $uploadDir = dirname(__DIR__, 2) . '/storage/uploads/parts/';
        $thumbDir = $uploadDir . 'thumbs/';
        
        if (file_exists($uploadDir . $filename)) {
            unlink($uploadDir . $filename);
        }
        
        if (file_exists($thumbDir . $filename)) {
            unlink($thumbDir . $filename);
        }
    }
    
    private function createThumbnail(string $source, string $dest, int $width, int $height): bool {
        $info = getimagesize($source);
        
        switch ($info['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source);
                break;
            default:
                return false;
        }
        
        $origWidth = imagesx($image);
        $origHeight = imagesy($image);
        
        // Calculate aspect ratio
        $ratio = min($width / $origWidth, $height / $origHeight);
        $newWidth = round($origWidth * $ratio);
        $newHeight = round($origHeight * $ratio);
        
        // Create new image
        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG
        if ($info['mime'] == 'image/png') {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
        }
        
        // Resize
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        
        // Save
        switch ($info['mime']) {
            case 'image/jpeg':
                imagejpeg($thumb, $dest, 90);
                break;
            case 'image/png':
                imagepng($thumb, $dest, 9);
                break;
            case 'image/gif':
                imagegif($thumb, $dest);
                break;
        }
        
        imagedestroy($image);
        imagedestroy($thumb);
        
        return true;
    }
}