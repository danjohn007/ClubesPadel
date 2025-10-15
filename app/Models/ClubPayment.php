<?php
namespace Models;

use Core\Database;

class ClubPayment {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $sql = "SELECT cp.*, c.name as club_name 
                FROM club_payments cp
                JOIN clubs c ON cp.club_id = c.id
                ORDER BY cp.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function findById($id) {
        $sql = "SELECT cp.*, c.name as club_name 
                FROM club_payments cp
                JOIN clubs c ON cp.club_id = c.id
                WHERE cp.id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function findByClubId($clubId) {
        $sql = "SELECT * FROM club_payments WHERE club_id = ? ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [$clubId]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO club_payments (club_id, amount, currency, payment_method, 
                transaction_id, status, payment_date, period_start, period_end, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['club_id'],
            $data['amount'],
            $data['currency'] ?? 'MXN',
            $data['payment_method'] ?? null,
            $data['transaction_id'] ?? null,
            $data['status'] ?? 'pending',
            $data['payment_date'] ?? date('Y-m-d H:i:s'),
            $data['period_start'] ?? null,
            $data['period_end'] ?? null,
            $data['notes'] ?? null
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        if (isset($data['amount'])) {
            $fields[] = 'amount = ?';
            $params[] = $data['amount'];
        }
        if (isset($data['payment_method'])) {
            $fields[] = 'payment_method = ?';
            $params[] = $data['payment_method'];
        }
        if (isset($data['status'])) {
            $fields[] = 'status = ?';
            $params[] = $data['status'];
        }
        if (isset($data['payment_date'])) {
            $fields[] = 'payment_date = ?';
            $params[] = $data['payment_date'];
        }
        if (isset($data['notes'])) {
            $fields[] = 'notes = ?';
            $params[] = $data['notes'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE club_payments SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->query($sql, $params);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM club_payments WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
