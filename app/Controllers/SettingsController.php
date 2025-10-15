<?php
namespace Controllers;

use Core\Controller;

class SettingsController extends Controller {
    
    public function index() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $data = [
            'title' => 'Ajustes',
            'error' => '',
            'success' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle settings update
            $data['success'] = 'Configuración actualizada exitosamente';
        }
        
        $this->view('settings/index', $data);
    }
}
