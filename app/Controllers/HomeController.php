<?php
namespace Controllers;

use Core\Controller;

class HomeController extends Controller {
    
    public function index() {
        // If user is logged in, redirect to appropriate dashboard
        if ($this->isLoggedIn()) {
            if ($this->isSuperAdmin()) {
                $this->redirect('superadmin/dashboard');
            } else {
                $this->redirect('dashboard');
            }
        }
        
        $data = [
            'title' => 'Bienvenido a ' . APP_NAME
        ];
        
        $this->view('home/index', $data);
    }
    
    public function about() {
        $data = [
            'title' => 'Acerca de ' . APP_NAME
        ];
        
        $this->view('home/about', $data);
    }
}
