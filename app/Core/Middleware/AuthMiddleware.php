<?php
// app/Core/Middleware/AuthMiddleware.php

namespace Core\Middleware;

use Core\Auth;

class AuthMiddleware {
    public function handle(): bool {
        if (!Auth::check()) {
            Auth::redirectToLogin();
            return false;
        }
        
        return true;
    }
}
