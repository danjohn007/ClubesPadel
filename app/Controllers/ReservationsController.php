<?php
namespace Controllers;

use Core\Controller;

class ReservationsController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $clubId = $this->getClubId();
        $reservationModel = $this->model('Reservation');
        
        $filters = [];
        if (isset($_GET['date'])) {
            $filters['date'] = $_GET['date'];
        }
        if (isset($_GET['court_id'])) {
            $filters['court_id'] = $_GET['court_id'];
        }
        
        $reservations = $reservationModel->getAll($clubId, $filters);
        
        // Get courts for filter
        $courtModel = $this->model('Court');
        $courts = $courtModel->getAll($clubId);
        
        $data = [
            'title' => 'Gestión de Reservaciones',
            'reservations' => $reservations,
            'courts' => $courts,
            'filters' => $filters
        ];
        
        $this->view('reservations/index', $data);
    }
    
    public function create() {
        $this->requireAuth();
        
        $clubId = $this->getClubId();
        $courtModel = $this->model('Court');
        $courts = $courtModel->getAll($clubId);
        
        // Get users for selection (if admin/receptionist)
        $users = [];
        if (in_array($this->getUserRole(), ['admin', 'receptionist'])) {
            $userModel = $this->model('User');
            $users = $userModel->getByClub($clubId);
        }
        
        $data = [
            'title' => 'Nueva Reservación',
            'courts' => $courts,
            'users' => $users,
            'error' => '',
            'success' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courtId = $_POST['court_id'] ?? '';
            $reservationDate = $_POST['reservation_date'] ?? '';
            $startTime = $_POST['start_time'] ?? '';
            $endTime = $_POST['end_time'] ?? '';
            $notes = $_POST['notes'] ?? '';
            
            // Determine user ID
            $userId = $this->getUserId();
            if (in_array($this->getUserRole(), ['admin', 'receptionist']) && !empty($_POST['user_id'])) {
                $userId = $_POST['user_id'];
            }
            
            if (empty($courtId) || empty($reservationDate) || empty($startTime) || empty($endTime)) {
                $data['error'] = 'Todos los campos obligatorios deben ser completados';
            } else {
                // Calculate duration and price
                $start = strtotime($startTime);
                $end = strtotime($endTime);
                $durationHours = ($end - $start) / 3600;
                
                // Get court price
                $court = $courtModel->findById($courtId, $clubId);
                $totalPrice = $durationHours * $court['hourly_price'];
                
                $reservationModel = $this->model('Reservation');
                
                $reservationData = [
                    'club_id' => $clubId,
                    'court_id' => $courtId,
                    'user_id' => $userId,
                    'reservation_date' => $reservationDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration_hours' => $durationHours,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'notes' => $notes
                ];
                
                $reservationId = $reservationModel->create($reservationData);
                
                if ($reservationId) {
                    $this->redirect('reservations?success=created');
                } else {
                    $data['error'] = 'Error al crear la reservación';
                }
            }
        }
        
        $this->view('reservations/create', $data);
    }
    
    public function calendar() {
        $this->requireAuth();
        
        $clubId = $this->getClubId();
        $reservationModel = $this->model('Reservation');
        
        // Get reservations for current month
        $date = $_GET['date'] ?? date('Y-m-d');
        $reservations = $reservationModel->getByDate($clubId, $date);
        
        $data = [
            'title' => 'Calendario de Reservaciones',
            'reservations' => $reservations,
            'date' => $date
        ];
        
        $this->view('reservations/calendar', $data);
    }
    
    public function update($id) {
        $this->requireAuth();
        
        $clubId = $this->getClubId();
        $reservationModel = $this->model('Reservation');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'] ?? '';
            $paymentStatus = $_POST['payment_status'] ?? '';
            
            $updateData = [
                'status' => $status,
                'payment_status' => $paymentStatus
            ];
            
            if ($reservationModel->update($id, $clubId, $updateData)) {
                $this->redirect('reservations?success=updated');
            } else {
                $this->redirect('reservations?error=update_failed');
            }
        }
    }
    
    public function cancel($id) {
        $this->requireAuth();
        
        $clubId = $this->getClubId();
        $reservationModel = $this->model('Reservation');
        
        $updateData = ['status' => 'cancelled'];
        
        if ($reservationModel->update($id, $clubId, $updateData)) {
            $this->redirect('reservations?success=cancelled');
        } else {
            $this->redirect('reservations?error=cancel_failed');
        }
    }
}
