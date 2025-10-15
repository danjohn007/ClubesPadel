<?php
namespace Controllers;

use Core\Controller;

class ReportsController extends Controller {
    
    public function index() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $clubId = $this->getClubId();
        
        $data = [
            'title' => 'Reportes',
            'club_id' => $clubId
        ];
        
        $this->view('reports/index', $data);
    }
}
