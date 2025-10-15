<?php
namespace Models;

use Core\Database;

class Reservation {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll($clubId, $filters = []) {
        $sql = "SELECT r.*, c.name as court_name, 
                u.first_name, u.last_name, u.email
                FROM reservations r
                JOIN courts c ON r.court_id = c.id
                JOIN users u ON r.user_id = u.id
                WHERE r.club_id = ?";
        
        $params = [$clubId];
        
        if (!empty($filters['date'])) {
            $sql .= " AND r.reservation_date = ?";
            $params[] = $filters['date'];
        }
        
        if (!empty($filters['court_id'])) {
            $sql .= " AND r.court_id = ?";
            $params[] = $filters['court_id'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND r.status = ?";
            $params[] = $filters['status'];
        }
        
        $sql .= " ORDER BY r.reservation_date DESC, r.start_time DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function findById($id, $clubId) {
        $sql = "SELECT r.*, c.name as court_name, 
                u.first_name, u.last_name, u.email, u.phone
                FROM reservations r
                JOIN courts c ON r.court_id = c.id
                JOIN users u ON r.user_id = u.id
                WHERE r.id = ? AND r.club_id = ?";
        return $this->db->fetchOne($sql, [$id, $clubId]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO reservations (club_id, court_id, user_id, reservation_date, 
                start_time, end_time, duration_hours, total_price, status, payment_status, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['club_id'],
            $data['court_id'],
            $data['user_id'],
            $data['reservation_date'],
            $data['start_time'],
            $data['end_time'],
            $data['duration_hours'],
            $data['total_price'],
            $data['status'] ?? 'pending',
            $data['payment_status'] ?? 'unpaid',
            $data['notes'] ?? null
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function update($id, $clubId, $data) {
        $fields = [];
        $params = [];
        
        if (isset($data['status'])) {
            $fields[] = 'status = ?';
            $params[] = $data['status'];
        }
        if (isset($data['payment_status'])) {
            $fields[] = 'payment_status = ?';
            $params[] = $data['payment_status'];
        }
        if (isset($data['notes'])) {
            $fields[] = 'notes = ?';
            $params[] = $data['notes'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $params[] = $clubId;
        $sql = "UPDATE reservations SET " . implode(', ', $fields) . " WHERE id = ? AND club_id = ?";
        
        return $this->db->query($sql, $params);
    }
    
    public function delete($id, $clubId) {
        $sql = "DELETE FROM reservations WHERE id = ? AND club_id = ?";
        return $this->db->query($sql, [$id, $clubId]);
    }
    
    public function getByDate($clubId, $date) {
        $sql = "SELECT r.*, c.name as court_name, 
                u.first_name, u.last_name
                FROM reservations r
                JOIN courts c ON r.court_id = c.id
                JOIN users u ON r.user_id = u.id
                WHERE r.club_id = ? AND r.reservation_date = ?
                ORDER BY r.start_time";
        
        return $this->db->fetchAll($sql, [$clubId, $date]);
    }
}
