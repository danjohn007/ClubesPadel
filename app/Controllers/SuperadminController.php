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
        
        // Get revenue data for last 6 months
        $sql = "SELECT 
                    DATE_FORMAT(payment_date, '%b') as month_name,
                    SUM(amount) as total
                FROM club_payments 
                WHERE status = 'completed'
                AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(payment_date, '%Y-%m'), DATE_FORMAT(payment_date, '%b')
                ORDER BY DATE_FORMAT(payment_date, '%Y-%m') ASC";
        $revenueData = $this->getDb()->fetchAll($sql);
        
        // Get subscription status data
        $sql = "SELECT 
                    subscription_status,
                    COUNT(*) as count
                FROM clubs 
                WHERE is_active = 1
                GROUP BY subscription_status";
        $subscriptionStatus = $this->getDb()->fetchAll($sql);
        
        $data = [
            'title' => 'SuperAdmin Dashboard',
            'stats' => $stats,
            'recent_clubs' => $recentClubs,
            'monthly_revenue' => $monthlyRevenue,
            'clubs_growth' => $clubsGrowth,
            'plans_distribution' => $plansDistribution,
            'revenue_data' => $revenueData,
            'subscription_status' => $subscriptionStatus
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
    
    public function reports() {
        $this->requireRole('superadmin');
        
        // Get revenue report
        $sql = "SELECT 
                    DATE_FORMAT(payment_date, '%Y-%m') as month,
                    DATE_FORMAT(payment_date, '%b %Y') as month_name,
                    SUM(amount) as total,
                    COUNT(*) as count
                FROM club_payments 
                WHERE status = 'completed'
                AND payment_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(payment_date, '%Y-%m'), DATE_FORMAT(payment_date, '%b %Y')
                ORDER BY month ASC";
        $revenueReport = $this->getDb()->fetchAll($sql);
        
        // Get club growth report
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    DATE_FORMAT(created_at, '%b %Y') as month_name,
                    COUNT(*) as new_clubs,
                    SUM(COUNT(*)) OVER (ORDER BY DATE_FORMAT(created_at, '%Y-%m')) as total_clubs
                FROM clubs 
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m'), DATE_FORMAT(created_at, '%b %Y')
                ORDER BY month ASC";
        $clubGrowthReport = $this->getDb()->fetchAll($sql);
        
        // Get subscription status report
        $sql = "SELECT 
                    subscription_status,
                    COUNT(*) as count,
                    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM clubs), 2) as percentage
                FROM clubs 
                WHERE is_active = 1
                GROUP BY subscription_status";
        $subscriptionReport = $this->getDb()->fetchAll($sql);
        
        // Get plans performance
        $sql = "SELECT 
                    sp.name,
                    COUNT(c.id) as clubs_count,
                    COALESCE(SUM(cp.amount), 0) as total_revenue
                FROM subscription_plans sp
                LEFT JOIN clubs c ON sp.id = c.subscription_plan_id AND c.is_active = 1
                LEFT JOIN club_payments cp ON c.id = cp.club_id AND cp.status = 'completed'
                GROUP BY sp.id, sp.name
                ORDER BY clubs_count DESC";
        $plansPerformance = $this->getDb()->fetchAll($sql);
        
        $data = [
            'title' => 'Reportes',
            'revenue_report' => $revenueReport,
            'club_growth_report' => $clubGrowthReport,
            'subscription_report' => $subscriptionReport,
            'plans_performance' => $plansPerformance
        ];
        
        $this->view('superadmin/reports', $data);
    }
    
    public function settings() {
        $this->requireRole('superadmin');
        
        $data = [
            'title' => 'Configuración del Sistema',
            'error' => '',
            'success' => ''
        ];
        
        $settingsModel = $this->model('SystemSettings');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Save settings
            foreach ($_POST as $key => $value) {
                if (strpos($key, 'setting_') === 0) {
                    $settingKey = substr($key, 8); // Remove 'setting_' prefix
                    $settingsModel->update($settingKey, $value);
                }
            }
            
            $data['success'] = 'Configuración guardada exitosamente';
        }
        
        // Get all settings grouped by category
        $data['settings'] = $settingsModel->getAllGrouped();
        
        $this->view('superadmin/settings', $data);
    }
}
