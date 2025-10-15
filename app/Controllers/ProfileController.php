<?php
namespace Controllers;

use Core\Controller;

class ProfileController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $userId = $this->getUserId();
        $userModel = $this->model('User');
        $user = $userModel->findById($userId);
        
        $data = [
            'title' => 'Mi Perfil',
            'user' => $user,
            'error' => '',
            'success' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            
            if (empty($firstName) || empty($lastName)) {
                $data['error'] = 'Nombre y apellido son obligatorios';
            } else {
                $userData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $phone
                ];
                
                if ($userModel->update($userId, $userData)) {
                    $_SESSION['user_name'] = $firstName . ' ' . $lastName;
                    $data['success'] = 'Perfil actualizado exitosamente';
                    $data['user'] = $userModel->findById($userId);
                } else {
                    $data['error'] = 'Error al actualizar el perfil';
                }
            }
        }
        
        $this->view('profile/index', $data);
    }
}
