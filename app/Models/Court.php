<?php
namespace Models;

use Core\Database;

class Court {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll($clubId) {
        $sql = "SELECT * FROM courts WHERE club_id = ? ORDER BY name";
        return $this->db->fetchAll($sql, [$clubId]);
    }
    
    public function findById($id, $clubId) {
        $sql = "SELECT * FROM courts WHERE id = ? AND club_id = ?";
        return $this->db->fetchOne($sql, [$id, $clubId]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO courts (club_id, name, court_type, surface, has_lighting, 
                hourly_price, status, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['club_id'],
            $data['name'],
            $data['court_type'] ?? 'outdoor',
            $data['surface'] ?? null,
            isset($data['has_lighting']) ? 1 : 0,
            $data['hourly_price'] ?? 0,
            $data['status'] ?? 'available',
            $data['description'] ?? null
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
        if (isset($data['court_type'])) {
            $fields[] = 'court_type = ?';
            $params[] = $data['court_type'];
        }
        if (isset($data['surface'])) {
            $fields[] = 'surface = ?';
            $params[] = $data['surface'];
        }
        if (isset($data['has_lighting'])) {
            $fields[] = 'has_lighting = ?';
            $params[] = $data['has_lighting'] ? 1 : 0;
        }
        if (isset($data['hourly_price'])) {
            $fields[] = 'hourly_price = ?';
            $params[] = $data['hourly_price'];
        }
        if (isset($data['status'])) {
            $fields[] = 'status = ?';
            $params[] = $data['status'];
        }
        if (isset($data['description'])) {
            $fields[] = 'description = ?';
            $params[] = $data['description'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $params[] = $clubId;
        $sql = "UPDATE courts SET " . implode(', ', $fields) . " WHERE id = ? AND club_id = ?";
        
        return $this->db->query($sql, $params);
    }
    
    public function delete($id, $clubId) {
        $sql = "DELETE FROM courts WHERE id = ? AND club_id = ?";
        return $this->db->query($sql, [$id, $clubId]);
    }
    
    public function getAvailableCourts($clubId, $date, $startTime, $endTime) {
        $sql = "SELECT c.* FROM courts c
                WHERE c.club_id = ? AND c.status = 'available'
                AND c.id NOT IN (
                    SELECT court_id FROM reservations
                    WHERE club_id = ? 
                    AND reservation_date = ?
                    AND status != 'cancelled'
                    AND (
                        (start_time <= ? AND end_time > ?)
                        OR (start_time < ? AND end_time >= ?)
                        OR (start_time >= ? AND end_time <= ?)
                    )
                )
                ORDER BY c.name";
        
        return $this->db->fetchAll($sql, [
            $clubId, $clubId, $date,
            $startTime, $startTime,
            $endTime, $endTime,
            $startTime, $endTime
        ]);
    }
}
