<?php
// app/Core/Auth.php

namespace Core;

use Models\User;

class Auth {
    private static ?User $user = null;
    
    public static function attempt(string $username, string $password): bool {
        $userModel = new User();
        $user = $userModel->findByUsername($username);
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['is_active'] == 1) {
                self::login($user);
                return true;
            }
        }
        
        return false;
    }
    
    public static function login(array $user): void {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        // CSRF token generálása
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        // Utolsó bejelentkezés frissítése
        $userModel = new User();
        $userModel->updateLastLogin($user['id']);
    }
    
    public static function logout(): void {
        // Session változók törlése
        $_SESSION = [];
        
        // Session cookie törlése
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Session megsemmisítése
        session_destroy();
    }
    
    public static function check(): bool {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            // Session timeout ellenőrzés
            if (isset($_SESSION['login_time'])) {
                if (time() - $_SESSION['login_time'] > SESSION_LIFETIME) {
                    self::logout();
                    return false;
                }
            }
            
            return true;
        }
        
        return false;
    }
    
    public static function user(): ?User {
        if (self::check() && self::$user === null) {
            $userModel = new User();
            $userData = $userModel->find($_SESSION['user_id']);
            if ($userData) {
                self::$user = new User();
                self::$user->setData($userData);
            }
        }
        
        return self::$user;
    }
    
    public static function id(): ?int {
        return self::check() ? $_SESSION['user_id'] : null;
    }
    
    public static function username(): ?string {
        return self::check() ? $_SESSION['username'] : null;
    }
    
    public static function roleId(): ?int {
        return self::check() ? $_SESSION['role_id'] : null;
    }
    
    public static function hasPermission(string $permission): bool {
        if (!self::check()) {
            return false;
        }
        
        $user = self::user();
        if ($user) {
            return $user->hasPermission($permission);
        }
        
        return false;
    }
    
    public static function isAdmin(): bool {
        if (!self::check()) {
            return false;
        }
        
        $user = self::user();
        if ($user) {
            return $user->isAdmin();
        }
        
        return false;
    }
    
    public static function csrfToken(): string {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    public static function verifyCsrfToken(string $token): bool {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public static function generatePassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => PASSWORD_SALT_ROUNDS]);
    }
    
    public static function redirect(string $url = ''): void {
        header('Location: ' . APP_URL . '/' . $url);
        exit;
    }
    
    public static function redirectToLogin(): void {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        self::redirect('login');
    }
    
    public static function getRedirectUrl(): string {
        if (isset($_SESSION['redirect_after_login'])) {
            $url = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            return $url;
        }
        
        return APP_URL . '/dashboard';
    }
}
