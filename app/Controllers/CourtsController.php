<?php
namespace Controllers;

use Core\Controller;

class CourtsController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $clubId = $this->getClubId();
        $courtModel = $this->model('Court');
        $courts = $courtModel->getAll($clubId);
        
        $data = [
            'title' => 'Gestión de Canchas',
            'courts' => $courts
        ];
        
        $this->view('courts/index', $data);
    }
    
    public function create() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $data = [
            'title' => 'Nueva Cancha',
            'error' => '',
            'success' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $clubId = $this->getClubId();
            $name = $_POST['name'] ?? '';
            $courtType = $_POST['court_type'] ?? 'outdoor';
            $surface = $_POST['surface'] ?? '';
            $hasLighting = isset($_POST['has_lighting']) ? 1 : 0;
            $hourlyPrice = $_POST['hourly_price'] ?? 0;
            $status = $_POST['status'] ?? 'available';
            $description = $_POST['description'] ?? '';
            
            if (empty($name) || empty($hourlyPrice)) {
                $data['error'] = 'El nombre y precio por hora son obligatorios';
            } else {
                $courtModel = $this->model('Court');
                
                $courtData = [
                    'club_id' => $clubId,
                    'name' => $name,
                    'court_type' => $courtType,
                    'surface' => $surface,
                    'has_lighting' => $hasLighting,
                    'hourly_price' => $hourlyPrice,
                    'status' => $status,
                    'description' => $description
                ];
                
                $courtId = $courtModel->create($courtData);
                
                if ($courtId) {
                    $this->redirect('courts?success=created');
                } else {
                    $data['error'] = 'Error al crear la cancha';
                }
            }
        }
        
        $this->view('courts/create', $data);
    }
    
    public function edit($id) {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $clubId = $this->getClubId();
        $courtModel = $this->model('Court');
        $court = $courtModel->findById($id, $clubId);
        
        if (!$court) {
            $this->redirect('courts?error=not_found');
        }
        
        $data = [
            'title' => 'Editar Cancha',
            'court' => $court,
            'error' => '',
            'success' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $courtType = $_POST['court_type'] ?? 'outdoor';
            $surface = $_POST['surface'] ?? '';
            $hasLighting = isset($_POST['has_lighting']) ? 1 : 0;
            $hourlyPrice = $_POST['hourly_price'] ?? 0;
            $status = $_POST['status'] ?? 'available';
            $description = $_POST['description'] ?? '';
            
            if (empty($name) || empty($hourlyPrice)) {
                $data['error'] = 'El nombre y precio por hora son obligatorios';
            } else {
                $courtData = [
                    'name' => $name,
                    'court_type' => $courtType,
                    'surface' => $surface,
                    'has_lighting' => $hasLighting,
                    'hourly_price' => $hourlyPrice,
                    'status' => $status,
                    'description' => $description
                ];
                
                if ($courtModel->update($id, $clubId, $courtData)) {
                    $this->redirect('courts?success=updated');
                } else {
                    $data['error'] = 'Error al actualizar la cancha';
                }
            }
        }
        
        $this->view('courts/edit', $data);
    }
    
    public function delete($id) {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $clubId = $this->getClubId();
        $courtModel = $this->model('Court');
        
        if ($courtModel->delete($id, $clubId)) {
            $this->redirect('courts?success=deleted');
        } else {
            $this->redirect('courts?error=delete_failed');
        }
    }
    
    public function view($id) {
        $this->requireAuth();
        
        $clubId = $this->getClubId();
        $courtModel = $this->model('Court');
        $court = $courtModel->findById($id, $clubId);
        
        if (!$court) {
            $this->redirect('courts?error=not_found');
        }
        
        // Get reservations for this court
        $sql = "SELECT r.*, u.first_name, u.last_name 
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                WHERE r.court_id = ? AND r.club_id = ?
                ORDER BY r.reservation_date DESC, r.start_time DESC
                LIMIT 10";
        $reservations = $this->getDb()->fetchAll($sql, [$id, $clubId]);
        
        $data = [
            'title' => 'Detalles de Cancha',
            'court' => $court,
            'reservations' => $reservations
        ];
        
        $this->view('courts/view', $data);
    }
}
