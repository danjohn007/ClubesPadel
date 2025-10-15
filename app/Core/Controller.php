<?php
namespace Core;

/**
 * Base Controller
 * All controllers extend this class
 */
class Controller {
    protected $db;
    
    public function __construct() {
        // Database connection is now lazy-loaded when first accessed
    }
    
    /**
     * Get database instance (lazy loading)
     */
    protected function getDb() {
        if ($this->db === null) {
            $this->db = Database::getInstance();
        }
        return $this->db;
    }
    
    /**
     * Load a view file
     */
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = APP_PATH . '/Views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: " . $view);
        }
    }
    
    /**
     * Load a model
     */
    protected function model($model) {
        $modelFile = APP_PATH . '/Models/' . $model . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            $modelClass = 'Models\\' . $model;
            return new $modelClass();
        } else {
            die("Model not found: " . $model);
        }
    }
    
    /**
     * Redirect to another page
     */
    protected function redirect($path = '') {
        $url = URL_BASE . '/' . ltrim($path, '/');
        header("Location: " . $url);
        exit;
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Check if user is logged in
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Get current user ID
     */
    protected function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role
     */
    protected function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    /**
     * Get current club ID (for tenant isolation)
     */
    protected function getClubId() {
        return $_SESSION['club_id'] ?? null;
    }
    
    /**
     * Require authentication
     */
    protected function requireAuth() {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }
    
    /**
     * Require specific role
     */
    protected function requireRole($role) {
        $this->requireAuth();
        if ($this->getUserRole() !== $role) {
            $this->redirect('dashboard');
        }
    }
    
    /**
     * Check if user is super admin
     */
    protected function isSuperAdmin() {
        return $this->getUserRole() === 'superadmin';
    }
}
