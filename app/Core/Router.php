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
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
}
