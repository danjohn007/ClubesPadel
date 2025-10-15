<?php
namespace Controllers;

use Core\Controller;

class FinancesController extends Controller {
    
    public function index() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $clubId = $this->getClubId();
        $financeModel = $this->model('Finance');
        
        // Get current month date range
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        
        // Get balance sheet
        $balance = $financeModel->getBalanceSheet($clubId, $startDate, $endDate);
        
        // Get recent transactions
        $recentIncome = array_slice($financeModel->getIncome($clubId), 0, 5);
        $recentExpenses = array_slice($financeModel->getExpenses($clubId), 0, 5);
        
        // Get data for charts
        $incomeByCategory = $financeModel->getIncomeByCategory($clubId, $startDate, $endDate);
        $expensesByCategory = $financeModel->getExpensesByCategory($clubId, $startDate, $endDate);
        
        $data = [
            'title' => 'Módulo Financiero',
            'balance' => $balance,
            'recent_income' => $recentIncome,
            'recent_expenses' => $recentExpenses,
            'income_by_category' => $incomeByCategory,
            'expenses_by_category' => $expensesByCategory,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        $this->view('finances/index', $data);
    }
    
    public function income() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $clubId = $this->getClubId();
        $financeModel = $this->model('Finance');
        
        $income = $financeModel->getIncome($clubId);
        
        $data = [
            'title' => 'Ingresos',
            'income' => $income
        ];
        
        $this->view('finances/income', $data);
    }
    
    public function createIncome() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $clubId = $this->getClubId();
        $financeModel = $this->model('Finance');
        $categories = $financeModel->getIncomeCategories($clubId);
        
        $data = [
            'title' => 'Registrar Ingreso',
            'categories' => $categories,
            'error' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryId = $_POST['category_id'] ?? '';
            $amount = $_POST['amount'] ?? '';
            $description = $_POST['description'] ?? '';
            $transactionDate = $_POST['transaction_date'] ?? date('Y-m-d');
            $paymentMethod = $_POST['payment_method'] ?? '';
            
            if (empty($categoryId) || empty($amount)) {
                $data['error'] = 'Categoría y monto son obligatorios';
            } else {
                $incomeData = [
                    'club_id' => $clubId,
                    'category_id' => $categoryId,
                    'amount' => $amount,
                    'description' => $description,
                    'transaction_date' => $transactionDate,
                    'payment_method' => $paymentMethod,
                    'created_by' => $this->getUserId()
                ];
                
                if ($financeModel->createIncome($incomeData)) {
                    $this->redirect('finances/income?success=created');
                } else {
                    $data['error'] = 'Error al registrar el ingreso';
                }
            }
        }
        
        $this->view('finances/create_income', $data);
    }
    
    public function expenses() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $clubId = $this->getClubId();
        $financeModel = $this->model('Finance');
        
        $expenses = $financeModel->getExpenses($clubId);
        
        $data = [
            'title' => 'Egresos',
            'expenses' => $expenses
        ];
        
        $this->view('finances/expenses', $data);
    }
    
    public function createExpense() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $clubId = $this->getClubId();
        $financeModel = $this->model('Finance');
        $categories = $financeModel->getExpenseCategories($clubId);
        
        $data = [
            'title' => 'Registrar Egreso',
            'categories' => $categories,
            'error' => ''
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryId = $_POST['category_id'] ?? '';
            $amount = $_POST['amount'] ?? '';
            $description = $_POST['description'] ?? '';
            $transactionDate = $_POST['transaction_date'] ?? date('Y-m-d');
            $paymentMethod = $_POST['payment_method'] ?? '';
            
            if (empty($categoryId) || empty($amount)) {
                $data['error'] = 'Categoría y monto son obligatorios';
            } else {
                $expenseData = [
                    'club_id' => $clubId,
                    'category_id' => $categoryId,
                    'amount' => $amount,
                    'description' => $description,
                    'transaction_date' => $transactionDate,
                    'payment_method' => $paymentMethod,
                    'created_by' => $this->getUserId()
                ];
                
                if ($financeModel->createExpense($expenseData)) {
                    $this->redirect('finances/expenses?success=created');
                } else {
                    $data['error'] = 'Error al registrar el egreso';
                }
            }
        }
        
        $this->view('finances/create_expense', $data);
    }
    
    public function reports() {
        $this->requireAuth();
        $this->requireRole('admin');
        
        $clubId = $this->getClubId();
        $financeModel = $this->model('Finance');
        
        // Get date range from request or use current month
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');
        
        $balance = $financeModel->getBalanceSheet($clubId, $startDate, $endDate);
        $incomeByCategory = $financeModel->getIncomeByCategory($clubId, $startDate, $endDate);
        $expensesByCategory = $financeModel->getExpensesByCategory($clubId, $startDate, $endDate);
        
        $data = [
            'title' => 'Reportes Financieros',
            'balance' => $balance,
            'income_by_category' => $incomeByCategory,
            'expenses_by_category' => $expensesByCategory,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        $this->view('finances/reports', $data);
    }
}
