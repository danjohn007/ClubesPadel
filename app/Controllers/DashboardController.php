<?php
namespace Controllers;

use Core\Controller;

class DashboardController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $clubId = $this->getClubId();
        $role = $this->getUserRole();
        
        // Get dashboard statistics
        $stats = $this->getDashboardStats($clubId);
        
        $data = [
            'title' => 'Dashboard',
            'stats' => $stats,
            'role' => $role
        ];
        
        $this->view('dashboard/index', $data);
    }
    
    private function getDashboardStats($clubId) {
        $stats = [];
        
        // Total reservations today
        $sql = "SELECT COUNT(*) as count FROM reservations 
                WHERE club_id = ? AND reservation_date = CURDATE()";
        $result = $this->db->fetchOne($sql, [$clubId]);
        $stats['reservations_today'] = $result['count'] ?? 0;
        
        // Total income this month
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM income_transactions 
                WHERE club_id = ? AND MONTH(transaction_date) = MONTH(CURDATE())";
        $result = $this->db->fetchOne($sql, [$clubId]);
        $stats['income_month'] = $result['total'] ?? 0;
        
        // Total active members
        $sql = "SELECT COUNT(*) as count FROM users 
                WHERE club_id = ? AND is_active = 1 AND role = 'player'";
        $result = $this->db->fetchOne($sql, [$clubId]);
        $stats['active_members'] = $result['count'] ?? 0;
        
        // Active tournaments
        $sql = "SELECT COUNT(*) as count FROM tournaments 
                WHERE club_id = ? AND status IN ('registration_open', 'in_progress')";
        $result = $this->db->fetchOne($sql, [$clubId]);
        $stats['active_tournaments'] = $result['count'] ?? 0;
        
        // Recent reservations
        $sql = "SELECT r.*, u.first_name, u.last_name, c.name as court_name 
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN courts c ON r.court_id = c.id
                WHERE r.club_id = ? 
                ORDER BY r.created_at DESC 
                LIMIT 5";
        $stats['recent_reservations'] = $this->db->fetchAll($sql, [$clubId]);
        
        return $stats;
    }
}
