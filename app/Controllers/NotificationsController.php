<?php
namespace Controllers;

use Core\Controller;

class NotificationsController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $data = [
            'title' => 'Notificaciones',
            'notifications' => []
        ];
        
        $this->view('notifications/index', $data);
    }
}
