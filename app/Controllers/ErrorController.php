<?php
// app/Controllers/ErrorController.php

namespace Controllers;

use Core\Controller;

class ErrorController extends Controller {
    
    public function forbidden(): void {
        http_response_code(403);
        $this->view->render('errors/403');
    }
    
    public function notFound(): void {
        http_response_code(404);
        $this->view->render('errors/404');
    }
    
    public function serverError(): void {
        http_response_code(500);
        $this->view->render('errors/500');
    }
}
