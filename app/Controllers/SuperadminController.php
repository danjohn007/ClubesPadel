<?php
namespace Controllers;

use Core\Controller;

class SuperadminController extends Controller {
    
    public function dashboard() {
        $this->requireRole('superadmin');
        
        $clubModel = $this->model('Club');
        $stats = $clubModel->getStats();
        
        // Get recent clubs
        $sql = "SELECT * FROM clubs ORDER BY created_at DESC LIMIT 10";
        $recentClubs = $this->getDb()->fetchAll($sql);
        
        // Get revenue this month
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM club_payments 
                WHERE MONTH(payment_date) = MONTH(CURDATE()) AND status = 'completed'";
        $result = $this->getDb()->fetchOne($sql);
        $monthlyRevenue = $result['total'] ?? 0;
        
        // Get clubs growth data for last 6 months
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    DATE_FORMAT(created_at, '%b') as month_name,
                    COUNT(*) as count
                FROM clubs 
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b')
                ORDER BY month ASC";
        $clubsGrowth = $this->getDb()->fetchAll($sql);
        
        // Get plans distribution
        $sql = "SELECT 
                    sp.name,
                    COUNT(c.id) as count
                FROM clubs c
                JOIN subscription_plans sp ON c.subscription_plan_id = sp.id
                WHERE c.is_active = 1
                GROUP BY sp.id, sp.name
                ORDER BY sp.id ASC";
        $plansDistribution = $this->getDb()->fetchAll($sql);
        
        $data = [
            'title' => 'SuperAdmin Dashboard',
            'stats' => $stats,
            'recent_clubs' => $recentClubs,
            'monthly_revenue' => $monthlyRevenue,
            'clubs_growth' => $clubsGrowth,
            'plans_distribution' => $plansDistribution
        ];
        
        $this->view('superadmin/dashboard', $data);
    }
    
    public function clubs() {
        $this->requireRole('superadmin');
        
        $clubModel = $this->model('Club');
        $clubs = $clubModel->getAll();
        
        $data = [
            'title' => 'Gestión de Clubes',
            'clubs' => $clubs
        ];
        
        $this->view('superadmin/clubs', $data);
    }
    
    public function plans() {
        $this->requireRole('superadmin');
        
        $sql = "SELECT * FROM subscription_plans ORDER BY price_monthly ASC";
        $plans = $this->getDb()->fetchAll($sql);
        
        $data = [
            'title' => 'Planes de Suscripción',
            'plans' => $plans
        ];
        
        $this->view('superadmin/plans', $data);
    }
    
    public function payments() {
        $this->requireRole('superadmin');
        
        $sql = "SELECT cp.*, c.name as club_name 
                FROM club_payments cp
                JOIN clubs c ON cp.club_id = c.id
                ORDER BY cp.created_at DESC
                LIMIT 50";
        $payments = $this->getDb()->fetchAll($sql);
        
        $data = [
            'title' => 'Pagos de Clubes',
            'payments' => $payments
        ];
        
        $this->view('superadmin/payments', $data);
    }
}
