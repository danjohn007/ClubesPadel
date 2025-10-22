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
        
        // Get subscription plans for dropdown
        $sql = "SELECT * FROM subscription_plans WHERE is_active = 1 ORDER BY name";
        $plans = $this->getDb()->fetchAll($sql);
        
        $data = [
            'title' => 'Gestión de Clubes',
            'clubs' => $clubs,
            'plans' => $plans,
            'error' => '',
            'success' => ''
        ];
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'create') {
                $clubData = [
                    'name' => $_POST['name'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'address' => $_POST['address'] ?? '',
                    'city' => $_POST['city'] ?? '',
                    'state' => $_POST['state'] ?? '',
                    'country' => $_POST['country'] ?? 'México',
                    'subdomain' => $_POST['subdomain'] ?? '',
                    'subscription_plan_id' => $_POST['subscription_plan_id'] ?? 1,
                    'is_active' => 1
                ];
                
                if ($clubModel->create($clubData)) {
                    $data['success'] = 'Club creado exitosamente';
                    $clubs = $clubModel->getAll();
                    $data['clubs'] = $clubs;
                } else {
                    $data['error'] = 'Error al crear el club';
                }
            } elseif ($_POST['action'] === 'edit' && isset($_POST['club_id'])) {
                $clubData = [
                    'email' => $_POST['email'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'address' => $_POST['address'] ?? '',
                    'city' => $_POST['city'] ?? '',
                    'state' => $_POST['state'] ?? ''
                ];
                
                if ($clubModel->update($_POST['club_id'], $clubData)) {
                    $data['success'] = 'Club actualizado exitosamente';
                    $clubs = $clubModel->getAll();
                    $data['clubs'] = $clubs;
                } else {
                    $data['error'] = 'Error al actualizar el club';
                }
            } elseif ($_POST['action'] === 'suspend' && isset($_POST['club_id'])) {
                $sql = "UPDATE clubs SET subscription_status = 'suspended' WHERE id = ?";
                if ($this->getDb()->query($sql, [$_POST['club_id']])) {
                    $data['success'] = 'Club suspendido exitosamente';
                    $clubs = $clubModel->getAll();
                    $data['clubs'] = $clubs;
                } else {
                    $data['error'] = 'Error al suspender el club';
                }
            } elseif ($_POST['action'] === 'reactivate' && isset($_POST['club_id'])) {
                $sql = "UPDATE clubs SET subscription_status = 'active' WHERE id = ?";
                if ($this->getDb()->query($sql, [$_POST['club_id']])) {
                    $data['success'] = 'Club reactivado exitosamente';
                    $clubs = $clubModel->getAll();
                    $data['clubs'] = $clubs;
                } else {
                    $data['error'] = 'Error al reactivar el club';
                }
            }
        }
        
        $this->view('superadmin/clubs', $data);
    }
    
    public function plans() {
        $this->requireRole('superadmin');
        
        $planModel = $this->model('SubscriptionPlan');
        $plans = $planModel->getAll();
        
        $data = [
            'title' => 'Planes de Suscripción',
            'plans' => $plans,
            'error' => '',
            'success' => ''
        ];
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'edit' && isset($_POST['plan_id'])) {
                $planData = [
                    'name' => $_POST['name'] ?? '',
                    'description' => $_POST['description'] ?? '',
                    'price_monthly' => $_POST['price_monthly'] ?? 0,
                    'price_yearly' => $_POST['price_yearly'] ?? 0,
                    'max_users' => $_POST['max_users'] ?? null,
                    'max_courts' => $_POST['max_courts'] ?? null,
                    'max_tournaments' => $_POST['max_tournaments'] ?? null,
                    'max_storage_mb' => $_POST['max_storage_mb'] ?? null,
                    'features' => $_POST['features'] ?? '',
                    'is_active' => isset($_POST['is_active']) ? 1 : 0
                ];
                
                if ($planModel->update($_POST['plan_id'], $planData)) {
                    $data['success'] = 'Plan actualizado exitosamente';
                    $plans = $planModel->getAll();
                    $data['plans'] = $plans;
                } else {
                    $data['error'] = 'Error al actualizar el plan';
                }
            }
        }
        
        $this->view('superadmin/plans', $data);
    }
    
    public function payments() {
        $this->requireRole('superadmin');
        
        $paymentModel = $this->model('ClubPayment');
        $payments = $paymentModel->getAll();
        
        // Get clubs for dropdown
        $clubModel = $this->model('Club');
        $clubs = $clubModel->getAll();
        
        $data = [
            'title' => 'Pagos de Clubes',
            'payments' => $payments,
            'clubs' => $clubs,
            'error' => '',
            'success' => ''
        ];
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            if ($_POST['action'] === 'create') {
                $paymentData = [
                    'club_id' => $_POST['club_id'] ?? '',
                    'amount' => $_POST['amount'] ?? 0,
                    'currency' => $_POST['currency'] ?? 'MXN',
                    'payment_method' => $_POST['payment_method'] ?? '',
                    'transaction_id' => $_POST['transaction_id'] ?? '',
                    'status' => $_POST['status'] ?? 'completed',
                    'payment_date' => $_POST['payment_date'] ?? date('Y-m-d H:i:s'),
                    'period_start' => $_POST['period_start'] ?? null,
                    'period_end' => $_POST['period_end'] ?? null,
                    'notes' => $_POST['notes'] ?? ''
                ];
                
                if ($paymentModel->create($paymentData)) {
                    $data['success'] = 'Pago registrado exitosamente';
                    $payments = $paymentModel->getAll();
                    $data['payments'] = $payments;
                } else {
                    $data['error'] = 'Error al registrar el pago';
                }
            }
        }
        
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
    
    public function users() {
        $this->requireRole('superadmin');
        
        // Get all users across all clubs
        $sql = "SELECT u.*, c.name as club_name 
                FROM users u
                LEFT JOIN clubs c ON u.club_id = c.id
                ORDER BY u.created_at DESC";
        $users = $this->getDb()->fetchAll($sql);
        
        $data = [
            'title' => 'CRM Usuarios',
            'users' => $users
        ];
        
        $this->view('superadmin/users', $data);
    }
    
    public function developments() {
        $this->requireRole('superadmin');
        
        $data = [
            'title' => 'CRM Desarrollos',
            'developments' => []
        ];
        
        $this->view('superadmin/developments', $data);
    }
    
    public function sports() {
        $this->requireRole('superadmin');
        
        $data = [
            'title' => 'CRM Deportivas',
            'sports' => []
        ];
        
        $this->view('superadmin/sports', $data);
    }
    
    public function sponsors() {
        $this->requireRole('superadmin');
        
        $data = [
            'title' => 'Patrocinadores',
            'sponsors' => []
        ];
        
        $this->view('superadmin/sponsors', $data);
    }
    
    public function loyalty() {
        $this->requireRole('superadmin');
        
        $data = [
            'title' => 'Sistema de Lealtad',
            'loyalty_programs' => []
        ];
        
        $this->view('superadmin/loyalty', $data);
    }
}
