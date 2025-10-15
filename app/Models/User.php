<?php
namespace Models;

use Core\Database;

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ? AND is_active = 1";
        return $this->db->fetchOne($sql, [$email]);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM users WHERE id = ? AND is_active = 1";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function authenticate($email, $password) {
        $user = $this->findByEmail($email);
        
        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $this->updateLastLogin($user['id']);
            return $user;
        }
        
        return false;
    }
    
    public function create($data) {
        $sql = "INSERT INTO users (club_id, role, email, password, first_name, last_name, phone, skill_level) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $params = [
            $data['club_id'] ?? null,
            $data['role'] ?? 'player',
            $data['email'],
            $hashedPassword,
            $data['first_name'],
            $data['last_name'],
            $data['phone'] ?? null,
            $data['skill_level'] ?? 'beginner'
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        if (isset($data['first_name'])) {
            $fields[] = 'first_name = ?';
            $params[] = $data['first_name'];
        }
        if (isset($data['last_name'])) {
            $fields[] = 'last_name = ?';
            $params[] = $data['last_name'];
        }
        if (isset($data['phone'])) {
            $fields[] = 'phone = ?';
            $params[] = $data['phone'];
        }
        if (isset($data['photo'])) {
            $fields[] = 'photo = ?';
            $params[] = $data['photo'];
        }
        if (isset($data['skill_level'])) {
            $fields[] = 'skill_level = ?';
            $params[] = $data['skill_level'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->query($sql, $params);
    }
    
    public function updateLastLogin($userId) {
        $sql = "UPDATE users SET last_login_at = NOW() WHERE id = ?";
        return $this->db->query($sql, [$userId]);
    }
    
    public function getByClub($clubId, $role = null) {
        $sql = "SELECT * FROM users WHERE club_id = ? AND is_active = 1";
        $params = [$clubId];
        
        if ($role) {
            $sql .= " AND role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY first_name, last_name";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getAllSuperAdmins() {
        $sql = "SELECT * FROM users WHERE role = 'superadmin' AND is_active = 1 ORDER BY first_name, last_name";
        return $this->db->fetchAll($sql);
    }
}
