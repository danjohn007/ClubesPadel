<?php
namespace Controllers;

use Core\Controller;

class UsersController extends Controller {
    
    public function index() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $clubId = $this->getClubId();
        $userModel = $this->model('User');
        $users = $userModel->getByClub($clubId);
        
        $data = [
            'title' => 'Gestión de Usuarios',
            'users' => $users
        ];
        
        $this->view('users/index', $data);
    }
}
