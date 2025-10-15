<?php
namespace Controllers;

use Core\Controller;

class AuthController extends Controller {
    
    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            if ($this->isSuperAdmin()) {
                $this->redirect('superadmin/dashboard');
            } else {
                $this->redirect('dashboard');
            }
        }
        
        $data = [
            'title' => 'Iniciar Sesión',
            'error' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $data['error'] = 'Por favor ingresa tu email y contraseña';
            } else {
                $userModel = $this->model('User');
                $user = $userModel->authenticate($email, $password);
                
                if ($user) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                    $_SESSION['club_id'] = $user['club_id'];
                    
                    // Log activity
                    $this->logActivity($user['club_id'], $user['id'], 'user_login', 'Inicio de sesión exitoso');
                    
                    // Redirect based on role
                    if ($user['role'] === 'superadmin') {
                        $this->redirect('superadmin/dashboard');
                    } else {
                        $this->redirect('dashboard');
                    }
                } else {
                    $data['error'] = 'Email o contraseña incorrectos';
                }
            }
        }
        
        $this->view('auth/login', $data);
    }
    
    public function logout() {
        // Log activity before destroying session
        if ($this->isLoggedIn()) {
            $this->logActivity($this->getClubId(), $this->getUserId(), 'user_logout', 'Cierre de sesión');
        }
        
        // Destroy session
        session_destroy();
        
        // Redirect to home
        $this->redirect('');
    }
    
    public function register() {
        $data = [
            'title' => 'Registro',
            'error' => '',
            'success' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $userType = $_POST['user_type'] ?? 'player';
            $clubName = $_POST['club_name'] ?? '';
            $captcha = $_POST['captcha'] ?? '';
            
            // Validation
            if (empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
                $data['error'] = 'Todos los campos obligatorios deben ser completados';
            } elseif ($userType === 'club' && empty($clubName)) {
                $data['error'] = 'El nombre del club es obligatorio';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Email inválido';
            } elseif (strlen($password) < PASSWORD_MIN_LENGTH) {
                $data['error'] = 'La contraseña debe tener al menos ' . PASSWORD_MIN_LENGTH . ' caracteres';
            } elseif ($password !== $confirmPassword) {
                $data['error'] = 'Las contraseñas no coinciden';
            } elseif (empty($captcha) || !isset($_SESSION['captcha_answer']) || intval($captcha) !== intval($_SESSION['captcha_answer'])) {
                $data['error'] = 'Respuesta de verificación incorrecta';
            } else {
                $userModel = $this->model('User');
                
                // Check if email already exists
                if ($userModel->findByEmail($email)) {
                    $data['error'] = 'Este email ya está registrado';
                } else {
                    // If user type is club, create the club first
                    $clubId = null;
                    if ($userType === 'club') {
                        $clubModel = $this->model('Club');
                        $clubData = [
                            'name' => $clubName,
                            'email' => $email,
                            'phone' => $phone,
                            'is_active' => 1,
                            'subscription_plan_id' => 1 // Default to basic plan
                        ];
                        $clubId = $clubModel->create($clubData);
                        
                        if (!$clubId) {
                            $data['error'] = 'Error al crear el club. Por favor intenta nuevamente.';
                            $this->view('auth/register', $data);
                            return;
                        }
                    }
                    
                    // Create user
                    $userData = [
                        'email' => $email,
                        'password' => $password,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'phone' => $phone,
                        'role' => $userType === 'club' ? 'admin' : 'player',
                        'club_id' => $clubId
                    ];
                    
                    $userId = $userModel->create($userData);
                    
                    if ($userId) {
                        $data['success'] = 'Registro exitoso. Ya puedes iniciar sesión.';
                    } else {
                        $data['error'] = 'Error al crear la cuenta. Por favor intenta nuevamente.';
                    }
                }
            }
            
            // Clear captcha answer
            unset($_SESSION['captcha_answer']);
        }
        
        $this->view('auth/register', $data);
    }
    
    private function logActivity($clubId, $userId, $action, $description) {
        $sql = "INSERT INTO activity_log (club_id, user_id, action, description, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $params = [
            $clubId,
            $userId,
            $action,
            $description,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ];
        
        $this->getDb()->query($sql, $params);
    }
}
