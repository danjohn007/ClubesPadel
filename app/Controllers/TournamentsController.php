<?php
namespace Controllers;

use Core\Controller;

class TournamentsController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $clubId = $this->getClubId();
        $tournamentModel = $this->model('Tournament');
        $tournaments = $tournamentModel->getAll($clubId);
        
        $data = [
            'title' => 'Torneos',
            'tournaments' => $tournaments
        ];
        
        $this->view('tournaments/index', $data);
    }
    
    public function create() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $data = [
            'title' => 'Crear Torneo',
            'error' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $tournamentType = $_POST['tournament_type'] ?? 'doubles';
            $format = $_POST['format'] ?? 'elimination';
            $registrationFee = $_POST['registration_fee'] ?? 0;
            $maxParticipants = $_POST['max_participants'] ?? null;
            $startDate = $_POST['start_date'] ?? '';
            $endDate = $_POST['end_date'] ?? '';
            $registrationDeadline = $_POST['registration_deadline'] ?? null;
            
            if (empty($name) || empty($startDate) || empty($endDate)) {
                $data['error'] = 'Nombre, fecha de inicio y fin son obligatorios';
            } else {
                $clubId = $this->getClubId();
                $tournamentModel = $this->model('Tournament');
                
                $tournamentData = [
                    'club_id' => $clubId,
                    'name' => $name,
                    'description' => $description,
                    'tournament_type' => $tournamentType,
                    'format' => $format,
                    'registration_fee' => $registrationFee,
                    'max_participants' => $maxParticipants,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'registration_deadline' => $registrationDeadline,
                    'status' => 'upcoming',
                    'created_by' => $this->getUserId()
                ];
                
                $tournamentId = $tournamentModel->create($tournamentData);
                
                if ($tournamentId) {
                    $this->redirect('tournaments?success=created');
                } else {
                    $data['error'] = 'Error al crear el torneo';
                }
            }
        }
        
        $this->view('tournaments/create', $data);
    }
    
    public function viewTournament($id) {
        $this->requireAuth();
        
        $clubId = $this->getClubId();
        $tournamentModel = $this->model('Tournament');
        $tournament = $tournamentModel->findById($id, $clubId);
        
        if (!$tournament) {
            $this->redirect('tournaments?error=not_found');
        }
        
        $participants = $tournamentModel->getParticipants($id);
        
        $data = [
            'title' => 'Detalles del Torneo',
            'tournament' => $tournament,
            'participants' => $participants
        ];
        
        $this->view('tournaments/view', $data);
    }
    
    public function register($id) {
        $this->requireAuth();
        
        $clubId = $this->getClubId();
        $tournamentModel = $this->model('Tournament');
        $tournament = $tournamentModel->findById($id, $clubId);
        
        if (!$tournament) {
            $this->redirect('tournaments?error=not_found');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $partnerId = $_POST['partner_id'] ?? null;
            
            $participantData = [
                'tournament_id' => $id,
                'user_id' => $this->getUserId(),
                'partner_id' => $partnerId,
                'payment_status' => 'unpaid',
                'status' => 'registered'
            ];
            
            if ($tournamentModel->registerParticipant($participantData)) {
                $this->redirect('tournaments/view/' . $id . '?success=registered');
            } else {
                $this->redirect('tournaments/view/' . $id . '?error=registration_failed');
            }
        }
    }
}
