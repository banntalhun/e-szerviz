<?php
// app/Core/Controller.php

namespace Core;

abstract class Controller {
    protected array $routeParams = [];
    protected View $view;
    protected Database $db;
    
    public function __construct(array $routeParams) {
        $this->routeParams = $routeParams;
        $this->view = new View();
        $this->db = Database::getInstance();
        
        // CSRF token ellenőrzés POST kéréseknél
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrfToken();
        }
    }
    
    protected function checkCsrfToken(): void {
        $token = $_POST[CSRF_TOKEN_NAME] ?? '';
        
        if (!Auth::verifyCsrfToken($token)) {
            $this->redirect('error/403');
        }
    }
    
    protected function redirect(string $url = ''): void {
        header('Location: ' . APP_URL . '/' . ltrim($url, '/'));
        exit;
    }
    
    protected function redirectBack(): void {
        $referer = $_SERVER['HTTP_REFERER'] ?? APP_URL;
        header('Location: ' . $referer);
        exit;
    }
    
    protected function json(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    protected function success(string $message): void {
        $_SESSION['success'] = $message;
    }
    
    protected function error(string $message): void {
        $_SESSION['error'] = $message;
    }
    
    protected function setOldInput(array $data): void {
        $_SESSION['old'] = $data;
    }
    
    protected function setErrors(array $errors): void {
        $_SESSION['errors'] = $errors;
    }
    
    protected function clearFlashData(): void {
        unset($_SESSION['old']);
        unset($_SESSION['errors']);
        unset($_SESSION['success']);
        unset($_SESSION['error']);
    }
    
    protected function validate(array $data, array $rules): array {
        $errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';
            $rulesArray = explode('|', $fieldRules);
            
            foreach ($rulesArray as $rule) {
                if (strpos($rule, ':') !== false) {
                    [$ruleName, $parameter] = explode(':', $rule, 2);
                } else {
                    $ruleName = $rule;
                    $parameter = null;
                }
                
                $error = $this->validateRule($field, $value, $ruleName, $parameter, $data);
                if ($error) {
                    $errors[$field] = $error;
                    break;
                }
            }
        }
        
        return $errors;
    }
    
    private function validateRule(string $field, $value, string $rule, ?string $parameter, array $data): ?string {
        switch ($rule) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    return "A(z) {$field} mező kitöltése kötelező.";
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return "A(z) {$field} mező érvényes email címet kell tartalmazzon.";
                }
                break;
                
            case 'min':
                if (strlen($value) < (int)$parameter) {
                    return "A(z) {$field} mező legalább {$parameter} karakter hosszú kell legyen.";
                }
                break;
                
            case 'max':
                if (strlen($value) > (int)$parameter) {
                    return "A(z) {$field} mező maximum {$parameter} karakter hosszú lehet.";
                }
                break;
                
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    return "A(z) {$field} mező csak számokat tartalmazhat.";
                }
                break;
                
            case 'unique':
                if (!empty($value) && $parameter) {
                    [$table, $column] = explode('.', $parameter);
                    $sql = "SELECT COUNT(*) as count FROM " . DB_PREFIX . $table . " WHERE {$column} = ?";
                    $result = $this->db->fetchOne($sql, [$value]);
                    if ($result['count'] > 0) {
                        return "A(z) {$field} már létezik.";
                    }
                }
                break;
                
            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if ($value !== ($data[$confirmField] ?? '')) {
                    return "A(z) {$field} megerősítése nem egyezik.";
                }
                break;
        }
        
        return null;
    }
    
    protected function upload(string $fieldName, string $directory = ''): ?string {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $file = $_FILES[$fieldName];
        
        // Fájl méret ellenőrzés
        if ($file['size'] > MAX_FILE_SIZE) {
            $this->error('A fájl mérete túl nagy. Maximum ' . (MAX_FILE_SIZE / 1024 / 1024) . 'MB lehet.');
            return null;
        }
        
        // Kiterjesztés ellenőrzés
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ALLOWED_EXTENSIONS)) {
            $this->error('Nem engedélyezett fájl típus.');
            return null;
        }
        
        // Cél könyvtár
        $uploadDir = UPLOAD_PATH . $directory;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Egyedi fájlnév generálás
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . '/' . $filename;
        
        // Fájl mozgatás
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $directory . '/' . $filename;
        }
        
        return null;
    }
    
    protected function deleteFile(string $filepath): bool {
        $fullPath = UPLOAD_PATH . $filepath;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
    
    protected function requireAuth(): void {
        if (!Auth::check()) {
            Auth::redirectToLogin();
        }
    }
    
    protected function requireAdmin(): void {
        $this->requireAuth();
        
        if (!Auth::isAdmin()) {
            $this->redirect('error/403');
        }
    }
    
    protected function requirePermission(string $permission): void {
        $this->requireAuth();
        
        if (!Auth::hasPermission($permission)) {
            $this->redirect('error/403');
        }
    }
}
