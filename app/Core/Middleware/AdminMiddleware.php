<?php
// app/Core/Middleware/AdminMiddleware.php

namespace Core\Middleware;

use Core\Auth;

class AdminMiddleware {
    public function handle(): bool {
        if (!Auth::check()) {
            Auth::redirectToLogin();
            return false;
        }
        
        if (!Auth::isAdmin()) {
            header('Location: ' . APP_URL . '/error/403');
            exit;
        }
        
        return true;
    }
}
