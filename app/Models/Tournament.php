<?php
namespace Models;

use Core\Database;

class Tournament {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll($clubId) {
        $sql = "SELECT t.*, u.first_name, u.last_name,
                (SELECT COUNT(*) FROM tournament_participants WHERE tournament_id = t.id) as participants_count
                FROM tournaments t
                JOIN users u ON t.created_by = u.id
                WHERE t.club_id = ?
                ORDER BY t.start_date DESC";
        
        return $this->db->fetchAll($sql, [$clubId]);
    }
    
    public function findById($id, $clubId) {
        $sql = "SELECT t.*, u.first_name, u.last_name
                FROM tournaments t
                JOIN users u ON t.created_by = u.id
                WHERE t.id = ? AND t.club_id = ?";
        
        return $this->db->fetchOne($sql, [$id, $clubId]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO tournaments (club_id, name, description, tournament_type, format, 
                registration_fee, max_participants, start_date, end_date, registration_deadline, 
                status, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['club_id'],
            $data['name'],
            $data['description'] ?? null,
            $data['tournament_type'] ?? 'doubles',
            $data['format'] ?? 'elimination',
            $data['registration_fee'] ?? 0,
            $data['max_participants'] ?? null,
            $data['start_date'],
            $data['end_date'],
            $data['registration_deadline'] ?? null,
            $data['status'] ?? 'upcoming',
            $data['created_by']
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function update($id, $clubId, $data) {
        $fields = [];
        $params = [];
        
        if (isset($data['name'])) {
            $fields[] = 'name = ?';
            $params[] = $data['name'];
        }
        if (isset($data['description'])) {
            $fields[] = 'description = ?';
            $params[] = $data['description'];
        }
        if (isset($data['status'])) {
            $fields[] = 'status = ?';
            $params[] = $data['status'];
        }
        if (isset($data['registration_fee'])) {
            $fields[] = 'registration_fee = ?';
            $params[] = $data['registration_fee'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $params[] = $clubId;
        $sql = "UPDATE tournaments SET " . implode(', ', $fields) . " WHERE id = ? AND club_id = ?";
        
        return $this->db->query($sql, $params);
    }
    
    public function getParticipants($tournamentId) {
        $sql = "SELECT tp.*, u.first_name, u.last_name, u.email,
                p.first_name as partner_first_name, p.last_name as partner_last_name
                FROM tournament_participants tp
                JOIN users u ON tp.user_id = u.id
                LEFT JOIN users p ON tp.partner_id = p.id
                WHERE tp.tournament_id = ?
                ORDER BY tp.registration_date";
        
        return $this->db->fetchAll($sql, [$tournamentId]);
    }
    
    public function registerParticipant($data) {
        $sql = "INSERT INTO tournament_participants (tournament_id, user_id, partner_id, 
                registration_date, payment_status, status) 
                VALUES (?, ?, ?, NOW(), ?, ?)";
        
        $params = [
            $data['tournament_id'],
            $data['user_id'],
            $data['partner_id'] ?? null,
            $data['payment_status'] ?? 'unpaid',
            $data['status'] ?? 'registered'
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
}
