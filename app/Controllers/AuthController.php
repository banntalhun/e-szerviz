<?php
// app/Controllers/AuthController.php

namespace Controllers;

use Core\Controller;
use Core\Auth;

class AuthController extends Controller {
    
    public function login(): void {
        if (Auth::check()) {
            $this->redirect('dashboard');
        }
        
        $this->view->setLayout('auth');
        $this->view->render('auth/login');
    }
    
    public function doLogin(): void {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Validáció
        $errors = [];
        if (empty($username)) {
            $errors['username'] = 'A felhasználónév megadása kötelező.';
        }
        if (empty($password)) {
            $errors['password'] = 'A jelszó megadása kötelező.';
        }
        
        if (!empty($errors)) {
            $this->setErrors($errors);
            $this->setOldInput($_POST);
            $this->redirectBack();
        }
        
        // Bejelentkezés
        if (Auth::attempt($username, $password)) {
            $this->success('Sikeres bejelentkezés!');
            $redirectUrl = Auth::getRedirectUrl();
            header('Location: ' . $redirectUrl);
            exit;
        } else {
            $this->error('Hibás felhasználónév vagy jelszó!');
            $this->setOldInput(['username' => $username]);
            $this->redirectBack();
        }
    }
    
    public function logout(): void {
        Auth::logout();
        $this->success('Sikeres kijelentkezés!');
        $this->redirect('login');
    }
}
