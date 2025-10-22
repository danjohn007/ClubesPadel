<?php
namespace Models;

use Core\Database;

class SystemSettings {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM system_settings ORDER BY setting_group, setting_key";
        return $this->db->fetchAll($sql);
    }
    
    public function getByGroup($group) {
        $sql = "SELECT * FROM system_settings WHERE setting_group = ? ORDER BY setting_key";
        return $this->db->fetchAll($sql, [$group]);
    }
    
    public function get($key, $default = null) {
        $sql = "SELECT setting_value FROM system_settings WHERE setting_key = ?";
        $result = $this->db->fetchOne($sql, [$key]);
        return $result ? $result['setting_value'] : $default;
    }
    
    public function set($key, $value, $group = 'general', $description = null) {
        $sql = "INSERT INTO system_settings (setting_key, setting_value, setting_group, description) 
                VALUES (?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE setting_value = ?, setting_group = ?, description = COALESCE(?, description)";
        
        $params = [$key, $value, $group, $description, $value, $group, $description];
        return $this->db->query($sql, $params);
    }
    
    public function update($key, $value) {
        $sql = "UPDATE system_settings SET setting_value = ? WHERE setting_key = ?";
        return $this->db->query($sql, [$value, $key]);
    }
    
    public function delete($key) {
        $sql = "DELETE FROM system_settings WHERE setting_key = ?";
        return $this->db->query($sql, [$key]);
    }
    
    public function getAllGrouped() {
        $settings = $this->getAll();
        $grouped = [];
        
        foreach ($settings as $setting) {
            $group = $setting['setting_group'];
            if (!isset($grouped[$group])) {
                $grouped[$group] = [];
            }
            $grouped[$group][] = $setting;
        }
        
        return $grouped;
    }
}
