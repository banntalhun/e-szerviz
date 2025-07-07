<?php
// app/Core/Database.php

namespace Core;

use PDO;
use PDOException;

class Database {
    private static ?Database $instance = null;
    private PDO $connection;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
            ];
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            throw new \Exception("Adatbázis kapcsolat hiba: " . $e->getMessage());
        }
    }
    
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection(): PDO {
        return $this->connection;
    }
    
    public function query(string $sql, array $params = []): \PDOStatement {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new \Exception("Adatbázis lekérdezési hiba: " . $e->getMessage());
        }
    }
    
    public function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    public function fetchOne(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    public function insert(string $table, array $data): int {
        $table = DB_PREFIX . $table;
        $fields = array_keys($data);
        $placeholders = array_map(function($field) { return ':' . $field; }, $fields);
        
        $sql = "INSERT INTO {$table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        $this->query($sql, $data);
        return (int) $this->connection->lastInsertId();
    }
    
    public function update(string $table, array $data, array $where): int {
        $table = DB_PREFIX . $table;
        $setParts = [];
        $params = [];
        
        foreach ($data as $field => $value) {
            $setParts[] = "{$field} = :set_{$field}";
            $params["set_{$field}"] = $value;
        }
        
        $whereParts = [];
        foreach ($where as $field => $value) {
            $whereParts[] = "{$field} = :where_{$field}";
            $params["where_{$field}"] = $value;
        }
        
        $sql = "UPDATE {$table} SET " . implode(', ', $setParts) . " WHERE " . implode(' AND ', $whereParts);
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    public function delete(string $table, array $where): int {
        $table = DB_PREFIX . $table;
        $whereParts = [];
        $params = [];
        
        foreach ($where as $field => $value) {
            $whereParts[] = "{$field} = :where_{$field}";
            $params["where_{$field}"] = $value;
        }
        
        $sql = "DELETE FROM {$table} WHERE " . implode(' AND ', $whereParts);
        
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    public function beginTransaction(): void {
        $this->connection->beginTransaction();
    }
    
    public function commit(): void {
        $this->connection->commit();
    }
    
    public function rollback(): void {
        $this->connection->rollBack();
    }
    
    public function getLastInsertId(): int {
        return (int) $this->connection->lastInsertId();
    }
    
    private function __clone() {}
    
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}
