<?php
namespace Models;

use Core\Database;

class SubscriptionPlan {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM subscription_plans ORDER BY price_monthly ASC";
        return $this->db->fetchAll($sql);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM subscription_plans WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }
    
    public function create($data) {
        $sql = "INSERT INTO subscription_plans (name, description, price_monthly, price_yearly, 
                max_users, max_courts, max_tournaments, max_storage_mb, features, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['name'],
            $data['description'] ?? null,
            $data['price_monthly'],
            $data['price_yearly'] ?? 0,
            $data['max_users'] ?? null,
            $data['max_courts'] ?? null,
            $data['max_tournaments'] ?? null,
            $data['max_storage_mb'] ?? null,
            $data['features'] ?? null,
            $data['is_active'] ?? 1
        ];
        
        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }
    
    public function update($id, $data) {
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
        if (isset($data['price_monthly'])) {
            $fields[] = 'price_monthly = ?';
            $params[] = $data['price_monthly'];
        }
        if (isset($data['price_yearly'])) {
            $fields[] = 'price_yearly = ?';
            $params[] = $data['price_yearly'];
        }
        if (isset($data['max_users'])) {
            $fields[] = 'max_users = ?';
            $params[] = $data['max_users'];
        }
        if (isset($data['max_courts'])) {
            $fields[] = 'max_courts = ?';
            $params[] = $data['max_courts'];
        }
        if (isset($data['max_tournaments'])) {
            $fields[] = 'max_tournaments = ?';
            $params[] = $data['max_tournaments'];
        }
        if (isset($data['max_storage_mb'])) {
            $fields[] = 'max_storage_mb = ?';
            $params[] = $data['max_storage_mb'];
        }
        if (isset($data['features'])) {
            $fields[] = 'features = ?';
            $params[] = $data['features'];
        }
        if (isset($data['is_active'])) {
            $fields[] = 'is_active = ?';
            $params[] = $data['is_active'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $params[] = $id;
        $sql = "UPDATE subscription_plans SET " . implode(', ', $fields) . " WHERE id = ?";
        
        return $this->db->query($sql, $params);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM subscription_plans WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }
}
