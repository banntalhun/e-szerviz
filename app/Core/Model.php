<?php
// app/Core/Model.php

namespace Core;

abstract class Model {
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected array $hidden = [];
    protected array $data = [];
    protected bool $timestamps = true;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function find(int $id): ?array {
        $sql = "SELECT * FROM " . DB_PREFIX . $this->table . " WHERE {$this->primaryKey} = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function findBy(string $field, $value): ?array {
        $sql = "SELECT * FROM " . DB_PREFIX . $this->table . " WHERE {$field} = ?";
        return $this->db->fetchOne($sql, [$value]);
    }
    
    public function all(array $conditions = [], string $orderBy = '', int $limit = 0, int $offset = 0): array {
        $sql = "SELECT * FROM " . DB_PREFIX . $this->table;
        $params = [];
        
        // WHERE feltételek
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $field => $value) {
                if (is_array($value)) {
                    $operator = $value[0];
                    $val = $value[1];
                    $whereClauses[] = "{$field} {$operator} ?";
                    $params[] = $val;
                } else {
                    $whereClauses[] = "{$field} = ?";
                    $params[] = $value;
                }
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }
        
        // ORDER BY
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        // LIMIT és OFFSET
        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
            if ($offset > 0) {
                $sql .= " OFFSET {$offset}";
            }
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function count(array $conditions = []): int {
        $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . $this->table;
        $params = [];
        
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $field => $value) {
                $whereClauses[] = "{$field} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return (int) $result['count'];
    }
    
    public function create(array $data): int {
        // Csak a fillable mezőket használjuk
        $filteredData = $this->filterFillable($data);
        
        // Timestamps hozzáadása
        if ($this->timestamps) {
            $filteredData['created_at'] = date('Y-m-d H:i:s');
            $filteredData['updated_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->db->insert($this->table, $filteredData);
    }
    
    public function update(int $id, array $data): bool {
        // Csak a fillable mezőket használjuk
        $filteredData = $this->filterFillable($data);
        
        // Timestamps frissítése
        if ($this->timestamps) {
            $filteredData['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $result = $this->db->update($this->table, $filteredData, [$this->primaryKey => $id]);
        return $result > 0;
    }
    
    public function delete(int $id): bool {
        $result = $this->db->delete($this->table, [$this->primaryKey => $id]);
        return $result > 0;
    }
    
    public function softDelete(int $id): bool {
        return $this->update($id, ['deleted_at' => date('Y-m-d H:i:s')]);
    }
    
    protected function filterFillable(array $data): array {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    public function paginate(int $page = 1, int $perPage = null, array $conditions = [], string $orderBy = ''): array {
        $perPage = $perPage ?? ITEMS_PER_PAGE;
        $offset = ($page - 1) * $perPage;
        
        $total = $this->count($conditions);
        $items = $this->all($conditions, $orderBy, $perPage, $offset);
        
        return [
            'items' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => (int) ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ];
    }
    
    public function search(string $query, array $searchFields, array $conditions = []): array {
        $sql = "SELECT * FROM " . DB_PREFIX . $this->table . " WHERE (";
        $params = [];
        
        // Keresési feltételek
        $searchClauses = [];
        foreach ($searchFields as $field) {
            $searchClauses[] = "{$field} LIKE ?";
            $params[] = "%{$query}%";
        }
        $sql .= implode(' OR ', $searchClauses) . ")";
        
        // További feltételek
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $field => $value) {
                $whereClauses[] = "{$field} = ?";
                $params[] = $value;
            }
            $sql .= " AND " . implode(' AND ', $whereClauses);
        }
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function setData(array $data): void {
        $this->data = $data;
    }
    
    public function getData(): array {
        // Hidden mezők kiszűrése
        if (!empty($this->hidden)) {
            return array_diff_key($this->data, array_flip($this->hidden));
        }
        
        return $this->data;
    }
    
    public function __get(string $name) {
        return $this->data[$name] ?? null;
    }
    
    public function __set(string $name, $value): void {
        $this->data[$name] = $value;
    }
    
    public function beginTransaction(): void {
        $this->db->beginTransaction();
    }
    
    public function commit(): void {
        $this->db->commit();
    }
    
    public function rollback(): void {
        $this->db->rollback();
    }
}
