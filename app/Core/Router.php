<?php
// app/Core/Router.php

namespace Core;

class Router {
    private array $routes = [];
    private array $params = [];
    
    public function add(string $route, array $params = []): void {
    // Konvertáljuk a route-ot reguláris kifejezéssé
    $route = preg_replace('/\//', '\\/', $route);
    
    // JAVÍTÁS: engedjük a camelCase paraméter neveket is (a-zA-Z)
    $route = preg_replace('/\{([a-zA-Z]+)\}/', '(?P<\1>[^\/]+)', $route);
    $route = preg_replace('/\{([a-zA-Z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
    
    $route = '/^' . $route . '$/i';
    
    $this->routes[$route] = $params;
}
    
    public function get(string $route, array $params): void {
        $params['method'] = 'GET';
        $this->add($route, $params);
    }
    
    public function post(string $route, array $params): void {
        $params['method'] = 'POST';
        $this->add($route, $params);
    }
    
    public function put(string $route, array $params): void {
        $params['method'] = 'PUT';
        $this->add($route, $params);
    }
    
    public function delete(string $route, array $params): void {
        $params['method'] = 'DELETE';
        $this->add($route, $params);
    }
    
    public function match(string $url): bool {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                // Ellenőrizzük a HTTP metódust
                if (isset($params['method']) && $params['method'] !== $_SERVER['REQUEST_METHOD']) {
                    continue;
                }
                
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                
                $this->params = $params;
                return true;
            }
        }
        
        return false;
    }
    
    public function dispatch(string $url): void {
        $url = $this->removeQueryStringVariables($url);
        
        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = "Controllers\\{$controller}Controller";
            
            if (class_exists($controller)) {
                $controllerObject = new $controller($this->params);
                
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);
                
                if (is_callable([$controllerObject, $action])) {
                    // Middleware ellenőrzés
                    if (isset($this->params['middleware'])) {
                        $middleware = $this->params['middleware'];
                        if (!$this->checkMiddleware($middleware)) {
                            return;
                        }
                    }
                    
                    unset($this->params['controller']);
                    unset($this->params['action']);
                    unset($this->params['method']);
                    unset($this->params['middleware']);
                    
                    call_user_func_array([$controllerObject, $action], array_values($this->params));
                } else {
                    throw new \Exception("A metódus {$action} nem található a {$controller} kontrollerben");
                }
            } else {
                throw new \Exception("A {$controller} kontroller osztály nem található");
            }
        } else {
            $this->notFound();
        }
    }
    
    private function removeQueryStringVariables(string $url): string {
        if ($url !== '') {
            $parts = explode('&', $url, 2);
            
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        
        return rtrim($url, '/');
    }
    
    private function convertToStudlyCaps(string $string): string {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }
    
    private function convertToCamelCase(string $string): string {
        return lcfirst($this->convertToStudlyCaps($string));
    }
    
    private function checkMiddleware(string $middleware): bool {
        $middlewareClass = "Core\\Middleware\\{$middleware}";
        
        if (class_exists($middlewareClass)) {
            $middlewareObject = new $middlewareClass();
            return $middlewareObject->handle();
        }
        
        return true;
    }
    
    private function notFound(): void {
        header("HTTP/1.0 404 Not Found");
        $view = new View();
        $view->render('errors/404');
        exit;
    }
}
