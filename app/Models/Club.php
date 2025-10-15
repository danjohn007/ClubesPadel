<?php
namespace Models;

use Core\Database;

class Club {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $sql = "SELECT c.*, sp.name as plan_name 
                FROM clubs c
                LEFT JOIN subscription_plans sp ON c.subscription_plan_id = sp.id
                ORDER BY c.created_at DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function findById($id) {
        $sql = "SELECT c.*, sp.name as plan_name 
                FROM clubs c
                LEFT JOIN subscription_plans sp ON c.subscription_plan_id = sp.id
                WHERE c.id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function findBySubdomain($subdomain) {
        $sql = "SELECT c.*, sp.name as plan_name 
                FROM clubs c
                LEFT JOIN subscription_plans sp ON c.subscription_plan_id = sp.id
                WHERE c.subdomain = ?";
        return $this->db->fetchOne($sql, [$subdomain]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO clubs (subdomain, name, email, phone, address, city, state, country, 
                subscription_plan_id, subscription_status, trial_ends_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $trialEndsAt = date('Y-m-d H:i:s', strtotime('+' . TRIAL_PERIOD_DAYS . ' days'));
        
        // Generate subdomain from name if not provided
        $subdomain = $data['subdomain'] ?? $this->generateSubdomain($data['name']);
        
        $params = [
            $subdomain,
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['address'] ?? null,
            $data['city'] ?? null,
            $data['state'] ?? null,
            $data['country'] ?? 'México',
            $data['subscription_plan_id'],
            'trial',
            $trialEndsAt
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    private function generateSubdomain($name) {
        // Convert to lowercase and remove special characters
        $subdomain = strtolower(trim($name));
        $subdomain = preg_replace('/[^a-z0-9]+/', '-', $subdomain);
        $subdomain = trim($subdomain, '-');
        
        // Check if subdomain exists and add number if needed
        $originalSubdomain = $subdomain;
        $counter = 1;
        while ($this->findBySubdomain($subdomain)) {
            $subdomain = $originalSubdomain . '-' . $counter;
            $counter++;
        }
        
        return $subdomain;
    }
    
    public function update($id, $data) {
        $fields = [];
        $params = [];
        
        if (isset($data['name'])) {
            $fields[] = 'name = ?';
            $params[] = $data['name'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = ?';
            $params[] = $data['email'];
        }
        if (isset($data['phone'])) {
            $fields[] = 'phone = ?';
            $params[] = $data['phone'];
        }
        if (isset($data['address'])) {
            $fields[] = 'address = ?';
            $params[] = $data['address'];
        }
        if (isset($data['city'])) {
            $fields[] = 'city = ?';
            $params[] = $data['city'];
        }
        if (isset($data['state'])) {
            $fields[] = 'state = ?';
            $params[] = $data['state'];
        }
        if (isset($data['logo'])) {
            $fields[] = 'logo = ?';
            $params[] = $data['logo'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE clubs SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->query($sql, $params);
    }
    
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total_clubs,
                SUM(CASE WHEN subscription_status = 'active' THEN 1 ELSE 0 END) as active_clubs,
                SUM(CASE WHEN subscription_status = 'trial' THEN 1 ELSE 0 END) as trial_clubs,
                SUM(CASE WHEN subscription_status = 'suspended' THEN 1 ELSE 0 END) as suspended_clubs
                FROM clubs";
        return $this->db->fetchOne($sql);
    }
}
