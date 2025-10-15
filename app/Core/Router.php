<?php
namespace Core;

/**
 * Router - Handles URL routing
 * Supports friendly URLs with controller/action/params pattern
 */
class Router {
    private $controller = 'HomeController';
    private $method = 'index';
    private $params = [];
    
    public function __construct() {
        $url = $this->parseUrl();
        
        // Check for controller
        if (isset($url[0]) && !empty($url[0])) {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerFile = APP_PATH . '/Controllers/' . $controllerName . '.php';
            
            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                // Controller not found - will show 404
                $this->show404();
                return;
            }
        }
        
        // Require controller file
        require_once APP_PATH . '/Controllers/' . $this->controller . '.php';
        
        // Instantiate controller
        $controllerClass = 'Controllers\\' . $this->controller;
        $this->controller = new $controllerClass;
        
        // Check for method
        if (isset($url[1]) && !empty($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                // Method not found - will show 404
                $this->show404();
                return;
            }
        }
        
        // Get params
        $this->params = $url ? array_values($url) : [];
    }
    
    private function parseUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
    
    public function dispatch() {
        if ($this->controller !== null) {
            call_user_func_array([$this->controller, $this->method], $this->params);
        }
    }
    
    private function show404() {
        http_response_code(404);
        $this->controller = null; // Prevent dispatch
        
        $viewFile = APP_PATH . '/Views/errors/404.php';
        if (file_exists($viewFile)) {
            $title = '404 - Página No Encontrada';
            require_once $viewFile;
        } else {
            echo "<h1>404 - Página No Encontrada</h1>";
            echo "<p>La página que buscas no existe.</p>";
        }
        exit;
    }
}
