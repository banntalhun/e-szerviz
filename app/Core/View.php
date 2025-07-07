<?php
// app/Core/View.php

namespace Core;

class View {
    private array $data = [];
    private string $layout = 'main';
    
    public function render(string $view, array $data = [], bool $return = false): ?string {
        $this->data = array_merge($this->data, $data);
        
        // Extract változók
        extract($this->data);
        
        // View fájl elérési útja
        $viewFile = ROOT . '/app/Views/' . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("A view fájl nem található: {$viewFile}");
        }
        
        // Output buffer indítása
        ob_start();
        
        // View betöltése
        require $viewFile;
        
        $content = ob_get_clean();
        
        // Ha van layout
        if ($this->layout) {
            $layoutFile = ROOT . '/app/Views/layouts/' . $this->layout . '.php';
            
            if (!file_exists($layoutFile)) {
                throw new \Exception("A layout fájl nem található: {$layoutFile}");
            }
            
            ob_start();
            require $layoutFile;
            $output = ob_get_clean();
        } else {
            $output = $content;
        }
        
        if ($return) {
            return $output;
        }
        
        echo $output;
        return null;
    }
    
    public function renderPartial(string $partial, array $data = []): void {
        $this->layout = null;
        $this->render('partials/' . $partial, $data);
    }
    
    public function setLayout(string $layout): void {
        $this->layout = $layout;
    }
    
    public function setData(array $data): void {
        $this->data = array_merge($this->data, $data);
    }
    
    public function addCss(string $css): void {
        if (!isset($this->data['css'])) {
            $this->data['css'] = [];
        }
        $this->data['css'][] = $css;
    }
    
    public function addJs(string $js): void {
        if (!isset($this->data['js'])) {
            $this->data['js'] = [];
        }
        $this->data['js'][] = $js;
    }
    
    public function escape(string $string): string {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    public function url(string $path = ''): string {
        return APP_URL . '/' . ltrim($path, '/');
    }
    
    public function asset(string $path): string {
        return APP_URL . '/assets/' . ltrim($path, '/');
    }
    
    public function csrfField(): string {
        return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . Auth::csrfToken() . '">';
    }
    
    public function method(string $method): string {
        return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
    }
    
    public function old(string $field, string $default = ''): string {
        return isset($_SESSION['old'][$field]) ? $this->escape($_SESSION['old'][$field]) : $this->escape($default);
    }
    
    public function error(string $field): string {
        if (isset($_SESSION['errors'][$field])) {
            return '<span class="text-danger small">' . $this->escape($_SESSION['errors'][$field]) . '</span>';
        }
        return '';
    }
    
    public function hasError(string $field): bool {
        return isset($_SESSION['errors'][$field]);
    }
    
    public function success(): string {
        if (isset($_SESSION['success'])) {
            $message = $_SESSION['success'];
            unset($_SESSION['success']);
            return '<div class="alert alert-success alert-dismissible fade show" role="alert">' . 
                   $this->escape($message) . 
                   '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
        return '';
    }
    
    public function errorMessage(): string {
        if (isset($_SESSION['error'])) {
            $message = $_SESSION['error'];
            unset($_SESSION['error']);
            return '<div class="alert alert-danger alert-dismissible fade show" role="alert">' . 
                   $this->escape($message) . 
                   '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
        return '';
    }
    
    public function formatDate(string $date, string $format = 'Y.m.d H:i'): string {
        return date($format, strtotime($date));
    }
    
    public function formatPrice(float $price): string {
        return number_format($price, 0, ',', ' ') . ' Ft';
    }
}
