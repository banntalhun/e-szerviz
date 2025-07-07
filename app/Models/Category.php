<?php
// app/Models/Category.php

namespace Models;

use Core\Model;

class Category extends Model {
    protected string $table = 'parts_categories';
    protected array $fillable = [
        'parent_id', 'name', 'code', 'description', 'path', 'level', 'sort_order', 'is_active'
    ];
    
    /**
     * Get all categories as tree structure
     */
    public function getTree(): array {
        $categories = $this->all(['is_active' => 1], 'sort_order ASC, name ASC');
        return $this->buildTree($categories);
    }
    
    /**
     * Build tree structure from flat array
     */
    private function buildTree(array $elements, $parentId = null): array {
        $branch = [];
        
        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        
        return $branch;
    }
    
    /**
     * Get categories for select dropdown
     */
    public function getForSelect(): array {
        $categories = $this->all(['is_active' => 1], 'path ASC');
        $options = [];
        
        foreach ($categories as $category) {
            $prefix = str_repeat('— ', $category['level']);
            $options[$category['id']] = $prefix . $category['name'];
        }
        
        return $options;
    }
    
    /**
     * Get breadcrumb path for category
     */
    public function getBreadcrumb(int $categoryId): array {
        $sql = "SELECT * FROM " . DB_PREFIX . "parts_categories 
                WHERE FIND_IN_SET(id, (
                    SELECT REPLACE(REPLACE(path, '/', ','), ',,', ',') 
                    FROM " . DB_PREFIX . "parts_categories 
                    WHERE id = ?
                ))
                ORDER BY level ASC";
        
        return $this->db->fetchAll($sql, [$categoryId]);
    }
    
    /**
     * Get children categories
     */
    public function getChildren(int $parentId): array {
        return $this->all(['parent_id' => $parentId, 'is_active' => 1], 'sort_order ASC, name ASC');
    }
    
    /**
     * Check if category has children
     */
    public function hasChildren(int $categoryId): bool {
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "parts_categories 
                WHERE parent_id = ?";
        $result = $this->db->fetchOne($sql, [$categoryId]);
        return $result['count'] > 0;
    }
    
    /**
     * Check if category has parts
     */
    public function hasParts(int $categoryId): bool {
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "parts_services 
                WHERE category_id = ?";
        $result = $this->db->fetchOne($sql, [$categoryId]);
        return $result['count'] > 0;
    }
    
    /**
     * Get parts count for category (including subcategories)
     */
    public function getPartsCount(int $categoryId): int {
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "parts_services ps
                JOIN " . DB_PREFIX . "parts_categories pc ON ps.category_id = pc.id
                WHERE pc.path LIKE CONCAT('%/', ?, '/%') OR pc.id = ?";
        $result = $this->db->fetchOne($sql, [$categoryId, $categoryId]);
        return (int) $result['count'];
    }
    
    /**
     * Create new category
     */
    public function create(array $data): int {
        // Generate path and level
        if (!empty($data['parent_id'])) {
            $parent = $this->find($data['parent_id']);
            $data['level'] = $parent['level'] + 1;
        } else {
            $data['parent_id'] = null;
            $data['level'] = 0;
        }
        
        // Create category
        $id = parent::create($data);
        
        // Update path
        if (!empty($data['parent_id'])) {
            $path = $parent['path'] . $id . '/';
        } else {
            $path = '/' . $id . '/';
        }
        
        $this->update($id, ['path' => $path]);
        
        return $id;
    }
    
    /**
     * Update category
     */
    public function update(int $id, array $data): bool {
        $oldCategory = $this->find($id);
        
        // If parent changed, update path and level for this and all children
        if (isset($data['parent_id']) && $data['parent_id'] != $oldCategory['parent_id']) {
            $this->updateTreeStructure($id, $data['parent_id']);
        }
        
        return parent::update($id, $data);
    }
    
    /**
     * Update tree structure when parent changes
     */
    private function updateTreeStructure(int $categoryId, ?int $newParentId): void {
        $category = $this->find($categoryId);
        $oldPath = $category['path'];
        
        if ($newParentId) {
            $newParent = $this->find($newParentId);
            $newPath = $newParent['path'] . $categoryId . '/';
            $newLevel = $newParent['level'] + 1;
        } else {
            $newPath = '/' . $categoryId . '/';
            $newLevel = 0;
        }
        
        // Update this category
        $this->db->execute(
            "UPDATE " . DB_PREFIX . "parts_categories 
             SET parent_id = ?, path = ?, level = ? 
             WHERE id = ?",
            [$newParentId, $newPath, $newLevel, $categoryId]
        );
        
        // Update all children
        $levelDiff = $newLevel - $category['level'];
        $this->db->execute(
            "UPDATE " . DB_PREFIX . "parts_categories 
             SET path = REPLACE(path, ?, ?), 
                 level = level + ? 
             WHERE path LIKE ?",
            [$oldPath, $newPath, $levelDiff, $oldPath . '%']
        );
    }
    
    /**
     * Delete category (only if empty)
     */
    public function delete(int $id): bool {
        if ($this->hasChildren($id) || $this->hasParts($id)) {
            return false;
        }
        
        return parent::delete($id);
    }
    
    /**
     * Check if code is unique
     */
    public function checkCode(string $code, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . "parts_categories 
                WHERE code = ?";
        $params = [$code];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['count'] > 0;
    }
    
    /**
     * Get category statistics
     */
    public function getStatistics(int $categoryId): array {
        $category = $this->find($categoryId);
        
        // Direct parts
        $directParts = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM " . DB_PREFIX . "parts_services 
             WHERE category_id = ?",
            [$categoryId]
        );
        
        // All parts (including subcategories)
        $allParts = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM " . DB_PREFIX . "parts_services ps
             JOIN " . DB_PREFIX . "parts_categories pc ON ps.category_id = pc.id
             WHERE pc.path LIKE ? OR pc.id = ?",
            ['%/' . $categoryId . '/%', $categoryId]
        );
        
        // Subcategories count
        $subcategories = $this->db->fetchOne(
            "SELECT COUNT(*) as count FROM " . DB_PREFIX . "parts_categories 
             WHERE path LIKE ? AND id != ?",
            ['%/' . $categoryId . '/%', $categoryId]
        );
        
        return [
            'direct_parts' => (int) $directParts['count'],
            'total_parts' => (int) $allParts['count'],
            'subcategories' => (int) $subcategories['count']
        ];
    }
}