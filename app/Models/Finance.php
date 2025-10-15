<?php
namespace Models;

use Core\Database;

class Finance {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Income methods
    public function getIncome($clubId, $startDate = null, $endDate = null) {
        $sql = "SELECT it.*, ic.name as category_name, u.first_name, u.last_name
                FROM income_transactions it
                JOIN income_categories ic ON it.category_id = ic.id
                JOIN users u ON it.created_by = u.id
                WHERE it.club_id = ?";
        
        $params = [$clubId];
        
        if ($startDate && $endDate) {
            $sql .= " AND it.transaction_date BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
        $sql .= " ORDER BY it.transaction_date DESC, it.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getTotalIncome($clubId, $startDate = null, $endDate = null) {
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM income_transactions WHERE club_id = ?";
        $params = [$clubId];
        
        if ($startDate && $endDate) {
            $sql .= " AND transaction_date BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }
    
    public function createIncome($data) {
        $sql = "INSERT INTO income_transactions (club_id, category_id, amount, description, 
                transaction_date, payment_method, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['club_id'],
            $data['category_id'],
            $data['amount'],
            $data['description'] ?? null,
            $data['transaction_date'],
            $data['payment_method'] ?? null,
            $data['created_by']
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    // Expense methods
    public function getExpenses($clubId, $startDate = null, $endDate = null) {
        $sql = "SELECT et.*, ec.name as category_name, u.first_name, u.last_name
                FROM expense_transactions et
                JOIN expense_categories ec ON et.category_id = ec.id
                JOIN users u ON et.created_by = u.id
                WHERE et.club_id = ?";
        
        $params = [$clubId];
        
        if ($startDate && $endDate) {
            $sql .= " AND et.transaction_date BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
        $sql .= " ORDER BY et.transaction_date DESC, et.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getTotalExpenses($clubId, $startDate = null, $endDate = null) {
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM expense_transactions WHERE club_id = ?";
        $params = [$clubId];
        
        if ($startDate && $endDate) {
            $sql .= " AND transaction_date BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }
    
    public function createExpense($data) {
        $sql = "INSERT INTO expense_transactions (club_id, category_id, amount, description, 
                transaction_date, payment_method, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['club_id'],
            $data['category_id'],
            $data['amount'],
            $data['description'] ?? null,
            $data['transaction_date'],
            $data['payment_method'] ?? null,
            $data['created_by']
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    // Category methods
    public function getIncomeCategories($clubId) {
        $sql = "SELECT * FROM income_categories WHERE club_id = ? AND is_active = 1 ORDER BY name";
        return $this->db->fetchAll($sql, [$clubId]);
    }
    
    public function getExpenseCategories($clubId) {
        $sql = "SELECT * FROM expense_categories WHERE club_id = ? AND is_active = 1 ORDER BY name";
        return $this->db->fetchAll($sql, [$clubId]);
    }
    
    // Reports
    public function getBalanceSheet($clubId, $startDate, $endDate) {
        return [
            'total_income' => $this->getTotalIncome($clubId, $startDate, $endDate),
            'total_expenses' => $this->getTotalExpenses($clubId, $startDate, $endDate),
            'net_profit' => $this->getTotalIncome($clubId, $startDate, $endDate) - 
                           $this->getTotalExpenses($clubId, $startDate, $endDate)
        ];
    }
    
    public function getIncomeByCategory($clubId, $startDate, $endDate) {
        $sql = "SELECT ic.name, COALESCE(SUM(it.amount), 0) as total
                FROM income_categories ic
                LEFT JOIN income_transactions it ON ic.id = it.category_id 
                    AND it.club_id = ? AND it.transaction_date BETWEEN ? AND ?
                WHERE ic.club_id = ?
                GROUP BY ic.id, ic.name
                ORDER BY total DESC";
        
        return $this->db->fetchAll($sql, [$clubId, $startDate, $endDate, $clubId]);
    }
    
    public function getExpensesByCategory($clubId, $startDate, $endDate) {
        $sql = "SELECT ec.name, COALESCE(SUM(et.amount), 0) as total
                FROM expense_categories ec
                LEFT JOIN expense_transactions et ON ec.id = et.category_id 
                    AND et.club_id = ? AND et.transaction_date BETWEEN ? AND ?
                WHERE ec.club_id = ?
                GROUP BY ec.id, ec.name
                ORDER BY total DESC";
        
        return $this->db->fetchAll($sql, [$clubId, $startDate, $endDate, $clubId]);
    }
}
